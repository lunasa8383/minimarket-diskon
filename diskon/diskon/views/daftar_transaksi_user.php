<?php
session_start();
require_once '../config/database.php';

// Cek jika user belum login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Ambil koneksi database
$db = new Database();
$conn = $db->getConnection();

// Ambil data transaksi untuk user yang sedang login
$id_user = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM transaksi WHERE id_user = ?");
$stmt->execute([$id_user]);
$data_transaksi = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Transaksi Saya</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e9f0f7;
            margin: 0;
            padding: 40px 20px;
        }

        .container {
            max-width: 1000px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #1a73e8;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 6px;
            overflow: hidden;
        }

        th, td {
            padding: 14px 16px;
            text-align: center;
            border: 1px solid #ddd;
        }

        th {
            background-color: #1a73e8;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f4f8fc;
        }

        tr:hover {
            background-color: #e0eefa;
        }

        .btn-detail, .btn-kembali {
            display: inline-block;
            padding: 10px 16px;
            text-decoration: none;
            color: white;
            background-color: #1a73e8;
            border-radius: 6px;
            transition: background-color 0.3s ease;
            font-size: 14px;
        }

        .btn-detail:hover, .btn-kembali:hover {
            background-color: #0f5ec7;
        }

        .btn-kembali {
            margin-top: 30px;
            display: block;
            width: 220px;
            margin-left: auto;
            margin-right: auto;
            text-align: center;
        }

        .no-transaksi {
            text-align: center;
            font-style: italic;
            padding: 20px;
            background-color: #fff;
            color: #666;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Transaksi <?= htmlspecialchars($_SESSION['username']) ?></h2>

    <table>
        <thead>
        <tr>
            <th>No</th>
            <th>Nama Barang</th>
            <th>Jumlah</th>
            <th>Diskon</th>
            <th>Total Bayar</th>
            <th>Metode Pembayaran</th>
            <th>Tanggal Transaksi</th>
            <th>Detail</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($data_transaksi)): ?>
            <?php $no = 1; foreach ($data_transaksi as $row): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['nama_barang']) ?></td>
                    <td><?= $row['jumlah_barang'] ?></td>
                    <td><?= $row['diskon'] ?>%</td>
                    <td>Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                    <td><?= htmlspecialchars($row['metode_pembayaran']) ?></td>
                    <td><?= date('d-m-Y H:i', strtotime($row['tanggal_transaksi'])) ?></td>
                    <td><a class="btn-detail" href="detail_transaksi_user.php?id=<?= $row['id_transaksi'] ?>">Detail</a></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="8" class="no-transaksi">Belum ada transaksi.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>

    <a href="dashboard_user.php" class="btn-kembali">← Kembali ke Dashboard</a>
</div>
</body>
</html>