<?php
session_start();

// Hanya admin yang bisa menghapus barang
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../models/Barang.php';

$barangModel = new Barang();

// Pastikan parameter id tersedia
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: daftar_barang.php?error=ID barang tidak ditemukan");
    exit();
}

$id = $_GET['id'];
$barang = $barangModel->getById($id);

// Pastikan barang ada
if (!$barang) {
    header("Location: daftar_barang.php?error=Barang tidak ditemukan");
    exit();
}

// Lakukan penghapusan
$deleted = $barangModel->delete($id);

if ($deleted) {
    header("Location: daftar_barang.php?success=Barang berhasil dihapus");
    exit();
} else {
    header("Location: daftar_barang.php?error=Gagal menghapus barang");
    exit();
}

?>