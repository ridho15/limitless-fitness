<?php
$env = @file_get_contents(__DIR__ . '/../.env');
$map = [];
foreach (preg_split('/\R/', $env) as $line) {
    if (!strpos($line, '=')) continue;
    list($k,$v) = explode('=', $line, 2);
    $map[trim($k)] = trim($v, " \t\"'");
}
$host = $map['DB_HOST'] ?? '127.0.0.1';
$port = $map['DB_PORT'] ?? '3306';
$user = $map['DB_USERNAME'] ?? '';
$pass = $map['DB_PASSWORD'] ?? '';
$dbname = $map['DB_DATABASE'] ?? '';
$tests = [
    ['label'=>'tcp_127.0.0.1','dsn'=>"mysql:host=127.0.0.1;port={$port};dbname={$dbname};charset=utf8mb4"],
    ['label'=>'tcp_localhost','dsn'=>"mysql:host=localhost;port={$port};dbname={$dbname};charset=utf8mb4"],
    ['label'=>'no_db_tcp','dsn'=>"mysql:host=127.0.0.1;port={$port};charset=utf8mb4"],
];
$out=[];
foreach ($tests as $t) {
    try {
        $pdo = new PDO($t['dsn'],$user,$pass,[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
        $info = $pdo->query("SELECT @@hostname AS hostname, @@socket AS socket, @@version AS version, DATABASE() AS db")->fetch(PDO::FETCH_ASSOC);
        $out[$t['label']]=['ok'=>true,'info'=>$info];
    } catch (PDOException $e) {
        $out[$t['label']]=['ok'=>false,'error'=>$e->getMessage()];
    }
}
echo json_encode($out, JSON_PRETTY_PRINT);
