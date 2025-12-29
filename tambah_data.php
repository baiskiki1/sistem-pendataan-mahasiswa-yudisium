<?php
include_once 'config.php';
check_login();

// Proteksi Role: Admin dan Staf Prodi diperbolehkan menambah data
if ($_SESSION['role'] !== 'Admin' && $_SESSION['role'] !== 'Staf Prodi') { 
    die("Akses Ditolak! Anda tidak memiliki wewenang menambah data."); 
}

$error_msg = "";

if (isset($_POST['submit_data'])) {
    // Amankan input dan pastikan format angka benar
    $user_id      = mysqli_real_escape_string($conn, $_POST['user_id']);
    $periode_id   = mysqli_real_escape_string($conn, $_POST['periode_id']);
    $no_sk        = mysqli_real_escape_string($conn, $_POST['no_sk']);
    $tgl_sk       = $_POST['tgl_sk'];
    $mulai_kuliah = $_POST['mulai_kuliah'];
    $tgl_ujian    = $_POST['tgl_ujian']; 
    $ipk          = (float)$_POST['ipk']; // Memastikan dikirim sebagai angka desimal
    $predikat     = mysqli_real_escape_string($conn, $_POST['predikat']);
    $peringkat    = mysqli_real_escape_string($conn, $_POST['peringkat']);

    // Query INSERT eksplisit
    $query = "INSERT INTO yudisium 
              (user_id, periode_id, no_sk_yudisium, tgl_sk, tgl_mulai_kuliah, tgl_ujian, ipk, predikat, peringkat, status_validasi) 
              VALUES 
              ('$user_id', '$periode_id', '$no_sk', '$tgl_sk', '$mulai_kuliah', '$tgl_ujian', '$ipk', '$predikat', '$peringkat', 'Pending')";
    
    if (mysqli_query($conn, $query)) {
        header("Location: dashboard.php?msg=input_success");
        exit;
    } else {
        // Menangkap error jika nilai masih out of range atau ada masalah lain
        $error_msg = "Gagal Simpan: " . mysqli_error($conn);
    }
}

// Ambil data Mahasiswa & Periode
$mahasiswa = mysqli_query($conn, "SELECT id, nama, npm_nik FROM users WHERE role = 'Mahasiswa' ORDER BY nama ASC");
$periode_data = mysqli_query($conn, "SELECT id, nama_periode FROM periode ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Data | SIM Yudisium</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-6 italic">
    <div class="max-w-3xl w-full bg-white p-10 rounded-3xl shadow-2xl border">
        <h1 class="text-2xl font-black text-[#2D7A4D] mb-6 uppercase border-b pb-4">Tambah Data Yudisium (Admin/Prodi)</h1>

        <?php if ($error_msg): ?>
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 font-bold text-xs uppercase italic">
                ⚠ Error: <?= $error_msg; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="grid grid-cols-2 gap-6 font-bold">
            <div class="col-span-2">
                <label class="text-[10px] text-gray-400 uppercase">Pilih Mahasiswa</label>
                <select name="user_id" class="w-full border-2 p-3 rounded-2xl outline-none focus:border-[#2D7A4D]" required>
                    <option value="">-- Mahasiswa --</option>
                    <?php while($m = mysqli_fetch_assoc($mahasiswa)): ?>
                        <option value="<?= $m['id'] ?>"><?= h($m['npm_nik']) ?> - <?= h($m['nama']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="col-span-2">
                <label class="text-[10px] text-gray-400 uppercase">Periode</label>
                <select name="periode_id" class="w-full border-2 p-3 rounded-2xl outline-none focus:border-[#2D7A4D]" required>
                    <option value="">-- Periode --</option>
                    <?php while($p = mysqli_fetch_assoc($periode_data)): ?>
                        <option value="<?= $p['id'] ?>"><?= h($p['nama_periode']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div>
                <label class="text-[10px] text-gray-400 uppercase">No. SK Yudisium</label>
                <input type="text" name="no_sk" class="w-full border-2 p-3 rounded-2xl" required>
            </div>
            <div>
                <label class="text-[10px] text-gray-400 uppercase">Tgl SK</label>
                <input type="date" name="tgl_sk" class="w-full border-2 p-3 rounded-2xl" required>
            </div>
            <div>
                <label class="text-[10px] text-gray-400 uppercase">IPK (Gunakan titik, misal: 3.75)</label>
                <input type="number" step="0.01" max="4.00" name="ipk" class="w-full border-2 p-3 rounded-2xl" required>
            </div>
            <div>
                <label class="text-[10px] text-gray-400 uppercase">Peringkat</label>
                <input type="number" name="peringkat" class="w-full border-2 p-3 rounded-2xl" required>
            </div>
            <div class="col-span-2">
                <label class="text-[10px] text-gray-400 uppercase">Tgl Ujian</label>
                <input type="date" name="tgl_ujian" class="w-full border-2 p-3 rounded-2xl" required>
            </div>
             <div class="col-span-2">
                <label class="text-[10px] text-gray-400 uppercase">Tgl Mulai Kuliah</label>
                <input type="date" name="mulai_kuliah" class="w-full border-2 p-3 rounded-2xl" required>
            </div>
            <div class="col-span-2">
                <label class="text-[10px] text-gray-400 uppercase">Predikat</label>
                <input type="text" name="predikat" class="w-full border-2 p-3 rounded-2xl" placeholder="PUJIAN" required>
            </div>

            <button type="submit" name="submit_data" class="col-span-2 bg-[#2D7A4D] text-white py-4 rounded-2xl font-black uppercase tracking-widest shadow-lg">Simpan Data</button>
        </form>
    </div>
</body>
</html>