<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "healthfile_db");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Clinic Dashboard</title>
    <!-- Link to Chart.js for Requirement 2 -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f4f4f4; margin: 0; display: flex; }
        .sidebar { width: 250px; background: #9A6F77; height: 100vh; color: white; padding: 20px; }
        .main-content { flex-grow: 1; padding: 40px; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        .logout-btn { color: white; text-decoration: none; background: #7d5a61; padding: 10px; border-radius: 5px; display: inline-block; margin-top: 20px; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Clinic Admin</h2>
    <p>Welcome, <?php echo $_SESSION['user']; ?>!</p>
    <a href="logout.php" class="logout-btn">Logout</a>
</div>

<div class="main-content">
    <div class="card" style="width: 400px; margin: 0 auto;">
    <h3>Patient Status Overview</h3>
    <canvas id="statusChart"></canvas>
</div>
    <h1>Dashboard Overview</h1>
    
    <div class="card">
        <h3>Patient-Doctor Assignments (SQL Join)</h3>
        <table>
            <tr>
                <th>Patient Name</th>
                <th>Diagnosis</th>
                <th>Assigned Doctor</th>
                <th>Specialization</th>
            </tr>
            <?php

            $sql = "SELECT patients.patient_name, patients.diagnosis, doctors.doctor_name, doctors.specialization 
                    FROM patients 
                    INNER JOIN doctors ON patients.doctor_id = doctors.doctor_id";
            
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['patient_name']}</td>
                            <td>{$row['diagnosis']}</td>
                            <td>{$row['doctor_name']}</td>
                            <td>{$row['specialization']}</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No records found. Add data in the shell!</td></tr>";
            }
            ?>
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