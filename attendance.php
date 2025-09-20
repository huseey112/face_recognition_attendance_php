<?php include 'header.php'; ?>
<?php include 'db.php'; if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; } ?>
<!doctype html><html><head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Mark Attendance</title>
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
  <h3>Mark Attendance</h3>
  <p>Logged in as: <strong><?=htmlspecialchars($_SESSION['username'])?></strong> | <a href="logout.php">Logout</a></p>
  <video id="video" width="100%" autoplay playsinline></video>
  <button id="capture" class="btn btn-primary mt-2">Capture & Mark</button>
  <div id="result" class="mt-3"></div>
  <p class="mt-3"><a href="student_dashboard.php">My Dashboard</a></p>
</div>
<script>
const video = document.getElementById('video');
navigator.mediaDevices.getUserMedia({video:true}).then(s=>{video.srcObject=s;}).catch(e=>alert('Camera error: '+e));
document.getElementById('capture').onclick = async ()=>{
  const canvas = document.createElement('canvas');
  canvas.width = video.videoWidth || 640; canvas.height = video.videoHeight ||480;
  canvas.getContext('2d').drawImage(video,0,0,canvas.width,canvas.height);
  const dataUrl = canvas.toDataURL('image/jpeg', 0.9);
  document.getElementById('result').innerText = 'Processing...';
  const resp = await fetch('attendance_action.php', {
    method: 'POST',
    headers: {'Content-Type':'application/json'},
    body: JSON.stringify({image: dataUrl})
  });
  const data = await resp.json();
  if (data.success) {
    document.getElementById('result').innerHTML = '<div class="card mt-3"><div class="card-body"><h5 class="card-title"><i class="fas fa-check-circle text-success"></i> Welcome, ' + data.name + '</h5><img src="'+data.photo+'" class="img-thumbnail img-thumb" width="200"><p class="mt-2 text-success">Attendance recorded successfully.</p></div></div>';
  } else {
    document.getElementById('result').innerHTML = '<div class="alert alert-warning">' + data.message + '</div>';
  }
};
</script>
</body></html>

<?php include 'footer.php'; ?>
