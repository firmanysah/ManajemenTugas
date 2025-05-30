<?php
include "../../include/config.php";

$id_tugas = $_GET['id_tugas'] ?? null;

if (!$id_tugas) {
    echo "ID tugas tidak ditemukan.";
    exit;
}

$query = "DELETE FROM tugas WHERE id_tugas = '$id_tugas'";

if (mysqli_query($conn, $query)) {
    header("Location: ../../user_admin/data_tugas.php");
    exit;
} else {
    echo "Gagal menghapus tugas: " . mysqli_error($conn);
}
?>
