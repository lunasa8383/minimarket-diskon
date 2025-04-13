<?php
require_once __DIR__ . '/../models/User.php';

class AuthController {
    private $user;

    public function __construct() {
        session_start();
        $this->user = new User();
    }

    public function register($username, $password) {
        if ($this->user->register($username, $password)) {
            header("Location: ../views/login.php");
            exit();
        } else {
            echo "Registrasi gagal. Username mungkin sudah digunakan.";
        }
    }

    public function login($username, $password) {
        $user = $this->user->getUserByUsername($username);
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'admin') {
                header("Location: ../views/dashboard_admin.php");
            } else {
                header("Location: ../views/dashboard_user.php");
            }
            exit();
        } else {
            echo "Login gagal. Periksa kembali username dan password Anda.";
        }
    }

    public function logout() {
        session_destroy();
        header("Location: ../views/login.php");
        exit();
    }
}

// Proses register
if (isset($_POST['register'])) {
    $auth = new AuthController();
    $auth->register($_POST['username'], $_POST['password']);
}

// Proses login
if (isset($_POST['login'])) {
    $auth = new AuthController();
    $auth->login($_POST['username'], $_POST['password']);
}

// Proses logout
if (isset($_GET['logout'])) {
    $auth = new AuthController();
    $auth->logout();
}
?>