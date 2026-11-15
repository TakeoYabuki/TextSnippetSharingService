<?php

return function (MySQLWrapper $db): void {
    $db->execute("
      CREATE TABLE IF NOT EXISTS snippets (
        id INT AUTO_INCREMENT PRIMARY KEY,
        unique_key VARCHAR(64) NOT NULL UNIQUE,
        content TEXT NOT NULL,
        language VARCHAR(50) NOT NULL,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        expires_at DATETIME NULL
      )
    ");
};