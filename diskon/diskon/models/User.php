<?php
require_once __DIR__ . '/../config/database.php';

class User {
    private $conn;
    private $table = "users";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection(); // Ambil koneksi dengan getConnection()
    }

    public function register($username, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO $this->table (username, password, role) VALUES (?, ?, 'user')");
        return $stmt->execute([$username, $hashedPassword]);
    }

    public function getUserByUsername($username) {
        $stmt = $this->conn->prepare("SELECT * FROM $this->table WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllUsers() {
        // Perbaikan pada ORDER BY id_user ASC
        $stmt = $this->conn->prepare("SELECT id_user, username, role FROM $this->table ORDER BY id_user ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserById($id) {
        $db = new Database();
        $conn = $db->getConnection();
    
        $stmt = $conn->prepare("SELECT * FROM users WHERE id_user = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function updateUser($id, $data) {
        $db = new Database();
        $conn = $db->getConnection();
    
        // Buat bagian query dinamis berdasarkan field yang tersedia
        $fields = [];
        $params = [];
    
        if (isset($data['username'])) {
            $fields[] = "username = ?";
            $params[] = $data['username'];
        }
    
        if (isset($data['role'])) {
            $fields[] = "role = ?";
            $params[] = $data['role'];
        }
    
        if (isset($data['password'])) {
            $fields[] = "password = ?";
            $params[] = $data['password'];
        }
    
        // Jika tidak ada field yang mau diupdate
        if (empty($fields)) {
            return false;
        }
    
        // Tambahkan ID ke parameter terakhir
        $params[] = $id;
    
        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id_user = ?";
        $stmt = $conn->prepare($sql);
    
        return $stmt->execute($params);
    }
    
    public function deleteUser($id) {
        $db = new Database();
        $conn = $db->getConnection();
    
        $stmt = $conn->prepare("DELETE FROM users WHERE id_user = ?");
        return $stmt->execute([$id]);
    }
    
    public function createUser($username, $password, $role) {
        $db = new Database();
        $conn = $db->getConnection();
    
        $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        return $stmt->execute([$username, $password, $role]);
    }
    
}
?>