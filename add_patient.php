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
    <input type="text" name="patient_name" placeholder="Full Name" required>

    <label>Course:</label>
    <input type="text" name="course" placeholder="e.g., BSIT" required>

    <label>School Year:</label>
    <input type="text" name="school_year" placeholder="e.g., 2025-2026" required>

    <label>Date Recorded:</label>
    <input type="date" name="date_recorded" required>

    <label>Diagnosis:</label>
    <input type="text" name="diagnosis" placeholder="Patient condition" required>

    <label>Meds Given:</label>
    <textarea name="meds_given" placeholder="List medications provided (e.g., Paracetamol in blister pack)"></textarea>

    <label>Assigned Doctor:</label>
    <select name="doctor_id" required>
        <?php
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

    <button type="submit" style="background-color: #9A6F77; color: white; padding: 12px; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; margin-top: 20px;">
        Save Patient Record
    </button>
</form>
    <a href="dashboard.php" class="back-link">Back to Dashboard</a>
</div>

</body>
</html>