<?php include 'header.php'; ?>
<?php
session_start();
include 'db.php';
if (!isset($_SESSION['role']) || $_SESSION['role']!=='admin') { header('Location: login.php'); exit; }
?>
<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Manage Students</title>
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
  <h3>Manage Students</h3>
  <p><a href="admin.php">Back to Admin</a> | <a href="admin_add.php" class="btn btn-success">Add Student</a></p>
  <div class='table-responsive'><div class="table-responsive"><table class="table table-striped">
    <thead><tr><th>ID</th><th>Name</th><th>Username</th><th>Photo</th><th>Actions</th></tr></thead><tbody>
<?php
$rows = $pdo->query('SELECT * FROM users WHERE role="student" ORDER BY id DESC')->fetchAll();
foreach ($rows as $r) {
  echo '<tr><td>'.$r['id'].'</td><td>'.htmlspecialchars($r['name']).'</td><td>'.htmlspecialchars($r['username']).'</td><td><img src="'.htmlspecialchars($r['photo']).'" width="80"></td><td><a class="btn btn-sm btn-primary" href="admin_edit.php?id='.$r['id'].'">Edit</a> <a class="btn btn-sm btn-danger" href="admin_delete.php?id='.$r['id'].'" onclick="return confirm(\'Delete?\')">Delete</a></td></tr>';
}
?>
    </tbody>
  </table></div></div>
</div>
</body></html>

<?php include 'footer.php'; ?>
