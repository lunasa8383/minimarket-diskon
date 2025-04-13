<?php
session_start();

// Cek apakah admin sudah login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../config/database.php';

$db = new Database();
$conn = $db->conn;

// Pastikan ada parameter ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: dashboard_admin.php");
    exit();
}

$id = $_GET['id'];

try {
    // Hapus barang berdasarkan ID
    $stmt = $conn->prepare("DELETE FROM barang WHERE id = ?");
    if ($stmt->execute([$id])) {
        header("Location: dashboard_admin.php?success=Barang berhasil dihapus!");
        exit();
    } else {
        header("Location: dashboard_admin.php?error=Gagal menghapus barang!");
        exit();
    }
} catch (PDOException $e) {
    header("Location: dashboard_admin.php?error=Error: " . $e->getMessage());
    exit();
}
?>