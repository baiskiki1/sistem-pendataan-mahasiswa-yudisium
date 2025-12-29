<?php 
include_once 'config.php'; 
check_login();

$role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];

// Query Data: Mahasiswa hanya melihat miliknya, yang lain melihat semua
$sql = "SELECT y.*, u.nama, u.npm_nik, p.nama_periode 
        FROM yudisium y 
        JOIN users u ON y.user_id = u.id 
        JOIN periode p ON y.periode_id = p.id";

if ($role == 'Mahasiswa') {
    $sql .= " WHERE y.user_id = '$user_id'";
}
$data = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SIM Yudisium | Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex h-screen font-sans italic font-bold">
    <div class="w-64 bg-[#2D7A4D] text-white p-6 shadow-xl">
        <h1 class="text-xl font-black mb-10 border-b pb-4 uppercase tracking-tighter italic text-center">SIM Yudisium</h1>
        <nav class="space-y-4 text-sm uppercase italic">
            <a href="dashboard.php" class="flex items-center gap-3 p-3 bg-green-900 rounded-xl">
                <span>🏠</span> DASHBOARD
            </a>
            
            <?php if($role == 'Admin' || $role == 'Staf Prodi'): ?>
                <a href="tambah_data.php" class="flex items-center gap-3 p-3 hover:bg-green-800 transition rounded-xl">
                    <span>➕</span> TAMBAH DATA
                </a>
            <?php endif; ?>

            <a href="logout.php" class="flex items-center gap-3 p-3 text-red-300 hover:text-white mt-10 italic">
                <span>🚪</span> KELUAR
            </a>
        </nav>
    </div>

    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white p-6 shadow-sm flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-black text-gray-800 uppercase italic">SELAMAT DATANG, <?= h($_SESSION['nama']) ?></h2>
                <p class="text-[10px] text-green-600 uppercase tracking-widest font-bold">WEWENANG: <?= h($role) ?></p>
            </div>
        </header>

        <main class="p-8 overflow-auto">
            <div class="bg-white rounded-3xl shadow-sm border overflow-hidden">
                <table class="w-full text-[11px] text-left">
                    <thead class="bg-gray-50 text-gray-400 uppercase border-b italic">
                        <tr>
                            <th class="p-4">Mahasiswa</th>
                            <th class="p-4">SK & Periode</th>
                            <th class="p-4">IPK & Predikat</th>
                            <th class="p-4 text-center">NINA (BAA ONLY)</th>
                            <th class="p-4 text-center">Status</th>
                            <th class="p-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 italic">
                        <?php while($row = mysqli_fetch_assoc($data)): ?>
                        <tr>
                            <td class="p-4 font-black">
                                <span class="text-green-700 text-xs italic"><?= h($row['npm_nik']) ?></span><br>
                                <span class="uppercase text-gray-800"><?= h($row['nama']) ?></span>
                            </td>
                            <td class="p-4">
                                <span class="italic font-bold text-gray-700"><?= h($row['no_sk_yudisium']) ?></span><br>
                                <span class="text-blue-500 italic"><?= h($row['nama_periode']) ?></span>
                            </td>
                            <td class="p-4">
                                <span class="text-sm font-black text-gray-800"><?= h($row['ipk']) ?></span><br>
                                <span class="text-gray-400 uppercase font-bold"><?= h($row['predikat']) ?></span>
                            </td>
                            
                            <td class="p-4 text-center">
                                <?php if($role == 'Staf BAA' || $role == 'Admin'): ?>
                                    <form method="POST" action="proses_crud.php" class="flex gap-1 justify-center items-center">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                        <input type="text" name="nina_val" value="<?= h($row['nina']) ?>" 
                                               class="border-2 border-gray-100 p-1 w-28 rounded-lg text-[10px] focus:border-blue-400 outline-none shadow-inner italic" placeholder="Input NINA...">
                                        <button name="update_nina" class="bg-blue-600 text-white px-3 py-1 rounded-lg hover:bg-blue-700 transition font-black uppercase text-[9px]">SAVE</button>
                                    </form>
                                <?php else: ?>
                                    <span class="font-mono font-black text-blue-800 italic">
                                        <?= ($row['nina']) ? h($row['nina']) : '<span class="text-gray-300">Belum Terbit</span>' ?>
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td class="p-4 text-center">
                                <span class="px-3 py-1 rounded-full text-[9px] uppercase font-black
                                    <?= $row['status_validasi'] == 'Valid' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' ?>">
                                    <?= h($row['status_validasi']) ?>
                                </span>
                            </td>

                            <td class="p-4 text-center">
                                <div class="flex flex-wrap gap-1 justify-center">
                                    
                                    <?php if($row['status_validasi'] == 'Valid'): ?>
                                        <a href="cetak_yudisium.php?id=<?= $row['id'] ?>" target="_blank" 
                                           class="bg-green-600 text-white px-2 py-1 rounded text-[9px] font-black uppercase hover:bg-green-700 transition">
                                            📄 CETAK PDF
                                        </a>
                                    <?php endif; ?>

                                    <?php if(($role == 'Staf Prodi' || $role == 'Admin') && $row['status_validasi'] == 'Pending'): ?>
                                        <a href="proses_crud.php?action=validasi&id=<?= $row['id'] ?>&token=<?= $_SESSION['csrf_token'] ?>" 
                                           class="bg-orange-500 text-white px-2 py-1 rounded text-[9px] font-black uppercase hover:bg-orange-600 transition">
                                            ✔ VALIDASI
                                        </a>
                                    <?php endif; ?>

                                    <?php if($role == 'Admin'): ?>
                                        <a href="proses_crud.php?action=delete&id=<?= $row['id'] ?>&token=<?= $_SESSION['csrf_token'] ?>" 
                                           onclick="return confirm('Hapus data ini?')" 
                                           class="bg-red-500 text-white px-2 py-1 rounded text-[9px] font-black uppercase hover:bg-red-600 transition">
                                            🗑 HAPUS
                                        </a>
                                    <?php endif; ?>

                                    <?php if($row['status_validasi'] == 'Pending' && $role == 'Mahasiswa'): ?>
                                        <span class="text-gray-400 text-[8px] italic uppercase">Proses Verifikasi</span>
                                    <?php endif; ?>

                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>