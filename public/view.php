<?php

require_once __DIR__ . '/../MySQLWrapper.php';

$key = $_GET["key"] ?? null;

//キーのバリデーション
if (!is_string($key) || trim($key) === "") {
    http_response_code(400);
    exit("キーが指定されていません。");
}

try {
    $db = new MySQLWrapper();

    $stmt = $db->execute(
    "SELECT * FROM snippets WHERE unique_key = ?
    AND (expires_at IS NULL OR expires_at > UTC_TIMESTAMP())",
    [$key]
    );

    $snippet = $stmt->fetch(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    error_log($e->getMessage());
    http_response_code(500);
    exit("データベースエラーが発生しました。");
}

//該当するレコードが存在しない場合の処理
if ($snippet === false) {
    http_response_code(404);
}

?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>スニペット表示</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <main class="container">

        <h1>Text Snippet Sharing Service</h1>
        <p class="subtitle">Shared Snippet</p>

        <?php if ($snippet !== false): ?>
            <div class="snippet-header">
                <h2><?= htmlspecialchars($snippet["language"], ENT_QUOTES, 'UTF-8') ?></h2>
                <button type="button" id="copy-code" class="copy-code-button">Copy Code</button>
            </div>
            <pre id="snippet-content"><?= htmlspecialchars($snippet["content"], ENT_QUOTES, 'UTF-8') ?></pre>
            <button type="button" id="copy-url" class="copy-url-button">Copy URL</button>
        <?php else: ?>
            <p class="error">データが見つかりません。</p>
        <?php endif; ?>

        <a href="index.php" class="new-snippet">＋ New Snippet</a>

    </main>

    <script src="js/script.js"></script>
</body>
</html>
