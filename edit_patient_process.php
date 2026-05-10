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
            $item_ids = $_POST['item_ids'];
            $quantities = $_POST['quantities'];
            $med_stmt = $conn->prepare("INSERT INTO patient_medications (patient_id, item_id, quantity_given) VALUES (?, ?, ?)");
            
            for ($i = 0; $i < count($item_ids); $i++) {
                $id = $item_ids[$i];
                $qty = $quantities[$i];

                if (!empty($id) && $qty > 0) {
                    $med_stmt->bind_param("iii", $patient_id, $id, $qty);
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