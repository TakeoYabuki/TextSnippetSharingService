<?php

require_once __DIR__ . '/MySQLWrapper.php';

$db = new MySQLWrapper();

$db->execute("
    CREATE TABLE IF NOT EXISTS migrations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        migration_name VARCHAR(255) NOT NULL UNIQUE,
        executed_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
    )
");

//ロールバック履歴
$stmt = $db->execute(
    "SELECT migration_name FROM migrations"
);

$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

//print_r($rows);

//マイグレーション履歴を確認する
$executed = array_column($rows, 'migration_name');

$files = glob(__DIR__ . '/migrations/*.php');
sort($files);

foreach ($files as $file) {

    //ファイル名のみを$nameに格納
    $name = basename($file);

    //マイグレーション実行済みか確認
    if (in_array($name, $executed, true)) {
        continue;
    }

    //未実装のマイグレーションを実行
    $migration = require $file;
    $migration($db);

    //実行後にmigrationsテーブルに履歴を記録
    $db->execute(
        "INSERT INTO migrations (migration_name) VALUES (?)",
        [$name]
    );

    echo "未実行: " . $name . "\n";
}