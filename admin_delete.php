<?php
session_start();
include 'db.php';
if (!isset($_SESSION['role']) || $_SESSION['role']!=='admin') { header('Location: login.php'); exit; }
$id = $_GET['id'] ?? 0;
$pdo->prepare('DELETE FROM users WHERE id=?')->execute([$id]);
header('Location: admin_manage.php'); exit;
?>