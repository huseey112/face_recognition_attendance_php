<?php
if (session_status() == PHP_SESSION_NONE) session_start();
?>
<!doctype html><html><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Face Attendance</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="assets/styles.css">
</head><body class="<?php echo (isset($_SESSION['theme']) && $_SESSION['theme']=='dark')? 'dark-mode':''; ?>">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php"><img src="assets/logo.png" alt="logo" width="30" height="30" class="me-2"> FaceAttendance</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="index.php"><i class="fas fa-home"></i> Home</a></li>
        <?php if(isset($_SESSION['user_id'])): ?>
          <li class="nav-item"><a class="nav-link" href="student_dashboard.php"><i class="fas fa-user"></i> Dashboard</a></li>
        <?php endif; ?>
        <?php if(isset($_SESSION['role']) && $_SESSION['role']==='admin'): ?>
          <li class="nav-item"><a class="nav-link" href="admin.php"><i class="fas fa-tachometer-alt"></i> Admin</a></li>
        <?php endif; ?>
      </ul>
      <div class="d-flex align-items-center">
        <div class="form-check form-switch text-light me-3">
          <input class="form-check-input" type="checkbox" id="themeToggle">
          <label class="form-check-label text-light" for="themeToggle"><i class="fas fa-moon"></i></label>
        </div>
        <?php if(isset($_SESSION['username'])): ?>
          <div class="me-2 text-light d-none d-md-block">Hi, <strong><?=htmlspecialchars($_SESSION['username'])?></strong></div>
          <a class="btn btn-outline-light btn-sm" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        <?php else: ?>
          <a class="btn btn-outline-light btn-sm" href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>
<div class="container py-4">
