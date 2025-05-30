<?php
session_start();
include "../../include/config.php"; // Pastikan $conn tersedia di sini

if (!isset($_SESSION['id_dosen'])) {
    header("Location: ../../auth/login.php");
    exit;
}

$id_tugas = $_GET['id_tugas'] ?? null;
if (!$id_tugas) {
    echo "ID Tugas tidak ditemukan.";
    exit;
}

// Ambil data tugas dari DB
$query = "SELECT * FROM tugas WHERE id_tugas = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_tugas);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();

if (!$data) {
    echo "Data tugas tidak ditemukan.";
    exit;
}

// Proses update jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $deskripsi = $_POST['deskripsi'];
    $tenggat = $_POST['tenggat'];

    // Validasi sederhana tenggat agar tidak kosong dan dalam format datetime yang benar
    if (empty($deskripsi) || empty($tenggat)) {
        echo "Deskripsi dan tenggat harus diisi.";
        exit;
    }

    // Gunakan id_kelas dan id_materi dari data awal, tidak dari form
    $id_kelas = $data['id_kelas'];
    $id_materi = $data['id_materi'];

    $update_query = "UPDATE tugas SET id_kelas = ?, id_materi = ?, deskripsi = ?, tenggat = ? WHERE id_tugas = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("iissi", $id_kelas, $id_materi, $deskripsi, $tenggat, $id_tugas);

    if ($stmt->execute()) {
        $stmt->close();
        header("Location: ../../user_admin/data_tugas.php");
        exit;
    } else {
        echo "Gagal update: " . $conn->error;
        $stmt->close();
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Tugas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/style.css">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="index.php">Manajemen Tugas</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="../../user_admin/index.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="../../user_admin/data_kelas.php">Kelas</a></li>
                <li class="nav-item"><a class="nav-link" href="../../user_admin/data_materi.php">Materi</a></li>
                <li class="nav-item"><a class="nav-link active" href="../../user_admin/data_tugas.php">Tugas</a></li>
                <li class="nav-item"><a class="nav-link" href="../../user_admin/profil.php">Profil</a></li>
                <li class="nav-item"><a class="nav-link" href="../../auth/logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h3>Edit Tugas</h3>

    <form method="post">

        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="3" required><?= htmlspecialchars($data['deskripsi']) ?></textarea>
        </div>

        <div class="mb-3">
            <label for="tenggat" class="form-label">Deadline</label>
            <input type="datetime-local" name="tenggat" class="form-control"
                   value="<?= date('Y-m-d\TH:i', strtotime($data['tenggat'])) ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Update Tugas</button>
        <a href="../../user_admin/data_tugas.php" class="btn btn-secondary ms-2">Kembali</a>
    </form>
</div>

</body>
</html>
