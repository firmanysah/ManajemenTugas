<?php
include "../../include/config.php";

$id_materi = $_GET['id_materi'] ?? null;

if (!$id_materi) {
    echo "ID materi tidak ditemukan.";
    exit;
}

// Hapus relasi materi_kelas
$query1 = "DELETE FROM materi_kelas WHERE id_materi = ?";
$stmt1 = $conn->prepare($query1);
$stmt1->bind_param("i", $id_materi);
$stmt1->execute();
$stmt1->close();

// Hapus tugas yang terkait materi ini
$query2 = "DELETE FROM tugas WHERE id_materi = ?";
$stmt2 = $conn->prepare($query2);
$stmt2->bind_param("i", $id_materi);
$stmt2->execute();
$stmt2->close();

// Baru hapus data materi
$query3 = "DELETE FROM materi WHERE id_materi = ?";
$stmt3 = $conn->prepare($query3);
$stmt3->bind_param("i", $id_materi);

if ($stmt3->execute()) {
    $stmt3->close();
    header("Location: ../../user_admin/data_materi.php?pesan=hapus");
    exit;
} else {
    echo "Gagal menghapus materi: " . $conn->error;
}
$stmt3->close();
?>
