<?php include 'header.php'; ?>
<?php
session_start();
include 'db.php';
include 'helpers.php';
if (!isset($_SESSION['role']) || $_SESSION['role']!=='admin') { header('Location: login.php'); exit; }
if ($_SERVER['REQUEST_METHOD']!=='POST') { header('Location: admin_manage.php'); exit; }
$name = $_POST['name']; $username = $_POST['username']; $password = $_POST['password'];
// upload photo
$uploads_dir = __DIR__ . '/uploads'; if (!is_dir($uploads_dir)) mkdir($uploads_dir,0755,true);
$ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
$filename = uniqid('u_') . '.' . $ext;
$dest = $uploads_dir . '/' . $filename;
move_uploaded_file($_FILES['photo']['tmp_name'], $dest);
$ahash = image_ahash_from_file($dest);
$pdo->prepare('INSERT INTO users (name, photo, ahash, username, password, role) VALUES (?,?,?,?,?,?)')->execute([$name,'uploads/'.$filename,$ahash,$username,$password,'student']);
header('Location: admin_manage.php');
exit;
?>
<?php include 'footer.php'; ?>
