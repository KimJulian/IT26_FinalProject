<?php
session_start();
if (!isset($_SESSION['user'])) { header("Location: login.php"); exit(); }
$conn = new mysqli("localhost", "root", "", "healthfile_db");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['patient_name'];
    $course = $_POST['course'];
    $sy = $_POST['school_year'];
    $date = $_POST['date_recorded'];
    $diagnosis = $_POST['diagnosis'];
    $meds = $_POST['meds_given'];
    $doctor_id = $_POST['doctor_id'];
    $status = "Active";
    $date = date('Y-m-d');

    $sql = "INSERT INTO patients (patient_name, course, school_year, date_recorded, diagnosis, meds_given, doctor_id, status) 
        VALUES ('$name', '$course', '$sy', '$date', '$diagnosis', '$meds', '$doctor_id', 'Active')";
    if ($conn->query($sql) === TRUE) {
        header("Location: dashboard.php?msg=PatientAdded");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>