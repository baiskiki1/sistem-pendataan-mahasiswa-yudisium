<?php 
include 'config.php'; 

$error = "";

if (isset($_POST['login'])) {
    // 1. Validasi CSRF Token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Akses ditolak: Token CSRF tidak valid.");
    }

    $npm_nik = mysqli_real_escape_string($conn, $_POST['npm_nik']);
    $password = $_POST['password'];
    $role_selected = mysqli_real_escape_string($conn, $_POST['role']);

    // 2. Query validasi NPM dan Role (RBAC)
    // Query ini mencari kecocokan NPM DAN Role sekaligus
    $query = "SELECT * FROM users WHERE npm_nik = '$npm_nik' AND role = '$role_selected' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($user = mysqli_fetch_assoc($result)) {
        // 3. Verifikasi Password BCrypt
        if (password_verify($password, $user['password'])) {
            session_regenerate_id(true); 

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nama']    = $user['nama'];
            $_SESSION['role']    = $user['role'];
            
            // Mengarahkan ke dashboard jika sukses
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Password salah untuk akses sebagai " . h($role_selected);
        }
    } else {
        $error = "Akun tidak ditemukan atau Role tidak sesuai.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Multi-User | SIM Yudisium</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen p-4">

    <div class="bg-white p-10 rounded-3xl shadow-2xl w-full max-w-md border border-gray-50">
        <div class="text-center mb-8">
            <div class="bg-green-100 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-inner">
                <span class="text-4xl text-[#2D7A4D]">🎓</span>
            </div>
            <h1 class="text-2xl font-black text-gray-800 italic tracking-tight">SIM Yudisium & Wisuda</h1>
            <p class="text-gray-400 text-[10px] mt-1 uppercase tracking-[0.2em] font-black">Authentication System</p>
        </div>

        <?php if ($error): ?>
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-3 mb-6 text-[11px] font-bold rounded italic shadow-sm">
                ⚠ <?= h($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="" class="space-y-5 italic font-bold">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">

            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Wewenang / Role</label>
                <select name="role" class="w-full px-4 py-4 border-2 border-gray-50 bg-gray-50 rounded-2xl focus:bg-white focus:border-[#2D7A4D] outline-none transition-all text-gray-700 shadow-inner" required>
                    <option value="Mahasiswa">Mahasiswa</option>
                    <option value="Staf Prodi">Staf Prodi</option>
                    <option value="Staf BAA">Staf BAA</option>
                    <option value="Admin">Admin</option>
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">NPM / NIK</label>
                <input type="text" name="npm_nik" required
                    class="w-full px-4 py-4 border-2 border-gray-50 bg-gray-50 rounded-2xl focus:bg-white focus:border-[#2D7A4D] outline-none transition-all shadow-inner"
                    placeholder="Masukkan Nomor Induk">
            </div>

            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Password</label>
                <input type="password" name="password" required
                    class="w-full px-4 py-4 border-2 border-gray-50 bg-gray-50 rounded-2xl focus:bg-white focus:border-[#2D7A4D] outline-none transition-all shadow-inner"
                    placeholder="••••••••">
            </div>

            <button type="submit" name="login"
                class="w-full bg-[#2D7A4D] hover:bg-green-800 text-white font-black py-5 rounded-2xl transition shadow-xl shadow-green-100 uppercase tracking-widest text-sm mt-4">
                Login Ke Dashboard
            </button>
        </form>

        <div class="mt-10 text-center border-t border-gray-100 pt-6">
            <p class="text-[10px] text-gray-400 uppercase tracking-widest mb-2">Belum terdaftar sebagai mahasiswa?</p>
            <a href="register.php" class="text-[#2D7A4D] font-black hover:underline text-xs uppercase">Buat Akun Baru</a>
        </div>
    </div>

</body>
</html>