<?php
session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Barang.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: daftar_barang.php");
    exit();
}

$id = $_GET['id'];
$barangModel = new Barang();
$barang = $barangModel->getById($id);

if (!$barang) {
    header("Location: daftar_barang.php?error=Barang tidak ditemukan!");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Barang</title>
    <style>
        * { box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #dff6ff, #e8f0fe);
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 700px;
            margin: 50px auto;
            padding: 40px;
            background-color: #ffffff;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #2c3e50;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        td {
            padding: 14px 10px;
            vertical-align: top;
            color: #444;
        }

        td:first-child {
            font-weight: 600;
            width: 200px;
            color: #34495e;
        }

        .btn {
            display: inline-block;
            text-decoration: none;
            background-color: #3498db;
            color: white;
            padding: 12px 18px;
            border-radius: 10px;
            margin-right: 12px;
            font-size: 15px;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #2980b9;
        }

        .btn:last-child {
            background-color: #2ecc71;
        }

        .btn:last-child:hover {
            background-color: #27ae60;
        }

        @media (max-width: 600px) {
            .container {
                padding: 24px;
            }

            table td:first-child {
                width: 120px;
            }

            .btn {
                display: block;
                width: 100%;
                margin-bottom: 12px;
            }

            .btn:last-child {
                margin-right: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Detail Barang</h1>

        <table>
            
            <tr><td>Nama Barang</td><td>: <?= htmlspecialchars($barang['nama_barang']) ?></td></tr>
            <tr><td>Harga</td><td>: Rp <?= number_format($barang['harga'], 0, ',', '.') ?></td></tr>
            <tr><td>Diskon</td><td>: <?= $barang['diskon'] ?>%</td></tr>
            <tr><td>Harga Setelah Diskon</td>
                <td>: Rp <?= number_format($barang['harga'] - ($barang['harga'] * $barang['diskon'] / 100), 0, ',', '.') ?></td>
            </tr>
            <tr><td>Stok</td><td>: <?= $barang['stok'] ?></td></tr>
        </table>

        <a href="daftar_barang.php" class="btn">Kembali</a>

        <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'user'): ?>
            <a href="tambah_transaksi.php?id_barang=<?= $barang['id_barang'] ?>" class="btn">Beli Sekarang</a>
        <?php endif; ?>
    </div>
</body>
</html>
