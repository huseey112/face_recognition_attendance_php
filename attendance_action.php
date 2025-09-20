<?php include 'header.php'; ?>
<?php
include 'db.php';
include 'helpers.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) { echo json_encode(['success'=>false,'message'=>'Not logged in']); exit; }

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);
if (!$data || empty($data['image'])) {
    echo json_encode(['success'=>false, 'message'=>'No image provided']); exit;
}
$img = $data['image'];
if (preg_match('/^data:\w+\/\w+;base64,/', $img)) {
    $img = preg_replace('/^data:\w+\/\w+;base64,/', '', $img);
}
$imgdata = base64_decode($img);
if ($imgdata === false) { echo json_encode(['success'=>false,'message'=>'Invalid image data']); exit; }

$uploads = __DIR__ . '/uploads';
if (!is_dir($uploads)) mkdir($uploads,0755,true);
$tmpname = $uploads . '/tmp_' . uniqid() . '.jpg';
file_put_contents($tmpname, $imgdata);

$ahash = image_ahash_from_file($tmpname);
if ($ahash === false) { @unlink($tmpname); echo json_encode(['success'=>false,'message'=>'Unable to process image']); exit; }

// load users and compare
$stmt = $pdo->query('SELECT id,name,photo,ahash FROM users');
$users = $stmt->fetchAll();
$best = null; $bestdist = PHP_INT_MAX;
foreach ($users as $u) {
    if (empty($u['ahash'])) continue;
    $dist = hamming_distance_hex($ahash, $u['ahash']);
    if ($dist < $bestdist) { $bestdist = $dist; $best = $u; }
}
// threshold for match
$threshold = 12;
$session_id = $_SESSION['user_id'];
if ($best && $bestdist <= $threshold) {
    if ($best['id'] != $session_id) {
        @unlink($tmpname);
        echo json_encode(['success'=>false, 'message'=>'Face does not match your account (closest: '.$best['name'].')', 'distance'=>$bestdist]);
        exit;
    }
    // log attendance if not already for today
    $today = date('Y-m-d');
    $stmt = $pdo->prepare('SELECT id FROM attendance WHERE user_id=? AND date=?');
    $stmt->execute([$best['id'], $today]);
    if (!$stmt->fetch()) {
        $time = date('H:i:s');
        $ins = $pdo->prepare('INSERT INTO attendance (user_id, date, time, status) VALUES (?,?,?,?)');
        $ins->execute([$best['id'], $today, $time, 'Present']);
    }
    @unlink($tmpname);
    echo json_encode(['success'=>true, 'name'=>$best['name'], 'photo'=>$best['photo'], 'distance'=>$bestdist]);
    exit;
} else {
    @unlink($tmpname);
    echo json_encode(['success'=>false, 'message'=>'No matching user found (distance: '.$bestdist.')']);
    exit;
}
?>
<?php include 'footer.php'; ?>
