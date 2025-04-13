<?php
session_start();

// Pastikan hanya admin yang bisa menghapus pengguna
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../models/User.php';

$userModel = new User();

// Cek apakah parameter ID tersedia
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: daftar_user.php?error=ID pengguna tidak valid.");
    exit();
}

$id = $_GET['id'];

// Pastikan user yang ingin dihapus benar-benar ada
$user = $userModel->getUserById($id);
if (!$user) {
    header("Location: daftar_user.php?error=Pengguna tidak ditemukan.");
    exit();
}

// Lanjutkan penghapusan
$deleted = $userModel->deleteUser($id);

if ($deleted) {
    header("Location: daftar_user.php?success=Pengguna berhasil dihapus.");
} else {
    header("Location: daftar_user.php?error=Gagal menghapus pengguna.");
}
exit();
?>
