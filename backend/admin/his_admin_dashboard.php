<?php
  session_start();
  include('assets/inc/config.php');
  include('assets/inc/checklogin.php');
  check_login();
  $aid=$_SESSION['ad_id'];


// Query untuk mengambil daftar poliklinik berdasarkan doc_id
$query_poliklinik = "
  SELECT DISTINCT d.doc_id, d.doc_dept 
  FROM pat_treatment pt
  JOIN his_docs d ON pt.doc_id = d.doc_id
";
$result_poliklinik = $mysqli->query($query_poliklinik);

$polikliniks = [];
while ($row = $result_poliklinik->fetch_assoc()) {
    $polikliniks[] = $row;
}

// Ambil data statistik pasien berdasarkan poliklinik yang dipilih
$selected_doc_id = isset($_POST['poliklinik']) ? $_POST['poliklinik'] : '';
$query = "
  SELECT YEAR(created_at) as year, COUNT(*) as count 
  FROM pat_treatment 
  WHERE doc_id = ? 
  GROUP BY YEAR(created_at)
";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $selected_doc_id);
$stmt->execute();
$result = $stmt->get_result();

$years = [];
$counts = [];
while ($row = $result->fetch_assoc()) {
    $years[] = $row['year'];
    $counts[] = $row['count'];
}
// Check for pending patient registrations in pat_treatment
$query_pending = "SELECT COUNT(*) AS pending_count FROM pat_treatment WHERE status = 'menunggu'";
$result_pending = $mysqli->query($query_pending);
$row_pending = $result_pending->fetch_assoc();

if ($row_pending['pending_count'] > 0) {
    $pending_count = $row_pending['pending_count'];
    echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Pendaftaran Menunggu!',
                    text: 'Ada $pending_count pendaftaran pasien berobat, segera lakukan konfirmasi.',
                    icon: 'info',
                    confirmButtonText: 'OK'
                });
            });
          </script>";
}
?>
<!DOCTYPE html>
<html lang="en">
    
    <!--Head Code-->
    <?php include("assets/inc/head.php");?>

    <style>
    /* Mengatur ukuran dan posisi dropdown */
    #poliklinik {
        width: 15%;            /* Atur ukuran lebar dropdown */
        margin-left: 10px;     /* Geser dropdown ke kiri */
        padding: 10px 15px;     /* Ruang dalam dropdown */
        font-size: 14px;        /* Ukuran teks di dropdown */
    }

    .form-group label {
        font-weight: bold;
        margin-left: 10px;     /* Geser label ke kiri untuk sejajar dengan dropdown */
    }

    /* Jika menggunakan selectpicker dari Bootstrap */
    .bootstrap-select .dropdown-toggle {
        width: 100%;
        margin-left: 10px;
    }

    .card-box {
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 20px;
    }

    .header-title {
        font-weight: bold;
        color: #333;
        text-transform: uppercase;
        margin-bottom: 20px;
    }
