<?php include 'header.php'; ?>
<?php
session_start();
include 'db.php';
include 'helpers.php';
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }
$uid = $_SESSION['user_id'];
$user = $pdo->prepare('SELECT * FROM users WHERE id=?');
$user->execute([$uid]);
$u = $user->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? $u['name'];
    $password = $_POST['password'] ?? '';
    // handle photo upload optional
    if (!empty($_FILES['photo']['name'])) {
        $uploads_dir = __DIR__ . '/uploads';
        if (!is_dir($uploads_dir)) mkdir($uploads_dir,0755,true);
        $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $filename = uniqid('u_') . '.' . $ext;
        $dest = $uploads_dir . '/' . $filename;
        move_uploaded_file($_FILES['photo']['tmp_name'], $dest);
        $ahash = image_ahash_from_file($dest);
        $photo_path = 'uploads/'.$filename;
        $stmt = $pdo->prepare('UPDATE users SET name=?, photo=?, ahash=?'.(empty($password)?'':' , password=?').' WHERE id=?');
        if (empty($password)) $stmt->execute([$name, $photo_path, $ahash, $uid]);
        else $stmt->execute([$name, $photo_path, $ahash, $password, $uid]);
    } else {
        if (empty($password)) {
            $stmt = $pdo->prepare('UPDATE users SET name=? WHERE id=?');
            $stmt->execute([$name, $uid]);
        } else {
            $stmt = $pdo->prepare('UPDATE users SET name=?, password=? WHERE id=?');
            $stmt->execute([$name, $password, $uid]);
        }
    }
    header('Location: student_dashboard.php'); exit;
}
?>
<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Edit Profile</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head><body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php"><i class="fas fa-user-check"></i> Face Attendance</a>
    <div class="d-flex">
      <div class="form-check form-switch text-light me-3">
        <input class="form-check-input" type="checkbox" id="themeToggle">
        <label class="form-check-label" for="themeToggle">🌙</label>
      </div>
      <a href="logout.php" class="btn btn-outline-light btn-sm"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
  </div>
</nav>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<script>
document.addEventListener('DOMContentLoaded', ()=>{
  const toggle = document.getElementById('themeToggle');
  if (localStorage.getItem('theme') === 'dark') {
    document.body.classList.add('dark-mode');
    toggle.checked = true;
  }
  toggle.addEventListener('change', ()=>{
    document.body.classList.toggle('dark-mode');
    localStorage.setItem('theme', document.body.classList.contains('dark-mode') ? 'dark' : 'light');
  });
});
</script>
<style>
body.dark-mode { background-color:#121212; color:#f1f1f1; }
body.dark-mode .card { background-color:#1e1e1e; color:#fff; }
body.dark-mode .table { color:#fff; }
.table-responsive { overflow-x:auto; }
</style>

<div class="container py-4">
  <h3>Edit Profile</h3>
  <form method="post" enctype="multipart/form-data">
    <div class="mb-3">
      <label class="form-label">Name</label>
      <input name="name" class="form-control" value="<?=htmlspecialchars($u['name'])?>">
    </div>
    <div class="mb-3">
      <label class="form-label">Password (leave blank to keep)</label>
      <input name="password" type="text" class="form-control">
    </div>
    <div class="mb-3">
      <label class="form-label">Change Photo</label>
      <input name="photo" type="file" accept="image/*" class="form-control">
    </div>
    <button class="btn btn-primary">Save</button>
    <a class="btn btn-secondary" href="student_dashboard.php">Cancel</a>
  </form>
</div>
</body></html>

<?php include 'footer.php'; ?>
