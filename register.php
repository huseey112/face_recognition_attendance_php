<?php include 'db.php'; session_start(); ?>
<?php include 'header.php'; ?>
<!doctype html>
<html><head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Register - Face Attendance</title>
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
  <h3>Student Registration</h3>
  <div class="row">
    <div class="col-md-6">
      <label>Full name</label>
      <input id="name" class="form-control mb-2" placeholder="Full name">
      <label>Username</label>
      <input id="username" class="form-control mb-2" placeholder="username">
      <label>Password</label>
      <input id="password" type="password" class="form-control mb-2" placeholder="password">
      <video id="video" width="100%" autoplay playsinline></video>
      <button id="capture" class="btn btn-success mt-2">Capture & Register</button>
      <div id="msg" class="mt-2"></div>
    </div>
    <div class="col-md-6">
      <p>Or upload photo</p>
      <form id="uploadForm" action="register_action.php" method="post" enctype="multipart/form-data">
        <input type="text" name="name" id="name2" class="form-control mb-2" placeholder="Full name">
        <input type="text" name="username" id="username2" class="form-control mb-2" placeholder="username">
        <input type="text" name="password" id="password2" class="form-control mb-2" placeholder="password">
        <input type="file" name="photo" accept="image/*" class="form-control mb-2" required>
        <button class="btn btn-primary" type="submit">Upload & Register</button>
      </form>
    </div>
  </div>
  <p class="mt-3"><a href="index.php">Home</a></p>
</div>
<script>
const video = document.getElementById('video');
navigator.mediaDevices.getUserMedia({video:true}).then(s=>{video.srcObject = s;}).catch(e=>console.log('Camera error',e));
function dataURLtoFile(dataurl, filename) {
  var arr = dataurl.split(','), mime = arr[0].match(/:(.*?);/)[1],
      bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);
  while(n--){ u8arr[n] = bstr.charCodeAt(n); }
  return new File([u8arr], filename, {type:mime});
}
document.getElementById('capture').onclick = async ()=>{
  const name = document.getElementById('name').value.trim();
  const username = document.getElementById('username').value.trim();
  const password = document.getElementById('password').value.trim();
  if (!name || !username || !password) return alert('Enter name, username and password');
  const canvas = document.createElement('canvas');
  canvas.width = video.videoWidth || 640;
  canvas.height = video.videoHeight || 480;
  canvas.getContext('2d').drawImage(video,0,0,canvas.width,canvas.height);
  const dataUrl = canvas.toDataURL('image/jpeg');
  const file = dataURLtoFile(dataUrl, 'capture.jpg');
  const fd = new FormData();
  fd.append('name', name);
  fd.append('username', username);
  fd.append('password', password);
  fd.append('photo', file);
  const res = await fetch('register_action.php', {method:'POST', body: fd});
  const txt = await res.text();
  document.getElementById('msg').innerHTML = txt;
};
</script>
</body></html>
<?php include 'footer.php'; ?>
