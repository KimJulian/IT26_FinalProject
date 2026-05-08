<?php
$conn = new mysqli("localhost", "root", "", "healthfile_db");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['patient_id'];
    $name = $_POST['patient_name'];
    $course = $_POST['course'];
    $year = $_POST['school_year'];
    $diagnosis = $_POST['diagnosis'];
    $meds = $_POST['meds_given'];

    $sql = "UPDATE patients SET 
            patient_name='$name', 
            course='$course', 
            school_year='$year', 
            diagnosis='$diagnosis', 
            meds_given='$meds' 
            WHERE patient_id=$id";

    if ($conn->query($sql) === TRUE) {
        header("Location: dashboard.php?msg=UpdatedSuccessfully");
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>