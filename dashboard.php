<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "healthfile_db");

// 1. Fetch Chart Data
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Clinic Dashboard - HealthFile</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { 
            font-family: 'Segoe UI', sans-serif; 
            margin: 0; 
            display: flex; 
            background-color: #f0f2f5;
            overflow-x: hidden; 
        }
        .sidebar { 
            width: 250px; 
            background-color: #003366;
            color: white; 
            height: 100vh; 
            padding: 20px; 
            position: fixed; 
            left: 0; top: 0; 
            display: flex; 
            flex-direction: column; 
            box-sizing: border-box; 
        }
        .sidebar h2 { 
            font-size: 1.5rem; 
            border-bottom: 1px solid #FFCC00; 
            padding-bottom: 15px; 
            margin-top: 0;
            color: #FFCC00; 
        }
        .sidebar ul { 
            list-style: none; 
            padding: 0; 
            margin: 0; 
            flex-grow: 1; 
        }
        .sidebar li { 
            margin-bottom: 15px; 
        }
        .sidebar a { 
            color: white; 
            text-decoration: none; 
            display: flex; 
            align-items: center; 
            gap: 10px; 
            font-weight: 500; 
        }
        .sidebar a:hover { 
            background-color: rgba(255, 204, 0, 0.1);
            color: #FFCC00; 
        }
        .logout-section { 
            margin-top: auto; 
            border-top: 1px solid rgba(255,255,255,0.2); 
            padding-top: 20px; 
            margin-bottom: 20px; 
        }

        .container { 
            margin-left: 250px;
            padding: 40px; 
            width: calc(100% - 250px); 
            box-sizing: border-box; 
            min-height: 100vh;
        }

        .chart-row { 
            display: flex; 
            gap: 20px; 
            margin-bottom: 25px; 
        }
        .chart-card { 
            background: white; 
            padding: 20px; 
            border-radius: 8px; 
            flex: 1; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.1); 
            border-top: 4px solid #003366;
        }
        .chart-card h3 { 
            margin-top: 0; 
            color: #666; 
            font-size: 0.85rem; 
            text-transform: uppercase; 
            letter-spacing: 1px; 
        }

        .table-container { 
            background: white; 
            padding: 25px; 
            border-radius: 8px; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.1); 
        }
        .table-header { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            margin-bottom: 20px; 
        }
        .btn-add { 
            background: #FFCC00; 
            color: #003366;
            padding: 10px 20px; 
            border-radius: 6px; 
            text-decoration: none; 
            font-size: 0.9rem; 
            font-weight: bold; 
            transition: 0.3s; 
        }
        .btn-add:hover { 
            background: #7d5a61; 
        }

        table { 
            width: 100%; 
            border-collapse: collapse; 
        }
        th { 
            text-align: left; 
            background: #f8f9fa; 
            padding: 15px; 
            border-bottom: 2px solid #003366;
            font-size: 0.85rem; 
            color: #333; 
        }
        td { 
            padding: 15px; 
            border-bottom: 1px solid #eee; 
            font-size: 0.9rem; 
            color: #555; 
        }
        .action-links a { 
            font-weight: bold; 
            text-decoration: none; 
            font-size: 0.85rem; 
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>HealthFile</h2>
    <ul>
        <li><a href="about.php">👥 About Us</a></li>
        <li><a href="inventory.php">💊 Available Med/Capsule</a></li>
    </ul>
    <div class="logout-section">
        <a href="logout.php" style="color: white; font-weight: bold">
            <span style="font-size: 1.2rem;">🚪</span> Logout
        </a>
    </div>
</div>

<div class="container">
    
    <?php if (isset($_GET['msg'])): ?>
        <div style="padding: 15px; margin-bottom: 25px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 8px; text-align: center; font-weight: 500;">
            <?php 
                if ($_GET['msg'] == 'UpdatedSuccessfully') echo "✅ Record updated successfully!";
                if ($_GET['msg'] == 'PatientAdded') echo "✅ New patient added!";
                if ($_GET['msg'] == 'Deleted') echo "🗑️ Record removed successfully!";
            ?>
        </div>
    <?php endif; ?>

    <div class="chart-row">
        <div class="chart-card">
            <h3>Students by Institute</h3>
            <div style="height: 220px;"><canvas id="courseChart"></canvas></div>
        </div>
        <div class="chart-card">
            <h3>Year Level Distribution</h3>
            <div style="height: 220px;"><canvas id="yearChart"></canvas></div>
        </div>
    </div>

    <div class="table-container">
        <div class="table-header">
            <a href="add_patient.php" class="btn-add">+ Add Record</a>
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
                    <th>Medicines</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT patients.*, inventory.category, inventory.unit 
                        FROM patients 
                        LEFT JOIN inventory ON patients.meds_given = inventory.item_name 
                    ORDER BY patients.date_recorded DESC";
            
                $result = $conn->query($sql);

                if ($result->num_rows > 0): 
                    while($row = $result->fetch_assoc()): 
                        $fullName = trim($row['patient_name']);
                        $nameParts = explode(' ', $fullName);
                        $count = count($nameParts);

                    if ($fullName == "Kim Julian D. Mentopa") {
                        $firstName = "Kim Julian"; $middleInitial = "D."; $lastName = "Mentopa";
                    } elseif ($count >= 4) {
                        $firstName = $nameParts[0] . " " . $nameParts[1]; $middleInitial = $nameParts[2]; $lastName = $nameParts[3];
                    } elseif ($count == 3) {
                        $firstName = $nameParts[0]; $middleInitial = $nameParts[1]; $lastName = $nameParts[2];
                    } else {
                        $firstName = $nameParts[0]; $middleInitial = ""; $lastName = $nameParts[1] ?? "";
                    }
                ?>
                <tr>
                    <td><?php echo $row['patient_id']; ?></td>
                    <td><?php echo htmlspecialchars($firstName); ?></td>
                    <td><?php echo htmlspecialchars($middleInitial); ?></td> 
                    <td><?php echo htmlspecialchars($lastName); ?></td>
                    <td><?php echo htmlspecialchars($row['course']); ?></td>
                    <td><?php echo htmlspecialchars($row['school_year']); ?></td>
                    <td><?php echo htmlspecialchars($row['date_recorded']); ?></td>
                    <td><?php echo htmlspecialchars($row['diagnosis']); ?></td>
                    <td><?php echo htmlspecialchars($row['meds_given']); ?></td>
                    <td class="action-links" style="display: flex; gap: 10px;">
                        <a href="edit_patient.php?id=<?php echo $row['patient_id']; ?>" style="color: #9A6F77; text-decoration: none; font-weight: bold;">Update</a>
    
                        <span style="color: #ccc;">|</span> 
    
                        <a href="delete_patient.php?id=<?php echo $row['patient_id']; ?>" 
                        onclick="return confirm('Delete this record?');" 
                        style="color: #d9534f; text-decoration: none; font-weight: bold;">
                        Delete
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>

                <?php else: ?>
                <tr>
                    <td colspan="10" style="text-align: center; padding: 50px; color: #7a7a7a;">
                        <div style="font-size: 24px; margin-bottom: 10px;">📋</div>
                        <p style="font-style: italic; margin: 0;">No student records have been found in the HealthFile system.</p>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

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
        options: { maintainAspectRatio: false, plugins: { legend: { display: false } } }
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
        options: { maintainAspectRatio: false, plugins: { legend: { position: 'right' } } }
    });
</script>
</body>
</html>