<?php
session_start();

if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: views/dashboard_admin.php");
    } else {
        header("Location: views/dashboard_user.php");
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang di Diskon App</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #dff6ff, #e8f0fe);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: white;
            padding: 40px 30px;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 400px;
            width: 90%;
        }

        h1 {
            color: #2c3e50;
            margin-bottom: 10px;
            font-size: 24px;
        }

        h1 i {
            color: #3498db;
            margin-right: 8px;
        }

        p {
            font-size: 16px;
            color: #555;
            margin-bottom: 30px;
        }

        a {
            display: inline-block;
            text-decoration: none;
            background-color: #3498db;
            color: white;
            padding: 12px 24px;
            margin: 0 10px;
            border-radius: 8px;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        a:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
        }

        @media (max-width: 480px) {
            .container {
                padding: 30px 20px;
            }

            a {
                display: block;
                margin: 10px auto;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><i class="fas fa-shopping-cart"></i>Selamat Datang di Aplikasi Diskon</h1>
        <p>Silakan login atau daftar untuk melanjutkan.</p>
        <a href="views/login.php">Login</a>
        <a href="views/register.php">Daftar</a>
    </div>
</body>
</html>
