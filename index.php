<?php include 'db.php'; ?>
<?php include 'header.php'; ?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Face Attendance System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">FaceAttendance</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="attendance.php">Mark Attendance</a></li>
        <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
        <li class="nav-item"><a class="nav-link" href="admin.php">Admin</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container py-4">
  <div class="card p-4 mb-4">
    <h1 class="h3">Web Attendance (PHP + MySQL)</h1>
    <p class="lead">Prototype using webcam capture and simple image hashing for matching.</p>
    <hr>
    <a class="btn btn-primary" href="register.php">Register User</a>
    <a class="btn btn-success" href="attendance.php">Mark Attendance</a>
  </div>
</div>
</body>
</html>
<?php include 'footer.php'; ?>
