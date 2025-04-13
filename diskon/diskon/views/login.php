<?php
require_once __DIR__ . '/../config/database.php';
session_start();

// Jika pengguna sudah login, arahkan ke dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: " . ($_SESSION['role'] === 'admin' ? "dashboard_admin.php" : "dashboard_user.php"));
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        header("Location: login.php?error=empty");
        exit();
    } else {
        try {
            $db = new Database();
            $conn = $db->getConnection();

            $stmt = $conn->prepare("SELECT id_user, username, password, role FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id_user'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];

                    header("Location: login.php?success=1");
                    exit();
                } else {
                    header("Location: login.php?error=password");
                    exit();
                }
            } else {
                header("Location: login.php?error=username");
                exit();
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
    <title>Login Akun</title>
    <style>
        * { box-sizing: border-box; }
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
        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
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
        .success { color: green; }
        .error { color: red; }
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
        <h1>Login Akun</h1>

        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <p class="message success">✅ Login berhasil! Mengalihkan ke dashboard...</p>
            <script>
                setTimeout(() => {
                    window.location.href = "<?= $_SESSION['role'] === 'admin' ? 'dashboard_admin.php' : 'dashboard_user.php' ?>";
                }, 1500);
            </script>
        <?php elseif (isset($_GET['error'])): ?>
            <p class="message error">
                <?php
                    switch ($_GET['error']) {
                        case 'empty':
                            echo "⚠️ Username dan password harus diisi!";
                            break;
                        case 'username':
                            echo "❌ Username tidak ditemukan!";
                            break;
                        case 'password':
                            echo "❌ Password salah!";
                            break;
                        default:
                            echo "❌ Terjadi kesalahan login.";
                    }
                ?>
            </p>
        <?php endif; ?>

        <form action="" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>

        <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
    </div>
</body>
</html>
