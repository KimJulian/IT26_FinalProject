<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "healthfile_db");

$totalPatients = $conn->query("SELECT * FROM patients")->num_rows;
$activeCases = $conn->query("SELECT * FROM patients WHERE status='Active'")->num_rows;
$newToday = $conn->query("SELECT * FROM patients WHERE DATE(date_recorded) = CURDATE()")->num_rows;


$course_data = $conn->query("SELECT course, COUNT(*) as count FROM patients GROUP BY course");
$courses = []; $course_counts = [];
while($row = $course_data->fetch_assoc()){
    $courses[] = $row['course'];
    $course_counts[] = $row['count'];
}

$year_data = $conn->query("SELECT school_year, COUNT(*) as count FROM patients GROUP BY school_year");
$years = []; $year_counts = [];
while($row = $year_data->fetch_assoc()){
    $years[] = $row['school_year'];
    $year_counts[] = $row['count'];
}
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

        .container { margin-left: 260px; padding: 30px; width: calc(100% - 280px); }
        .stats-row { display: flex; gap: 20px; margin-bottom: 20px; }
        .stat-card { background: white; padding: 20px; border-radius: 8px; flex: 1; box-shadow: 0 2px 5px rgba(0,0,0,0.1); border-top: 4px solid #9A6F77; }
        .stat-card h3 { margin: 0; font-size: 0.9rem; color: #666; text-transform: uppercase; }
        .stat-card p { font-size: 1.8rem; font-weight: bold; margin: 10px 0 0; color: #9A6F77; }

        .chart-row { display: flex; gap: 20px; margin-bottom: 20px; }
        .chart-card { background: white; padding: 20px; border-radius: 8px; flex: 1; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }

        .table-container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .table-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
        .search-box { padding: 8px; border: 1px solid #ddd; border-radius: 5px; width: 200px; }
        
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; background: #f8f9fa; padding: 12px; border-bottom: 2px solid #dee2e6; font-size: 0.85rem; }
        td { padding: 12px; border-bottom: 1px solid #eee; font-size: 0.9rem; }
        .btn-add { background: #9A6F77; color: white; padding: 8px 15px; border-radius: 5px; text-decoration: none; font-size: 0.9rem; font-weight: bold; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>HealthFile</h2>
    <ul>
        <li><a href="about.php">👥 About Us</a></li>
        <li><a href="appointments.php">📅 Appointments</a></li>
        <li><a href="inventory.php">💊 Available Med/Capsule</a></li>
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

    <div class="stats-row">
        <div class="stat-card">
            <h3>Total Patients</h3>
            <p>532</p>
        </div>
        <div class="stat-card">
            <h3>Active Cases</h3>
            <p>12</p>
        </div>
        <div class="stat-card">
            <h3>New Today</h3>
            <p>5</p>
        </div>
    </div>
    <div class="table-container">
        <div class="table-header">
            <a href="add_patient.php" class="btn-add">+ Add Record</a>
            <div class="search-area">
                <input type="text" class="search-box" placeholder="Search Lastname...">
            </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Firstname</th>
                    <th>Middle</th>
                    <th>Lastname</th>
                    <th>Course</th>
                    <th>Year</th>
                    <th>Date</th>
                    <th>Diagnosis</th>
                    <th>Meds</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM patients";
                $result = $conn->query($sql);
                while($row = $result->fetch_assoc()): 
                    $nameParts = explode(' ', $row['patient_name']);
                    $firstName = $nameParts[0];
                    $lastName = end($nameParts);
                ?>
                <tr>
                    <td><?php echo $row['patient_id']; ?></td>
                    <td><?php echo htmlspecialchars($firstName); ?></td>
                    <td>M.</td>
                    <td><?php echo htmlspecialchars($lastName); ?></td>
                    <td><?php echo htmlspecialchars($row['course']); ?></td>
                    <td><?php echo htmlspecialchars($row['school_year']); ?></td>
                    <td><?php echo htmlspecialchars($row['date_recorded']); ?></td>
                    <td><?php echo htmlspecialchars($row['diagnosis']); ?></td>
                    <td><?php echo htmlspecialchars($row['meds_given']); ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                    <td>
                        <a href="edit_patient.php?id=<?php echo $row['patient_id']; ?>" style="color: #9A6F77; font-weight: bold; text-decoration: none;">Update</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
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