<?php
include_once 'config.php'; // Menggunakan include_once untuk mencegah memory exhaustion

/**
 * Fungsi untuk membuat user testing dengan role yang spesifik
 *
 */
function create_user($npm, $nama, $pass, $role) {
    global $conn;
    
    // 1. Hashing Password dengan BCrypt
    $hash = password_hash($pass, PASSWORD_BCRYPT);
    
    // 2. Enkripsi Email (Cek apakah fungsi encryptAES ada di config.php)
    $email_raw = $npm . "@example.com";
    $email = function_exists('encryptAES') ? encryptAES($email_raw) : $email_raw;

    // 3. Gunakan ON DUPLICATE KEY UPDATE agar jika dijalankan berkali-kali tidak error
    $sql = "INSERT INTO users (npm_nik, nama, email, password, role) 
            VALUES ('$npm', '$nama', '$email', '$hash', '$role')
            ON DUPLICATE KEY UPDATE 
            password = '$hash', 
            role = '$role',
            nama = '$nama'";
            
    if (mysqli_query($conn, $sql)) {
        echo "✅ Akun <b>$role</b> ($npm) siap digunakan.<br>";
    } else {
        echo "❌ Gagal membuat akun $npm: " . mysqli_error($conn) . "<br>";
    }
}

echo "<h2>🛠 Pengaturan Akun Testing</h2><hr>";

// Membuat contoh akun untuk masing-masing wewenang
// Password diset '123' untuk kemudahan testing
create_user('admin01', 'Admin Utama', '123', 'Admin'); // Fasilitas Full
create_user('baa01', 'Staf BAA Pusat', '123', 'Staf BAA'); // Input NINA
create_user('prodi01', 'Staf Prodi Informatika', '123', 'Staf Prodi'); // Validasi & Update

echo "<hr><p>Sukses! Silakan login di <b>index.php</b> dengan password: <b>123</b></p>";
?>