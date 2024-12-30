<?php
    session_start();
    unset($_SESSION['pat_id']);
    session_destroy();

    header("Location: his_patient_logout.php");
    exit;
?>