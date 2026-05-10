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
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background-color: #f0f2f5; 
            display: flex; 
            justify-content: center; 
            padding-top: 50px; 
        }
        .form-card { 
            background: white; 
            padding: 30px; 
            border-radius: 12px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.1); 
            width: 420px; 
            border-top: 5px solid #003366; 
        }
        h2 { 
            color: #003366;
            margin-top: 0; 
            font-size: 1.6rem;
            text-align: center; 
        }
        .form-group { 
            margin-bottom: 15px; 
        }
        label { 
            display: block; 
            margin-bottom: 5px; 
            font-weight: bold; 
            color: #003366;
            font-size: 0.9rem; 
        }
        input, select, textarea { 
            width: 100%; 
            padding: 10px; 
            border: 1px solid #ccd1d9; 
            border-radius: 6px; 
            box-sizing: border-box; 
            font-size: 0.95rem; 
        }
        input:focus, select:focus {
            outline: none;
            border-color: #003366;
            box-shadow: 0 0 0 2px rgba(0, 51, 102, 0.1);
        }
        button { 
            background: #FFCC00;
            color: #003366;
            border: none; 
            padding: 14px; 
            width: 100%; 
            border-radius: 6px; 
            font-weight: bold; 
            font-size: 1rem;
            cursor: pointer; 
            margin-top: 15px; 
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1); 
        }
        button:hover {
            background: #e6b800;
            transform: translateY(-1px);
        }
        .back-link { 
            display: block; 
            text-align: center; 
            margin-top: 20px; 
            color: #003366; 
            text-decoration: none; 
            font-size: 0.9rem; 
            font-weight: 500; 
        }
        .back-link:hover {
            text-decoration: underline;
        }
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
    <label>Medications & Quantities:</label>
    <table class="table" id="medicationTable">
        <thead>
            <tr>
                <th>Medicine</th>
                <th>Quantity</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="medicationBody">
            <tr>
                <td>
                    <select name="item_ids[]" class="form-control" required>
                        <option value="">-- Select --</option>
                        <?php
                        $items = $conn->query("SELECT item_id, item_name, stock_quantity FROM inventory WHERE stock_quantity > 0");
                        while($row = $items->fetch_assoc()) {
                            echo "<option value='".$row['item_id']."'>".$row['item_name']." (Stock: ".$row['stock_quantity'].")</option>";
                        }
                        ?>
                    </select>
                </td>
                <td><input type="number" name="quantities[]" class="form-control" min="1" value="1" required></td>
                <td><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">Remove</button></td>
            </tr>
        </tbody>
    </table>
    <button type="button" class="btn btn-info btn-sm" onclick="addRow()">+ Add Another Medicine</button>
</div>

<div class="form-group" id="quantity_container" style="display: none;">
    <label for="quantity_given">Quantity (Tablets/Pieces):</label>
    <input type="number" name="quantity_given" id="quantity_given" class="form-control" min="1">
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
<script>
function toggleQuantityField() {
    var select = document.getElementById('item_id');
    var container = document.getElementById('quantity_container');
    var input = document.getElementById('quantity_given');
    
    if (select.value !== "") {
        container.style.display = "block";
        input.setAttribute('required', 'required');
        
        var selectedOption = select.options[select.selectedIndex];
        var maxStock = selectedOption.getAttribute('data-stock');
        
        input.setAttribute('max', maxStock);
    } else {
        container.style.display = "none";
        input.removeAttribute('required');
        input.value = "";
    }
}

document.getElementById('quantity_given').addEventListener('input', function() {
    var select = document.getElementById('item_id');
    var maxStock = parseInt(select.options[select.selectedIndex].getAttribute('data-stock'));
    var enteredValue = parseInt(this.value);

    if (enteredValue > maxStock) {
        alert("Error: You only have " + maxStock + " units in stock!");
        this.value = maxStock;
    }
});
</script>
<script>
function addRow() {
    let tbody = document.getElementById('medicationBody');
    let firstRow = tbody.rows[0].cloneNode(true); // Clone the first row
    firstRow.querySelectorAll('input').forEach(input => input.value = '1'); // Reset quantity
    tbody.appendChild(firstRow);
}

function removeRow(btn) {
    let row = btn.parentNode.parentNode;
    if (document.getElementById('medicationBody').rows.length > 1) {
        row.parentNode.removeChild(row);
    } else {
        alert("At least one medication row is required.");
    }
}
</script>
</html>