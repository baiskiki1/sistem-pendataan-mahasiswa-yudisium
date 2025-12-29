<?php
// Memanggil library DOMPDF yang diinstal via Composer
require_once 'vendor/autoload.php'; 
include_once 'config.php';
check_login(); // Pastikan user sudah login

use Dompdf\Dompdf;
use Dompdf\Options;

// 1. Ambil ID dari URL dengan pengecekan untuk menghindari Warning
$id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : '';

if (empty($id)) {
    die("Error: ID Yudisium tidak ditemukan. Silakan kembali ke Dashboard.");
}

// 2. Query Data Lengkap (Hanya bisa dicetak jika sudah VALID)
$query = "SELECT y.*, u.nama, u.npm_nik, u.prodi, p.nama_periode 
          FROM yudisium y 
          JOIN users u ON y.user_id = u.id 
          JOIN periode p ON y.periode_id = p.id 
          WHERE y.id = '$id' AND y.status_validasi = 'Valid'";

$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

// Jika data tidak ditemukan atau status masih 'Pending'
if (!$data) {
    die("Data tidak ditemukan atau status mahasiswa belum VALID. Harap hubungi Staf Prodi.");
}

// 3. Desain HTML untuk PDF (CSS Internal untuk tata letak resmi)
$html = '
<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: "Helvetica", sans-serif; line-height: 1.5; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .title { font-size: 16px; font-weight: bold; text-transform: uppercase; margin: 0; }
        .sub-title { font-size: 14px; margin: 5px 0; }
        .content-table { width: 100%; margin-top: 20px; border-collapse: collapse; }
        .content-table td { padding: 10px; vertical-align: top; font-size: 12px; }
        .label { width: 35%; font-weight: bold; }
        .nina-box { margin-top: 40px; padding: 20px; border: 2px dashed #2D7A4D; text-align: center; }
        .nina-label { font-size: 10px; color: #2D7A4D; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
        .nina-value { font-size: 18px; font-family: "Courier", monospace; font-weight: bold; display: block; margin-top: 5px; }
        .footer { margin-top: 60px; float: right; width: 250px; text-align: center; font-size: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <p class="title">SURAT KETERANGAN LULUS YUDISIUM</p>
        <p class="sub-title">FAKULTAS TEKNIK - UNIVERSITAS PADEK NIAN PULO</p>
    </div>

    <p style="font-size: 12px;">Diberikan kepada mahasiswa yang identitasnya tercantum di bawah ini, dinyatakan telah menyelesaikan seluruh persyaratan akademik dan administrasi:</p>

    <table class="content-table">
        <tr>
            <td class="label">Nama Mahasiswa</td>
            <td>: ' . h($data['nama']) . '</td>
        </tr>
        <tr>
            <td class="label">NPM / NIK</td>
            <td>: ' . h($data['npm_nik']) . '</td>
        </tr>
        <tr>
            <td class="label">Program Studi</td>
            <td>: ' . h($data['prodi']) . '</td>
        </tr>
        <tr>
            <td class="label">Periode Yudisium</td>
            <td>: ' . h($data['nama_periode']) . '</td>
        </tr>
        <tr>
            <td class="label">Indeks Prestasi Kumulatif</td>
            <td>: <strong>' . h($data['ipk']) . '</strong></td>
        </tr>
        <tr>
            <td class="label">Predikat Kelulusan</td>
            <td>: ' . h($data['predikat']) . '</td>
        </tr>
        <tr>
            <td class="label">Nomor SK Yudisium</td>
            <td>: ' . h($data['no_sk_yudisium']) . '</td>
        </tr>
    </table>

    <div class="nina-box">
        <span class="nina-label">Nomor Induk Ijazah Nasional (NINA)</span><br>
        <span class="nina-value">' . ($data['nina'] ? h($data['nina']) : 'DALAM PROSES PENERBITAN') . '</span>
    </div>

    <div class="footer">
        Bengkulu, ' . date('d F Y') . '<br>
        Kepala Biro Administrasi Akademik,<br><br><br><br><br>
        <strong>( MUHAMMAD BAIS AL HAKIKI )</strong><br>
        NIP. ...........................
    </div>
</body>
</html>';

// 4. Inisialisasi DOMPDF
$options = new Options();
$options->set('isRemoteEnabled', true); // Memungkinkan loading gambar/CSS eksternal
$dompdf = new Dompdf($options);

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// 5. Output PDF ke Browser (Attachment 0 agar PDF terbuka langsung di tab baru)
$dompdf->stream("Laporan_Yudisium_" . $data['npm_nik'] . ".pdf", array("Attachment" => 0));
?>