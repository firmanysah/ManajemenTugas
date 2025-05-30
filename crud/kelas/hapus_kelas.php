<?php
include "../../include/config.php";

$id_kelas = $_GET['id_kelas'] ?? null;

if (!$id_kelas) {
    echo "ID kelas tidak ditemukan.";
    exit;
}

// Hapus data di tabel materi_kelas yang terkait dengan kelas ini
$stmt = $conn->prepare("DELETE FROM materi_kelas WHERE id_kelas = ?");
$stmt->bind_param("i", $id_kelas);
$stmt->execute();
$stmt->close();

// Setelah relasi dihapus, hapus data kelas
$stmt = $conn->prepare("DELETE FROM kelas WHERE id_kelas = ?");
$stmt->bind_param("i", $id_kelas);
if ($stmt->execute()) {
    $stmt->close();
    header("Location: ../../user_admin/data_kelas.php?pesan=hapus");
    exit;
} else {
    echo "Gagal menghapus kelas: " . $conn->error;
}
?>
