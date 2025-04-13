<?php
class Transaksi {
    private $conn;

    public function __construct($conn = null) {
        if ($conn) {
            $this->conn = $conn;
        } else {
            require_once __DIR__ . '/../config/database.php';
            $db = new Database();
            $this->conn = $db->getConnection();
        }
    }

    public function getAll() {
        $sql = "SELECT transaksi.*, user.username, barang.nama_barang 
                FROM transaksi 
                JOIN user ON transaksi.id_user = user.id_user 
                JOIN barang ON transaksi.id_barang = barang.id_barang";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByUserId($user_id) {
        $sql = "SELECT transaksi.*, barang.nama_barang 
                FROM transaksi 
                JOIN barang ON transaksi.id_barang = barang.id_barang 
                WHERE transaksi.id_user = :user_id
                ORDER BY transaksi.tanggal_transaksi DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function tambahTransaksi($user_id, $barang_id, $jumlah, $diskon_manual, $metode) {
        $sql_barang = "SELECT harga FROM barang WHERE id_barang = :id_barang";
        $stmt = $this->conn->prepare($sql_barang);
        $stmt->bindParam(':id_barang', $barang_id);
        $stmt->execute();
        $barang = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$barang) return false;

        $harga = $barang['harga'];
        $total_sebelum_diskon = $harga * $jumlah;
        $diskon = ($diskon_manual / 100) * $total_sebelum_diskon;
        $total_harga = $total_sebelum_diskon - $diskon;

        $sql = "INSERT INTO transaksi (id_user, id_barang, jumlah_barang, total_harga, diskon, metode_pembayaran, tanggal_transaksi)
                VALUES (:id_user, :id_barang, :jumlah, :total_harga, :diskon, :metode, NOW())";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_user', $user_id);
        $stmt->bindParam(':id_barang', $barang_id);
        $stmt->bindParam(':jumlah', $jumlah);
        $stmt->bindParam(':total_harga', $total_harga);
        $stmt->bindParam(':diskon', $diskon_manual);
        $stmt->bindParam(':metode', $metode);

        return $stmt->execute();
    }

    public function delete($id_transaksi) {
        $sql = "DELETE FROM transaksi WHERE id_transaksi = :id_transaksi";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_transaksi', $id_transaksi);
        return $stmt->execute();
    }

    public function getById($id_transaksi) {
        $sql = "SELECT transaksi.*, user.username, barang.nama_barang 
                FROM transaksi 
                JOIN user ON transaksi.id_user = user.id_user 
                JOIN barang ON transaksi.id_barang = barang.id_barang 
                WHERE id_transaksi = :id_transaksi";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_transaksi', $id_transaksi);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
