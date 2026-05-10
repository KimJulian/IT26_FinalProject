<?php
session_start();
if (!isset($_SESSION['user'])) { header("Location: login.php"); exit(); }
$conn = new mysqli("localhost", "root", "", "healthfile_db");

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM patients WHERE patient_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$patient = $result->fetch_assoc();

$assigned_meds = [];
$med_query = $conn->prepare("SELECT item_id FROM patient_medications WHERE patient_id = ?");
$med_query->bind_param("i", $id);
$med_query->execute();
$med_result = $med_query->get_result();
while($med_row = $med_result->fetch_assoc()) {
    $assigned_meds[] = $med_row['item_id'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Patient - HealthFile</title>
    <style>
    body { 
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
        background-color: #f0f2f5; 
        padding: 50px; 
    }
    .form-card { 
        background: white; 
        padding: 40px; 
        border-radius: 12px; 
        max-width: 550px; 
        margin: auto; 
        box-shadow: 0 4px 15px rgba(0,0,0,0.1); 
        border-top: 5px solid #003366; 
    }
    .form-card h2 {
        color: #003366;
        margin-top: 0;
        text-align: center;
        font-size: 1.6rem;
    }
    .form-group {
        margin-bottom: 20px;
        display: flex;
        flex-direction: column;
    }
    label { 
        font-weight: bold; 
        margin-bottom: 8px; 
        color: #003366;
        font-size: 0.95rem; 
    }
    input, select, textarea, button { 
        width: 100%; 
        padding: 12px; 
        border: 1px solid #ccd1d9; 
        border-radius: 6px; 
        box-sizing: border-box; 
        font-size: 1rem;
        transition: border-color 0.3s ease; 
    }
    input:focus, select:focus, textarea:focus {
        outline: none;
        border-color: #003366;
        box-shadow: 0 0 0 2px rgba(0, 51, 102, 0.1);
    }
    textarea { 
        font-family: inherit; 
        resize: vertical; 
        min-height: 100px; 
    }
    button { 
        background-color: #FFCC00;
        color: #003366;
        border: none; 
        padding: 15px;
        width: 100%;
        border-radius: 6px;
        cursor: pointer; 
        font-weight: bold;
        font-size: 1rem;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    button:hover { 
        background-color: #e6b800;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15); 
    }
    .back-link { 
        text-align: center; 
        display: block; 
        margin-top: 20px; 
        color: #003366; 
        text-decoration: none; 
        font-weight: 500;
        font-size: 0.9rem;
    }
    .back-link:hover {
        text-decoration: underline;
    }
</style>
</head>
<body>
<div class="form-card">
    <h2>Edit Patient Record</h2>
    <form action="edit_patient_process.php" method="POST">
        <input type="hidden" name="patient_id" value="<?php echo $patient['patient_id']; ?>">
        
        <label>Patient Name:</label>
        <input type="text" name="patient_name" value="<?php echo htmlspecialchars($patient['patient_name']); ?>" required>

        <div class="form-group">
         <label>Course:</label>
          <select name="course" required>
           <option value="" disabled <?php if(empty($patient['course'])) echo 'selected'; ?>>Select Department</option>
           <option value="ICS" <?php if($patient['course'] == 'ICS') echo 'selected'; ?>>ICS (Institute for Computer Studies)</option>
           <option value="IBM" <?php if($patient['course'] == 'IBM') echo 'selected'; ?>>IBM (Institute for Business Management)</option>
           <option value="ITE" <?php if($patient['course'] == 'ITE') echo 'selected'; ?>>ITE (Institute for Teacher Education)</option>
         </select>
        </div>

        <div class="form-group">
         <label>School Year:</label>
          <select name="school_year" required>
           <option value="" disabled <?php if(empty($patient['school_year'])) echo 'selected'; ?>>Select School Year</option>
           <option value="1st Year" <?php if($patient['school_year'] == '1st Year') echo 'selected'; ?>>1st Year</option>
           <option value="2nd Year" <?php if($patient['school_year'] == '2nd Year') echo 'selected'; ?>>2nd Year</option>
           <option value="3rd Year" <?php if($patient['school_year'] == '3rd Year') echo 'selected'; ?>>3rd Year</option>
           <option value="4th Year" <?php if($patient['school_year'] == '4th Year') echo 'selected'; ?>>4th Year</option>
          </select>
        </div>

        <label>Date Recorded:</label>
        <input type="date" name="date_recorded" value="<?php echo $patient['date_recorded']; ?>" required>

        <label>Diagnosis:</label>
        <input type="text" name="diagnosis" value="<?php echo htmlspecialchars($patient['diagnosis']); ?>" required>

        <div class="form-group">
        <label>Meds Given:</label>
          <select name="item_ids[]" multiple class="form-control" style="height: 120px;">
              <?php
              $inventory = $conn->query("SELECT item_id, item_name FROM inventory");
              while($inv_row = $inventory->fetch_assoc()) {
                  $selected = in_array($inv_row['item_id'], $assigned_meds) ? 'selected' : '';
                  echo "<option value='".$inv_row['item_id']."' $selected>".$inv_row['item_name']."</option>";
                }
                ?>
            </select>
            <small style="color: #003366; margin-top: 5px;">Hold <b>Ctrl</b> (Windows) to select multiple items.</small>
        </div>

        <button type="submit">Update Record</button>
    </form>
    <a href="dashboard.php" class="back-link">Cancel</a>
</div>
</body>
</html>