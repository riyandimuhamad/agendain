<?php
session_start();

// Hapus variabel session KHUSUS PESERTA
// Ini penting agar tidak mengganggu session panitia jika dibuka di browser yang sama
unset($_SESSION['participant_loggedin']);
unset($_SESSION['participant_account_id']);
unset($_SESSION['participant_email']);
unset($_SESSION['participant_nama']);

// Redirect ke halaman login peserta
header("location: login_participant.php");
exit;
?>