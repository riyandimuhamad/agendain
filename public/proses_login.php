<?php
require_once __DIR__ . '/../src/bootstrap.php';

use App\Controllers\AuthController;

// Jika sudah login, redirect ke dashboard
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("location: dashboard.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $auth = new AuthController($pdo);
    $result = $auth->login($email, $password);

    if ($result['success']) {
        header("location: dashboard.php");
        exit();
    } else {
        $_SESSION['login_errors'] = $result['errors'];
        $_SESSION['login_input'] = ['email' => $email];
        header("location: login.php");
        exit();
    }
}

// Jika diakses langsung
header("location: login.php");
exit();
