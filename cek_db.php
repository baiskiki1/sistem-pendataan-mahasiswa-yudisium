<?php
include 'config.php';

echo "<h2>Proses Pengisian Database...</h2><hr>";

// 1. Matikan pengecekan Foreign Key agar bisa TRUNCATE
mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 0");

// 2. Bersihkan tabel
mysqli_query($conn, "TRUNCATE TABLE users");
// Opsional: Jika ingin membersihkan tabel yudisium juga, buka komentar baris bawah:
// mysqli_query($conn, "TRUNCATE TABLE yudisium");

// 3. Hidupkan kembali pengecekan Foreign Key
mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 1");

// Fungsi Input User
function input_user($npm, $nama, $pass, $role) {
    global $conn;
    $hash = password_hash($pass, PASSWORD_BCRYPT);
    
    // Perbaikan: Gunakan email biasa jika enkripsi belum siap
    $email = $npm . "@univ.ac.id";
    if (function_exists('encryptAES')) {
        $email = encryptAES($email);
    }

    $sql = "INSERT INTO users (npm_nik, nama, email, password, role) 
            VALUES ('$npm', '$nama', '$email', '$hash', '$role')";
    
    if (mysqli_query($conn, $sql)) {
        echo "✅ Akun <b>$role</b> ($npm) BERHASIL dibuat.<br>";
    } else {
        echo "❌ Gagal membuat akun $npm: " . mysqli_error($conn) . "<br>";
    }
}

// Jalankan Input Akun Staf
input_user('admin01', 'Admin Utama', 'admin123', 'Admin');
input_user('baa01', 'Staf BAA Hendra', 'baa123', 'Staf BAA');
input_user('prodi01', 'Staf Prodi Santi', 'prodi123', 'Staf Prodi');

echo "<hr><p>Selesai! Sekarang silakan login ke <b>index.php</b> dengan role yang sesuai.</p>";
?>