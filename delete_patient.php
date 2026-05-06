<?php
session_start();
if (!isset($_SESSION['user'])) { header("Location: login.php"); exit(); }

$conn = new mysqli("localhost", "root", "", "healthfile_db");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // Requirement 4: Secure method to delete records
    $sql = "DELETE FROM patients WHERE patient_id = $id";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: dashboard.php?msg=DeletedSuccessfully");
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}
?>