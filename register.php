<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi Mahasiswa | SIM Yudisium</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen p-4 font-sans">
    <div class="bg-white p-8 rounded-3xl shadow-2xl w-full max-w-md border border-gray-50">
        <div class="text-center mb-6">
            <h2 class="text-2xl font-black text-[#2D7A4D] italic uppercase tracking-tighter">Registrasi Akun</h2>
            <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">Portal Pendaftaran Mahasiswa</p>
        </div>

        <?php
        if (isset($_POST['submit_reg'])) {
            // 1. Validasi CSRF
            if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) { 
                die("Akses Ilegal: CSRF Token Invalid"); 
            }

            $npm      = mysqli_real_escape_string($conn, $_POST['npm']);
            $nama     = mysqli_real_escape_string($conn, $_POST['nama']);
            $prodi    = mysqli_real_escape_string($conn, $_POST['prodi']);
            $email_in = $_POST['email'];
            $pass_in  = $_POST['password'];
            $pass_cfm = $_POST['confirm_password'];

            // 2. Validasi Input Dasar
            if ($pass_in !== $pass_cfm) {
                echo "<div class='bg-red-50 text-red-700 p-3 rounded-xl mb-4 text-[11px] font-bold italic border-l-4 border-red-500'>⚠ Konfirmasi password tidak cocok!</div>";
            } else {
                // 3. Cek apakah NPM sudah terdaftar
                $cek_user = mysqli_query($conn, "SELECT id FROM users WHERE npm_nik = '$npm'");
                if (mysqli_num_rows($cek_user) > 0) {
                    echo "<div class='bg-red-50 text-red-700 p-3 rounded-xl mb-4 text-[11px] font-bold italic border-l-4 border-red-500'>⚠ NPM sudah terdaftar dalam sistem!</div>";
                } else {
                    // 4. Keamanan Data
                    // Cek apakah fungsi encryptAES tersedia di config.php
                    $email_final = function_exists('encryptAES') ? encryptAES($email_in) : $email_in;
                    $pass_hash   = password_hash($pass_in, PASSWORD_BCRYPT);

                    // 5. Query Insert
                    $query = "INSERT INTO users (npm_nik, nama, email, password, role, prodi) 
                              VALUES ('$npm', '$nama', '$email_final', '$pass_hash', 'Mahasiswa', '$prodi')";
                    
                    if (mysqli_query($conn, $query)) {
                        echo "<div class='bg-green-50 text-green-700 p-3 rounded-xl mb-4 text-[11px] font-bold italic border-l-4 border-green-500'>✅ Registrasi Berhasil! Silakan <a href='index.php' class='underline'>Login</a>.</div>";
                    } else {
                        echo "<div class='bg-red-50 text-red-700 p-3 rounded-xl mb-4 text-[11px] font-bold italic border-l-4 border-red-500'>❌ Gagal: " . mysqli_error($conn) . "</div>";
                    }
                }
            }
        }
        ?>

        <form method="POST" class="space-y-4 font-bold italic">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            
            <div>
                <label class="block text-[10px] text-gray-400 uppercase mb-1 tracking-widest">Nomor Pokok Mahasiswa (NPM)</label>
                <input type="text" name="npm" placeholder="Contoh: 201011001" class="w-full p-3 border-2 border-gray-50 bg-gray-50 rounded-2xl outline-none focus:border-[#2D7A4D] transition shadow-inner" required>
            </div>

            <div>
                <label class="block text-[10px] text-gray-400 uppercase mb-1 tracking-widest">Nama Lengkap</label>
                <input type="text" name="nama" placeholder="Sesuai Ijazah" class="w-full p-3 border-2 border-gray-50 bg-gray-50 rounded-2xl outline-none focus:border-[#2D7A4D] transition shadow-inner" required>
            </div>

            <div>
                <label class="block text-[10px] text-gray-400 uppercase mb-1 tracking-widest">Alamat Email</label>
                <input type="email" name="email" placeholder="email@univ.ac.id" class="w-full p-3 border-2 border-gray-50 bg-gray-50 rounded-2xl outline-none focus:border-[#2D7A4D] transition shadow-inner" required>
            </div>

            <div>
                <label class="block text-[10px] text-gray-400 uppercase mb-1 tracking-widest">Program Studi</label>
                <select name="prodi" class="w-full p-3 border-2 border-gray-50 bg-gray-50 rounded-2xl outline-none focus:border-[#2D7A4D] transition shadow-inner appearance-none">
                    <option value="Informatika">Informatika</option>
                    <option value="Sistem Informasi">Sistem Informasi</option>
                    <option value="Teknik Sipil">Teknik Sipil</option>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-[10px] text-gray-400 uppercase mb-1 tracking-widest">Password</label>
                    <input type="password" name="password" placeholder="••••••••" class="w-full p-3 border-2 border-gray-50 bg-gray-50 rounded-2xl outline-none focus:border-[#2D7A4D] transition shadow-inner" required>
                </div>
                <div>
                    <label class="block text-[10px] text-gray-400 uppercase mb-1 tracking-widest">Konfirmasi</label>
                    <input type="password" name="confirm_password" placeholder="••••••••" class="w-full p-3 border-2 border-gray-50 bg-gray-50 rounded-2xl outline-none focus:border-[#2D7A4D] transition shadow-inner" required>
                </div>
            </div>

            <button name="submit_reg" class="w-full bg-[#2D7A4D] text-white py-4 rounded-2xl font-black hover:bg-green-800 transition shadow-xl shadow-green-100 uppercase tracking-widest text-xs">
                Buat Akun Sekarang
            </button>
        </form>

        <div class="mt-6 text-center border-t pt-4">
            <p class="text-[10px] text-gray-400 uppercase tracking-widest mb-1">Sudah memiliki akun?</p>
            <a href="index.php" class="text-[#2D7A4D] font-black text-xs uppercase hover:underline">Masuk Ke Sistem</a>
        </div>
    </div>
</body>
</html>