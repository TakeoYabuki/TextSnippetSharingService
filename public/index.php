<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Text Snippet Sharing Service</title>
        <link rel="stylesheet" href="css/style.css">
    </head>

    <body>
        <main class="container">

            <h1>Text Snippet Sharing Service</h1>
            <p class="subtitle">テキストやソースコードを共有しましょう。</p>

            <form action="create.php" method="POST">

                <label for="language">Language</label>

                <select name="language" id="language">
                    <option value="text">Plain Text</option>
                    <option value="php">PHP</option>
                    <option value="javascript">JavaScript</option>
                    <option value="python">Python</option>
                </select>

                <label for="content">Content</label>

                <textarea
                    name="content"
                    id="content"
                    placeholder="テキストやコードを入力してください..."
                    required
                ></textarea>

                <p id="byte-count" class="byte-count">0 / 60,000 bytes</p>

                <div class="progress-bar">
                    <div id="byte-progress"></div>
                </div>

                <label for="expiration">Expiration</label>

                <select name="expiration" id="expiration">
                    <option value="never">無期限</option>
                    <option value="1h">1時間</option>
                    <option value="24h">24時間</option>
                    <option value="7d">7日間</option>
                </select>

                <button type="submit" id="submit-button">Create Snippet</button>

            </form>

        </main>

        <script src="js/script.js"></script>
    </body>
</html>
