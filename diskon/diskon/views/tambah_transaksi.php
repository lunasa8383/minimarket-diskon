<?php
session_start();
require_once '../config/database.php';

// Cek jika user belum login atau bukan role 'user'
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

$db = new Database();
$conn = $db->getConnection();

$id_user = $_SESSION['user_id'];
$id_barang = isset($_GET['id_barang']) ? $_GET['id_barang'] : null;

// Cek jika id_barang ada
if (!$id_barang) {
    header("Location: daftar_barang.php");
    exit();
}

// Ambil data barang dari database
$stmt = $conn->prepare("SELECT id_barang, nama_barang, harga, diskon, stok FROM barang WHERE id_barang = ?");
$stmt->execute([$id_barang]);
$barang = $stmt->fetch();

// Cek jika barang tidak ditemukan
if (!$barang) {
    header("Location: daftar_barang.php?error=Barang tidak ditemukan!");
    exit();
}

// Handle submit form transaksi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jumlah_barang = $_POST['jumlah_barang'];
    $metode_pembayaran = $_POST['metode_pembayaran'];

    // Validasi jika jumlah barang melebihi stok
    if ($jumlah_barang > $barang['stok']) {
        echo "<script>alert('Stok tidak mencukupi! Sisa stok hanya {$barang['stok']}'); window.history.back();</script>";
        exit();
    }

    // Validasi jika metode pembayaran kosong
    if (empty($metode_pembayaran)) {
        echo "<script>alert('Metode pembayaran wajib dipilih!'); window.history.back();</script>";
        exit();
    }

    // Hitung subtotal, diskon, dan total harga
    $subtotal = $barang['harga'] * $jumlah_barang;
    $diskon_rp = ($barang['diskon'] / 100) * $subtotal;
    $total_harga = $subtotal - $diskon_rp;

    // Masukkan data transaksi ke database
    $insert = $conn->prepare("INSERT INTO transaksi (id_user, id_barang, nama_barang, jumlah_barang, total_harga, diskon, metode_pembayaran, tanggal_transaksi) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $insert->execute([ 
        $id_user,
        $barang['id_barang'],
        $barang['nama_barang'],
        $jumlah_barang,
        $total_harga,
        $barang['diskon'],
        $metode_pembayaran,
        $tanggaltransaksi = date("Y-m-d")
    ]);

    // Update stok barang setelah transaksi
    $stok_baru = $barang['stok'] - $jumlah_barang;
    $update = $conn->prepare("UPDATE barang SET stok = ? WHERE id_barang = ?");
    $update->execute([$stok_baru, $barang['id_barang']]);

    // Redirect ke daftar transaksi user
    echo "<script>alert('Transaksi berhasil ditambahkan'); window.location.href='daftar_transaksi_user.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Transaksi</title>
    <style>
        body {
            font-family: sans-serif;
            background: #f1f7ff;
            margin: 0;
            padding: 0;
        }

        .container {
            display: flex;
            justify-content: space-between;
            margin: 40px auto;
            max-width: 900px;
            gap: 20px;
        }

        .form-box, .result-box {
            background: #fff;
            padding: 30px;
            border-radius: 14px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            flex: 1;
        }

        .form-box h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #2c3e50;
        }

        label {
            font-weight: 600;
            display: block;
            margin-bottom: 8px;
        }

        input[type="text"],
        input[type="number"],
        select {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        input[readonly] {
            background-color: #f4f4f4;
        }

        button {
            width: 100%;
            background: #007bff;
            color: white;
            padding: 14px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background: #0056b3;
        }

        .result-box h3 {
            margin-top: 0;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        .result-item {
            margin-bottom: 15px;
        }

        .result-item label {
            font-weight: 600;
        }

        .result-item span {
            display: block;
            font-size: 16px;
            color: #333;
        }
    </style>
</head>
<body>
    <form method="POST">
        <div class="container">
            <!-- Kolom Form -->
            <div class="form-box">
                <h2>Tambah Transaksi</h2>

                <label>Nama Barang:</label>
                <input type="text" value="<?= htmlspecialchars($barang['nama_barang']) ?>" readonly>

                <label>Harga:</label>
                <input type="text" value="Rp <?= number_format($barang['harga'], 0, ',', '.') ?>" readonly>

                <label>Diskon:</label>
                <input type="text" value="<?= $barang['diskon'] ?>%" readonly>

                <label for="jumlah_barang">Jumlah yang Dibeli:</label>
                <input type="number" name="jumlah_barang" id="jumlah_barang" min="1" max="<?= $barang['stok'] ?>" required oninput="updateTotal()">

                <label for="metode_pembayaran">Metode Pembayaran:</label>
                <select name="metode_pembayaran" id="metode_pembayaran" required>
                    <option value="">-- Pilih Metode Pembayaran --</option>
                    <option value="Cash">Cash</option>
                    <option value="Transfer">Transfer</option>
                </select>

                <button type="submit">Simpan Transaksi</button>
            </div>

            <!-- Kolom Perhitungan -->
            <div class="result-box">
                <h3>Perhitungan</h3>

                <div class="result-item">
                    <label>Subtotal (Harga x Jumlah):</label>
                    <span id="subtotal">Rp 0</span>
                </div>

                <div class="result-item">
                    <label>Diskon:</label>
                    <span id="diskon">Rp 0</span>
                </div>

                <div class="result-item">
                    <label>Total Harga Setelah Diskon:</label>
                    <span id="totalHarga">Rp 0</span>
                </div>
            </div>
        </div>
    </form>

    <script>
        const harga = <?= $barang['harga'] ?>;
        const diskon = <?= $barang['diskon'] ?>;

        function updateTotal() {
            const jumlahBarang = document.getElementById('jumlah_barang').value;
            if (jumlahBarang > 0) {
                const subtotal = harga * jumlahBarang;
                const diskonRp = (diskon / 100) * subtotal;
                const totalHarga = subtotal - diskonRp;

                document.getElementById('subtotal').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
                document.getElementById('diskon').textContent = 'Rp ' + diskonRp.toLocaleString('id-ID');
                document.getElementById('totalHarga').textContent = 'Rp ' + totalHarga.toLocaleString('id-ID');
            } else {
                document.getElementById('subtotal').textContent = 'Rp 0';
                document.getElementById('diskon').textContent = 'Rp 0';
                document.getElementById('totalHarga').textContent = 'Rp 0';
            }
        }
    </script>
</body>
</html>