</style>


    <body>

        <!-- Begin page -->
        <div id="wrapper">

            <!-- Topbar Start -->
            <?php include('assets/inc/nav.php');?>
            <!-- end Topbar -->

            <!-- ========== Left Sidebar Start ========== -->
            <?php include('assets/inc/sidebar.php');?>
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
                                    
                                    <h4 class="page-title">Hospital Management System Dashboard</h4>
                                </div>
                            </div>
                        </div>     
                        <!-- end page title --> 
                        

                        <div class="row">
                            <!--Start OutPatients-->
                            <div class="col-md-6 col-xl-6">
                                <div class="widget-rounded-circle card-box">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="avatar-lg rounded-circle bg-soft-primary border-primary border">
                                                <i class="fab fa-accessible-icon  font-22 avatar-title text-primary"></i>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-right">
                                                <?php
                                                    //code for summing up number of out patients 
                                                    $result ="SELECT count(*) FROM pat_treatment ";
                                                    $stmt = $mysqli->prepare($result);
                                                    $stmt->execute();
                                                    $stmt->bind_result($outpatient);
                                                    $stmt->fetch();
                                                    $stmt->close();
                                                ?>
                                                <h3 class="text-dark mt-1"><span data-plugin="counterup"><?php echo $outpatient;?></span></h3>
                                                <p class="text-muted mb-1 text-truncate">Pasien Masuk</p>
                                            </div>
                                        </div>
                                    </div> <!-- end row-->
                                </div> <!-- end widget-rounded-circle-->
                            </div> <!-- end col-->
                            <!--End Out Patients-->


                            <!--Start InPatients-->
                            <div class="col-md-6 col-xl-6">
                                <div class="widget-rounded-circle card-box">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="avatar-lg rounded-circle bg-soft-primary border-primary border">
                                                <i class="mdi mdi-hotel   font-22 avatar-title text-primary"></i>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-right">
                                                <?php
                                                    //code for summing up number of in / admitted  patients 
                                                    $result ="SELECT count(*) FROM his_medical_records";
                                                    $stmt = $mysqli->prepare($result);
                                                    $stmt->execute();
                                                    $stmt->bind_result($mdr_number);
                                                    $stmt->fetch();
                                                    $stmt->close();
                                                ?>
                                                <h3 class="text-dark mt-1"><span data-plugin="counterup"><?php echo $mdr_number;?></span></h3>
                                                <p class="text-muted mb-1 text-truncate">Rekam Medis</p>
                                            </div>
                                        </div>
                                    </div> <!-- end row-->
                                </div> <!-- end widget-rounded-circle-->
                            </div> <!-- end col-->
                            <!--End InPatients-->
                        
                        </div>

                        
                        <div class="row">
                            <div class="col-md-6 col-xl-6">
                                <div class="widget-rounded-circle card-box">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="avatar-lg rounded-circle bg-soft-primary border-primary border">
                                                <i class="mdi mdi-doctor font-22 avatar-title text-primary"></i>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-right">
                                                <?php
                                                    //code for summing up number of employees in the certain Hospital 
                                                    $result ="SELECT count(*) FROM his_docs ";
                                                    $stmt = $mysqli->prepare($result);
                                                    $stmt->execute();
                                                    $stmt->bind_result($doc);
                                                    $stmt->fetch();
                                                    $stmt->close();
                                                ?>
                                                <h3 class="text-dark mt-1"><span data-plugin="counterup"><?php echo $doc;?></span></h3>
                                                <p class="text-muted mb-1 text-truncate">Dokter</p>
                                            </div>
                                        </div>
                                    </div> <!-- end row-->
                                </div> <!-- end widget-rounded-circle-->
                            </div> <!-- end col-->
                            <!--End Employees-->

                            <!--Start Pharmaceuticals-->
                            <div class="col-md-6 col-xl-6">
                                <div class="widget-rounded-circle card-box">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="avatar-lg rounded-circle bg-soft-primary border-primary border">
                                                <i class="mdi mdi-pill font-22 avatar-title text-primary"></i>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-right">
                                                <?php
                                                    /* 
                                                     * code for summing up number of pharmaceuticals,
                                                     */ 
                                                    $result ="SELECT count(*) FROM his_alur ";
                                                    $stmt = $mysqli->prepare($result);
                                                    $stmt->execute();
                                                    $stmt->bind_result($judul);
                                                    $stmt->fetch();
                                                    $stmt->close();
                                                ?>
                                                <h3 class="text-dark mt-1"><span data-plugin="counterup"><?php echo $judul;?></span></h3>
                                                <p class="text-muted mb-1 text-truncate">Alur</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <!-- Statistik Pasien Masuk -->
                            <div class="col-md-12 col-xl-12">
                                <div class="card-box">
                                    <h4 class="header-title mb-3">Statistik Pasien Masuk Berdasarkan Poliklinik</h4>

                                    <!-- Dropdown untuk memilih poliklinik -->
                                    <form method="POST" id="poliklinikForm">
                                        <div class="form-group">
                                            <label for="poliklinik">Pilih Poliklinik:</label>
                                            <select class="form-control selectpicker" name="poliklinik" id="poliklinik" 
                                                onchange="document.getElementById('poliklinikForm').submit();">
                                                <option value="">Pilih Poliklinik</option>
                                                <?php foreach ($polikliniks as $poliklinik) { ?>
                                                    <option value="<?php echo $poliklinik['doc_id']; ?>" 
                                                        <?php echo ($selected_doc_id == $poliklinik['doc_id']) ? 'selected' : ''; ?>>
                                                        <?php echo $poliklinik['doc_dept']; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </form>

                                    <!-- Elemen canvas untuk menampilkan grafik Chart.js -->
                                    <canvas id="patientChart" width="400" height="200"></canvas>
                                </div>
                            </div>
                        </div>



                        
                        

                        
                        <!--Recently Employed Employees-->
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="card-box">
                                    <h4 class="header-title mb-3">Data Dokter</h4>

                                    <div class="table-responsive">
                                        <table class="table table-borderless table-hover table-centered m-0">

                                            <thead class="thead-light">
                                                <tr>
                                                    <th colspan="2">Picture</th>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Department</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <?php
                                                $ret="SELECT * FROM his_docs ORDER BY RAND() LIMIT 10 "; 
                                                //sql code to get to ten docs  randomly
                                                $stmt= $mysqli->prepare($ret) ;
                                                $stmt->execute() ;//ok
                                                $res=$stmt->get_result();
                                                $cnt=1;
                                                while($row=$res->fetch_object())
                                                {
                                            ?>
                                            <tbody>
                                                <tr>
                                                    <td style="width: 36px;">
                                                        <img src="../doc/assets/images/users/<?php echo $row->doc_dpic;?>" alt="img" title="contact-img" class="rounded-circle avatar-sm" />
                                                    </td>
                                                    <td>
                                                    </td>
                                                    <td>
                                                        <?php echo $row->doc_fname;?> <?php echo $row->doc_lname;?>
                                                    </td>    
                                                    <td>
                                                        <?php echo $row->doc_email;?>
                                                    </td>
                                                    <td>
                                                        <?php echo $row->doc_dept;?>
                                                    </td>
                                                    <td>
                                                        <a href="his_admin_view_single_employee.php?doc_id=<?php echo $row->doc_id;?>&&doc_number=<?php echo $row->doc_number;?>" class="btn btn-xs btn-primary"><i class="mdi mdi-eye"></i> View</a>
                                                    </td>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   
                                                </tr>
                                            </tbody>
                                            <?php }?>
                                        </table>
                                    </div>
                                </div>
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
        <!-- /Right-bar -->

        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

        <!-- Vendor js -->
        <script src="assets/js/vendor.min.js"></script>

        <!-- Plugins js-->
        <script src="assets/libs/flatpickr/flatpickr.min.js"></script>
        <script src="assets/libs/jquery-knob/jquery.knob.min.js"></script>
        <script src="assets/libs/jquery-sparkline/jquery.sparkline.min.js"></script>
        <script src="assets/libs/flot-charts/jquery.flot.js"></script>
        <script src="assets/libs/flot-charts/jquery.flot.time.js"></script>
        <script src="assets/libs/flot-charts/jquery.flot.tooltip.min.js"></script>
        <script src="assets/libs/flot-charts/jquery.flot.selection.js"></script>
        <script src="assets/libs/flot-charts/jquery.flot.crosshair.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


        <!-- Dashboar 1 init js-->
        <script src="assets/js/pages/dashboard-1.init.js"></script>

        <!-- App js-->
        <script src="assets/js/app.min.js"></script>
        
        <!-- Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Grafik Chart.js -->
<script>
    var ctx = document.getElementById('patientChart').getContext('2d');
    var patientChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($years); ?>,
            datasets: [{
                label: 'Jumlah Pasien Masuk',
                data: <?php echo json_encode($counts); ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

        
    </body>

</html>
