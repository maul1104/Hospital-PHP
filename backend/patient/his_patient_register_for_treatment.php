<?php
session_start();
include('assets/inc/config.php');

// Pastikan bahwa pat_id diset di sesi
if (!isset($_SESSION['pat_id'])) {
    die('Patient ID is not set in session.');
}

// Assuming the patient's ID is stored in session upon login
$pat_id = $_SESSION['pat_id'];

// Query untuk mengambil data pasien
$query = "SELECT pat_fname, pat_lname, pat_birth, pat_nik, pat_nobpjs FROM his_patient WHERE pat_id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param('s', $pat_id);
$stmt->execute();
$stmt->bind_result($pat_fname, $pat_lname, $pat_birth, $pat_nik, $pat_nobpjs);
$stmt->fetch();
$stmt->close();

if (isset($_POST['add_patient'])) {
    $pat_fname = $_POST['pat_fname'];
    $pat_lname = $_POST['pat_lname'];
    $pat_birth = $_POST['pat_birth'];
    $pat_age = $_POST['pat_age'];
    $alamat = $_POST['alamat'];
    $pat_nohp = $_POST['pat_nohp'];
    $pat_refnumber = $_POST['pat_refnumber'];
    $pat_nik = $_POST['pat_nik'];
    $pat_med_cardnumber = $_POST['pat_med_cardnumber'];
    $pat_nobpjs = $_POST['pat_nobpjs'];
    $status = 'Menunggu';
    $status_polik = 'Menunggu';

    // Check if files were uploaded
    if (isset($_FILES["ktp_file"]) && isset($_FILES["bpjs_file"]) && isset($_FILES["rujukan_file"])) {
        $target_dir = "../patient/assets/images/users/";

        // Handle KTP file
        $ktp_file = $target_dir . basename($_FILES["ktp_file"]["name"]);
        $ktp_file_type = strtolower(pathinfo($ktp_file, PATHINFO_EXTENSION));

        // Handle BPJS file
        $bpjs_file = $target_dir . basename($_FILES["bpjs_file"]["name"]);
        $bpjs_file_type = strtolower(pathinfo($bpjs_file, PATHINFO_EXTENSION));

        // Handle BPJS file
        $rujukan_file = $target_dir . basename($_FILES["rujukan_file"]["name"]);
        $rujukan_file_type = strtolower(pathinfo($rujukan_file, PATHINFO_EXTENSION));

        // Check file types
        $allowed_types = array('jpg', 'jpeg', 'png');
        if (in_array($ktp_file_type, $allowed_types) && in_array($bpjs_file_type, $allowed_types) && in_array($rujukan_file_type, $allowed_types)) {
            // Move uploaded files to target directory
            if (move_uploaded_file($_FILES["ktp_file"]["tmp_name"], $ktp_file) && move_uploaded_file($_FILES["bpjs_file"]["tmp_name"], $bpjs_file) && move_uploaded_file($_FILES["rujukan_file"]["tmp_name"], $rujukan_file)) {
                // SQL to insert captured values
                $query = "INSERT INTO pat_treatment (pat_fname, pat_lname, pat_birth, pat_age, alamat, pat_nohp, pat_refnumber, pat_nik, pat_med_cardnumber, pat_nobpjs, pat_id, status, status_polik, ktp_file, bpjs_file, rujukan_file) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $mysqli->prepare($query);
                if ($stmt === false) {
                    die('Prepare failed: ' . htmlspecialchars($mysqli->error));
                }
                $stmt->bind_param('ssssssssssssssss', $pat_fname, $pat_lname, $pat_birth, $pat_age, $alamat, $pat_nohp, $pat_refnumber, $pat_nik, $pat_med_cardnumber, $pat_nobpjs, $pat_id, $status, $status_polik, $ktp_file, $bpjs_file, $rujukan_file);
                if ($stmt->execute()) {
                    echo "<script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                title: 'Success!',
                                text: 'Data sudah dikirim, menunggu persetujuan dari admin!',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = 'his_patient_regist_history.php?status=success';
                                }
                            });
                        });
                    </script>";
                } else {
                    echo "Execute failed: " . htmlspecialchars($stmt->error);
                }
                $stmt->close();
            } else {
                echo "Sorry, there was an error uploading your files.";
            }
        } else {
            echo "Only JPG, JPEG, and PNG files are allowed.";
        }
    } else {
        echo "Files are not set.";
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
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Pasien</a></li>
                                        <li class="breadcrumb-item active">Add Patient</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Daftar Berobat</h4>
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
                                    <form method="post" enctype="multipart/form-data">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="inputNamaDepan" class="col-form-label">Nama Depan</label>
                                                <input type="text" required="required" name="pat_fname" class="form-control" id="inputNamaDepan" placeholder="Nama Depan Pasien" value="<?php echo htmlspecialchars($pat_fname); ?>" readonly>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="inputNamaBelakang" class="col-form-label">Nama Belakang</label>
                                                <input required="required" type="text" name="pat_lname" class="form-control" id="inputNamaBelakang" placeholder="Nama Belakang Pasien" value="<?php echo htmlspecialchars($pat_lname); ?>" readonly>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="inputTanggalLahir" class="col-form-label">Tanggal Lahir</label>
                                                <input type="date" required="required" name="pat_birth" class="form-control" id="inputTanggalLahir" placeholder="DD/MM/YYYY" value="<?php echo htmlspecialchars($pat_birth); ?>" onchange="calculateAge()" readonly>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="inputUsia" class="col-form-label">Usia</label>
                                                <input required="required" type="text" name="pat_age" class="form-control" id="inputUsia" placeholder="Usia Pasien" readonly>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="inputAlamat" class="col-form-label">Alamat</label>
                                            <input required="required" type="text" class="form-control" name="alamat" id="inputAlamat" placeholder="Alamat Pasien">
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label for="inputNoHp" class="col-form-label">Nomor Hp</label>
                                                <input required="required" type="text" name="pat_nohp" class="form-control" id="inputNoHp">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="inputRefNumber" class="col-form-label">Nomor Surat Rujukan</label>
                                                <input required="required" type="text" name="pat_refnumber" class="form-control" id="inputRefNumber">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="inputNoBpjs" class="col-form-label">Nomor BPJS</label>
                                                <input required="required" type="text" name="pat_nobpjs" class="form-control" id="inputNoBpjs" value="<?php echo htmlspecialchars($pat_nobpjs); ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="inputNoNik" class="col-form-label">Nomor NIK</label>
                                                <input required="required" type="text" name="pat_nik" class="form-control" id="inputNoNik" value="<?php echo htmlspecialchars($pat_nik); ?>" readonly>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="inputNoKartuBerobat" class="col-form-label">Nomor Kartu Berobat</label>
                                                <input type="text" name="pat_med_cardnumber" class="form-control" id="inputNoKartuBerobat">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label for="inputKTP" class="col-form-label">Upload KTP</label>
                                                <input required="required" type="file" name="ktp_file" class="btn btn-success form-control" id="inputKTP">
                                            </div>
                                            <!--<div class="form-group col-md-4">
                                                <label for="inputKTP" class="col-form-label">Upload KTP</label>
                                                <input required="required" type="file" name="ktp_file" class="btn btn-success form-control" id="inputKTP" accept="image/*" onchange="processKTP()">
                                                <p id="ocr-status"></p>
                                            </div>-->

                                            <div class="form-group col-md-4">
                                                <label for="inputBPJS" class="col-form-label">Upload BPJS</label>
                                                <input required="required" type="file" name="bpjs_file" class="btn btn-success form-control" id="inputBPJS">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="inputSuratRujukan" class="col-form-label">Upload Surat Rujukan</label>
                                                <input required="required" type="file" name="rujukan_file" class="btn btn-success form-control" id="inputSuratRujukan">
                                            </div>
                                        </div>

                                        <button type="submit" name="add_patient" class="ladda-button btn btn-primary" data-style="expand-right">Daftar</button>
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
    </div>
    <!-- END wrapper -->

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- Vendor js -->
    <script src="assets/js/vendor.min.js"></script>
    <!-- App js-->
    <script src="assets/js/app.min.js"></script>
    <!-- SweetAlert js -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Custom JavaScript to Calculate Age -->
    <script>
    // Fungsi untuk menghitung usia berdasarkan tanggal lahir
    function calculateAge() {
        const birthDate = document.getElementById('inputTanggalLahir').value;
        const birthDateObj = new Date(birthDate);
        const today = new Date();
        let age = today.getFullYear() - birthDateObj.getFullYear();
        const m = today.getMonth() - birthDateObj.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birthDateObj.getDate())) {
            age--;
        }
        document.getElementById('inputUsia').value = age;
    }

    // Panggil fungsi calculateAge secara otomatis setelah halaman dimuat
    window.onload = function() {
        if (document.getElementById('inputTanggalLahir').value) {
            calculateAge();
        }
    };
