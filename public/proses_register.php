<?php
require_once __DIR__ . '/../src/bootstrap.php';

use App\Controllers\AuthController;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $auth = new AuthController($pdo);
    $result = $auth->register($_POST);

    if ($result['success']) {
        $_SESSION['flash_message'] = "Registrasi sukses! Silakan login.";
        header("location: login.php");
        exit();
    } else {
        $_SESSION['register_errors'] = $result['errors'];
        $_SESSION['register_input'] = $_POST;
        header("location: register.php");
        exit();
    }
}

header("location: register.php");
exit();
