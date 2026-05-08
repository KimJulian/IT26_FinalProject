<?php
session_start();
if (!isset($_SESSION['user'])) { header("Location: login.php"); exit(); }
$conn = new mysqli("localhost", "root", "", "healthfile_db");

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM patients WHERE patient_id = $id");
$patient = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Patient - HealthFile</title>
    <style>
    body { font-family: 'Segoe UI', sans-serif; background-color: #f4f4f4; padding: 50px; }
    .form-card { background: white; padding: 30px; border-radius: 8px; max-width: 500px; margin: auto; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    
    .form-group {
        margin-bottom: 15px;
        display: flex;
        flex-direction: column;
    }

    label { 
        font-weight: bold; 
        margin-bottom: 5px; 
        color: #333; 
    }

    input, select, textarea, button { 
        width: 100%; 
        padding: 10px; 
        margin: 5px 0 10px 0; 
        border: 1px solid #ddd; 
        border-radius: 5px; 
        box-sizing: border-box; 
    }

    textarea { font-family: inherit; resize: vertical; }

    button { background-color: #9A6F77; color: white; border: none; cursor: pointer; font-weight: bold; }
    button:hover { background-color: #7d5a61; }

    .back-link { text-align: center; display: block; margin-top: 15px; color: #9A6F77; text-decoration: none; }
</style>
</head>
<body>
<div class="form-card">
    <h2>Edit Patient Record</h2>
    <form action="edit_patient_process.php" method="POST">
        <input type="hidden" name="patient_id" value="<?php echo $patient['patient_id']; ?>">
        
        <label>Patient Name:</label>
        <input type="text" name="patient_name" value="<?php echo htmlspecialchars($patient['patient_name']); ?>" required>

        <label>Course:</label>
        <input type="text" name="course" value="<?php echo htmlspecialchars($patient['course']); ?>" required>

        <label>School Year:</label>
        <input type="text" name="school_year" value="<?php echo htmlspecialchars($patient['school_year']); ?>" required>

        <label>Date Recorded:</label>
        <input type="date" name="date_recorded" value="<?php echo $patient['date_recorded']; ?>" required>

        <label>Diagnosis:</label>
        <input type="text" name="diagnosis" value="<?php echo htmlspecialchars($patient['diagnosis']); ?>" required>

        <label>Meds Given:</label>
        <textarea name="meds_given"><?php echo htmlspecialchars($patient['meds_given']); ?></textarea>

        <label>Status:</label>
        <select name="status">
            <option value="Active" <?php if($patient['status'] == 'Active') echo 'selected'; ?>>Active</option>
            <option value="Discharged" <?php if($patient['status'] == 'Discharged') echo 'selected'; ?>>Discharged</option>
        </select>

        <button type="submit">Update Record</button>
    </form>
    <a href="dashboard.php" class="back-link">Cancel</a>
</div>
</body>
</html>