</script>

    <!--<script>
function processKTP() {
    const fileInput = document.getElementById('inputKTP');
    const file = fileInput.files[0];
    const statusText = document.getElementById('ocr-status');
    
    if (!file) {
        alert("Silakan upload foto KTP.");
        return;
    }

    // Jalankan Tesseract untuk membaca teks dari gambar KTP
    Tesseract.recognize(
        file,
        'ind',  // Tentukan bahasa (bisa menggunakan 'ind' untuk bahasa Indonesia)
        {
            logger: (m) => console.log(m) // Log progres OCR
        }
    ).then(({ data: { text } }) => {
        console.log(text); // Lihat hasil teks dari OCR

        // Cek apakah teks hasil OCR mengandung NIK
        if (text.includes("NIK") || text.match(/\d{16}/)) {
            statusText.textContent = "Foto KTP valid.";
            statusText.style.color = "green";
        } else {
            statusText.textContent = "Foto KTP tidak valid. Pastikan mengupload foto KTP yang jelas.";
            statusText.style.color = "red";
            fileInput.value = ""; // Reset input file jika tidak valid
        }
    }).catch(err => {
        console.error(err);
        statusText.textContent = "Terjadi kesalahan saat membaca foto.";
        statusText.style.color = "red";
    });
}
</script>-->

</body>
</html>
