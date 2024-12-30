<?php
session_start();
include('assets/inc/config.php');
include('assets/inc/checklogin.php');
check_login();

$doc_id = $_SESSION['doc_id'];
$doc_number = $_SESSION['doc_number'];

if (isset($_GET['delete_mdr_number'])) {
    $id = intval($_GET['delete_mdr_number']);
    $adn = "DELETE FROM his_medical_records WHERE mdr_number = ?";
    $stmt = $mysqli->prepare($adn);
    if ($stmt) {
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->close();

        if ($stmt) {
            $success = "Medical Records Deleted";
        } else {
            $err = "Try Again Later";
        }
    } else {
        echo "Error preparing statement: " . $mysqli->error;
    }
}
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

        <!-- ========== Left Sidebar Start ========== -->
        <?php include("assets/inc/sidebar.php"); ?>
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->
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
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Rekam Medis</a></li>
                                        <li class="breadcrumb-item active">Manage Medical Records</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Manage Medical Records</h4>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <!-- Medical Records Table -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="header-title">All Medical Records</h4>
                                    <table id="datatable" class="table dt-responsive nowrap">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Tanggal</th>
                                                <th>Nama Pasien</th>
                                                <th>Anamnesia Dan pemeriksaan</th>
                                                <th>Penyakit</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                            $cnt = 1;
                                            // Query joining with pat_treatment and filtering by doc_id
                                            $ret = "SELECT his_medical_records.mdr_id, his_medical_records.mdr_pat_name, his_medical_records.mdr_date_rec, his_medical_records.mdr_pat_inspect, his_medical_records.mdr_pat_prescr, his_medical_records.mdr_pat_ailment, his_medical_records.mdr_number, pat_treatment.pat_id, pat_treatment.doc_id
                                            FROM his_medical_records 
                                            JOIN pat_treatment ON his_medical_records.treat_id = pat_treatment.treat_id
                                            WHERE pat_treatment.doc_id = ?";
                                    
                                            $stmt = $mysqli->prepare($ret);
                                            if ($stmt) {
                                                $stmt->bind_param('i', $doc_id);
                                                $stmt->execute();
                                                $res = $stmt->get_result();

                                                while ($row = $res->fetch_assoc()) {
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $cnt; ?></td>
                                                        <td>
                                                            <?php 
                                                                $date_rec = $row['mdr_date_rec'];
                                                                if ($date_rec) {
                                                                    echo date("d M Y", strtotime($date_rec));
                                                                } else {
                                                                    echo 'N/A';
                                                                }
                                                            ?>
                                                        </td>
                                                        <td><?php echo isset($row['mdr_pat_name']) ? $row['mdr_pat_name'] : 'N/A'; ?></td>
                                                        <td><?php echo isset($row['mdr_pat_inspect']) ? $row['mdr_pat_inspect'] : 'N/A'; ?> <br> <?php echo isset($row['mdr_pat_prescr']) ? $row['mdr_pat_prescr'] : 'N/A'; ?></td>
                                                        <td><?php echo isset($row['mdr_pat_ailment']) ? $row['mdr_pat_ailment'] : 'N/A'; ?></td>
                                                        <td>
                                                        <a href="his_doc_view_single_medical_record.php?mdr_id=<?php echo $row['mdr_id']; ?>" class="badge badge-success"><i class="fas fa-eye"></i> View</a>
                                                        <a href="his_doc_update_single_medical_record.php?mdr_number=<?php echo $row['mdr_number']; ?>" class="badge badge-warning"><i class="fas fa-eye-dropper "></i> Update</a>
                                                        <a href="his_doc_manage_medical_record.php?delete_mdr_number=<?php echo $row['mdr_number']; ?>" class="badge badge-danger"><i class=" fas fa-trash-alt "></i> Delete</a>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                    $cnt++;
                                                }
                                            } else {
                                                echo "Error preparing statement: " . $mysqli->error;
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div> <!-- end card body-->
                            </div> <!-- end card -->
                        </div><!-- end col-->
                    </div>
                    <!-- end row-->

                </div> <!-- container -->

            </div> <!-- content -->

            <!-- Footer Start -->
            <?php include('assets/inc/footer.php'); ?>
            <!-- end Footer -->

        </div>
        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

    </div>
    <!-- END wrapper -->

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- Vendor js -->
    <script src="assets/js/vendor.min.js"></script>

    <!-- App js -->
    <script src="assets/js/app.min.js"></script>

    <!-- Datatables init -->
    <script src="assets/js/pages/datatables.init.js"></script>

</body>
</html>
