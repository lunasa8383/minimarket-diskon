<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../config/database.php';

$db = new Database();
$conn = $db->getConnection();

$total_users = $conn->query("SELECT COUNT(*) FROM users")->fetchColumn();
$total_barang = $conn->query("SELECT COUNT(*) FROM barang")->fetchColumn();
$total_transaksi = $conn->query("SELECT COUNT(*) FROM transaksi")->fetchColumn();

$admin_name = isset($_SESSION['username']) ? $_SESSION['username'] : 'Admin';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to right, #e3f2fd, #ffffff);
            margin: 0;
            padding: 20px;
        }

        .dashboard {
            max-width: 950px;
            margin: auto;
            background-color: #ffffff;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            animation: fadeInUp 0.6s ease;
        }

        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 10px;
            animation: fadeInUp 0.8s ease;
        }

        .admin-info {
            text-align: center;
            font-size: 16px;
            color: #555;
            margin-bottom: 30px;
            animation: fadeInUp 1s ease;
        }

        .stats {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 40px;
            flex-wrap: wrap;
        }

        .stat-box {
            flex: 1;
            background-color: #2196f3;
            color: white;
            padding: 30px 20px;
            border-radius: 10px;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            animation: fadeInUp 1.2s ease;
        }

        .stat-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .stat-box span {
            display: block;
            font-size: 32px;
            margin-top: 10px;
            font-weight: normal;
        }

        .actions {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 40px;
            margin-bottom: 30px;
        }

        .actions a {
            background-color: #1976d2;
            color: white;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: bold;
            text-align: center;
            flex: 1;
            transition: background-color 0.3s ease, transform 0.3s ease;
            animation: fadeInUp 1.4s ease;
        }

        .actions a:hover {
            background-color: #0d47a1;
            transform: scale(1.05);
        }

        .logout {
            display: block;
            width: 180px;
            text-align: center;
            background-color: #f44336;
            color: white;
            padding: 12px;
            border-radius: 8px;
            text-decoration: none;
            margin: 0 auto;
            transition: background-color 0.3s ease, transform 0.3s ease;
            animation: fadeInUp 1.6s ease;
        }

        .logout:hover {
            background-color: #d32f2f;
            transform: scale(1.05);
        }

        @media (max-width: 768px) {
            .stats {
                flex-direction: column;
            }

            .stat-box {
                margin-bottom: 20px;
            }

            .actions {
                flex-direction: column;
            }

            .actions a {
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>

<div class="dashboard">
    <h1>Dashboard Admin</h1>
    <div class="admin-info">Halo, <?= htmlspecialchars($admin_name) ?>!</div>

    <div class="stats">
        <div class="stat-box">
            Jumlah User
            <span><?= $total_users ?></span>
        </div>
        <div class="stat-box">
            Jumlah Barang
            <span><?= $total_barang ?></span>
        </div>
        <div class="stat-box">
            Jumlah Transaksi
            <span><?= $total_transaksi ?></span>
        </div>
    </div>

    <div class="actions">
        <a href="daftar_user.php">Kelola User</a>
        <a href="daftar_barang.php">Kelola Barang</a>
        <a href="daftar_transaksi_admin.php">Kelola Transaksi</a>
    </div>

    <a class="logout" href="logout.php">Logout</a>
</div>

</body>
</html>
