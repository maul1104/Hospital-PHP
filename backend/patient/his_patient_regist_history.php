<?php
session_start();
include('assets/inc/config.php');

// Pastikan bahwa pat_id diset di sesi
if (!isset($_SESSION['pat_id'])) {
    die('Patient ID is not set in session.');
}

// Assuming the patient's ID is stored in session upon login
$pat_id = $_SESSION['pat_id'];
?>

<!DOCTYPE html>
<html lang="en">
<!--Head-->
<?php include('assets/inc/head.php'); ?>
<body>
    <!-- Begin page -->
    <div id="wrapper">
        <!-- Topbar Start -->
        <?php include("assets/inc/nav.php"); ?>
        <!-- end Topbar -->
        <!-- Left Sidebar Start -->
        <?php include("assets/inc/sidebar.php"); ?>
        <!-- Left Sidebar End -->

        <div class="content-page">
            <div class="content">
                <!-- Start Content-->
                <div class="container-fluid">
                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="his_admin_dashboard.php">Dashboard</a></li>
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Pasien</a></li>
                                        <li class="breadcrumb-item active">Riwayat Pendaftaran</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Riwayat Pendaftaran Pasien</h4>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <!-- Riwayat Pendaftaran -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <?php
                                    if (isset($_GET['status']) && $_GET['status'] == 'success') {
                                        echo '<div id="success-alert" class="alert alert-success">Pendaftaran berhasil!</div>';
                                    }
                                    ?>
                                    <h4 class="header-title">Riwayat Pendaftaran Pasien</h4>
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Nama Pasien</th>
                                                <th>Tanggal</th>
                                                <th>Alamat</th>
                                                <th>Nomor Surat Rujukan</th>
                                                <th>Nomor BPJS</th>
                                                <th>Status</th>
                                                <th>Dokter Tujuan</th>
                                                <th>Nomor Antrian</th> <!-- Kolom baru untuk menampilkan nomor antrian -->
                                                <th>Revisi (Khusus Penolakan)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // SQL to select patient's treatment history with doctor's full name and queue number
                                            $query = "SELECT pt.*, CONCAT(d.doc_fname, ' ', d.doc_lname) as doc_fullname 
                                                      FROM pat_treatment pt 
                                                      LEFT JOIN his_docs d ON pt.doc_id = d.doc_id 
                                                      WHERE pt.pat_id = ? AND (pt.status IN ('Menunggu', 'Disetujui', 'Ditolak') OR pt.status_polik IN ('Menunggu', 'Dikirim ke Dokter'))";
                                            $stmt = $mysqli->prepare($query);

                                            if ($stmt === false) {
                                                die('Prepare failed: ' . htmlspecialchars($mysqli->error));
                                            }

                                            $stmt->bind_param('i', $pat_id);
                                            $stmt->execute();
                                            $result = $stmt->get_result();

                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tr>";
                                                echo "<td>" . htmlspecialchars($row['pat_fname'] . " " . $row['pat_lname']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['alamat']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['pat_refnumber']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['pat_nobpjs']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                                                echo "<td class='text-center'>" . (isset($row['doc_fullname']) ? htmlspecialchars($row['doc_fullname']) : '---') . "</td>";
                                                echo "<td class='text-center'>" . (!empty($row['antrian']) ? htmlspecialchars($row['antrian']) : 'Belum Disetujui') . "</td>"; // Menampilkan nomor antrian atau pesan default
                                                echo "<td class='text-center'>" . (isset($row['reason']) ? htmlspecialchars($row['reason']) : '---') . "</td>";
                                                echo "</tr>";
                                            }

                                            $stmt->close();
                                            ?>
                                        </tbody>
                                    </table>
                                </div> <!-- end card-body -->
                            </div> <!-- end card-->
                        </div> <!-- end col -->
                    </div>
                    <!-- end row -->
                </div> <!-- container -->
            </div> <!-- content -->

            <!-- Footer Start -->
            <?php include('assets/inc/footer.php'); ?>
            <!-- end Footer -->
        </div>
    </div>
    <!-- END wrapper -->

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- Vendor js -->
    <script src="assets/js/vendor.min.js"></script>
    <!-- App js-->
    <script src="assets/js/app.min.js"></script>

    <script>
        // Auto-hide success alert after 5 seconds
        document.addEventListener("DOMContentLoaded", function() {
            var successAlert = document.getElementById("success-alert");
            if (successAlert) {
                setTimeout(function() {
                    successAlert.style.display = "none";
                }, 3000);
            }
        });
    </script>
</body>
</html>
