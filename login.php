<?php include 'header.php'; ?>
<?php

include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'] ?? '';
    $pass = $_POST['password'] ?? '';

    // check admins table first
    $stmt = $pdo->prepare('SELECT * FROM admins WHERE username=? AND password=?');
    $stmt->execute([$user, $pass]);
    $admin = $stmt->fetch();
    if ($admin) {
        $_SESSION['role'] = 'admin';
        $_SESSION['username'] = $admin['username'];
        header('Location: admin.php'); exit;
    }

    // check users table
    $stmt = $pdo->prepare('SELECT * FROM users WHERE username=? AND password=?');
    $stmt->execute([$user, $pass]);
    $u = $stmt->fetch();
    if ($u) {
        $_SESSION['role'] = $u['role'];
        $_SESSION['user_id'] = $u['id'];
        $_SESSION['username'] = $u['username'];
        if ($u['role'] === 'admin') {
            header('Location: admin.php'); exit;
        } else {
            header('Location: student_dashboard.php'); exit;
        }
    } else {
        $error = "Invalid credentials";
    }
}
?>
<!doctype html><html><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Login</title>
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

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">Login</div>
        <div class="card-body">
          <?php if (!empty($error)) echo '<div class="alert alert-danger">'.$error.'</div>'; ?>
          <form method="post">
            <div class="mb-3">
              <label class="form-label">Username</label>
              <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Password</label>
              <input type="password" name="password" class="form-control" required>
            </div>
            <button class="btn btn-primary w-100" type="submit">Login</button>
          </form>
        </div>
      </div>
      <p class="mt-2 text-center"><a href="register.php">Register as Student</a></p>
    </div>
  </div>
</div>
</body></html>

<?php include 'footer.php'; ?>
