<?php
session_start();

// Cek apakah admin sudah login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../config/database.php';

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_barang = trim($_POST['nama_barang']);
    $harga_awal = trim($_POST['harga_awal']);
    $harga_diskon = trim($_POST['harga_diskon']);

    if (empty($nama_barang) || empty($harga_awal) || empty($harga_diskon)) {
        $error = "Semua kolom harus diisi!";
    } else {
        try {
            $db = new Database();
            $conn = $db->conn;

            $stmt = $conn->prepare("INSERT INTO barang (nama_barang, harga_awal, harga_diskon) VALUES (?, ?, ?)");
            if ($stmt->execute([$nama_barang, $harga_awal, $harga_diskon])) {
                $success = "Barang berhasil ditambahkan!";
            } else {
                $error = "Gagal menambahkan barang!";
            }
        } catch (PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Barang</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="container">
        <h1>Tambah Barang</h1>

        <?php if ($error): ?>
            <p style="color: red;"><?= $error ?></p>
        <?php endif; ?>

        <?php if ($success): ?>
            <p style="color: green;"><?= $success ?></p>
        <?php endif; ?>

        <form action="" method="POST">
            <label for="nama_barang">Nama Barang:</label>
            <input type="text" name="nama_barang" required>

            <label for="harga_awal">Harga Awal:</label>
            <input type="number" name="harga_awal" required>

            <label for="harga_diskon">Harga Diskon:</label>
            <input type="number" name="harga_diskon" required>

            <button type="submit">Tambah Barang</button>
        </form>

        <a href="dashboard_admin.php">Kembali ke Dashboard</a>
    </div>
</body>
</html>