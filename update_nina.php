<?php
include 'config.php';
// Proteksi RBAC
if ($_SESSION['role'] !== 'Staf BAA') { header("Location: dashboard.php"); exit(); }

if (isset($_POST['update_nina'])) {
    if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) { die("CSRF Token Invalid"); }

    $id = mysqli_real_escape_string($conn, $_POST['id_yudisium']);
    $nina = mysqli_real_escape_string($conn, $_POST['nina']);

    // Update data NINA
    $sql = "UPDATE yudisium SET nina = '$nina' WHERE id = '$id'";
    if (mysqli_query($conn, $sql)) {
        header("Location: dashboard.php?msg=NINA Berhasil Diupdate");
    }
}
?>