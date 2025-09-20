document.addEventListener('DOMContentLoaded', ()=>{
  const toggle = document.getElementById('themeToggle');
  const body = document.body;
  const saved = localStorage.getItem('theme');
  if (saved === 'dark') { body.classList.add('dark-mode'); if (toggle) toggle.checked = true; }
  if (toggle) {
    toggle.addEventListener('change', ()=>{
      body.classList.toggle('dark-mode');
      localStorage.setItem('theme', body.classList.contains('dark-mode') ? 'dark' : 'light');
    });
  }
});