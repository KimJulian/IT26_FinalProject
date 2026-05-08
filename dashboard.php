<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "healthfile_db");

$course_data = $conn->query("SELECT course, COUNT(*) as count FROM patients GROUP BY course");
$courses = []; $course_counts = [];
while($c_row = $course_data->fetch_assoc()){
    $courses[] = $c_row['course'];
    $course_counts[] = $c_row['count'];
}

$year_data = $conn->query("SELECT school_year, COUNT(*) as count FROM patients GROUP BY school_year");
$years = []; $year_counts = [];
while($y_row = $year_data->fetch_assoc()){
    $years[] = $y_row['school_year'];
    $year_counts[] = $y_row['count'];
}

$sql = "SELECT * FROM patients";
$result = $conn->query($sql);

while($row = $result->fetch_assoc()): 
    $nameParts = explode(' ', $row['patient_name']);
    $firstName = $nameParts[0];
    $middleInitial = (count($nameParts) > 2) ? $nameParts[1] : ""; 
    $lastName = end($nameParts);
?> <tr>
    <td><?php echo $row['patient_id']; ?></td>
    <td><?php echo htmlspecialchars($firstName); ?></td>
    <td><?php echo htmlspecialchars($middleInitial); ?></td> 
    <td><?php echo htmlspecialchars($lastName); ?></td>
</tr>

<?php endwhile; ?>

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
        .chart-card { background: white; padding: 15px; border-radius: 8px; flex: 1; box-shadow: 0 2px 5px rgba(0,0,0,0.1); max-height: 300px;}

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

    <div class="chart-row" style="display: flex; gap: 20px; margin-bottom: 25px;">
        <div class="chart-card" style="background: white; padding: 20px; border-radius: 8px; flex: 1; box-shadow: 0 2px 5px rgba(0,0,0,0.1); border-top: 4px solid #9A6F77;">
            <h3 style="margin-top: 0; color: #666; font-size: 0.9rem;">STUDENTS BY INSTITUTE</h3>
            <canvas id="courseChart" height="120"></canvas>
        </div>

        <div class="chart-card" style="background: white; padding: 20px; border-radius: 8px; flex: 1; box-shadow: 0 2px 5px rgba(0,0,0,0.1); border-top: 4px solid #9A6F77; height: 300px; overflow: hidden;">
            <h3 style="margin-top: 0; color: #666; font-size: 0.9rem;">YEAR LEVEL DISTRIBUTION</h3>
            <div style="position: relative; height: 220px; width: 100%;">
                <canvas id="yearChart"></canvas>
            </div>
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
                    <td><?php echo htmlspecialchars($row['middle_name']); ?></td>
                    <td><?php echo htmlspecialchars($lastName); ?></td>
                    <td><?php echo htmlspecialchars($row['course']); ?></td>
                    <td><?php echo htmlspecialchars($row['school_year']); ?></td>
                    <td><?php echo htmlspecialchars($row['date_recorded']); ?></td>
                    <td><?php echo htmlspecialchars($row['diagnosis']); ?></td>
                    <td><?php echo htmlspecialchars($row['meds_given']); ?></td>
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
    const courseCtx = document.getElementById('courseChart').getContext('2d');
    new Chart(courseCtx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($courses); ?>,
            datasets: [{
            label: 'Students',
            data: <?php echo json_encode($course_counts); ?>,
            backgroundColor: '#9A6F77'
        }]
    },
    options: { maintainAspectRatio: false }
});

   const yearCtx = document.getElementById('yearChart').getContext('2d');
new Chart(yearCtx, {
    type: 'pie',
    data: {
        labels: <?php echo json_encode($years); ?>,
        datasets: [{
            data: <?php echo json_encode($year_counts); ?>,
            backgroundColor: ['#9A6F77', '#C8A2C8', '#7d5a61', '#b38b93'],
            borderWidth: 1
        }]
    },
    options: { 
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top',
                labels: {
                    boxWidth: 12,
                    padding: 10
                }
            }
        }
    }
});
</script>
</html>