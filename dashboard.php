<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
/*start http://localhost/HealthFile/login.php USE THIS IN GIT BASH*/
$conn = new mysqli("localhost", "root", "", "healthfile_db");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Clinic Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: 'Segoe UI', sans-serif; margin: 0; display: flex; background-color: #f4f4f4; }
        .sidebar { width: 220px; background-color: #9A6F77; color: white; height: 100vh; padding: 20px; position: fixed; }
        .sidebar h2 { font-size: 1.2rem; border-bottom: 1px solid rgba(255,255,255,0.3); padding-bottom: 10px; }
        .sidebar ul { list-style: none; padding: 0; }
        .sidebar li { padding: 12px 0; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .sidebar a { color: white; text-decoration: none; display: block; }
        .user-profile { position: absolute; bottom: 30px; }

        .container { margin-left: 260px; padding: 30px; width: 100%; }
        .stats-row { display: flex; gap: 20px; margin-bottom: 20px; }
        .stat-card { background: white; padding: 20px; border-radius: 8px; flex: 1; box-shadow: 0 2px 5px rgba(0,0,0,0.1); border-top: 4px solid #9A6F77; }
        .stat-card p { font-size: 1.8rem; font-weight: bold; margin: 10px 0 0; color: #9A6F77; }

        .table-container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .table-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; background: #f8f9fa; padding: 12px; border-bottom: 2px solid #dee2e6; }
        td { padding: 12px; border-bottom: 1px solid #eee; }
        .btn-add { background: #9A6F77; color: white; padding: 8px 15px; border-radius: 5px; text-decoration: none; font-size: 0.9rem; font-weight: bold; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>HealthFile</h2>
    <ul>
        <li><a href="dashboard.php">About Us</a></li>
        <li><a href="#">Appointments</a></li>
        <li><a href="#">Available Med/Capsule</a></li>
    </ul>
    <div class="user-profile">
        <p>👤 Dr. Dela Cruz</p>
        <a href="logout.php" style="font-size: 0.8rem; opacity: 0.8; color: white;">Logout</a>
    </div>
</div>

<div class="container">
    <?php if (isset($_GET['msg'])): ?>
        <div style="padding: 10px; margin-bottom: 20px; background-color: #d4edda; color: #155724; border-radius: 5px; text-align: center;">
            <?php 
                if ($_GET['msg'] == 'UpdatedSuccessfully') echo "✅ Record updated successfully!";
                if ($_GET['msg'] == 'PatientAdded') echo "✅ New patient added!";
                if ($_GET['msg'] == 'Deleted') echo "🗑️ Record removed successfully!";
            ?>
        </div>
    <?php endif; ?>
    
</div>
    <h1>Dashboard Overview</h1>
    
    <div class="card">
    <h3>Patient-Doctor Assignments (SQL Join)</h3>
    <table>
        <thead>
            <tr>
                <th>Patient Name</th>
                <th>Diagnosis</th>
                <th>Assigned Doctor</th>
                <th>Specialization</th>
                <th>Status</th>
                <th>Actions</th> 
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT patients.patient_id, 
               patients.patient_name, 
               patients.diagnosis, 
               patients.status,
               doctors.doctor_name, 
               doctors.specialization 
        FROM patients 
        INNER JOIN doctors ON patients.doctor_id = doctors.doctor_id";
            $result = $conn->query($sql);
            
            if ($result && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['patient_name']) . "</td>
                            <td>" . htmlspecialchars($row['diagnosis']) . "</td>
                            <td>" . htmlspecialchars($row['doctor_name']) . "</td>
                            <td>" . htmlspecialchars($row['specialization']) . "</td>
                            <td>" . htmlspecialchars($row['status']) . "</td>
                            <td>
                                 <a href='edit_patient.php?id=" . $row['patient_id'] . "' 
                                   style='color: #9A6F77; font-weight: bold; text-decoration: none; margin-right: 10px;'>
                                   Edit
                                </a>
                                <a href='delete_patient.php?id=" . $row['patient_id'] . "' 
                                   style='color: #9A6F77; font-weight: bold; text-decoration: none;'
                                   onclick='return confirm(\"Are you sure you want to delete this record?\")'>
                                   Delete
                                </a>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No records found. Click '+ Add New Patient' to start!</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
</div>

</body>
<script>
    const ctx = document.getElementById('statusChart').getContext('2d');
    const statusChart = new Chart(ctx, {
        type: 'pie', 
        data: {
            labels: ['Active', 'Discharged'],
            datasets: [{
                label: 'Number of Patients',
                data: [
                    <?php 
                        $active = $conn->query("SELECT * FROM patients WHERE status='Active'")->num_rows;
                        $discharged = $conn->query("SELECT * FROM patients WHERE status='Discharged'")->num_rows;
                        echo "$active, $discharged";
                    ?>
                ],
                backgroundColor: ['#9A6F77', '#C8A2C8'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true
        }
    });
</script>
</html>