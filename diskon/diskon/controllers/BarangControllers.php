<?php
require_once __DIR__ . '/../models/Barang.php';

if (!class_exists('Barang')) {
    die("Error: Class Barang tidak ditemukan!");
}

$barang = new Barang();
var_dump($barang);

class BarangController {
    public function index() {
        $barang = new Barang();
        $data = $barang->getAll();
        include __DIR__ . '/../views/daftar_barang.php';
    }
}
?>