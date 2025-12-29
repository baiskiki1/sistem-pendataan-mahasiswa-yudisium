<?php
include 'config.php';
if ($_SESSION['role'] !== 'Staf Prodi') { header("Location: dashboard.php"); exit(); }

$id = mysqli_real_escape_string($conn, $_GET['id']);
$sql = "UPDATE yudisium SET status_validasi = 'Valid' WHERE id = '$id'";

if (mysqli_query($conn, $sql)) {
    header("Location: dashboard.php?msg=Mahasiswa Berhasil divalidasi");
}
?>