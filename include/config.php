<?php
$server = "localhost";
$username = "root";
$password = "";
$database = "manajemen_tugas";

$conn = mysqli_connect($server, $username, $password, $database);

if (!$conn) {
    echo ("Tidak dapat terhubung: " . mysqli_connect_error());
}
?>