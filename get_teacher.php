<?php
// =============================
// get_teacher.php
// 功能：透過 GET 傳遞班級編號，查詢班級導師資訊
// 呼叫範例：get_teacher.php?班級編號=C001
// =============================

require_once 'db.php';

header('Content-Type: application/json; charset=utf-8');

// 取得並驗證參數
$classId = trim($_GET['班級編號'] ?? '');

if ($classId === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => '請提供班級編號']);
    exit;
}

$conn = getDBConnection();

// 使用 Prepared Statement 防止 SQL Injection
$stmt = $conn->prepare(
    "SELECT 班級編號, 班級名稱, 班級導師 FROM class WHERE 班級編號 = ?"
);
$stmt->bind_param('s', $classId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => '找不到該班級編號：' . htmlspecialchars($classId)]);
} else {
    $row = $result->fetch_assoc();
    echo json_encode([
        'success'  => true,
        'data'     => [
            '班級編號' => $row['班級編號'],
            '班級名稱' => $row['班級名稱'],
            '班級導師' => $row['班級導師'],
        ]
    ], JSON_UNESCAPED_UNICODE);
}

$stmt->close();
$conn->close();
?>
