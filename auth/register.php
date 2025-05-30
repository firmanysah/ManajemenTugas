<?php
include "../include/config.php";

// Proses form saat disubmit
$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama     = trim($_POST["nama"]);
    $username = trim($_POST["username"]);
    $password = $_POST["password"];
    $confirm  = $_POST["confirm"];

    // Validasi sederhana
    if (empty($nama) || empty($username) || empty($password) || empty($confirm)) {
        $error = "Semua field harus diisi.";
    } elseif ($password !== $confirm) {
        $error = "Konfirmasi kata sandi tidak cocok.";
    } else {
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Cek apakah username sudah ada
        $stmt = $conn->prepare("SELECT id_dosen FROM dosen WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Username sudah digunakan.";
        } else {
            // Simpan ke database
            $stmt = $conn->prepare("INSERT INTO dosen (nama_dosen, username, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $nama, $username, $hashedPassword);
            if ($stmt->execute()) {
                $success = "Registrasi berhasil!";
            } else {
                $error = "Terjadi kesalahan saat menyimpan data.";
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Form Registrasi Dosen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4>Registrasi Dosen</h4>
        </div>
        <div class="card-body">
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php elseif ($success): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" id="nama" required>
                </div>
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" id="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Kata Sandi</label>
                    <input type="password" name="password" class="form-control" id="password" required>
                </div>
                <div class="mb-3">
                    <label for="confirm" class="form-label">Konfirmasi Kata Sandi</label>
                    <input type="password" name="confirm" class="form-control" id="confirm" required>
                </div>
                <button type="submit" class="btn btn-primary">Daftar</button>
                <a href="login.php">Login</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>
