<?php
session_start();
include "../include/config.php";

if (!isset($_SESSION['id_dosen'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id_dosen = $_SESSION['id_dosen'];

// Ambil semua materi milik dosen
$materi_query = "SELECT * FROM materi WHERE id_dosen = '$id_dosen' ORDER BY materi ASC";
$materi_result = mysqli_query($conn, $materi_query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Tugas per Materi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" >
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
                <li class="nav-item"><a class="nav-link active" href="data_tugas.php">Tugas</a></li>
                <li class="nav-item"><a class="nav-link" href="profil.php">Profil</a></li>
                <li class="nav-item"><a class="nav-link" href="../auth/logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h3>Data Tugas per Materi</h3>
    <a href="../crud/tugas/tambah_tugas.php" class="btn btn-success mb-3">Tambah Tugas</a>

    <?php 
    if (mysqli_num_rows($materi_result) == 0) {
        echo "<p>Tidak ada materi.</p>";
    } else {
        while ($materi = mysqli_fetch_assoc($materi_result)) {
            echo "<h5>Materi: " . htmlspecialchars($materi['materi']) . "</h5>";

            // Ambil tugas untuk materi ini beserta nama kelas
            $id_materi = $materi['id_materi'];
            $tugas_query = "SELECT tugas.*, kelas.nama_kelas 
                            FROM tugas 
                            JOIN kelas ON tugas.id_kelas = kelas.id_kelas
                            WHERE tugas.id_materi = '$id_materi' 
                            ORDER BY tugas.tenggat ASC";
            $tugas_result = mysqli_query($conn, $tugas_query);

            if (mysqli_num_rows($tugas_result) == 0) {
                echo "<p><em>Tidak ada tugas untuk materi ini.</em></p>";
            } else {
                echo '<table class="table table-bordered table-hover mb-4">';
                echo '<thead class="table-light">';
                echo '<tr>
                        <th>No</th>
                        <th>Kelas</th>
                        <th>Deskripsi</th>
                        <th>Tenggat</th>
                        <th>Aksi</th>
                      </tr>';
                echo '</thead><tbody>';

                $no = 1;
                while ($tugas = mysqli_fetch_assoc($tugas_result)) {
                    echo "<tr>";
                    echo "<td>" . $no++ . "</td>";
                    echo "<td>" . htmlspecialchars($tugas['nama_kelas']) . "</td>";
                    echo "<td>" . htmlspecialchars($tugas['deskripsi']) . "</td>";
                    echo "<td>" . htmlspecialchars($tugas['tenggat']) . "</td>";
                    echo "<td>
                            <a href='../crud/tugas/edit_tugas.php?id_tugas={$tugas['id_tugas']}' class='btn btn-sm btn-warning'>Edit</a>
                            <a href='../crud/tugas/hapus_tugas.php?id_tugas={$tugas['id_tugas']}' class='btn btn-sm btn-danger' onclick=\"return confirm('Yakin ingin menghapus tugas ini?')\">Hapus</a>
                          </td>";
                    echo "</tr>";
                }

                echo '</tbody></table>';
            }
        }
    }
    ?>
</div>

</body>
</html>
