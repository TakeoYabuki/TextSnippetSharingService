//URLコピー処理
const copyUrlButton = document.getElementById("copy-url");

if (copyUrlButton) {
    copyUrlButton.addEventListener("click", async () => {
        try {
            await navigator.clipboard.writeText(window.location.href);

            copyUrlButton.textContent = "Copied!";
        } catch (error) {
            console.error(error);
            alert("コピーに失敗しました。");
        }
    });
}

//共有テキストとコードのコピー処理
const copyCodeButton = document.getElementById("copy-code");
const snippetContent = document.getElementById("snippet-content");

if (copyCodeButton && snippetContent) {
    copyCodeButton.addEventListener("click", async () => {
        try {
            await navigator.clipboard.writeText(snippetContent.textContent);

            copyCodeButton.textContent = "Copied!";
        } catch (error) {
            console.error(error);
            alert("コピーに失敗しました。");
        }
    });
}

//バイト数表示と容量超過時の警告
const contentInput = document.getElementById("content");
const byteCount = document.getElementById("byte-count");
const byteProgress = document.getElementById("byte-progress");
const submitButton = document.getElementById("submit-button");

const MAX_BYTES = 60000;

if (contentInput && byteCount && byteProgress && submitButton) {

    function updateByteCount() {
        const content = contentInput.value.replace(/\r\n|\r|\n/g, "\r\n");
        //UTF-8(TextEncoder())でのバイト数計算
        const bytes = new TextEncoder().encode(content).length;

        byteCount.textContent = `${bytes.toLocaleString()} / ${MAX_BYTES.toLocaleString()} bytes`;
        
        //プログレスバー管理（％表示）
        const percentage = Math.min((bytes / MAX_BYTES) * 100, 100);

        byteProgress.style.width = `${percentage}%`;

        //バイト数の超過の計算
        const overLimit = bytes > MAX_BYTES;

        byteCount.classList.toggle("over-limit", overLimit);
        byteProgress.classList.toggle("over-limit", overLimit);

        submitButton.disabled = overLimit;
    }

    contentInput.addEventListener("input", updateByteCount);

    updateByteCount();
}