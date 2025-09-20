<?php include 'header.php'; ?>
<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }
$uid = $_SESSION['user_id'];
$user = $pdo->prepare('SELECT * FROM users WHERE id=?');
$user->execute([$uid]);
$u = $user->fetch();
?>
<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Student Dashboard</title>
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
  <h3>Student Dashboard</h3>
  <p>Welcome, <strong><?=htmlspecialchars($u['name'])?></strong> | <a href="logout.php">Logout</a></p>
  <div class="row">
    <div class="col-md-4">
      <div class="card">
        <div class="card-body text-center">
          <?php if ($u['photo']): ?><img src="<?=htmlspecialchars($u['photo'])?>" class="img-fluid rounded mb-2"><?php endif; ?>
          <h5><?=htmlspecialchars($u['name'])?></h5>
          <p>Username: <?=htmlspecialchars($u['username'])?></p>
          <a class="btn btn-primary btn-responsive btn-icon" href="attendance.php"><i class="fas fa-user-check"></i> Mark Attendance</a>
          <a class="btn btn-secondary btn-responsive btn-icon" href="student_edit.php"><i class="fas fa-edit"></i> Edit Profile</a>
        </div>
      </div>
    </div>
    <div class="col-md-8">
      <h5>Your Attendance</h5>
      <div class='table-responsive'><table class="table table-striped">
        <thead><tr><th>Date</th><th>Time</th><th>Status</th></tr></thead>
        <tbody>
<?php
$stmt = $pdo->prepare('SELECT * FROM attendance WHERE user_id=? ORDER BY date DESC, time DESC');
$stmt->execute([$uid]);
while ($r = $stmt->fetch()) {
  echo '<tr><td>'.$r['date'].'</td><td>'.$r['time'].'</td><td>'.$r['status'].'</td></tr>';
}
?>
        </tbody>
      </table></div>
      <a class="btn btn-outline-primary" href="student_export.php">Export My Attendance (CSV)</a>
    </div>
  </div>
</div>
</body></html>

<?php include 'footer.php'; ?>
