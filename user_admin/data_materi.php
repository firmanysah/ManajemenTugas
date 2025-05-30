<?php
session_start();
include "../include/config.php";

// Cek apakah dosen sudah login
if (!isset($_SESSION['login']) || !isset($_SESSION['id_dosen'])) {
    header("Location: ../auth/login.php");
    exit();
}

$id_dosen = $_SESSION['id_dosen'];
$nama_dosen = $_SESSION['nama_dosen'] ?? '';
$pesan = $_GET['pesan'] ?? '';

// Proses tambah materi
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['nama_materi'], $_POST['id_kelas'])) {
    $nama_materi = trim($_POST['nama_materi']);
    $id_kelas_list = $_POST['id_kelas']; // array of id_kelas

    if (!empty($nama_materi) && is_array($id_kelas_list)) {
        // Simpan ke tabel materi
        $stmt = $conn->prepare("INSERT INTO materi (materi, id_dosen) VALUES (?, ?)");
        $stmt->bind_param("si", $nama_materi, $id_dosen);
        $stmt->execute();
        $id_materi_baru = $stmt->insert_id;
        $stmt->close();

        // Simpan ke tabel relasi materi_kelas
        $stmt_relasi = $conn->prepare("INSERT INTO materi_kelas (id_materi, id_kelas) VALUES (?, ?)");
        foreach ($id_kelas_list as $id_kelas) {
            $stmt_relasi->bind_param("ii", $id_materi_baru, $id_kelas);
            $stmt_relasi->execute();
        }
        $stmt_relasi->close();

        header("Location: data_materi.php?pesan=tambah");
        exit();
    }
}

// Ambil semua materi milik dosen
$query = "SELECT * FROM materi WHERE id_dosen = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_dosen);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Materi</title>
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
                <li class="nav-item"><a class="nav-link active" href="data_materi.php">Materi</a></li>
                <li class="nav-item"><a class="nav-link" href="data_tugas.php">Tugas</a></li>
                <li class="nav-item"><a class="nav-link" href="profil.php">Profil</a></li>
                <li class="nav-item"><a class="nav-link" href="../auth/logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h3>Daftar Materi</h3>

    <?php if ($pesan === "tambah"): ?>
        <div class="alert alert-success">Materi berhasil ditambahkan.</div>
    <?php elseif ($pesan === "edit"): ?>
        <div class="alert alert-success">Materi berhasil diedit.</div>
    <?php elseif ($pesan === "hapus"): ?>
        <div class="alert alert-success">Materi berhasil dihapus.</div>
    <?php endif; ?>

    <!-- Form tambah materi -->
    <form method="POST" class="row g-3 mb-4">
        <div class="col-md-4">
            <input type="text" name="nama_materi" class="form-control" placeholder="Nama materi" required>
        </div>
        <div class="form-check">
    <?php
    $kelas_result = mysqli_query($conn, "SELECT id_kelas, nama_kelas FROM kelas WHERE id_dosen = '$id_dosen' ORDER BY nama_kelas ASC");
    while ($row = mysqli_fetch_assoc($kelas_result)) {
        echo "<div class='form-check'>";
        echo "<input class='form-check-input' type='checkbox' name='id_kelas[]' value='{$row['id_kelas']}' id='kelas{$row['id_kelas']}'>";
        echo "<label class='form-check-label' for='kelas{$row['id_kelas']}'>" . htmlspecialchars($row['nama_kelas']) . "</label>";
        echo "</div>";
    }
    ?>
</div>

        <div class="col-md-4">
            <button type="submit" class="btn btn-success">Tambah Materi</button>
        </div>
    </form>

    <!-- Tabel materi -->
    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>Materi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['materi']) ?></td>
                    <td>
                        <a href="../crud/materi/edit_materi.php?id_materi=<?= $row['id_materi'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="../crud/materi/hapus_materi.php?id_materi=<?= $row['id_materi'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
