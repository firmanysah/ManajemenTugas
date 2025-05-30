<?php
include "../../include/config.php";

$id_materi = $_GET['id_materi'] ?? null;

if (!$id_materi) {
    echo json_encode([]);
    exit;
}

$sql = "SELECT k.id_kelas, k.nama_kelas
        FROM kelas k
        JOIN materi_kelas mk ON k.id_kelas = mk.id_kelas
        WHERE mk.id_materi = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_materi);
$stmt->execute();
$result = $stmt->get_result();

$kelas = [];
while ($row = $result->fetch_assoc()) {
    $kelas[] = $row;
}

echo json_encode($kelas);
?>
