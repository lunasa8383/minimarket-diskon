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

$error = "";
$success = "";

// Pastikan ada parameter ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: dashboard_admin.php");
    exit();
}

$id = $_GET['id'];

// Ambil data barang berdasarkan ID
$stmt = $conn->prepare("SELECT * FROM barang WHERE id = ?");
$stmt->execute([$id]);
$barang = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$barang) {
    header("Location: dashboard_admin.php");
    exit();
}

// Jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_barang = trim($_POST['nama_barang']);
    $harga_awal = trim($_POST['harga_awal']);
    $harga_diskon = trim($_POST['harga_diskon']);

    if (empty($nama_barang) || empty($harga_awal) || empty($harga_diskon)) {
        $error = "Semua kolom harus diisi!";
    } else {
        try {
            $stmt = $conn->prepare("UPDATE barang SET nama_barang = ?, harga_awal = ?, harga_diskon = ? WHERE id = ?");
            if ($stmt->execute([$nama_barang, $harga_awal, $harga_diskon, $id])) {
                $success = "Barang berhasil diperbarui!";
                // Perbarui data barang yang ditampilkan
                $barang['nama_barang'] = $nama_barang;
                $barang['harga_awal'] = $harga_awal;
                $barang['harga_diskon'] = $harga_diskon;
            } else {
                $error = "Gagal memperbarui barang!";
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
    <title>Edit Barang</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="container">
        <h1>Edit Barang</h1>

        <?php if ($error): ?>
            <p style="color: red;"><?= $error ?></p>
        <?php endif; ?>

        <?php if ($success): ?>
            <p style="color: green;"><?= $success ?></p>
        <?php endif; ?>

        <form action="" method="POST">
            <label for="nama_barang">Nama Barang:</label>
            <input type="text" name="nama_barang" value="<?= htmlspecialchars($barang['nama_barang']) ?>" required>

            <label for="harga_awal">Harga Awal:</label>
            <input type="number" name="harga_awal" value="<?= htmlspecialchars($barang['harga_awal']) ?>" required>

            <label for="harga_diskon">Harga Diskon:</label>
            <input type="number" name="harga_diskon" value="<?= htmlspecialchars($barang['harga_diskon']) ?>" required>

            <button type="submit">Simpan Perubahan</button>
        </form>

        <a href="dashboard_admin.php">Kembali ke Dashboard</a>
    </div>
</body>
</html>