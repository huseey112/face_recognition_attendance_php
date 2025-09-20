<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }
$uid = $_SESSION['user_id'];
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="my_attendance.csv"');
$out = fopen('php://output','w');
fputcsv($out, ['Date','Time','Status']);
$stmt = $pdo->prepare('SELECT * FROM attendance WHERE user_id=? ORDER BY date DESC, time DESC');
$stmt->execute([$uid]);
while ($r = $stmt->fetch()) {
    fputcsv($out, [$r['date'],$r['time'],$r['status']]);
}
fclose($out);
exit;
?>