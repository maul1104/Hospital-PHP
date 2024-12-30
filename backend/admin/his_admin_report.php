<?php
session_start();
include('assets/inc/config.php');
include('assets/inc/checklogin.php');
check_login();
$aid = $_SESSION['ad_id'];

// Mendapatkan data filter bulan dan polik jika dikirim
$filter_bulan = isset($_POST['filter_bulan']) ? $_POST['filter_bulan'] : '';
$filter_polik = isset($_POST['filter_polik']) ? $_POST['filter_polik'] : '';
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
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                                        <li class="breadcrumb-item active">Pendaftaran Disetujui/Ditolak</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Pendaftaran Disetujui atau Ditolak</h4>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <!-- Filter -->
                    <div class="row">
                        <div class="col-12">
                            <form method="post" action="">
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="filter_bulan">Pilih Bulan</label>
                                        <select id="filter_bulan" name="filter_bulan" class="form-control">
                                            <option value="">Semua Bulan</option>
                                            <?php
                                            // Membuat opsi bulan
                                            for ($m = 1; $m <= 12; $m++) {
                                                $month = date('F', mktime(0, 0, 0, $m, 1, date('Y')));
                                                echo "<option value='" . $m . "'>" . $month . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="filter_polik">Pilih Polik</label>
                                        <select id="filter_polik" name="filter_polik" class="form-control">
                                            <option value="">Semua Polik</option>
                                            <?php
                                            // Ambil data polik dari database
                                            $query_polik = "SELECT DISTINCT doc_dept FROM his_docs";
                                            $result_polik = $mysqli->query($query_polik);
                                            while ($row_polik = $result_polik->fetch_assoc()) {
                                                echo "<option value='" . htmlspecialchars($row_polik['doc_dept']) . "'>" . htmlspecialchars($row_polik['doc_dept']) . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4 align-self-end">
                                        <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- end filter -->
                     <!-- Tombol Cetak PDF -->
                        <form method="post" action="his_cetak_pdf.php" target="_blank">
                            <input type="hidden" name="filter_bulan" value="<?php echo isset($filter_bulan) ? $filter_bulan : ''; ?>">
                            <input type="hidden" name="filter_polik" value="<?php echo isset($filter_polik) ? $filter_polik : ''; ?>">
                            <button type="submit" class="btn btn-success">Cetak PDF</button>
                        </form>


                    <!-- Data Pendaftaran Disetujui/Ditolak -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="header-title">Data Pendaftaran Pasien</h4>
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Nama</th>
                                                <th>Alamat</th>
                                                <th>Nomor Surat Rujukan</th>
                                                <th>Nomor BPJS</th>
                                                <th>Status</th>
                                                <th>Polik</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Membuat query berdasarkan filter dengan join ke his_docs untuk mendapatkan nama polik
                                            $query = "SELECT pt.*, d.doc_dept 
                                                      FROM pat_treatment pt
                                                      LEFT JOIN his_docs d ON pt.doc_id = d.doc_id
                                                      WHERE (pt.status = 'Disetujui' OR pt.status_polik = 'Dikirim ke Dokter')";

                                            // Tambahkan filter berdasarkan bulan
                                            if (!empty($filter_bulan)) {
                                                $query .= " AND MONTH(pt.created_at) = ?";
                                            }

                                            // Tambahkan filter berdasarkan polik
                                            if (!empty($filter_polik)) {
                                                $query .= " AND d.doc_dept = ?";
                                            }

                                            $stmt = $mysqli->prepare($query);

                                            if ($stmt === false) {
                                                die('Prepare failed: ' . htmlspecialchars($mysqli->error));
                                            }

                                            // Bind parameters berdasarkan filter
                                            if (!empty($filter_bulan) && !empty($filter_polik)) {
                                                $stmt->bind_param('is', $filter_bulan, $filter_polik);
                                            } elseif (!empty($filter_bulan)) {
                                                $stmt->bind_param('i', $filter_bulan);
                                            } elseif (!empty($filter_polik)) {
                                                $stmt->bind_param('s', $filter_polik);
                                            }

                                            $stmt->execute();
                                            $result = $stmt->get_result();

                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tr>";
                                                echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['pat_fname'] ." ". $row['pat_lname']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['alamat']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['pat_refnumber']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['pat_nobpjs']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['doc_dept']) . "</td>";
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
</body>

</html>
