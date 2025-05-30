<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['login']) || !isset($_SESSION['username'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Ambil data dari session
$nama_dosen = $_SESSION['nama_dosen'];
$username   = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil Dosen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="index.php">Manajemen Tugas</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="data_kelas.php">Kelas</a></li>
                <li class="nav-item"><a class="nav-link" href="data_materi.php">Materi</a></li>
                <li class="nav-item"><a class="nav-link" href="data_tugas.php">Tugas</a></li>
                <li class="nav-item"><a class="nav-link active" href="profil.php">Profil</a></li>
                <li class="nav-item"><a class="nav-link" href="../auth/logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4>Profil Dosen</h4>
        </div>
        <div class="card-body">
            <p><strong>Nama Dosen:</strong> <?= htmlspecialchars($nama_dosen) ?></p>
            <p><strong>Username:</strong> <?= htmlspecialchars($username) ?></p>
            <a href="../auth/logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>
</div>
</body>
</html>
