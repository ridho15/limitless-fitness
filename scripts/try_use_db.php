<?php
$env = @file_get_contents(__DIR__ . '/../.env');
$map = [];
foreach (preg_split('/\R/', $env) as $line) {
    if (!strpos($line, '=')) continue;
    list($k,$v) = explode('=', $line, 2);
    $map[trim($k)] = trim($v, " \t\"'");
}
$user = $map['DB_USERNAME'] ?? '';
$pass = $map['DB_PASSWORD'] ?? '';
$port = $map['DB_PORT'] ?? '3306';
try{
    $pdo = new PDO("mysql:host=127.0.0.1;port={$port};charset=utf8mb4", $user, $pass, [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
    try{
        $pdo->exec("USE `limitless_fitness`");
        $db = $pdo->query("SELECT DATABASE() AS db")->fetch(PDO::FETCH_ASSOC)['db'];
        echo json_encode(['useOk'=>true,'db'=>$db]);
    } catch (PDOException $e) {
        echo json_encode(['useOk'=>false,'error'=>$e->getMessage()]);
    }
} catch (PDOException $e) { echo json_encode(['connect'=>false,'error'=>$e->getMessage()]); }
