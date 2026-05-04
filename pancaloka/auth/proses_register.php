<?php
session_start();
require '../config/database.php';

$nama = $_POST['nama'];
$email = $_POST['email'];
$password = $_POST['password'];
$confirm = $_POST['confirm_password'];

// Validasi sederhana
if(strlen($password) < 6){
    $_SESSION['error'] = "Password minimal 6 karakter!";
    header("Location: register.php");
    exit;
}

if($password !== $confirm){
    $_SESSION['error'] = "Konfirmasi password tidak cocok!";
    header("Location: register.php");
    exit;
}

// Cek email sudah ada atau belum
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$email]);

if($stmt->rowCount() > 0){
    $_SESSION['error'] = "Email sudah terdaftar!";
    header("Location: register.php");
    exit;
}

// Hash password
$hash = password_hash($password, PASSWORD_DEFAULT);

// Insert user (default role: user)
$stmt = $conn->prepare("INSERT INTO users (nama, email, password) VALUES (?, ?, ?)");
$stmt->execute([$nama, $email, $hash]);

$_SESSION['success'] = "Registrasi berhasil! Silakan login.";
header("Location: login.php");