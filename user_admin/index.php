<?php
session_start();
include "../include/config.php";

if (!isset($_SESSION['id_dosen'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id_dosen = $_SESSION['id_dosen'];

// Hitung jumlah materi dosen
$result_materi = mysqli_query($conn, "SELECT COUNT(*) as total_materi FROM materi WHERE id_dosen = '$id_dosen'");
$row_materi = mysqli_fetch_assoc($result_materi);

// Hitung jumlah kelas dosen
$result_kelas = mysqli_query($conn, "SELECT COUNT(*) as total_kelas FROM kelas WHERE id_dosen = '$id_dosen'");
$row_kelas = mysqli_fetch_assoc($result_kelas);

// Hitung jumlah tugas aktif (tenggat belum lewat)
$now = date('Y-m-d H:i:s');
$result_tugas = mysqli_query($conn, "
    SELECT COUNT(DISTINCT tugas.id_tugas) as total_tugas 
    FROM tugas 
    JOIN materi ON tugas.id_materi = materi.id_materi
    WHERE materi.id_dosen = '$id_dosen' AND tugas.tenggat >= '$now'
");
if (!$result_tugas) {
    die("Query tugas gagal: " . mysqli_error($conn));
}
$row_tugas = mysqli_fetch_assoc($result_tugas);

// Ambil 5 tugas terbaru berdasarkan tenggat
$tugas_terbaru_query = "SELECT tugas.*, materi.materi, kelas.nama_kelas 
                        FROM tugas 
                        JOIN materi ON tugas.id_materi = materi.id_materi
                        JOIN kelas ON tugas.id_kelas = kelas.id_kelas
                        WHERE materi.id_dosen = '$id_dosen'
                        ORDER BY tugas.tenggat ASC LIMIT 5";
$tugas_terbaru_result = mysqli_query($conn, $tugas_terbaru_query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Dashboard Dosen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../assets/style.css" />
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="index.php">Manajemen Tugas</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link active" href="index.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="data_kelas.php">Kelas</a></li>
                <li class="nav-item"><a class="nav-link" href="data_materi.php">Materi</a></li>
                <li class="nav-item"><a class="nav-link" href="data_tugas.php">Tugas</a></li>
                <li class="nav-item"><a class="nav-link" href="profil.php">Profil</a></li>
                <li class="nav-item"><a class="nav-link" href="../auth/logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">

    <h1>Dashboard</h1>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Jumlah Materi</h5>
                    <p class="card-text display-4"><?= $row_materi['total_materi'] ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Jumlah Kelas</h5>
                    <p class="card-text display-4"><?= $row_kelas['total_kelas'] ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Tugas Aktif</h5>
                    <p class="card-text display-4"><?= $row_tugas['total_tugas'] ?></p>
                </div>
            </div>
        </div>
    </div>

    <h3>5 Tugas Terbaru</h3>
    <?php if (mysqli_num_rows($tugas_terbaru_result) > 0): ?>
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Deskripsi</th>
                    <th>Materi</th>
                    <th>Kelas</th>
                    <th>Tenggat</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                while ($tugas = mysqli_fetch_assoc($tugas_terbaru_result)): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($tugas['deskripsi']) ?></td>
                    <td><?= htmlspecialchars($tugas['materi']) ?></td>
                    <td><?= htmlspecialchars($tugas['nama_kelas']) ?></td>
                    <td><?= htmlspecialchars($tugas['tenggat']) ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Tidak ada tugas.</p>
    <?php endif; ?>

</div>

</body>
</html>
