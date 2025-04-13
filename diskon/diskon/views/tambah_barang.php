<?php
session_start();

// Hanya admin yang boleh mengakses
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../models/Barang.php';

$barangModel = new Barang();
$error = ""; // ⬅️ fix warning undefined variable

// Proses saat form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_barang = trim($_POST['nama_barang']);
    $harga = floatval($_POST['harga']);
    $diskon = floatval($_POST['diskon']);
    $stok = intval($_POST['stok']);

    // Validasi sederhana
    if (empty($nama_barang) || $harga <= 0 || $stok < 0 || $diskon < 0) {
        $error = "Pastikan semua data diisi dengan benar.";
    } else {
        $created = $barangModel->create([
            'nama_barang' => $nama_barang,
            'harga' => $harga,
            'diskon' => $diskon,
            'stok' => $stok
        ]);

        if ($created) {
            header("Location: daftar_barang.php?success=Barang berhasil ditambahkan");
            exit();
        } else {
            $error = "Gagal menambahkan barang.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Barang</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to right, #e3f2fd, #ffffff);
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 420px;
            margin: 50px auto;
            background-color: #fff;
            padding: 25px 30px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        }

        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-top: 14px;
            color: #333;
            font-weight: 600;
        }

        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 8px 10px;
            margin-top: 6px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }

        .btn {
            display: block;
            width: 100%;
            margin-top: 22px;
            padding: 10px;
            font-size: 15px;
            background-color: #1976d2;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #0d47a1;
        }

        .error {
            color: red;
            text-align: center;
            margin-top: 12px;
            font-weight: bold;
        }

        .back {
            display: block;
            text-align: center;
            margin-top: 18px;
            color: #1976d2;
            text-decoration: none;
            font-weight: bold;
        }

        .back:hover {
            text-decoration: underline;
        }

        @media (max-width: 500px) {
            .container {
                margin: 20px 10px;
                padding: 20px;
            }

            input[type="text"],
            input[type="number"] {
                font-size: 13px;
            }

            .btn {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Tambah Barang</h2>
    
    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <label for="nama_barang">Nama Barang:</label>
        <input type="text" name="nama_barang" id="nama_barang" required>

        <label for="harga">Harga:</label>
        <input type="number" name="harga" id="harga" required min="0" step="100">

        <label for="diskon">Diskon (%):</label>
        <input type="number" name="diskon" id="diskon" required min="0" max="100" step="0.1">

        <label for="stok">Stok:</label>
        <input type="number" name="stok" id="stok" required min="0">

        <button type="submit" class="btn">Tambah Barang</button>
    </form>

    <a href="daftar_barang.php" class="back">← Kembali ke Daftar Barang</a>
</div>
</body>
</html>
