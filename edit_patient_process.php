<?php
$conn = new mysqli("localhost", "root", "", "healthfile_db");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patient_id = $_POST['patient_id'];
    $name = $_POST['patient_name'];
    $course = $_POST['course'];
    $sy = $_POST['school_year'];
    $date = $_POST['date_recorded'];
    $diagnosis = $_POST['diagnosis'];

    $stmt = $conn->prepare("UPDATE patients SET patient_name=?, course=?, school_year=?, date_recorded=?, diagnosis=? WHERE patient_id=?");
    $stmt->bind_param("sssssi", $name, $course, $sy, $date, $diagnosis, $patient_id);

    if ($stmt->execute()) {
        $conn->query("DELETE FROM patient_medications WHERE patient_id = $patient_id");

        if (!empty($_POST['item_ids']) && is_array($_POST['item_ids'])) {
            $med_stmt = $conn->prepare("INSERT INTO patient_medications (patient_id, item_id, quantity_given) VALUES (?, ?, 1)");
            
            foreach ($_POST['item_ids'] as $item_id) {
                if (!empty($item_id)) {
                    $med_stmt->bind_param("ii", $patient_id, $item_id);
                    $med_stmt->execute();
                }
            }
            $med_stmt->close();
        }

        header("Location: dashboard.php?msg=Updated");
        exit();
    } else {
        echo "Error updating record: " . $stmt->error;
    }
    $stmt->close();
}
?>