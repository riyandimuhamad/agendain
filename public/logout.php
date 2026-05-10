<?php
require_once __DIR__ . '/../src/bootstrap.php';
// Mulai session

// Hapus semua variabel session
$_SESSION = array();

// Hancurkan session
session_destroy();

// Redirect ke halaman login
header("location: login.php");
exit;
?>
