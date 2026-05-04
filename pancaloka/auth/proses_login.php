<?php
session_start();
require '../config/database.php';

$email = $_POST['email'];
$password = $_POST['password'];
$captcha = $_POST['captcha'];

// 1. Validasi CAPTCHA
if($captcha != $_SESSION['captcha']){
    $_SESSION['error'] = "CAPTCHA salah!";
    header("Location: login.php");
    exit;
}

// 2. Cek user
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if($user && password_verify($password, $user['password'])){
    
    // set session
    $_SESSION['login'] = true;
    $_SESSION['id'] = $user['id'];
    $_SESSION['nama'] = $user['nama'];
    $_SESSION['role'] = $user['role'];

    // redirect berdasarkan role
    if($user['role'] == 'admin'){
        header("Location: ../dashboard/admin.php");
    } elseif($user['role'] == 'staf'){
        header("Location: ../dashboard/staf.php");
    } else {
        header("Location: ../dashboard/user.php");
    }

} else {
    $_SESSION['error'] = "Email atau password salah!";
    header("Location: login.php");
}