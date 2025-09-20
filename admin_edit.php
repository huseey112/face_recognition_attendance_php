<?php include 'header.php'; ?>
<?php
session_start();
include 'db.php';
include 'helpers.php';
if (!isset($_SESSION['role']) || $_SESSION['role']!=='admin') { header('Location: login.php'); exit; }
$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare('SELECT * FROM users WHERE id=?'); $stmt->execute([$id]); $u = $stmt->fetch();
if (!$u) { header('Location: admin_manage.php'); exit; }
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name']; $password = $_POST['password'] ?? '';
    if (!empty($_FILES['photo']['name'])) {
        $uploads_dir = __DIR__ . '/uploads'; if (!is_dir($uploads_dir)) mkdir($uploads_dir,0755,true);
        $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $filename = uniqid('u_') . '.' . $ext;
        $dest = $uploads_dir . '/' . $filename; move_uploaded_file($_FILES['photo']['tmp_name'], $dest);
        $ahash = image_ahash_from_file($dest); $photo_path = 'uploads/'.$filename;
        if (empty($password)) $pdo->prepare('UPDATE users SET name=?, photo=?, ahash=? WHERE id=?')->execute([$name,$photo_path,$ahash,$id]);
        else $pdo->prepare('UPDATE users SET name=?, photo=?, ahash=?, password=? WHERE id=?')->execute([$name,$photo_path,$ahash,$password,$id]);
    } else {
        if (empty($password)) $pdo->prepare('UPDATE users SET name=? WHERE id=?')->execute([$name,$id]);
        else $pdo->prepare('UPDATE users SET name=?, password=? WHERE id=?')->execute([$name,$password,$id]);
    }
    header('Location: admin_manage.php'); exit;
}
?>
<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Edit Student</title>
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
  <h3>Edit Student</h3>
  <form method="post" enctype="multipart/form-data">
    <div class="mb-3"><label>Name</label><input name="name" class="form-control" value="<?=htmlspecialchars($u['name'])?>"></div>
    <div class="mb-3"><label>Password (leave blank to keep)</label><input name="password" class="form-control"></div>
    <div class="mb-3"><label>Change Photo</label><input type="file" name="photo" accept="image/*" class="form-control"></div>
    <button class="btn btn-primary">Save</button> <a class="btn btn-secondary" href="admin_manage.php">Cancel</a>
  </form>
</div></body></html>

<?php include 'footer.php'; ?>
