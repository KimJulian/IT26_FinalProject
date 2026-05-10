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

    $stmt = $conn->prepare("INSERT INTO patients (patient_name, course, school_year, date_recorded, diagnosis, doctor_id, status) VALUES (?, ?, ?, ?, ?, ?, 'Active')");
    $stmt->bind_param("sssssi", $name, $course, $sy, $date, $diagnosis, $doctor_id);
    
    if ($stmt->execute()) {
        $patient_id = $conn->insert_id;

        if (!empty($_POST['item_ids']) && is_array($_POST['item_ids'])) {
            
            $med_stmt = $conn->prepare("INSERT INTO patient_medications (patient_id, item_id, quantity_given) VALUES (?, ?, 1)");
            $update_stock = $conn->prepare("UPDATE inventory SET stock_quantity = stock_quantity - 1 WHERE item_id = ?");

            foreach ($_POST['item_ids'] as $item_id) {
                if (!empty($item_id)) {
                    $med_stmt->bind_param("ii", $patient_id, $item_id);
                    $med_stmt->execute();

                    $update_stock->bind_param("i", $item_id);
                    $update_stock->execute();
                }
            }
            $med_stmt->close();
            $update_stock->close();
        }
        
        header("Location: dashboard.php?msg=Success");
        exit();
    } else {
        echo "Error recording patient: " . $stmt->error;
    }
    $stmt->close();
}
?>