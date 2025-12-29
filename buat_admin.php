<?php
include_once 'config.php'; // Menggunakan include_once untuk stabilitas memori

// Konfigurasi akun admin
$npm_admin = "admin123";
$nama_admin = "Administrator Utama";
$password_mentah = "rahasia123"; 
$role = "Admin";

// 1. Amankan password dengan BCrypt
$password_hash = password_hash($password_mentah, PASSWORD_BCRYPT);

// 2. Enkripsi email dengan AES-256 (Cek ketersediaan fungsi dari config.php)
$email_raw = "admin@perguruan-tinggi.ac.id";
$email_aes = function_exists('encryptAES') ? encryptAES($email_raw) : $email_raw;

// 3. Masukkan ke database dengan fitur update jika data sudah ada
$sql = "INSERT INTO users (npm_nik, nama, email, password, role) 
        VALUES ('$npm_admin', '$nama_admin', '$email_aes', '$password_hash', '$role')
        ON DUPLICATE KEY UPDATE 
        password = '$password_hash', 
        nama = '$nama_admin',
        role = '$role'";

echo "<div style='font-family:sans-serif; padding:20px; max-width:500px; margin:20px auto; border:2px solid #2D7A4D; border-radius:15px; text-align:center;'>";

if (mysqli_query($conn, $sql)) {
    echo "<h2 style='color:#2D7A4D;'>✓ Sinkronisasi Admin Berhasil!</h2>";
    echo "<p style='color:#666;'>Akun administrator telah siap digunakan di sistem.</p>";
    echo "<div style='background:#f9f9f9; padding:15px; border-radius:10px; text-align:left; margin-bottom:20px;'>";
    echo "<b>NPM/NIK:</b> <span style='font-family:monospace;'>$npm_admin</span><br>";
    echo "<b>Password:</b> <span style='font-family:monospace;'>$password_mentah</span><br>";
    echo "<b>Role:</b> <span style='font-family:monospace;'>$role</span>";
    echo "</div>";
    echo "<a href='index.php' style='display:inline-block; background:#2D7A4D; color:white; padding:12px 25px; text-decoration:none; border-radius:8px; font-weight:bold; text-transform:uppercase; font-size:12px;'>Masuk Ke Dashboard</a>";
} else {
    echo "<h2 style='color:#e53e3e;'>❌ Terjadi Kesalahan</h2>";
    echo "Pesan Error: " . mysqli_error($conn);
}

echo "</div>";
?>