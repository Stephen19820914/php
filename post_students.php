<?php
// =============================
// post_students.php
// 功能：透過 POST 傳遞班級編號，查詢該班所有同學及成績
// 呼叫方式：POST body  班級編號=C001
// =============================

require_once 'db.php';

header('Content-Type: application/json; charset=utf-8');

// 只接受 POST 請求
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => '僅接受 POST 請求']);
    exit;
}

// 取得並驗證參數
$classId = trim($_POST['班級編號'] ?? '');

if ($classId === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => '請提供班級編號']);
    exit;
}

$conn = getDBConnection();

// 同時 JOIN class 取得班級名稱
$stmt = $conn->prepare(
    "SELECT
        cm.同學編號, cm.同學姓名,
        cm.英文成績, cm.數學成績, cm.國文成績,
        c.班級名稱
     FROM Classmates cm
     JOIN class c ON cm.班級編號 = c.班級編號
     WHERE cm.班級編號 = ?
     ORDER BY cm.同學編號"
);
$stmt->bind_param('s', $classId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => '找不到班級或該班無學生：' . htmlspecialchars($classId)]);
} else {
    $students  = [];
    $className = '';

    while ($row = $result->fetch_assoc()) {
        $className  = $row['班級名稱'];
        $eng        = (int)$row['英文成績'];
        $math       = (int)$row['數學成績'];
        $chi        = (int)$row['國文成績'];
        $avg        = round(($eng + $math + $chi) / 3, 1);

        $students[] = [
            '同學編號' => $row['同學編號'],
            '同學姓名' => $row['同學姓名'],
            '英文成績' => $eng,
            '數學成績' => $math,
            '國文成績' => $chi,
            '平均成績' => $avg,
        ];
    }

    echo json_encode([
        'success'    => true,
        '班級編號'   => $classId,
        '班級名稱'   => $className,
        '學生人數'   => count($students),
        'data'       => $students,
    ], JSON_UNESCAPED_UNICODE);
}

$stmt->close();
$conn->close();
?>
