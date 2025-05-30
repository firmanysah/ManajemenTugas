<?php
include "../../include/config.php";

// Proses form jika disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_kelas = $_POST['id_kelas'];
    $id_materi = $_POST['id_materi'];
    $deskripsi = $_POST['deskripsi'];
    $tenggat = $_POST['tenggat'];

    $query = "INSERT INTO tugas (id_kelas, id_materi, deskripsi, tenggat)
              VALUES ('$id_kelas', '$id_materi', '$deskripsi', '$tenggat')";

    $result = mysqli_query($conn, $query);

    if ($result) {
        header("Location: ../../user_admin/data_tugas.php?pesan=tambah");
        exit;
    } else {
        echo "Gagal menyimpan data: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Tugas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/style.css">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="#">Manajemen Tugas</a>
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
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Tambah Tugas</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="">
                <!-- Materi -->
                <div class="mb-3">
                    <label for="id_materi" class="form-label">Pilih Materi</label>
                    <select class="form-select" name="id_materi" required>
                        <option value="">-- Pilih Materi --</option>
                        <?php
                        $materi_query = "SELECT id_materi, materi FROM materi ORDER BY materi ASC";
                        $materi_result = mysqli_query($conn, $materi_query);
                        while ($row = mysqli_fetch_assoc($materi_result)) {
                            echo "<option value='" . $row['id_materi'] . "'>" . htmlspecialchars($row['materi']) . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- Kelas -->
                <div class="mb-3">
                    <label for="id_kelas" class="form-label">Pilih Kelas</label>
                    <select class="form-select" name="id_kelas" required>
                        <option value="">-- Pilih Kelas --</option>
                        <?php
                        $kelas_query = "SELECT id_kelas, nama_kelas FROM kelas ORDER BY nama_kelas ASC";
                        $kelas_result = mysqli_query($conn, $kelas_query);
                        while ($row = mysqli_fetch_assoc($kelas_result)) {
                            echo "<option value='" . $row['id_kelas'] . "'>" . htmlspecialchars($row['nama_kelas']) . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- Deskripsi -->
                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea class="form-control" name="deskripsi" rows="3" required></textarea>
                </div>

                <!-- Tenggat -->
                <div class="mb-3">
                    <label for="tenggat" class="form-label">Tenggat</label>
                    <input type="datetime-local" class="form-control" name="tenggat" required>
                </div>

                <button type="submit" class="btn btn-success">Simpan Tugas</button>
                <a href="../../user_admin/data_tugas.php" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>

</body>
</html>
