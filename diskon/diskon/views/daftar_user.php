<?php
require_once __DIR__ . '/../models/User.php';

$userModel = new User();
$users = $userModel->getAllUsers();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pengguna</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to right, #e3f2fd, #ffffff);
            margin: 0;
            padding: 30px;
        }

        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
        }

        .add-user,
        .back-dashboard {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px;
            background-color: #1976d2;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .add-user:hover,
        .back-dashboard:hover {
            background-color: #0d47a1;
            transform: translateY(-2px);
        }

        table {
            width: 90%;
            margin: auto;
            border-collapse: collapse;
            background-color: #ffffff;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            padding: 14px 18px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #2196f3;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f3faff;
        }

        .action-links a {
            margin: 0 5px;
            padding: 6px 14px;
            border-radius: 6px;
            text-decoration: none;
            color: white;
            font-size: 14px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .edit {
            background-color: #4caf50;
        }

        .edit:hover {
            background-color: #388e3c;
            transform: scale(1.05);
        }

        .delete {
            background-color: #f44336;
        }

        .delete:hover {
            background-color: #d32f2f;
            transform: scale(1.05);
        }

        @media (max-width: 768px) {
            table {
                width: 100%;
                font-size: 14px;
            }

            .add-user,
            .back-dashboard {
                display: block;
                margin: 10px auto;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <h1>Daftar Pengguna</h1>
    <a class="add-user" href="tambah_user.php">Tambah Pengguna</a>
    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Role</th>
            <th>Aksi</th>
        </tr>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?= $user['id_user'] ?></td>
            <td><?= htmlspecialchars($user['username']) ?></td>
            <td><?= htmlspecialchars($user['role']) ?></td>
            <td class="action-links">
                <a class="edit" href="edit_user.php?id=<?= $user['id_user'] ?>">Edit</a>
                <a class="delete" href="hapus_user.php?id=<?= $user['id_user'] ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <br>
    <a class="back-dashboard" href="dashboard_admin.php">Kembali ke Dashboard</a>
</body>
</html>
