<?php
session_start();
include('assets/inc/config.php');
require __DIR__ . '/assets/vendor/autoload.php'; // Menggunakan autoload Composer

use Dompdf\Dompdf;
use Dompdf\Options;

// Mendapatkan data filter
$filter_bulan = isset($_POST['filter_bulan']) ? $_POST['filter_bulan'] : '';
$filter_polik = isset($_POST['filter_polik']) ? $_POST['filter_polik'] : '';

// Mendapatkan data dari database dengan filter
$query = "SELECT pt.*, d.doc_dept 
          FROM pat_treatment pt
          LEFT JOIN his_docs d ON pt.doc_id = d.doc_id
          WHERE (pt.status = 'Disetujui' OR pt.status_polik = 'Dikirim ke Dokter')";

if (!empty($filter_bulan)) {
    $query .= " AND MONTH(pt.created_at) = ?";
}
if (!empty($filter_polik)) {
    $query .= " AND d.doc_dept = ?";
}

$stmt = $mysqli->prepare($query);
if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($mysqli->error));
}

if (!empty($filter_bulan) && !empty($filter_polik)) {
    $stmt->bind_param('is', $filter_bulan, $filter_polik);
} elseif (!empty($filter_bulan)) {
    $stmt->bind_param('i', $filter_bulan);
} elseif (!empty($filter_polik)) {
    $stmt->bind_param('s', $filter_polik);
}

$stmt->execute();
$result = $stmt->get_result();

// Membuat HTML untuk PDF
$html = '<h3>Laporan Pendaftaran Pasien</h3>';
$html .= '<table border="1" cellspacing="3" cellpadding="4">';
$html .= '<tr>
            <th>Tanggal</th>
            <th>Nama</th>
            <th>Alamat</th>
            <th>Nomor Surat Rujukan</th>
            <th>Nomor BPJS</th>
            <th>Status</th>
            <th>Polik</th>
          </tr>';

while ($row = $result->fetch_assoc()) {
    $html .= '<tr>
                <td>' . htmlspecialchars($row['created_at']) . '</td>
                <td>' . htmlspecialchars($row['pat_fname'] . " " . $row['pat_lname']) . '</td>
                <td>' . htmlspecialchars($row['alamat']) . '</td>
                <td>' . htmlspecialchars($row['pat_refnumber']) . '</td>
                <td>' . htmlspecialchars($row['pat_nobpjs']) . '</td>
                <td>' . htmlspecialchars($row['status']) . '</td>
                <td>' . htmlspecialchars($row['doc_dept']) . '</td>
              </tr>';
}

$html .= '</table>';

// Inisialisasi Dompdf
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true); // Jika ada gambar atau asset dari URL eksternal
$dompdf = new Dompdf($options);

// Memuat HTML ke Dompdf
$dompdf->loadHtml($html);

// Set ukuran dan orientasi kertas (opsional)
$dompdf->setPaper('A4', 'portrait');

// Render PDF
$dompdf->render();

// Output PDF ke browser
$dompdf->stream('laporan_pendaftaran_pasien.pdf', ['Attachment' => false]);

$stmt->close();
?>
