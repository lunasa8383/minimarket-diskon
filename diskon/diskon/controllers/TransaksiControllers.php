<?php
require_once '../config/database.php';
require_once '../models/Transaksi.php';
session_start();

class TransaksiController {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function simpanTransaksi() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validasi sesi login
            if (!isset($_SESSION['id_user'])) {
                $_SESSION['flash_message'] = 'Anda harus login terlebih dahulu.';
                header('Location: ../views/login.php');
                exit;
            }

            // Ambil data dari form
            $id_user   = $_SESSION['id_user'];
            $id_barang = $_POST['id_barang'] ?? null;
            $jumlah    = $_POST['jumlah_barang'] ?? 0;
            $diskon    = $_POST['diskon'] ?? 0;
            $metode    = $_POST['metode_pembayaran'] ?? null;

            // Validasi input sederhana
            if (!$id_barang || !$jumlah || !$metode) {
                $_SESSION['flash_message'] = 'Lengkapi semua data transaksi!';
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                exit;
            }

            $transaksi = new Transaksi($this->conn);
            $sukses = $transaksi->tambahTransaksi($id_user, $id_barang, $jumlah, $diskon, $metode);

            if ($sukses) {
                $_SESSION['flash_message'] = 'Transaksi berhasil ditambahkan!';
                header('Location: ../views/transaksi_user.php');
            } else {
                $_SESSION['flash_message'] = 'Transaksi gagal! Periksa stok atau data barang.';
                header('Location: ' . $_SERVER['HTTP_REFERER']);
            }

            exit;
        }
    }

    public function getByUser($id_user) {
        $transaksi = new Transaksi($this->conn);
        return $transaksi->getByUserId($id_user);
    }
}

// Jalankan aksi simpan jika form dikirim
if (isset($_POST['submit'])) {
    $controller = new TransaksiController();
    $controller->simpanTransaksi();
}
?>
