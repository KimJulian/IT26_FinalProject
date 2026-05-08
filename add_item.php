<?php
session_start();
if (!isset($_SESSION['user'])) { header("Location: login.php"); exit(); }

$conn = new mysqli("localhost", "root", "", "healthfile_db");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_name = $_POST['item_name'];
    $category = $_POST['category'];
    $stock = $_POST['stock'];
    $unit = $_POST['unit'];
    $expiry = $_POST['expiry'];

    $sql = "INSERT INTO inventory (item_name, category, stock_quantity, unit, expiry_date) 
            VALUES ('$item_name', '$category', $stock, '$unit', '$expiry')";

    if ($conn->query($sql) === TRUE) {
        header("Location: inventory.php?msg=ItemAdded");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Inventory - HealthFile</title>
   <style>
    body { 
        font-family: 'Segoe UI', sans-serif; 
        background-color: #f4f4f4; 
        display: flex; 
        margin: 0; 
    }

    .sidebar { 
        width: 250px; 
        background-color: #9A6F77; 
        color: white; 
        height: 100vh; 
        position: fixed; 
        padding: 20px; 
        box-sizing: border-box; 
    }

    .container { 
        margin-left: 250px; 
        padding: 40px; 
        width: calc(100% - 250px); 
        box-sizing: border-box; 
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: flex-start;
    }

    .form-card { 
        background: white; 
        padding: 40px; 
        border-radius: 12px; 
        box-shadow: 0 4px 15px rgba(0,0,0,0.1); 
        width: 100%;
        max-width: 600px;
    }

    input, 
    select { 
        width: 100%; 
        padding: 12px; 
        margin: 10px 0 20px 0; 
        border: 1px solid #ddd; 
        border-radius: 5px; 
        box-sizing: border-box; 
    }

    label {
        font-weight: bold;
        color: #555;
        font-size: 0.9rem;
    }

    .btn-save { 
        background: #9A6F77; 
        color: white; 
        border: none; 
        padding: 14px; 
        width: 100%; 
        border-radius: 5px; 
        cursor: pointer; 
        font-weight: bold; 
        font-size: 1rem;
        transition: background 0.3s ease;
    }

    .btn-save:hover {
        background: #7d5a61;
    }
</style>
</head>
<body>

<div class="sidebar">
    <h2 style="border-bottom: 1px solid rgba(255,255,255,0.2); padding-bottom: 10px;">HealthFile</h2>
    <ul style="list-style: none; padding: 0;">
        <li><a href="inventory.php" style="color: white; text-decoration: none;">⬅️ Back to Inventory</a></li>
    </ul>
</div>

<div class="container">
    <div class="form-card">
        <h2 style="margin-top: 0; color: #333;">Add New Medical Item</h2>
        <form method="POST">
            <label>Item Name</label>
            <input type="text" name="item_name" placeholder="e.g. Paracetamol" required>
            
            <label>Category</label>
            <select name="category">
                <option value="Medicine">Medicine</option>
                <option value="Capsule">Capsule</option>
                <option value="Supplies">Supplies</option>
            </select>
            
            <label>Stock Quantity</label>
            <input type="number" name="stock" placeholder="0" required>
            
            <label>Unit</label>
            <input type="text" name="unit" placeholder="e.g. pcs, boxes, bottles" required>
            
            <label>Expiry Date</label>
            <input type="date" name="expiry" required>
            
            <button type="submit" class="btn-save">Save Item</button>
        </form>
    </div>
</div>

</body>
</html>