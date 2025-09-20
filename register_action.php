<?php include 'header.php'; ?>
<?php
include 'db.php';
include 'helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { echo 'Invalid request'; exit; }
$name = trim($_POST['name'] ?? '');
$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

if (empty($name) || empty($username) || empty($password)) { echo '<div class="alert alert-danger">Name, username and password required</div>'; exit; }

if (!isset($_FILES['photo'])) { echo '<div class="alert alert-danger'> exit; }

// check username exists
$stmt = $pdo->prepare('SELECT id FROM users WHERE username=?');
$stmt->execute([$username]);
if ($stmt->fetch()) { echo '<div class="alert alert-danger">Username already taken</div>'; exit; }

$uploads_dir = __DIR__ . '/uploads';
if (!is_dir($uploads_dir)) mkdir($uploads_dir,0755,true);

$photo = $_FILES['photo'];
$ext = pathinfo($photo['name'], PATHINFO_EXTENSION);
$filename = uniqid('u_') . '.' . $ext;
$dest = $uploads_dir . '/' . $filename;
if (!move_uploaded_file($photo['tmp_name'], $dest)) { echo '<div class="alert alert-danger">Upload failed</div>'; exit; }

$ahash = image_ahash_from_file($dest);
if ($ahash === false) { unlink($dest); echo '<div class="alert alert-danger">Invalid image</div>'; exit; }

// insert to DB (role = student)
$stmt = $pdo->prepare('INSERT INTO users (name, photo, ahash, username, password, role) VALUES (?, ?, ?, ?, ?, ?)');
$stmt->execute([$name, 'uploads/'.$filename, $ahash, $username, $password, 'student']);

echo '<div class="alert alert-success">Registered successfully. You can now <a href="login.php">Login</a></div>';
?>
<?php include 'footer.php'; ?>
