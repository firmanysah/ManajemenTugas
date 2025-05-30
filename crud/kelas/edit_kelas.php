<?php
session_start();
include "../../include/config.php";

if (!isset($_SESSION['login']) || !isset($_SESSION['id_dosen'])) {
    header("Location: ../auth/login.php");
    exit();
}

$id_dosen = $_SESSION['id_dosen'];
$id_kelas = $_GET['id'] ?? null;

if (!$id_kelas) {
    header("Location: data_kelas.php");
    exit();
}

// Ambil data kelas
$stmt = $conn->prepare("SELECT * FROM kelas WHERE id_kelas = ? AND id_dosen = ?");
$stmt->bind_param("ii", $id_kelas, $id_dosen);
$stmt->execute();
$result = $stmt->get_result();
$kelas = $result->fetch_assoc();

if (!$kelas) {
    echo "Data tidak ditemukan.";
    exit();
}

// Proses update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nama_kelas'])) {
    $nama_kelas = trim($_POST['nama_kelas']);
    $stmt = $conn->prepare("UPDATE kelas SET nama_kelas = ? WHERE id_kelas = ? AND id_dosen = ?");
    $stmt->bind_param("sii", $nama_kelas, $id_kelas, $id_dosen);
    $stmt->execute();
    $stmt->close();
    header("Location: ../../user_admin/data_kelas.php?pesan=edit");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Kelas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/style.css" />
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="index.php">Manajemen Tugas</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="../../user_admin/index.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link active" href="../../user_admin/data_kelas.php">Kelas</a></li>
                <li class="nav-item"><a class="nav-link" href="../../user_admin/data_materi.php">Materi</a></li>
                <li class="nav-item"><a class="nav-link" href="../../user_admin/data_tugas.php">Tugas</a></li>
                <li class="nav-item"><a class="nav-link" href="../../user_admin/profil.php">Profil</a></li>
                <li class="nav-item"><a class="nav-link" href="../../auth/logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>
<div class="container mt-5">
    <h3>Edit Kelas</h3>
    <form method="POST">
        <div class="mb-3">
            <label for="nama_kelas" class="form-label">Nama Kelas</label>
            <input type="text" name="nama_kelas" class="form-control" id="nama_kelas" value="<?= htmlspecialchars($kelas['nama_kelas']) ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="../../user_admin/data_kelas.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

</body>
</html>
