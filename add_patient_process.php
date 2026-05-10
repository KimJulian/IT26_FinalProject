<?php
session_start();
if (!isset($_SESSION['user'])) { header("Location: login.php"); exit(); }
$conn = new mysqli("localhost", "root", "", "healthfile_db");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['patient_name'];
    $course = $_POST['course'];
    $sy = $_POST['school_year'];
    $diagnosis = $_POST['diagnosis'];
    $doctor_id = $_POST['doctor_id'];
    $date = date('Y-m-d');

    $item_id = $_POST['item_id']; 
    $quantity = isset($_POST['quantity_given']) ? (int)$_POST['quantity_given'] : 0;

    $med_name = "None";
    if (!empty($item_id)) {
        $get_med = $conn->query("SELECT item_name FROM inventory WHERE item_id = '$item_id'");
        if ($row = $get_med->fetch_assoc()) {
            $med_name = $row['item_name'];
        }
    }

    $sql = "INSERT INTO patients (patient_name, course, school_year, date_recorded, diagnosis, meds_given, item_id, doctor_id, status) 
        VALUES ('$name', '$course', '$sy', '$date', '$diagnosis', '$med_name', '$item_id', '$doctor_id', 'Active')";

    if ($conn->query($sql) === TRUE) {
        
        if (!empty($item_id) && $quantity > 0) {
            $sql_update_stock = "UPDATE inventory 
                                 SET stock_quantity = stock_quantity - $quantity 
                                 WHERE item_id = '$item_id'";
            
            $conn->query($sql_update_stock);
        }
        
        header("Location: dashboard.php?msg=Success");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>