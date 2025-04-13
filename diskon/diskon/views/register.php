<?php
require_once __DIR__ . '/../config/database.php';

session_start();

// Jika pengguna sudah login, arahkan ke dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard_user.php");
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role     = trim($_POST['role']);

    if (empty($username) || empty($password) || empty($role)) {
        $error = "Semua kolom harus diisi!";
    } else {
        try {
            $db = new Database();
            $conn = $db->getConnection();

            // Cek username sudah ada
            $stmt = $conn->prepare("SELECT id_user FROM users WHERE username = ?");
            $stmt->execute([$username]);

            if ($stmt->rowCount() > 0) {
                header("Location: register.php?error=username");
                exit();
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
                if ($stmt->execute([$username, $hashed_password, $role])) {
                    header("Location: register.php?success=1");
                    exit();
                } else {
                    header("Location: register.php?error=1");
                    exit();
                }
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
    <title>Daftar Akun</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #dff6ff, #e8f0fe);
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background: #fff;
            padding: 40px 30px;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
            text-align: center;
            width: 320px;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #2c3e50;
        }

        input, select {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
        }

        label {
            display: block;
            text-align: left;
            margin-top: 10px;
            font-size: 14px;
            color: #333;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #2980b9;
        }

        p {
            font-size: 14px;
            margin-top: 15px;
        }

        a {
            color: #3498db;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .message {
            font-size: 14px;
            margin-bottom: 15px;
        }

        .message.success {
            color: green;
        }

        .message.error {
            color: red;
        }

        @media (max-width: 480px) {
            .container {
                padding: 30px 20px;
                width: 90%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Daftar Akun</h1>

        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <p class="message success">✅ Pendaftaran berhasil! Silakan <a href="login.php">login</a>.</p>
        <?php elseif (isset($_GET['error']) && $_GET['error'] == 'username'): ?>
            <p class="message error">⚠️ Username sudah digunakan!</p>
        <?php elseif (isset($_GET['error']) && $_GET['error'] == 1): ?>
            <p class="message error">❌ Pendaftaran gagal. Silakan coba lagi.</p>
        <?php elseif (!empty($error)): ?>
            <p class="message error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form action="" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            
            <label for="role">Pilih Role:</label>
            <select name="role" required>
                <option value="">-- Pilih Role --</option>
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>

            <button type="submit">Daftar</button>
        </form>

        <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
    </div>
</body>
</html>
