<?php
// Memanggil file konfigurasi database dan fungsi keamanan
include_once 'config.php';

/**
 * Fungsi untuk membuat atau memperbarui akun staf
 * @param string $npm  NPM atau NIK sebagai username
 * @param string $nama Nama lengkap staf
 * @param string $pass Password mentah (akan di-hash)
 * @param string $role Role sesuai pilihan di login (Admin, Staf BAA, Staf Prodi)
 */
function buat_staf($npm, $nama, $pass, $role) {
    global $conn;
    
    // Menggunakan BCrypt untuk keamanan password
    $hash = password_hash($pass, PASSWORD_BCRYPT);
    
    // Menggunakan AES-256 untuk enkripsi email (jika fungsi encryptAES tersedia di config.php)
    $email = $npm . "@univ.ac.id";
    if (function_exists('encryptAES')) {
        $email = encryptAES($email);
    }
    
    // Query untuk memasukkan data. Jika NPM sudah ada, maka password dan role akan diperbarui.
    $sql = "INSERT INTO users (npm_nik, nama, email, password, role) 
            VALUES ('$npm', '$nama', '$email', '$hash', '$role')
            ON DUPLICATE KEY UPDATE 
            password = '$hash', 
            role = '$role',
            nama = '$nama'";
            
    if (mysqli_query($conn, $sql)) {
        echo "✅ Berhasil membuat/memperbarui user: <strong>$npm</strong> ($role)<br>";
    } else {
        echo "❌ Gagal memasukkan user $npm: " . mysqli_error($conn) . "<br>";
    }
}

echo "<h1>🛠 Sinkronisasi Akun Staf</h1>";
echo "<hr>";

// Eksekusi pembuatan akun testing
// Format: buat_staf('USERNAME', 'NAMA', 'PASSWORD', 'ROLE_DI_LOGIN');

buat_staf('admin01', 'Admin Sistem', 'admin123', 'Admin');
buat_staf('baa01', 'Hendra (BAA)', 'baa123', 'Staf BAA');
buat_staf('prodi01', 'Santi (Prodi)', 'prodi123', 'Staf Prodi');

echo "<hr>";
echo "<h2>Sukses! Data user telah diperbarui di database.</h2>";
echo "<p>Silakan kembali ke <a href='index.php'>Halaman Login</a> dan gunakan akun di atas.</p>";
echo "<p><strong>Catatan:</strong> Pastikan saat login, pilihan 'Wewenang / Role' sesuai dengan data di atas.</p>";
?>