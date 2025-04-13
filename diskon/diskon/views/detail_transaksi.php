<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "ID Transaksi tidak valid.";
    exit();
}

require_once '../config/database.php';

$db = new Database();
$conn = $db->getConnection();

$id_transaksi = $_GET['id'];
$id_user = ($_SESSION['user_id']);

// Ambil detail transaksi berdasarkan ID dan user login
$sql = "SELECT t.*, b.* 
        FROM transaksi t 
        JOIN barang b ON t.id_barang = b.id_barang 
        WHERE t.id_transaksi = :id_transaksi ";
        
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id_transaksi', $id_transaksi);
// $stmt->bindParam(':id_user', $id_user);
$stmt->execute();
$transaksi = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$transaksi) {
    echo "Transaksi tidak ditemukan. Pastikan ID dan kepemilikannya sesuai.";
    exit();
}
$subtotal = $transaksi['harga'] * $transaksi['jumlah_barang'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Transaksi</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f6fa;
            padding: 40px 20px;
        }

        .container {
            max-width: 700px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #1a73e8;
            margin-bottom: 30px;
        }

        .info {
            margin-bottom: 15px;
        }

        .info label {
            font-weight: bold;
            color: #444;
            display: inline-block;
            width: 180px;
        }

        .info span {
            color: #333;
        }

        .btn-kembali {
            margin-top: 30px;
            display: block;
            width: 240px;
            text-align: center;
            background-color: #1a73e8;
            color: white;
            text-decoration: none;
            padding: 12px 18px;
            border-radius: 6px;
            margin-left: auto;
            margin-right: auto;
            transition: background-color 0.3s ease;
        }

        .btn-kembali:hover {
            background-color: #0f5ec7;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Detail Transaksi</h2>

    <div class="info"><label>ID Transaksi:</label> <span><?= $transaksi['id_transaksi'] ?></span></div>
    <div class="info"><label>Nama Barang:</label> <span><?= htmlspecialchars($transaksi['nama_barang']) ?></span></div>
    <div class="info"><label>Harga Barang:</label> <span><?= number_format($transaksi['harga'], 0, ',', '.') ?></span></div>
    <div class="info"><label>Jumlah Barang:</label> <span><?= $transaksi['jumlah_barang'] ?></span></div>
    <div class="info"><label>Total Harga:</label> <span>Rp <?= number_format($subtotal, 0, ',', '.') ?></span></div>
    <div class="info"><label>Diskon:</label> <span><?= $transaksi['diskon'] ?>%</span></div>
    <div class="info"><label>Total Bayar (Setelah Diskon):</label> <span>Rp <?= number_format($transaksi['total_harga'], 0, ',', '.') ?></span></div>
    <div class="info"><label>Tanggal Transaksi:</label> <span><?= $transaksi['tanggal_transaksi'] ?></span></div>
    <div class="info"><label>Metode Pembayaran:</label> <span><?= htmlspecialchars($transaksi['metode_pembayaran']) ?></span></div>

    <a href="daftar_transaksi_admin.php" class="btn-kembali">← Kembali ke Daftar Transaksi</a>
</div>
</body>
</html>
