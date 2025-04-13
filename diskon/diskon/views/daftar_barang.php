<?php
require_once __DIR__ . '/../models/Barang.php';

session_start();

// Check if the user is logged in and has the correct role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: dashboard_user.php"); // Redirect to dashboard if not admin
    exit();
}

$barangModel = new Barang();
$barangList = $barangModel->getAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Daftar Barang</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(to right, #e3f2fd, #ffffff);
    }

    .wrapper {
      padding: 40px 20px;
      max-width: 1000px;
      margin: auto;
    }

    h2 {
      text-align: center;
      margin-bottom: 25px;
      color: #2c3e50;
    }

    .button-container {
      display: flex;
      justify-content: space-between;
      margin-bottom: 20px;
      flex-wrap: wrap;
    }

    .bottom-nav {
      display: flex;
      justify-content: flex-start;
      margin-top: 30px;
    }

    .btn {
      padding: 10px 20px;
      border: none;
      border-radius: 8px;
      background-color: #1976d2;
      color: white;
      font-size: 15px;
      font-weight: bold;
      text-decoration: none;
      transition: background-color 0.3s ease;
      margin: 5px 0;
    }

    .btn:hover {
      background-color: #0d47a1;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background-color: white;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.05);
    }

    th, td {
      padding: 14px 18px;
      text-align: center;
      border-bottom: 1px solid #ddd;
    }

    th {
      background-color: #1976d2;
      color: white;
    }

    tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    .aksi a {
      margin: 0 4px;
      padding: 6px 12px;
      border-radius: 6px;
      font-size: 13px;
      color: white;
      text-decoration: none;
    }

    .edit {
      background-color: #28a745;
    }

    .edit:hover {
      background-color: #218838;
    }

    .delete {
      background-color: #dc3545;
    }

    .delete:hover {
      background-color: #c82333;
    }

    /* Tambahkan jarak antara kolom pencarian dan tabel */
    div.dataTables_wrapper .dataTables_filter {
      margin-bottom: 15px;
    }

    @media (max-width: 768px) {
      .wrapper {
        padding: 20px 10px;
      }

      th, td {
        font-size: 13px;
        padding: 10px;
      }

      .btn {
        width: 100%;
        text-align: center;
      }

      .button-container,
      .bottom-nav {
        flex-direction: column;
        align-items: stretch;
      }
    }
  </style>
</head>
<body>

  <div class="wrapper">
    <h2>Daftar Barang</h2>

    <div class="button-container">
      <a class="btn" href="tambah_barang.php">Tambah Barang</a>
    </div>

    <table id="tabelBarang">
      <thead>
        <tr>
          <th>No</th>
          <th>ID Barang</th>
          <th>Nama Barang</th>
          <th>Harga</th>
          <th>Diskon (%)</th>
          <th>Harga Setelah Diskon</th>
          <th>Stok</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        $no = 1; 
        foreach ($barangList as $barang): ?>
          <?php 
            $harga_awal = $barang['harga']; 
            $diskon = $barang['diskon']; 
            $harga_diskon = $harga_awal - ($harga_awal * ($diskon / 100)); 
          ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($barang['id_barang']) ?></td>
            <td><?= htmlspecialchars($barang['nama_barang']) ?></td>
            <td>Rp <?= number_format($harga_awal, 0, ',', '.') ?></td>
            <td><?= $diskon ?>%</td>
            <td>Rp <?= number_format($harga_diskon, 0, ',', '.') ?></td>
            <td><?= htmlspecialchars($barang['stok']) ?></td>
            <td class="aksi">
              <a class="edit" href="edit_barang.php?id=<?= $barang['id_barang'] ?>">Edit</a>
              <a class="delete" href="hapus_barang.php?id=<?= $barang['id_barang'] ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus barang ini?');">Hapus</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <div class="bottom-nav">
      <a class="btn" href="dashboard_user.php">← Kembali ke Dashboard</a>
    </div>
  </div>
</body>
</html>