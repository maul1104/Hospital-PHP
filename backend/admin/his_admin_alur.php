<?php
session_start();
include('assets/inc/config.php');
include('assets/inc/checklogin.php');
check_login();
$aid = $_SESSION['ad_id'];

if(isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $adn = "DELETE FROM his_alur WHERE id_alur = ?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();

    if($stmt) {
        $success = "Alur deleted successfully";
    } else {
        $err = "Try Again Later";
    }
}

if(isset($_POST['add_alur'])) {
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $gambar = $_FILES['gambar_alur']['name'];
    move_uploaded_file($_FILES['gambar_alur']['tmp_name'], "assets/images/alur/" . $gambar);

    $query = "INSERT INTO his_alur (judul, deskripsi, gambar_alur) VALUES (?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('sss', $judul, $deskripsi, $gambar);
    $stmt->execute();
    $stmt->close();
}

if(isset($_POST['update_alur'])) {
    $id_alur = $_POST['id_alur'];
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $gambar = $_FILES['gambar_alur']['name'];
    if ($gambar != "") {
        move_uploaded_file($_FILES['gambar_alur']['tmp_name'], "assets/images/alur/" . $gambar);
        $query = "UPDATE his_alur SET judul = ?, deskripsi = ?, gambar_alur = ? WHERE id_alur = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('sssi', $judul, $deskripsi, $gambar, $id_alur);
    } else {
        $query = "UPDATE his_alur SET judul = ?, deskripsi = ? WHERE id_alur = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('ssi', $judul, $deskripsi, $id_alur);
    }
    $stmt->execute();
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
                                        <li class="breadcrumb-item active">Manage Alur</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Manage Alur</h4>
                            </div>
                        </div>
                    </div>     
                    <!-- end page title --> 

                    <div class="row">
                        <div class="col-12">
                            <div class="card-box">
                                <h4 class="header-title">Data Alur</h4>
                                <div class="mb-2">
                                    <button class="btn btn-primary" data-toggle="modal" data-target="#addAlurModal">Add Alur</button>
                                </div>

                                <div class="table-responsive">
                                    <table id="alurTable" class="table table-bordered">
                                        <thead>
                                        <tr class="text-center">
                                            <th>#</th>
                                            <th>Judul</th>
                                            <th>Deskripsi</th>
                                            <th>Gambar</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $ret = "SELECT * FROM his_alur ORDER BY id_alur DESC";
                                        $stmt = $mysqli->prepare($ret);
                                        $stmt->execute();
                                        $res = $stmt->get_result();
                                        $cnt = 1;
                                        while ($row = $res->fetch_object()) {
                                        ?>
                                            <tr>
                                                <td><?php echo $cnt;?></td>
                                                <td><?php echo $row->judul;?></td>
                                                <td><?php echo $row->deskripsi;?></td>
                                                <td width ="10%" class ="text-center"><img src="assets/images/alur/<?php echo $row->gambar_alur;?>" width="90%"></td>
                                                <td class="text-center">
                                                    <button class="btn btn-primary" data-toggle="modal" data-target="#updateAlurModal<?php echo $row->id_alur;?>">Update</button>
                                                    <a href="his_admin_manage_alur.php?delete=<?php echo $row->id_alur;?>" class="btn btn-danger">Delete</a>
                                                </td>
                                            </tr>

                                            <!-- Update Alur Modal -->
                                            <div class="modal fade" id="updateAlurModal<?php echo $row->id_alur;?>" tabindex="-1" role="dialog" aria-labelledby="updateAlurModalLabel<?php echo $row->id_alur;?>" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <form method="post" enctype="multipart/form-data">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="updateAlurModalLabel<?php echo $row->id_alur;?>">Update Alur</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <input type="hidden" name="id_alur" value="<?php echo $row->id_alur;?>">
                                                                <div class="form-group">
                                                                    <label for="judul">Judul</label>
                                                                    <input type="text" name="judul" class="form-control" value="<?php echo $row->judul;?>" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="deskripsi">Deskripsi</label>
                                                                    <textarea name="deskripsi" class="form-control" rows="3" required><?php echo $row->deskripsi;?></textarea>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="gambar_alur">Gambar</label>
                                                                    <input type="file" name="gambar_alur" class="form-control">
                                                                    <img src="assets/images/alur/<?php echo $row->gambar_alur;?>" width="100" class="mt-2">
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                <button type="submit" name="update_alur" class="btn btn-primary">Save changes</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php $cnt = $cnt + 1; }?>
                                        </tbody>
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

    <!-- Add Alur Modal -->
    <div class="modal fade" id="addAlurModal" tabindex="-1" role="dialog" aria-labelledby="addAlurModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="post" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addAlurModalLabel">Add Alur</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="judul">Judul</label>
                            <input type="text" name="judul" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="deskripsi">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="gambar_alur">Gambar</label>
                            <input type="file" name="gambar_alur" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="add_alur" class="btn btn-primary">Add Alur</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- Vendor js -->
    <script src="assets/js/vendor.min.js"></script>

    <!-- App js -->
    <script src="assets/js/app.min.js"></script>
    
</body>

</html>
