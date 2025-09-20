<?php include 'db.php';
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="attendance_export.csv"');
$out = fopen('php://output','w');
fputcsv($out, ['ID','User','Date','Time','Status']);
$stmt = $pdo->query('SELECT a.*, u.name FROM attendance a JOIN users u ON a.user_id=u.id ORDER BY a.date DESC, a.time DESC');
while ($r = $stmt->fetch()) {
    fputcsv($out, [$r['id'], $r['name'], $r['date'], $r['time'], $r['status']]);
}
fclose($out);
exit;
?>