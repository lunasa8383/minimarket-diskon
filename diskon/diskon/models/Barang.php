<?php
require_once __DIR__ . '/../config/database.php';

class Barang {
    private $conn;
    private $table = "barang";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection(); // Ambil koneksi dengan getConnection()
    }

    public function getAll() {
        // Gantilah "id" dengan "id_barang" jika struktur tabelnya menggunakan "id_barang"
        $stmt = $this->conn->prepare("SELECT id_barang, nama_barang, harga, diskon, stok FROM $this->table ORDER BY id_barang ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $query = "SELECT * FROM barang WHERE id_barang = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function create($data) {
        $db = new Database();
        $conn = $db->getConnection();
    
        $stmt = $conn->prepare("INSERT INTO barang (nama_barang, harga, diskon, stok) VALUES (?, ?, ?, ?)");
        return $stmt->execute([
            $data['nama_barang'],
            $data['harga'],
            $data['diskon'],
            $data['stok']
        ]);
    }
    
    public function update($id, $data) {
        $db = new Database();
        $conn = $db->getConnection();
    
        $stmt = $conn->prepare("UPDATE barang SET nama_barang = ?, harga = ?, diskon = ?, stok = ? WHERE id_barang = ?");
        return $stmt->execute([
            $data['nama_barang'],
            $data['harga'],
            $data['diskon'],
            $data['stok'],
            $id
        ]);
    }
    
    public function delete($id) {
        $db = new Database();
        $conn = $db->getConnection();
    
        $stmt = $conn->prepare("DELETE FROM barang WHERE id_barang = ?");
        return $stmt->execute([$id]);
    }
    
}
?>