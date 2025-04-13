<?php
session_start();

// Hanya admin yang boleh mengakses halaman ini
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../models/User.php';

$userModel = new User();

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    header("Location: daftar_user.php?error=ID tidak valid");
    exit();
}

$user = $userModel->getUserById($id);
if (!$user) {
    header("Location: daftar_user.php?error=User tidak ditemukan");
    exit();
}

$error = "";

// Proses saat form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $role = trim($_POST['role']);
    $password = trim($_POST['password']);

    if (empty($username)) {
        $error = "Username tidak boleh kosong!";
    } else {
        $updateData = [
            'username' => $username,
            'role' => $role
        ];

        if (!empty($password)) {
            $updateData['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $updated = $userModel->updateUser($id, $updateData);

        if ($updated) {
            header("Location: daftar_user.php?success=User berhasil diperbarui");
            exit();
        } else {
            $error = "Gagal memperbarui data pengguna.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Pengguna</title>
    <style>
        html, body {
            height: 100%;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to right, #e3f2fd, #ffffff);
        }

        .wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
        }

        .container {
            width: 320px;
            background: #ffffff;
            padding: 25px 30px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        }

        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-top: 12px;
            font-size: 14px;
            color: #333;
        }

        input[type="text"],
        input[type="password"],
        select {
            width: 100%;
            padding: 8px;
            margin-top: 6px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }

        .btn {
            width: 100%;
            margin-top: 20px;
            padding: 10px;
            background-color: #1976d2;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #0d47a1;
        }

        .back {
            display: block;
            text-align: center;
            margin-top: 15px;
            text-decoration: none;
            color: #1976d2;
            font-size: 14px;
            font-weight: bold;
        }

        .back:hover {
            text-decoration: underline;
            color: #0d47a1;
        }

        .error {
            color: #d32f2f;
            background-color: #ffebee;
            border-radius: 6px;
            padding: 10px;
            font-size: 14px;
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="container">
        <h2>Edit Pengguna</h2>

        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" value="<?= htmlspecialchars($user['username']) ?>" required>

            <label for="role">Role:</label>
            <select name="role" id="role" required>
                <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>User</option>
                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
            </select>

            <label for="password">Password (kosongkan jika tidak diubah):</label>
            <input type="password" name="password" id="password">

            <button type="submit" class="btn">Simpan Perubahan</button>
        </form>

        <a href="daftar_user.php" class="back">← Kembali ke Daftar Pengguna</a>
    </div>
</div>
</body>
</html>
