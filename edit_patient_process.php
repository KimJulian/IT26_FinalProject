<?php
session_start();
if (!isset($_SESSION['user'])) { header("Location: login.php"); exit(); }
$conn = new mysqli("localhost", "root", "", "healthfile_db");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['patient_id'];
    $name = $_POST['patient_name'];
    $diagnosis = $_POST['diagnosis'];
    $status = $_POST['status'];

    $sql = "UPDATE patients SET patient_name='$name', diagnosis='$diagnosis', status='$status' WHERE patient_id=$id";

    if ($conn->query($sql) === TRUE) {
        header("Location: dashboard.php?msg=UpdatedSuccessfully");
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>