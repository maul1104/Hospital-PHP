<?php
session_start();
include('assets/inc/config.php');
include('assets/inc/checklogin.php');
check_login();
$pat_id = $_SESSION['pat_id'];

if(isset($_GET['delete_mdr_number'])) {
    $id = intval($_GET['delete_mdr_number']);
    $adn = "DELETE FROM his_medical_records WHERE mdr_number = ? AND pat_id = ?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('ii', $id, $pat_id);
    $stmt->execute();
    if($stmt) {
        $success = "Medical Records Deleted";
    } else {
        $err = "Try Again Later";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<?php include('assets/inc/head.php');?>

<body>

    <!-- Begin page -->
    <div id="wrapper">

        <!-- Topbar Start -->
        <?php include('assets/inc/nav.php');?>
        <!-- end Topbar -->

        <!-- ========== Left Sidebar Start ========== -->
        <?php include("assets/inc/sidebar.php");?>
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
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Medical Records</a></li>
                                        <li class="breadcrumb-item active">Manage Medical Records</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Manage Medical Records</h4>
                            </div>
                        </div>
                    </div>     
                    <!-- end page title --> 

                    <div class="row">
                        <div class="col-12">
                            <div class="card-box">
                                <h4 class="header-title"></h4>
                                <div class="mb-2">
                                    <div class="row">
                                        <div class="col-12 text-sm-center form-inline" >
                                            <div class="form-group mr-2" style="display:none">
                                                <select id="demo-foo-filter-status" class="custom-select custom-select-sm">
                                                    <option value="">Show all</option>
                                                    <option value="Discharged">Discharged</option>
                                                    <option value="OutPatients">OutPatients</option>
                                                    <option value="InPatients">InPatients</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <input id="demo-foo-search" type="text" placeholder="Search" class="form-control form-control-sm" autocomplete="on">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <?php if (isset($success)) { ?>
                                    <div class='alert alert-success'><?php echo $success; ?></div>
                                <?php } ?>
                                <?php if (isset($err)) { ?>
                                    <div class='alert alert-danger'><?php echo $err; ?></div>
                                <?php } ?>
                                
                                <div class="table-responsive">
                                    <table id="demo-foo-filtering" class="table table-bordered toggle-circle mb-0" data-page-size="7">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th data-toggle="true">Tanggal</th>
                                            <th data-hide="phone">Anamnesia dan Pemeriksaan</th>
                                            <th data-hide="phone">Penyakit</th>
                                            <th data-hide="phone">Action</th>
                                        </tr>
                                        </thead>
                                        <?php
                                        /*
                                            *get details of allpatients
                                            *
                                        */
                                            $ret = "SELECT * FROM his_medical_records WHERE pat_id = ? ORDER BY RAND()";
                                            $stmt = $mysqli->prepare($ret);
                                            $stmt->bind_param('i', $pat_id);
                                            $stmt->execute();
                                            $res = $stmt->get_result();
                                            $cnt = 1;
                                            while($row = $res->fetch_object()) {
                                        ?>
                                            <tbody>
                                            <tr>
                                                <td><?php echo $cnt; ?></td>
                                                <td><?php echo date("d M Y", strtotime($row->mdr_date_rec)); ?></td>
                                                <td><?php echo $row->mdr_pat_prescr; ?> <br> <?php echo ($row->mdr_pat_inspect); ?></td>
                                                <td><?php echo $row->mdr_pat_ailment; ?></td>
                                                <td>
                                                    <a href="his_patient_view_single_medical_record.php?mdr_id=<?php echo $row->mdr_id; ?>&&mdr_number=<?php echo $row->mdr_number; ?>" class="badge badge-success"><i class="fas fa-eye"></i> View</a>
                                                </td>
                                            </tr>
                                            </tbody>
                                        <?php $cnt++; } ?>
                                        <tfoot>
                                        <tr class="active">
                                            <td colspan="8">
                                                <div class="text-right">
                                                    <ul class="pagination pagination-rounded justify-content-end footable-pagination m-t-10 mb-0"></ul>
                                                </div>
                                            </td>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div> <!-- end .table-responsive-->
                            </div> <!-- end card-box -->
                        </div> <!-- end col -->
                    </div>
                    <!-- end row -->

                </div> <!-- container -->

            </div> <!-- content -->

            <!-- Footer Start -->
            <?php include('assets/inc/footer.php');?>
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

    <!-- Footable js -->
    <script src="assets/libs/footable/footable.all.min.js"></script>

    <!-- Init js -->
    <script src="assets/js/pages/foo-tables.init.js"></script>

    <!-- App js -->
    <script src="assets/js/app.min.js"></script>
    
</body>

</html>
