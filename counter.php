<?php
require_once 'database.php'; // ไฟล์ getConnection.php ของคุณ

$pdo = getConnection();
$today = date("Y-m-d");

// เช็คว่ามีข้อมูลวันนี้แล้วหรือยัง
$stmt = $pdo->prepare("SELECT count FROM visitors WHERE visit_date = :today");
$stmt->execute(['today' => $today]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row) {
    // ถ้ามีแล้ว เพิ่ม count
    $count = $row['count'] + 1;
    $update = $pdo->prepare("UPDATE visitors SET count = :count WHERE visit_date = :today");
    $update->execute(['count' => $count, 'today' => $today]);
} else {
    // ถ้าไม่มี เพิ่มแถวใหม่
    $count = 1;
    $insert = $pdo->prepare("INSERT INTO visitors (visit_date, count) VALUES (:today, :count)");
    $insert->execute(['today' => $today, 'count' => $count]);
}

// ดึงจำนวนผู้เข้าชมรวมทั้งหมด
$stmtTotal = $pdo->query("SELECT SUM(count) AS total FROM visitors");
$total = $stmtTotal->fetch(PDO::FETCH_ASSOC)['total'];

echo $total;
?>
