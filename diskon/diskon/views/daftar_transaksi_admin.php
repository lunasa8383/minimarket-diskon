<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

require_once '../config/database.php';

$db = new Database();
$conn = $db->getConnection();

$sql = "SELECT 
            transaksi.id_transaksi,
            transaksi.jumlah_barang,
            transaksi.total_harga,  
            transaksi.diskon,
            users.username AS nama_user, 
            barang.nama_barang 
        FROM transaksi 
        JOIN users ON transaksi.id_user = users.id_user 
        JOIN barang ON transaksi.id_barang = barang.id_barang";

$stmt = $conn->prepare($sql);
$stmt->execute();
$transaksi = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Transaksi (Admin)</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to right, #e3f2fd, #f9f9f9);
            margin: 0;
            padding: 20px;
        }
        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
        }
        table {
            width: 95%;
            margin: auto;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 16px rgba(0,0,0,0.05);
        }
        th, td {
            padding: 14px 18px;
            text-align: center;
            font-size: 15px;
        }
        th {
            background-color: #2196f3;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f1f1f1;
        }
        tr:hover {
            background-color: #e0f2ff;
        }
        td {
            color: #444;
        }
        .btn-detail {
            padding: 6px 10px;
            background-color: #1976d2;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 13px;
        }
        .btn-detail:hover {
            background-color: #0d47a1;
        }
        .btn-kembali {
            display: block;
            width: 160px;
            margin: 35px auto 0;
            padding: 12px 20px;
            text-align: center;
            background-color: #2196f3;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 15px;
            transition: background-color 0.3s ease;
        }
        .btn-kembali:hover {
            background-color: #1976d2;
        }
        @media (max-width: 768px) {
            table {
                width: 100%;
                font-size: 14px;
            }
            th, td {
                padding: 10px;
            }
            .btn-kembali {
                width: 90%;
            }
        }
    </style>
</head>
<body>
    <h2>Daftar Transaksi (Admin)</h2>
    <table id="tabelTransaksi">
        <thead>
            <tr>
                <th>No</th> <!-- Kolom untuk nomor otomatis -->
                <th>Nama User</th>
                <th>Nama Barang</th>
                <th>Jumlah Barang</th>
                <th>Total</th>
                <th>Diskon</th>
                <th>Detail</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($transaksi)) : ?>
                <?php $no = 1; // Inisialisasi nomor urut ?>
                <?php foreach ($transaksi as $row) : ?>
                    <tr>
                        <td><?= $no++ ?></td> <!-- Menampilkan nomor urut manual -->
                        <td><?= htmlspecialchars($row['nama_user']) ?></td>
                        <td><?= htmlspecialchars($row['nama_barang']) ?></td>
                        <td><?= $row['jumlah_barang'] ?></td>
                        <td>Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td> <!-- Ganti total dengan total_harga -->
                        <td><?= $row['diskon'] ?>%</td>
                        <td><a class="btn-detail" href="detail_transaksi.php?id=<?= $row['id_transaksi'] ?>">Detail</a></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="7">Belum ada data transaksi.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <a href="dashboard_admin.php" class="btn-kembali">← Kembali</a>

</body>
</html>
