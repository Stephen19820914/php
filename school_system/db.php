<?php
// =============================
// db.php - 資料庫連線設定
// =============================

define('DB_HOST', 'localhost');
define('DB_USER', 'root');        // 修改為你的 MySQL 帳號
define('DB_PASS', '');    // 修改為你的 MySQL 密碼
define('DB_NAME', 'school_db');   // 修改為你的資料庫名稱
define('DB_CHARSET', 'utf8mb4');

function getDBConnection(): mysqli {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        die(json_encode([
            'success' => false,
            'message' => '資料庫連線失敗：' . $conn->connect_error
        ]));
    }

    $conn->set_charset(DB_CHARSET);
    return $conn;
}
?>
