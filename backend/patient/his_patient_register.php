<!--Server side code to handle  sign up-->
<?php
	session_start();
	include('assets/inc/config.php');
		if(isset($_POST['pat_sup']))
		{
			$pat_fname=$_POST['pat_fname'];
            $pat_lname=$_POST['pat_lname'];
            $pat_birth=$_POST['pat_birth'];
            $pat_nik=$_POST['pat_nik'];
            $pat_nobpjs=$_POST['pat_nobpjs'];
			$pat_user=$_POST['pat_user'];
			$pat_pass=sha1(md5($_POST['pat_pass']));//double encrypt to increase security
            //$pat_number =$_POST['pat_number'];
            //sql to insert captured values
			$query="insert into his_patient (pat_fname, pat_lname , pat_birth , pat_nik , pat_nobpjs , pat_user , pat_pass) values(?,?,?,?,?,?,?)";
			$stmt = $mysqli->prepare($query);
			$rc=$stmt->bind_param('sssssss', $pat_fname , $pat_lname , $pat_birth , $pat_nik , $pat_nobpjs , $pat_user, $pat_pass);
			$stmt->execute();
			/*
			*Use Sweet Alerts Instead Of This Fucked Up Javascript Alerts
			*echo"<script>alert('Successfully Created Account Proceed To Log In ');</script>";
			*/ 
			//declare a varible which will be passed to alert function
			if($stmt)
			{
				$success = "Created Account Proceed To Log In";
			}
			else {
				$err = "Please Try Again Or Try Later";
			}
			
			
		}
?>
<!--End Server Side-->
<!--End Login-->
<!DOCTYPE html>
<html lang="en">
    
<head>
        <meta charset="utf-8" />
        <title>Hospital Management Information System -A Super Responsive Information System</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
        <meta content="Coderthemes" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="assets/images/favicon.ico">

        <!-- App css -->
        <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" />
        <!--Load Sweet Alert Javascript-->
        <script src="assets/js/swal.js"></script>
        <!--Inject SWAL-->
        <?php if(isset($success)) {?>
        <!--This code for injecting an alert-->
                <script>
                            setTimeout(function () 
                            { 
                                swal("Success","<?php echo $success;?>","success");
                            },
                                100);
                </script>

        <?php } ?>

        <?php if(isset($err)) {?>
        <!--This code for injecting an alert-->
                <script>
                            setTimeout(function () 
                            { 
                                swal("Failed","<?php echo $err;?>","Failed");
                            },
                                100);
                </script>

        <?php } ?>

    </head>

    <body class="authentication-bg authentication-bg-pattern">

        <div class="account-pages mt-5 mb-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card bg-pattern">

                            <div class="card-body p-4">
                                
                                <div class="text-center w-75 m-auto">
                                    <a href="his_admin_register.php">
                                        <span><img src="assets/images/logo-dark.png" alt="" height="22"></span>
                                    </a>
                                    <p class="text-muted mb-4 mt-3">Don't have an account? Create your account, it takes less than a minute</p>
                                </div>

                                <form  method='post'>

                                    <div class="form-group">
                                        <label for="fullname">Nama Depan</label>
                                        <input class="form-control" type="text"  name = "pat_fname" id="fullname" placeholder="Enter your first name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="fullname">Nama Belakang</label>
                                        <input class="form-control" type="text"  name = "pat_lname" id="fullname" placeholder="Enter your last name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="fullname">Tanggal Lahir</label>
                                        <input class="form-control" type="date"  name = "pat_birth" id="fullname" placeholder="DD/MM/YYYY" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="fullname">No. KTP</label>
                                        <input class="form-control" type="text"  name = "pat_nik" id="fullname" placeholder="Enter your Number Of KTP" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="fullname">Nama Belakang</label>
                                        <input class="form-control" type="text"  name = "pat_nobpjs" id="fullname" placeholder="Enter your Number Of BPJS" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="fullname">Username</label>
                                        <input class="form-control" type="text" name="pat_user" id="fullname" placeholder="Enter your username" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input class="form-control" name="pat_pass" type="password" required id="password" placeholder="Enter your password">
                                    </div>
                                    <!--<div class="form-group col-md-2" style="display:none">
                                                    <?php 
                                                        $length = 5;    
                                                        $patient_number =  substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1,$length);
                                                    ?>
                                                    <label for="inputZip" class="col-form-label">Patient Number</label>
                                                    <input type="text" name="pat_number" value="<?php echo $patient_number;?>" class="form-control" id="inputZip">
                                                </div>-->
                                    
                                    <div class="form-group mb-0 text-center">
                                        <button class="btn btn-primary btn-block" name="pat_sup" type="submit"> Sign Up </button>
                                    </div>

                                </form>
                                <!--Lets Disable This For We tryna implement it in later versions of this system
                                <div class="text-center">
                                    <h5 class="mt-3 text-muted">Sign up using</h5>
                                    <ul class="social-list list-inline mt-3 mb-0">
                                        <li class="list-inline-item">
                                            <a href="javascript: void(0);" class="social-list-item border-primary text-primary"><i class="mdi mdi-facebook"></i></a>
                                        </li>
                                        <li class="list-inline-item">
                                            <a href="javascript: void(0);" class="social-list-item border-danger text-danger"><i class="mdi mdi-google"></i></a>
                                        </li>
                                        <li class="list-inline-item">
                                            <a href="javascript: void(0);" class="social-list-item border-info text-info"><i class="mdi mdi-twitter"></i></a>
                                        </li>
                                        <li class="list-inline-item">
                                            <a href="javascript: void(0);" class="social-list-item border-secondary text-secondary"><i class="mdi mdi-github-circle"></i></a>
                                        </li>
                                    </ul>
                                </div>
                                -->

                            </div> <!-- end card-body -->
                        </div>
                        <!-- end card -->

                        <div class="row mt-3">
                            <div class="col-12 text-center">
                                <p class="text-white-50">Already have account?  <a href="index.php" class="text-white ml-1"><b>Sign In</b></a></p>
                            </div> <!-- end col -->
                        </div>
                        <!-- end row -->

                    </div> <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end page -->

        <!--Footer-->
            <?php include("assets/inc/footer1.php");?>
        <!-- End Footer-->

        <!-- Vendor js -->
        <script src="assets/js/vendor.min.js"></script>

        <!-- App js -->
        <script src="assets/js/app.min.js"></script>
        
    </body>

</html>