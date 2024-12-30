<?php
session_start();
include('assets/inc/config.php');

if (isset($_POST['add_patient_mdr'])) {
    $mdr_pat_name = $_POST['mdr_pat_name'];
    $mdr_pat_number = $_POST['mdr_pat_number'];
    $mdr_pat_adr = $_POST['mdr_pat_adr'];
    $mdr_pat_age = $_POST['mdr_pat_age'];
    $mdr_number = $_POST['mdr_number'];
    $mdr_pat_prescr = $_POST['mdr_pat_prescr'];
    $mdr_pat_inspect = $_POST['mdr_pat_inspect'];
    $mdr_pat_ailment = $_POST['mdr_pat_ailment'];
    $pat_id = $_POST['pat_id'];
    $doc_id = $_POST['doc_id'];
    $treat_id = $_POST['treat_id'];

    // SQL to insert captured values
    $query = "INSERT INTO his_medical_records (mdr_pat_name, mdr_pat_number, mdr_pat_adr, mdr_pat_age, mdr_number, mdr_pat_prescr, mdr_pat_inspect, mdr_pat_ailment, pat_id, doc_id, treat_id) VALUES(?,?,?,?,?,?,?,?,?,?,?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('ssssssssiis', $mdr_pat_name, $mdr_pat_number, $mdr_pat_adr, $mdr_pat_age, $mdr_number, $mdr_pat_prescr, $mdr_pat_inspect, $mdr_pat_ailment, $pat_id, $doc_id, $treat_id);
    $stmt->execute();

    // Get the ID of the newly inserted record
    $new_record_id = $stmt->insert_id;

    // Update the status of the patient
    $update_query = "UPDATE pat_treatment SET status_med_rec = 'Rekam Medis Ditambahkan' WHERE treat_id = ?";
    $update_stmt = $mysqli->prepare($update_query);
    $update_stmt->bind_param('i', $treat_id);
    $update_stmt->execute();

    // Declare a variable which will be passed to alert function
    if ($stmt && $update_stmt) {
        $success = "Patient Medical Record Added";
        echo "<script>
                alert('$success');
                window.location.href='his_doc_manage_medical_record.php?mdr_id=$new_record_id';
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
        <?php
        if (isset($_GET['treat_id'])) {
            $treat_id = $_GET['treat_id'];
            echo "Treat ID: " . $treat_id; // Debugging output
            $ret = "SELECT * FROM pat_treatment WHERE treat_id=?";
            $stmt = $mysqli->prepare($ret);
            $stmt->bind_param('i', $treat_id);
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
                                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Rekam Medis</a></li>
                                                    <li class="breadcrumb-item active">Tambah Data Rekam Medis</li>
                                                </ol>
                                            </div>
                                            <h4 class="page-title">Add Medical Record</h4>
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
                                                <!--Add Patient Form-->
                                                <form method="post">
                                                    <div class="form-row">
                                                        <div class="form-group col-md-4">
                                                            <label for="inputEmail4" class="col-form-label">Patient Name</label>
                                                            <input type="text" required="required" readonly name="mdr_pat_name" value="<?php echo $row->pat_fname; ?> <?php echo $row->pat_lname; ?>" class="form-control" id="inputEmail4" placeholder="Patient's Name">
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label for="inputPassword4" class="col-form-label">Patient Age</label>
                                                            <input required="required" type="text" readonly name="mdr_pat_age" value="<?php echo $row->pat_age; ?>" class="form-control" id="inputPassword4" placeholder="Patient`s Age">
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label for="inputPassword4" class="col-form-label">Patient Address</label>
                                                            <input required="required" type="text" readonly name="mdr_pat_adr" value="<?php echo $row->alamat; ?>" class="form-control" id="inputPassword4" placeholder="Patient`s Address">
                                                        </div>
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="form-group col-md-6">
                                                            <label for="inputEmail4" class="col-form-label">Patient Number</label>
                                                            <input type="text" required="required" readonly name="mdr_pat_number" value="<?php echo $row->pat_refnumber; ?>" class="form-control" id="inputEmail4" placeholder="Patient Number">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="inputPassword4" class="col-form-label">Patient Ailment</label>
                                                            <input required="required" type="text" name="mdr_pat_ailment" value="" class="form-control" id="inputPassword4" placeholder="Patient Ailment">
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="form-row">
                                                        <div class="form-group col-md-2" style="display:none">
                                                            <?php
                                                            $length = 5;
                                                            $pres_no = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 1, $length);
                                                            ?>
                                                            <label for="inputZip" class="col-form-label">Medical Record Number</label>
                                                            <input type="text" name="mdr_number" value="<?php echo $pres_no; ?>" class="form-control" id="inputZip">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="inputAddress" class="col-form-label">Anamnesia dan Pemeriksaan</label>
                                                        <textarea required="required" type="text" class="form-control" name="mdr_pat_inspect" id="editor"></textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="inputAddress" class="col-form-label">Resep</label>
                                                        <textarea required="required" type="text" class="form-control" name="mdr_pat_prescr" id="editor1"></textarea>
                                                    </div>
                                                    <!-- Hidden fields for pat_id, doc_id, treat_id -->
                                                    <input type="hidden" name="pat_id" value="<?php echo $row->pat_id; ?>">
                                                    <input type="hidden" name="doc_id" value="<?php echo $row->doc_id; ?>">
                                                    <input type="hidden" name="treat_id" value="<?php echo $row->treat_id; ?>">

                                                    <button type="submit" name="add_patient_mdr" class="ladda-button btn btn-primary" data-style="expand-right">Add Patient Medical Record</button>
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
                        <?php include('assets/inc/footer.php'); ?>
                        <!-- end Footer -->
                    </div>
                <?php
                }
            } else {
                echo "Record not found!";
            }
        }
        ?>
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
</body>
</html>
