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
                                            <li class="breadcrumb-item active">Manage Pendaftaran</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">Manage Pendaftaran Berobat</h4>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <!-- Riwayat Pendaftaran -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title">Pendaftaran Berobat Pasien</h4>
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Nama Depan</th>
                                                    <th class="text-center">Tanggal</th>
                                                    <th class="text-center">Nomor NIK</th>
                                                    <th class="text-center">No. Kartu Berobat</th>
                                                    <th class="text-center">Alamat</th>
                                                    <!--<th class="text-center">Nomor HP</th>-->
                                                    <th class="text-center">Nomor Surat Rujukan</th>
                                                    <th class="text-center">Nomor BPJS</th>
                                                    <th class="text-center">KTP</th>
                                                    <th class="text-center">BPJS</th>
                                                    <th class="text-center">Surat Rujukan</th>
                                                    <th class="text-center">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    // SQL to select all patients' treatment history
                                                    $query = "SELECT * FROM pat_treatment WHERE status = 'Menunggu'";
                                                    $stmt = $mysqli->prepare($query);
                                                    $stmt->execute();
                                                    $result = $stmt->get_result();

                                                    while ($row = $result->fetch_assoc()) {
                                                        echo "<tr class='text-center'>";
                                                        echo "<td>" . htmlspecialchars($row['pat_fname']) . "</td>";
                                                        echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                                                        echo "<td>" . htmlspecialchars($row['pat_nik']) . "</td>";
                                                        echo "<td>" . htmlspecialchars($row['pat_med_cardnumber']) . "</td>";
                                                        echo "<td>" . htmlspecialchars($row['alamat']) . "</td>";
                                                        //echo "<td>" . htmlspecialchars($row['pat_nohp']) . "</td>";
                                                        echo "<td>" . htmlspecialchars($row['pat_refnumber']) . "</td>";
                                                        echo "<td>" . htmlspecialchars($row['pat_nobpjs']) . "</td>";
                                                        echo "<td><button class='btn btn-info btn-sm' data-toggle='modal' data-target='#ktpModal' data-ktp='". htmlspecialchars($row['ktp_file']) . "'><i class='fas fa-eye'></i></button></td>";
                                                        echo "<td><button class='btn btn-info btn-sm' data-toggle='modal' data-target='#bpjsModal' data-bpjs='" . htmlspecialchars($row['bpjs_file']) . "'><i class='fas fa-eye'></i></button></td>";
                                                        echo "<td><button class='btn btn-info btn-sm' data-toggle='modal' data-target='#rujukanModal' data-rujukan='" . htmlspecialchars($row['rujukan_file']) . "'><i class='fas fa-eye'></i></button></td>";
                                                        echo "<td>
                                                                <form method='post' action='his_admin_update_status.php' onsubmit='return confirm(\"Anda yakin ingin mengubah status?\")'>
                                                                    <input type='hidden' name='treat_id' value='" . htmlspecialchars($row['treat_id']) . "'>
                                                                    <button type='submit' name='status' value='Disetujui' class='btn btn-success btn-sm'><i class='fas fa-check'></i></button>
                                                                    <button type='button' class='btn btn-danger btn-sm' data-toggle='modal' data-target='#rejectModal' data-id='" . htmlspecialchars($row['treat_id']) . "'><i class='fas fa-times'></i></button>
                                                                </form>
                                                              </td>";
                                                        echo "</tr>";
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

                <!-- Modals for KTP and BPJS -->
                <div id="ktpModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="ktpModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="ktpModalLabel">Bukti KTP</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <img src="" id="ktpImage" class="img-fluid" alt="Gambar KTP">
                            </div>
                        </div>
                    </div>
                </div>

                <div id="bpjsModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="bpjsModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="bpjsModalLabel">Bukti BPJS</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <img src="" id="bpjsImage" class="img-fluid" alt="Gambar BPJS">
                            </div>
                        </div>
                    </div>
                </div>

                <div id="rujukanModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="rujukanModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="rujukanModalLabel">Bukti Surat Rujukan</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <img src="" id="rujukanImage" class="img-fluid" alt="Gambar Rujuaka">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal for Rejection Reason -->
                <div id="rejectModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="rejectModalLabel">Alasan Penolakan</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="rejectForm" method="post" action="his_admin_update_status.php">
                                    <input type="hidden" name="treat_id" id="rejectTreatId">
                                    <div class="form-group">
                                        <label for="reason">Alasan</label>
                                        <textarea class="form-control" name="reason" id="reason" rows="3" required></textarea>
                                    </div>
                                    <button type="submit" name="status" value="Ditolak" class="btn btn-danger">Kirim</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

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
            // Script to show the KTP image in the modal
            $('#ktpModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var ktp = button.data('ktp');
                var modal = $(this);
                modal.find('#ktpImage').attr('src', ktp);
            });

            // Script to show the BPJS image in the modal
            $('#bpjsModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var bpjs = button.data('bpjs');
                var modal = $(this);
                modal.find('#bpjsImage').attr('src', bpjs);
            });

            // Script to show the BPJS image in the modal
            $('#rujukanModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var rujukan = button.data('rujukan');
                var modal = $(this);
                modal.find('#rujukanImage').attr('src', rujukan);
            });

            // Script to handle rejection modal
            $('#rejectModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');
                var modal = $(this);
                modal.find('#rejectTreatId').val(id);
            });
        </script>
    </body>
</html>
