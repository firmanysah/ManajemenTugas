<?php
session_start();
include "../include/config.php";

if (!isset($_SESSION['login']) || !isset($_SESSION['id_dosen'])) {
    header("Location: ../auth/login.php");
    exit();
}

$id_dosen = $_SESSION['id_dosen'];
$nama_dosen = $_SESSION['nama_dosen'] ?? '';

// Tambah kelas
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['nama_kelas'])) {
    $nama_kelas = trim($_POST['nama_kelas']);
    if (!empty($nama_kelas)) {
        $stmt = $conn->prepare("INSERT INTO kelas (nama_kelas, id_dosen) VALUES (?, ?)");
        $stmt->bind_param("si", $nama_kelas, $id_dosen);
        $stmt->execute();
        $stmt->close();
        header("Location: data_kelas.php?pesan=tambah");
        exit();
    }
}

$pesan = $_GET['pesan'] ?? '';
$stmt = $conn->prepare("SELECT * FROM kelas WHERE id_dosen = ?");
$stmt->bind_param("i", $id_dosen);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Kelas</title>
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
                <li class="nav-item"><a class="nav-link active" href="data_kelas.php">Kelas</a></li>
                <li class="nav-item"><a class="nav-link" href="data_materi.php">Materi</a></li>
                <li class="nav-item"><a class="nav-link" href="data_tugas.php">Tugas</a></li>
                <li class="nav-item"><a class="nav-link" href="profil.php">Profil</a></li>
                <li class="nav-item"><a class="nav-link" href="../auth/logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h3>Daftar Kelas - <?= htmlspecialchars($nama_dosen) ?></h3>

    <?php if ($pesan === "tambah"): ?>
        <div class="alert alert-success">Kelas berhasil ditambahkan.</div>
    <?php elseif ($pesan === "edit"): ?>
        <div class="alert alert-success">Kelas berhasil diedit.</div>
    <?php elseif ($pesan === "hapus"): ?>
        <div class="alert alert-success">Kelas berhasil dihapus.</div>
    <?php endif; ?>

    <!-- Form tambah -->
    <form method="POST" class="row g-3 mb-4">
        <div class="col-md-6">
            <input type="text" name="nama_kelas" class="form-control" placeholder="Nama kelas" required>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-success">Tambah Kelas</button>
        </div>
    </form>

    <!-- Tabel kelas -->
    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>Nama Kelas</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['nama_kelas']) ?></td>
                    <td>
                        <a href="../crud/kelas/edit_kelas.php?id=<?= $row['id_kelas'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="../crud/kelas/hapus_kelas.php?id_kelas=<?= $row['id_kelas'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
