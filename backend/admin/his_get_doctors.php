<?php
include('assets/inc/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $doc_dept = $_POST['doc_dept'];

    $query = "SELECT * FROM his_docs WHERE doc_dept = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('s', $doc_dept);
    $stmt->execute();
    $result = $stmt->get_result();

    echo "<option value=''>Pilih Dokter</option>";
    while ($row = $result->fetch_assoc()) {
        echo "<option value='" . htmlspecialchars($row['doc_id']) . "'>" . htmlspecialchars($row['doc_fname']) . " " . htmlspecialchars($row['doc_lname']) . "</option>";
    }
}
?>
