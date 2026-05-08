<?php
session_start();
if (!isset($_SESSION['user'])) { header("Location: login.php"); exit(); }
$conn = new mysqli("localhost", "root", "", "healthfile_db");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Patient - HealthFile</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f4f4f4; display: flex; justify-content: center; padding-top: 50px; }
        .form-card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); width: 400px; }
        h2 { color: #9A6F77; margin-top: 0; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; color: #555; }
        input, select, textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        button { background: #9A6F77; color: white; border: none; padding: 12px; width: 100%; border-radius: 5px; font-weight: bold; cursor: pointer; margin-top: 10px; }
        .back-link { display: block; text-align: center; margin-top: 15px; color: #666; text-decoration: none; font-size: 0.9rem; }
    </style>
</head>
<body>

<div class="form-card">
    <h2>Add New Patient</h2>
    <form action="add_patient_process.php" method="POST">
    <label>Patient Name:</label>
    <input type="text" name="patient_name" placeholder="Full Name" required>

    <div class="form-group">
            <label>Course:</label>
            <select name="course" required>
                <option value="" disabled selected>Select Department</option>
                <option value="ICS">ICS (Institute for Computer Studies)</option>
                <option value="IBM">IBM (Institute for Business Management)</option>
                <option value="ITE">ITE (Institute for Teacher Education)</option>
            </select>
        </div>

        <div class="form-group">
            <label>School Year:</label>
            <select name="school_year" required>
                <option value="" disabled selected>Select School Year</option>
                <option value="1st Year">1st Year</option>
                <option value="2nd Year">2nd Year</option>
                <option value="3rd Year">3rd Year</option>
                <option value="4th Year">4th Year</option>
            </select>
        </div>

    <label>Date Recorded:</label>
    <input type="date" name="date_recorded" required>

    <div class="form-group">
            <label>Diagnosis:</label>
            <input type="text" name="diagnosis" placeholder="Patient condition" required>
        </div>

        <div class="form-group">
            <label>Meds Given:</label>
            <textarea name="meds_given" rows="3" placeholder="List medications provided"></textarea>
        </div>

    <label>Assigned Doctor:</label>
    <select name="doctor_id" required>
        <?php
        $doctors = $conn->query("SELECT * FROM doctors");
        while($row = $doctors->fetch_assoc()) {
            echo "<option value='".$row['doctor_id']."'>".$row['doctor_name']." (".$row['specialization'].")</option>";
        }
        ?>
    </select>

    <button type="submit">Save Patient Record</button>
        <a href="dashboard.php" class="back-link">Cancel and Go Back</a>
</form>
    <a href="dashboard.php" class="back-link">Back to Dashboard</a>
</div>

</body>
</html>