<?php
include 'config.php';
check_login();

// 1. Proses Update NINA (Hanya Staf BAA)
if (isset($_POST['update_nina'])) {
    if ($_SESSION['role'] !== 'Staf BAA') { die("Akses Ditolak"); }
    
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $nina = mysqli_real_escape_string($conn, $_POST['nina_val']);

    mysqli_query($conn, "UPDATE yudisium SET nina = '$nina' WHERE id = '$id'");
    header("Location: dashboard.php?msg=success");
}

// 2. Proses Validasi (Hanya Staf Prodi)
if (isset($_GET['validasi_id'])) {
    if ($_SESSION['role'] !== 'Staf Prodi') { die("Akses Ditolak"); }
    
    $id = mysqli_real_escape_string($conn, $_GET['validasi_id']);

    mysqli_query($conn, "UPDATE yudisium SET status_validasi = 'Valid' WHERE id = '$id'");
    header("Location: dashboard.php?msg=success");
}
?>