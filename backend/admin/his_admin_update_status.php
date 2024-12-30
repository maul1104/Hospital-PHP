<?php
session_start();
include('assets/inc/config.php');
include('assets/inc/checklogin.php');
check_login();
$aid = $_SESSION['ad_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $treat_id = $_POST['treat_id'];
    $status = $_POST['status'];
    $reason = isset($_POST['reason']) ? $_POST['reason'] : null;

    // SQL untuk memperbarui status dan alasan penolakan
    $query = "UPDATE pat_treatment SET status=?, reason=? WHERE treat_id=?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('ssi', $status, $reason, $treat_id);
    $stmt->execute();

    if ($stmt) {
        header("Location: his_admin_view_pharm_cat.php?status=updated");
    } else {
        header("Location: his_admin_view_pharm_cat.php?status=error");
    }
    exit();
}
?>

