<?php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../config/database.php';

$db = new Database();
$conn = $db->getConnection();

// Ambil data barang
$stmt = $conn->query("SELECT id_barang, nama_barang, harga, diskon FROM barang");
$barang = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard User</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #dff6ff, #e8f0fe);
            margin: 0;
            padding: 0;
        }

        .dashboard {
            max-width: 1000px;
            margin: 50px auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
            text-align: center;
            color: #2c3e50;
        }

        .barang-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .barang-item {
            background-color: #f9fbfc;
            border: 1px solid #dce3ea;
            border-radius: 12px;
            padding: 20px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .barang-item:hover {
            transform: scale(1.02);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }

        .barang-item h3 {
            margin-top: 0;
            color: #34495e;
        }

        .barang-item p {
            margin: 5px 0;
            color: #555;
        }

        .barang-item s {
            color: #999;
        }

        .barang-item strong {
            color: #e53935;
        }

        .barang-item a {
            display: inline-block;
            margin-top: 12px;
            padding: 10px 14px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .barang-item a:hover {
            background-color: #2980b9;
        }

        .logout {
            display: block;
            width: fit-content;
            margin: 40px auto 0;
            padding: 12px 24px;
            background-color: #f44336;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 15px;
            transition: background-color 0.3s ease;
        }

        .logout:hover {
            background-color: #d32f2f;
        }

        @media (max-width: 600px) {
            .barang-list {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <h1>Selamat Datang, <?= htmlspecialchars($_SESSION['username']) ?>!</h1>
        <h2>Daftar Barang Diskon</h2>
        <div class="barang-list">
            <?php if (!empty($barang)): ?>
                <?php foreach ($barang as $item): 
                    // Ambil harga awal dan hitung harga setelah diskon
                    $harga_awal   = $item['harga'] ?? 0;
                    $diskon       = $item['diskon'] ?? 0;
                    $harga_diskon = $harga_awal - ($harga_awal * $diskon / 100);
                ?>
                    <div class="barang-item">
                        <h3><?= htmlspecialchars($item['nama_barang']) ?></h3>
                        <p>Harga Awal: Rp <?= number_format($harga_awal, 0, ',', '.') ?></p>
                        <p>Harga Diskon: <strong>Rp <?= number_format($harga_diskon, 0, ',', '.') ?></strong></p>
                        <p>Diskon: <?= $diskon ?>%</p>
                        <a href="detail_barang.php?id=<?= $item['id_barang'] ?>">Lihat Detail</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align:center;">Tidak ada barang tersedia.</p>
            <?php endif; ?>
        </div>
        <a class="logout" href="logout.php">Logout</a>
    </div>
</body>
</html>
