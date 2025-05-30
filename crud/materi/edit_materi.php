<?php
include "../../include/config.php";

$id_materi = $_GET['id_materi'] ?? null;

if (!$id_materi) {
    echo "ID materi tidak ditemukan.";
    exit;
}

// Ambil data materi
$stmt = $conn->prepare("SELECT * FROM materi WHERE id_materi = ?");
$stmt->bind_param("i", $id_materi);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();

if (!$data) {
    echo "Data tidak ditemukan.";
    exit;
}

// Proses update
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $materi = trim($_POST['materi']);
    
    if ($materi === '') {
        $error = "Nama materi tidak boleh kosong.";
    } else {
        $stmt = $conn->prepare("UPDATE materi SET materi = ? WHERE id_materi = ?");
        $stmt->bind_param("si", $materi, $id_materi);
        if ($stmt->execute()) {
            $stmt->close();
            header("Location: ../../user_admin/data_materi.php?pesan=edit");
            exit;
        } else {
            $error = "Gagal update: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Edit Materi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../../assets/style.css" />
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="index.php">Manajemen Tugas</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="../../user_admin/index.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="../../user_admin/data_kelas.php">Kelas</a></li>
                <li class="nav-item"><a class="nav-link active" href="../../user_admin/data_materi.php">Materi</a></li>
                <li class="nav-item"><a class="nav-link" href="../../user_admin/data_tugas.php">Tugas</a></li>
                <li class="nav-item"><a class="nav-link" href="../../user_admin/profil.php">Profil</a></li>
                <li class="nav-item"><a class="nav-link" href="../../auth/logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h1>Edit Materi</h1>
    <?php if ($error): ?>
        <div class="error-message"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post" novalidate>
        <div class="mb-3">
            <label for="materi" class="form-label">Nama Materi</label>
            <input type="text" name="materi" id="materi" class="form-control" value="<?= htmlspecialchars($data['materi']) ?>" required />
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="../../user_admin/data_materi.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>

</body>
</html>
