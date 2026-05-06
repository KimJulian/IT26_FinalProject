<?php
session_start();
if (!isset($_SESSION['user'])) { header("Location: login.php"); exit(); }
$conn = new mysqli("localhost", "root", "", "healthfile_db");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Patient - HealthFile</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f4f4f4; padding: 50px; }
        .form-card { background: white; padding: 30px; border-radius: 8px; max-width: 500px; margin: auto; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        input, select, button { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        button { background-color: #9A6F77; color: white; border: none; cursor: pointer; font-weight: bold; }
        button:hover { background-color: #7d5a61; }
        .back-link { text-align: center; display: block; margin-top: 15px; color: #9A6F77; text-decoration: none; }
    </style>
</head>
<body>

<div class="form-card">
    <h2>Add New Patient</h2>
    <form action="add_patient_process.php" method="POST">
        <label>Patient Name:</label>
        <input type="text" name="patient_name" required>

        <label>Diagnosis:</label>
        <input type="text" name="diagnosis" required>

        <label>Assigned Doctor:</label>
        <select name="doctor_id" required>
            <?php
            // Fetch doctors for the dropdown
            $doctors = $conn->query("SELECT * FROM doctors");
            while($row = $doctors->fetch_assoc()) {
                echo "<option value='".$row['doctor_id']."'>".$row['doctor_name']." (".$row['specialization'].")</option>";
            }
            ?>
        </select>

        <label>Status:</label>
        <select name="status">
            <option value="Active">Active</option>
            <option value="Discharged">Discharged</option>
        </select>

        <button type="submit">Save Patient Record</button>
    </form>
    <a href="dashboard.php" class="back-link">Back to Dashboard</a>
</div>

</body>
</html>