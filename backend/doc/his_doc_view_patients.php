<?php
session_start();
include('assets/inc/config.php');
include('assets/inc/checklogin.php');
check_login();
$doc_id = $_SESSION['doc_id'];
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
                                        <li class="breadcrumb-item"><a href="his_doc_dashboard.php">Dashboard</a></li>
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dokter</a></li>
                                        <li class="breadcrumb-item active">Lihat Pasien</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Pasien yang Disetujui</h4>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <!-- Data Pasien yang Disetujui -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="header-title">Data Pasien</h4>
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Nama</th>
                                                <th>Tanggal Lahir</th>
                                                <th>Alamat</th>
                                                <th>Nomor Surat Rujukan</th>
                                                <th>Nomor BPJS</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $query = "SELECT * FROM pat_treatment WHERE doc_id = ? AND status_med_rec = 'Dikirim ke Dokter'";
                                            if ($stmt = $mysqli->prepare($query)) {
                                                $stmt->bind_param('i', $doc_id);
                                                $stmt->execute();
                                                $result = $stmt->get_result();

                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<tr>";
                                                    echo "<td>" . $row['pat_fname'] . "</td>";
                                                    echo "<td>" . $row['pat_birth'] . "</td>";
                                                    echo "<td>" . $row['alamat'] . "</td>";
                                                    echo "<td>" . $row['pat_refnumber'] . "</td>";
                                                    echo "<td>" . $row['pat_nobpjs'] . "</td>";
                                                    echo "<td><a href='his_doc_add_single_patient_medical_record.php?treat_id=" . $row['treat_id'] . "' class='badge badge-success'><i class=' fas fa-file-signature'></i> Add Medical Record</a></td>";
                                                    echo "</tr>";
                                                }
                                                $stmt->close();
                                            } else {
                                                echo "Error preparing statement: " . $mysqli->error;
                                            }
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
</body>
</html>
