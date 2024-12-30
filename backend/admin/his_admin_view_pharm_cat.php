<?php
session_start();
include('assets/inc/config.php');
include('assets/inc/checklogin.php');
check_login();
$aid = $_SESSION['ad_id'];
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
                                            $query = "SELECT * FROM pat_treatment WHERE status = 'Disetujui' OR status = 'Ditolak' OR status_polik = 'Dikirim ke Dokter'";
                                            $stmt = $mysqli->prepare($query);
                                            if ($stmt) {
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
                                                    echo "<td>";
                                                    if ($row['status'] == 'Ditolak') {
                                                        echo "Ditolak";
                                                    } elseif ($row['status'] == 'Disetujui' && empty($row['doc_id'])) {
                                                        echo "<form method='post' action='his_admin_submit_doc.php'>";
                                                        echo "<input type='hidden' name='treat_id' value='" . htmlspecialchars($row['treat_id']) . "'>";
                                                        echo "<select id='polik-dropdown' name='doc_dept' class='form-control' required>";
                                                        echo "<option value=''>Pilih Polik</option>";
                                                        $query_polik = "SELECT DISTINCT doc_dept FROM his_docs";
                                                        $result_polik = $mysqli->query($query_polik);
                                                        while ($row_polik = $result_polik->fetch_assoc()) {
                                                            echo "<option value='" . htmlspecialchars($row_polik['doc_dept']) . "'>" . htmlspecialchars($row_polik['doc_dept']) . "</option>";
                                                        }
                                                        echo "</select>";

                                                        echo "<select id='dokter-dropdown' name='doc_id' class='form-control' required>";
                                                        echo "<option value=''>Pilih Dokter</option>";
                                                        echo "</select>";

                                                        echo "<button type='submit' name='submit_to_doctor' class='btn btn-success'>Kirim ke Dokter</button>";
                                                        echo "</form>";
                                                    } elseif ($row['status_polik'] == 'Dikirim ke Dokter') {
                                                        echo "Dikirim ke Dokter dan Pasien";
                                                    }
                                                    echo "</td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "Error: " . $mysqli->error;
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
    
    <script>
    document.getElementById('polik-dropdown').addEventListener('change', function() {
        var polik = this.value;
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'his_get_doctors.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (this.status == 200) {
                document.getElementById('dokter-dropdown').innerHTML = this.responseText;
            }
        };
        xhr.send('doc_dept=' + polik);
    });
    </script>
</body>

</html>
