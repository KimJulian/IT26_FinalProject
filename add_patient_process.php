<?php
session_start();
if (!isset($_SESSION['user'])) { header("Location: login.php"); exit(); }
$conn = new mysqli("localhost", "root", "", "healthfile_db");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['patient_name'];
    $diagnosis = $_POST['diagnosis'];
    $doctor_id = $_POST['doctor_id'];
    $status = "Active";
    $date = date('Y-m-d');

    $sql = "INSERT INTO patients (patient_name, admission_date, diagnosis, doctor_id, status) 
            VALUES ('$name', '$date', '$diagnosis', $doctor_id, '$status')";

    if ($conn->query($sql) === TRUE) {
        header("Location: dashboard.php?msg=PatientAdded");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>