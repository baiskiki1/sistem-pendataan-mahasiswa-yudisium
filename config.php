<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

$conn = mysqli_connect("localhost", "root", "", "sim_yudisium");

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function h($str) { return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8'); }

function check_login() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php");
        exit;
    }
}
?>