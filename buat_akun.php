<?php
include_once 'config.php'; // Menggunakan include_once agar lebih stabil

// Data Akun Admin (Fasilitas Seluruh Aplikasi)
$npm      = "12345";
$nama     = "Administrator Utama";
$password = "admin123"; 
$role     = "Admin";

// 1. Hash Password dengan BCrypt
$password_hash = password_hash($password, PASSWORD_BCRYPT);

// 2. Enkripsi Email (Cek fungsi encryptAES agar tidak Fatal Error)
$email_raw = "admin@univ.ac.id";
$email_secret = function_exists('encryptAES') ? encryptAES($email_raw) : $email_raw;

// 3. Simpan ke Database dengan ON DUPLICATE KEY UPDATE
// Ini mencegah error jika NPM 12345 sudah ada di database
$sql = "INSERT INTO users (npm_nik, nama, email, password, role) 
        VALUES ('$npm', '$nama', '$email_secret', '$password_hash', '$role')
        ON DUPLICATE KEY UPDATE 
        password = '$password_hash', 
        nama = '$nama',
        role = '$role'";

echo "<div style='font-family:sans-serif; max-width:400px; margin:50px auto; padding:20px; border:2px solid #2D7A4D; border-radius:15px; text-align:center;'>";

if (mysqli_query($conn, $sql)) {
    echo "<h2 style='color:#2D7A4D;'>✅ Akun Admin Siap!</h2>";
    echo "<p>Gunakan kredensial ini untuk login:</p>";
    echo "<div style='background:#f4f4f4; padding:10px; border-radius:10px; text-align:left; font-family:monospace;'>";
    echo "NPM/NIK  : <b>$npm</b><br>";
    echo "Password : <b>$password</b><br>";
    echo "Role     : <b>$role</b>";
    echo "</div>";
    echo "<br><a href='index.php' style='display:inline-block; background:#2D7A4D; color:white; padding:10px 20px; text-decoration:none; border-radius:8px; font-weight:bold;'>KE HALAMAN LOGIN</a>";
} else {
    echo "<h2 style='color:red;'>❌ Gagal Membuat Akun</h2>";
    echo "Pesan Error: " . mysqli_error($conn);
}

echo "</div>";
?>