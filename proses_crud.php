<?php
include_once 'config.php';
check_login();

// 1. UPDATE NINA (Wewenang BAA & Admin)
if (isset($_POST['update_nina'])) {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("CSRF Token Invalid!");
    }
    if ($_SESSION['role'] !== 'Staf BAA' && $_SESSION['role'] !== 'Admin') {
        die("Akses Ditolak!");
    }
    
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $nina = mysqli_real_escape_string($conn, $_POST['nina_val']);
    mysqli_query($conn, "UPDATE yudisium SET nina = '$nina' WHERE id = '$id'");
    header("Location: dashboard.php?msg=nina_success");
    exit;
}

// 2. VALIDASI DATA (Wewenang Staf Prodi & Admin)
if (isset($_GET['action']) && $_GET['action'] == 'validasi') {
    if (!hash_equals($_SESSION['csrf_token'], $_GET['token'])) die("Token Invalid");
    if ($_SESSION['role'] !== 'Staf Prodi' && $_SESSION['role'] !== 'Admin') die("Akses Ditolak");

    $id = mysqli_real_escape_string($conn, $_GET['id']);
    mysqli_query($conn, "UPDATE yudisium SET status_validasi = 'Valid' WHERE id = '$id'");
    header("Location: dashboard.php?msg=valid_success");
    exit;
}

// 3. HAPUS DATA (Wewenang Mutlak Admin)
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    if (!hash_equals($_SESSION['csrf_token'], $_GET['token'])) die("Token Invalid");
    if ($_SESSION['role'] !== 'Admin') die("Hanya Admin yang boleh menghapus data!");

    $id = mysqli_real_escape_string($conn, $_GET['id']);
    mysqli_query($conn, "DELETE FROM yudisium WHERE id = '$id'");
    header("Location: dashboard.php?msg=delete_success");
    exit;
}
?>