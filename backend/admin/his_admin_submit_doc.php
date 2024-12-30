<?php
session_start();
include('assets/inc/config.php');
include('assets/inc/checklogin.php');
check_login();
$aid = $_SESSION['ad_id'];

if (isset($_POST['submit_to_doctor']) && isset($_POST['treat_id']) && isset($_POST['doc_id'])) {
    $treat_id = $_POST['treat_id'];
    $doc_id = $_POST['doc_id'];

    // Cek nomor antrian terakhir di polik yang sama (join dengan tabel his_docs untuk mendapatkan doc_dept)
    $query_queue = "
        SELECT MAX(pat_treatment.antrian) as last_queue 
        FROM pat_treatment 
        INNER JOIN his_docs ON pat_treatment.doc_id = his_docs.doc_id 
        WHERE his_docs.doc_id = ?
    ";

    $stmt_queue = $mysqli->prepare($query_queue);
    if ($stmt_queue) {
        $stmt_queue->bind_param('i', $doc_id);
        $stmt_queue->execute();
        $result_queue = $stmt_queue->get_result();
        $row_queue = $result_queue->fetch_assoc();
        $last_queue = $row_queue['last_queue'];

        // Jika tidak ada antrian sebelumnya, mulai dari 1
        if ($last_queue === null) {
            $new_queue_number = 1;
        } else {
            $new_queue_number = $last_queue + 1;
        }

        // Update tabel pat_treatment dengan dokter tujuan dan nomor antrian
        $query_update_treatment = "UPDATE pat_treatment SET doc_id = ?, status_polik = 'Dikirim ke Dokter', antrian = ? WHERE treat_id = ?";
        $stmt_update_treatment = $mysqli->prepare($query_update_treatment);
        if ($stmt_update_treatment) {
            $stmt_update_treatment->bind_param('iii', $doc_id, $new_queue_number, $treat_id);
            $stmt_update_treatment->execute();

            // Ambil data pasien untuk logging atau keperluan lain
            $query_select_patient = "SELECT pat_fname, pat_lname, pat_nohp FROM pat_treatment WHERE treat_id = ?";
            $stmt_select_patient = $mysqli->prepare($query_select_patient);
            if ($stmt_select_patient) {
                $stmt_select_patient->bind_param('i', $treat_id);
                $stmt_select_patient->execute();
                $result_patient = $stmt_select_patient->get_result();
                $patient = $result_patient->fetch_assoc();

                $pat_name = $patient['pat_fname'] . ' ' . $patient['pat_lname'];

                // Logging nama pasien (opsional)
                echo "Pendaftaran atas nama $pat_name telah disetujui. Nomor antrian baru adalah $new_queue_number.<br>";

                // Redirect ke halaman admin
                header("Location: his_admin_view_pharm_cat.php");
                exit();
            } else {
                echo "Error selecting patient: " . $mysqli->error . "<br>";
            }
        } else {
            echo "Error updating treatment: " . $mysqli->error . "<br>";
        }
    } else {
        echo "Error checking queue number: " . $mysqli->error . "<br>";
    }
} else {
    echo "Invalid request.<br>";
}
?>
