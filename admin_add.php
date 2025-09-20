<?php include 'header.php'; ?>
<?php
session_start();
include 'db.php';
if (!isset($_SESSION['role']) || $_SESSION['role']!=='admin') { header('Location: login.php'); exit; }
?>
<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Add Student</title>
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
  <h3>Add Student</h3>
  <form action="admin_add_action.php" method="post" enctype="multipart/form-data">
    <div class="mb-3"><label>Name</label><input name="name" class="form-control" required></div>
    <div class="mb-3"><label>Username</label><input name="username" class="form-control" required></div>
    <div class="mb-3"><label>Password</label><input name="password" class="form-control" required></div>
    <div class="mb-3"><label>Photo</label><input type="file" name="photo" accept="image/*" class="form-control" required></div>
    <button class="btn btn-success">Add Student</button> <a class="btn btn-secondary" href="admin_manage.php">Cancel</a>
  </form>
</div></body></html>

<?php include 'footer.php'; ?>
