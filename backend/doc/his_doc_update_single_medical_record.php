<!--Server side code to handle  Patient Registration-->
<?php
session_start();
include('assets/inc/config.php');
if (isset($_POST['update_patient_mdr'])) {
    $mdr_pat_adr = $_POST['mdr_pat_adr'];
    $mdr_pat_age = $_POST['mdr_pat_age'];
    $mdr_number = $_GET['mdr_number'];
    $mdr_pat_inspect = $_POST['mdr_pat_inspect'];
    $mdr_pat_prescr = $_POST['mdr_pat_prescr'];
    $mdr_pat_ailment = $_POST['mdr_pat_ailment'];

    // SQL untuk memperbarui nilai yang diambil
    $query = "UPDATE his_medical_records SET mdr_pat_adr = ?, mdr_pat_age = ?, mdr_pat_inspect = ?, mdr_pat_prescr = ?, mdr_pat_ailment = ? WHERE mdr_number = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('sssss', $mdr_pat_adr, $mdr_pat_age, $mdr_pat_inspect, $mdr_pat_prescr, $mdr_pat_ailment, $mdr_number);
    $stmt->execute();

    // Deklarasikan variabel yang akan diteruskan ke fungsi alert
    if ($stmt) {
        $success = "Patient Medical Record Updated";
        echo "<script>
                alert('$success');
                window.location.href='his_doc_manage_medical_record.php?mdr_number=$mdr_number';
              </script>";
    } else {
        $err = "Please Try Again Or Try Later";
        echo "<script>
                alert('$err');
              </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    
<!--Head-->
<?php include('assets/inc/head.php');?>
<body>

    <!-- Begin page -->
    <div id="wrapper">

        <!-- Topbar Start -->
        <?php include("assets/inc/nav.php");?>
        <!-- end Topbar -->

        <!-- ========== Left Sidebar Start ========== -->
        <?php include("assets/inc/sidebar.php");?>
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->
        <?php
        if (isset($_GET['mdr_number'])) {
            $mdr_number = $_GET['mdr_number'];
            $ret = "SELECT * FROM his_medical_records WHERE mdr_number=?";
            $stmt = $mysqli->prepare($ret);
            $stmt->bind_param('s', $mdr_number);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res->num_rows > 0) {
                while ($row = $res->fetch_object()) {
        ?>
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
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Medical Records</a></li>
                                        <li class="breadcrumb-item active">Manage Medical Record</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Update Medical Record</h4>
                            </div>
                        </div>
                    </div>     
                    <!-- end page title --> 
                    <!-- Form row -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="header-title">Fill all fields</h4>
                                    <!--Update Patient Form-->
                                    <form method="post">
                                        <div class="form-row">

                                            <div class="form-group col-md-4">
                                                <label for="inputEmail4" class="col-form-label">Patient Name</label>
                                                <input type="text" required="required" readonly name="mdr_pat_name" value="<?php echo $row->mdr_pat_name;?>" class="form-control" id="inputEmail4" placeholder="Patient's Name">
                                            </div>

                                            <div class="form-group col-md-4">
                                                <label for="inputPassword4" class="col-form-label">Patient Age</label>
                                                <input required="required" type="text"  name="mdr_pat_age" value="<?php echo $row->mdr_pat_age;?>" class="form-control"  id="inputPassword4" placeholder="Patient's Age">
                                            </div>

                                            <div class="form-group col-md-4">
                                                <label for="inputPassword4" class="col-form-label">Patient Address</label>
                                                <input required="required" type="text"  name="mdr_pat_adr" value="<?php echo $row->mdr_pat_adr;?>" class="form-control"  id="inputPassword4" placeholder="Patient's Address">
                                            </div>

                                        </div>

                                        <div class="form-row">

                                            <div class="form-group col-md-6">
                                                <label for="inputEmail4" class="col-form-label">Patient Number</label>
                                                <input type="text" required="required" readonly name="mdr_pat_number" value="<?php echo $row->mdr_pat_number;?>" class="form-control" id="inputEmail4" placeholder="Patient Number">
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="inputPassword4" class="col-form-label">Patient Ailment</label>
                                                <input required="required" type="text"  name="mdr_pat_ailment" value="<?php echo $row->mdr_pat_ailment;?>" class="form-control"  id="inputPassword4" placeholder="Patient Ailment">
                                            </div>
                                        </div>
                                       
                                        <hr>
                                        <div class="form-group">
                                            <label for="inputAddress" class="col-form-label">Anamnesia dan Pemeriksaan</label>
                                            <textarea required="required"  type="text" class="form-control" name="mdr_pat_inspect" id="editor"><?php echo $row->mdr_pat_inspect;?></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label for="inputAddress" class="col-form-label">Resep</label>
                                            <textarea required="required"  type="text" class="form-control" name="mdr_pat_prescr" id="editor1"><?php echo $row->mdr_pat_prescr;?></textarea>
                                        </div>

                                        <button type="submit" name="update_patient_mdr" class="ladda-button btn btn-warning" data-style="expand-right">Update Patient Medical Record</button>

                                    </form>
                                    <!--End Patient Form-->
                                </div> <!-- end card-body -->
                            </div> <!-- end card-->
                        </div> <!-- end col -->
                    </div>
                    <!-- end row -->

                </div> <!-- container -->

            </div> <!-- content -->

            <!-- Footer Start -->
            <?php include('assets/inc/footer.php');?>
            <!-- end Footer -->

        </div>
        <?php
                }
            } else {
                echo "No records found for the given mdr_number.";
            }
        } else {
            echo "mdr_number not set in URL.";
        }
        ?>
        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

    </div>
    <!-- END wrapper -->

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>
    <script src="//cdn.ckeditor.com/4.6.2/basic/ckeditor.js"></script>
    <script type="text/javascript">
    CKEDITOR.replace('editor')
    </script>
    <script type="text/javascript">
    CKEDITOR.replace('editor1')
    </script>

    <!-- Vendor js -->
    <script src="assets/js/vendor.min.js"></script>

    <!-- App js-->
    <script src="assets/js/app.min.js"></script>

    <!-- Loading buttons js -->
    <script src="assets/libs/ladda/spin.js"></script>
    <script src="assets/libs/ladda/ladda.js"></script>

    <!-- Buttons init js-->
    <script src="assets/js/pages/loading-btn.init.js"></script>
    
</body>

</html>
