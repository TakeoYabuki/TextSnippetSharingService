<?php

require_once __DIR__ . '/../MySQLWrapper.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Allow: POST");
    http_response_code(405);
    exit("Method Not Allowed");
}

//index.phpからの各種パラメータを確認
$content = $_POST["content"] ?? "";
$language = $_POST["language"] ?? "text";
$expiration = $_POST["expiration"] ?? "never";

//有効期限の実際の時間
$expirationSeconds = [
    "never" => null,
    "1h" => 3600,
    "24h" => 3600 * 24,
    "7d" => 3600 * 24 * 7
];

//テキストの有無と型チェック
if (!is_string($content) || trim($content) === "") {
    http_response_code(400);
    exit("テキストを入力してください。");
}

//有効なプログラミング言語か確認
$allowedLanguages = ["text", "php", "javascript", "python"];

if (!in_array($language, $allowedLanguages, true)) {
    http_response_code(400);
    exit("無効な言語が指定されました。");
}

//有効期限の有効性
if (!is_string($expiration) || !array_key_exists($expiration, $expirationSeconds)) {
    http_response_code(400);
    exit("無効な有効期限です。");
}

//テキストの容量制限
if (strlen($content) > 60000) {
    http_response_code(400);
    exit("テキストが長すぎます。");
}

$seconds = $expirationSeconds[$expiration];

$expiresAt = $seconds === null ? null : gmdate("Y-m-d H:i:s", time() + $seconds);


try {
    $db = new MySQLWrapper();
    //ランダムなバイナリーデータを文字列に変換。16バイトを生成後16進数(1バイトにつき2文字)
    $key = bin2hex(random_bytes(16));

    $db->execute(
        "INSERT INTO snippets (unique_key, content, language, expires_at)
         VALUES (?, ?, ?, ?)",
        [$key, $content, $language, $expiresAt]
    );
} catch (PDOException $e) {
    error_log($e->getMessage());
    http_response_code(500);
    exit("データベースエラーが発生しました。");
}

//ブラウザにview.php?key=*****へGETリクエスト(303)でリダイレクトを指示
header("Location: view.php?key=" . $key, true, 303);
exit;