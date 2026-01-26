<?php
// Small helper to check MySQL server, database existence and privileges using credentials from .env
$env = @file_get_contents(__DIR__ . '/../.env');
if ($env === false) {
    echo json_encode(['error' => 'Could not read .env']);
    exit(1);
}
// parse simple KEY=VALUE lines
$lines = preg_split('/\R/', $env);
$map = [];
foreach ($lines as $line) {
    $line = trim($line);
    if ($line === '' || strpos($line, '#') === 0) continue;
    if (!strpos($line, '=')) continue;
    list($k, $v) = explode('=', $line, 2);
    $v = trim($v, " \t\"'");
    $map[trim($k)] = $v;
}
$host = $map['DB_HOST'] ?? '127.0.0.1';
$port = $map['DB_PORT'] ?? '3306';
$user = $map['DB_USERNAME'] ?? '';
$pass = $map['DB_PASSWORD'] ?? '';
$dbname = $map['DB_DATABASE'] ?? '';
try {
    $dsn = "mysql:host={$host};port={$port};charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
    // connected
    $dbExistsStmt = $pdo->prepare("SHOW DATABASES LIKE :db");
    $dbExistsStmt->execute(['db' => $dbname]);
    $dbExists = $dbExistsStmt->fetchColumn() ? true : false;
    $currentUser = $pdo->query("SELECT CURRENT_USER()")->fetchColumn();
    $grants = [];
    foreach ($pdo->query("SHOW GRANTS FOR CURRENT_USER()") as $row) {
        $grants[] = $row[0];
    }
    $result = [
        'connected' => true,
        'host' => $host,
        'port' => $port,
        'user' => $user,
        'current_user' => $currentUser,
        'dbExists' => $dbExists,
        'grants' => $grants,
    ];
    if (!$dbExists) {
        // try to create DB if user has CREATE privilege
        $canCreate = false;
        foreach ($grants as $g) {
            if (stripos($g, 'CREATE') !== false || stripos($g, 'ALL PRIVILEGES') !== false) {
                $canCreate = true; break;
            }
        }
        if ($canCreate) {
            try {
                $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbname}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                $result['created'] = true;
                $result['dbExists'] = true;
            } catch (Exception $e) {
                $result['created'] = false;
                $result['createError'] = $e->getMessage();
            }
        } else {
            $result['created'] = false;
            $result['createError'] = 'User lacks CREATE/ALL PRIVILEGES';
        }
    }
    echo json_encode($result);
} catch (PDOException $e) {
    echo json_encode(['connected' => false, 'error' => $e->getMessage()]);
}
