<?php
session_start();
if (!isset($_SESSION['user'])) { header("Location: login.php"); exit(); }

$conn = new mysqli("localhost", "root", "", "healthfile_db");

// 1. Get the item details based on the ID from the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $conn->query("SELECT * FROM inventory WHERE item_id = $id");
    $item = $result->fetch_assoc();
}

// 2. Handle the update request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_name = $_POST['item_name'];
    $category  = $_POST['category'];
    $stock     = $_POST['stock'];
    $unit      = $_POST['unit'];
    $expiry    = $_POST['expiry'];
    $item_id   = $_POST['item_id'];

    $sql = "UPDATE inventory SET 
            item_name = '$item_name', 
            category = '$category', 
            stock_quantity = $stock, 
            unit = '$unit', 
            expiry_date = '$expiry' 
            WHERE item_id = $item_id";

    if ($conn->query($sql) === TRUE) {
        header("Location: inventory.php?msg=UpdatedSuccessfully");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Item - HealthFile</title>
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

        .sidebar h2 { 
            margin-top: 0; 
            font-size: 1.5rem; 
            border-bottom: 1px solid rgba(255, 255, 255, 0.2); 
            padding-bottom: 15px; 
        }

        .sidebar ul { 
            list-style: none; 
            padding: 0; 
        }

        .sidebar a { 
            color: white; 
            text-decoration: none; 
            font-weight: 500; 
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

        label {
            font-weight: bold;
            color: #555;
            font-size: 0.9rem;
        }

        input, select { 
            width: 100%; 
            padding: 12px; 
            margin: 10px 0 20px 0; 
            border: 1px solid #ddd; 
            border-radius: 5px; 
            box-sizing: border-box; 
        }

        .btn-update { 
            background: #9A6F77; 
            color: white; 
            border: none; 
            padding: 14px; 
            width: 100%; 
            border-radius: 5px; 
            cursor: pointer; 
            font-weight: bold; 
            font-size: 1rem;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>HealthFile</h2>
    <ul>
        <li><a href="inventory.php">⬅️ Back to Inventory</a></li>
    </ul>
</div>

<div class="container">
    <div class="form-card">
        <h2 style="margin-top: 0; color: #333;">Edit Medical Item</h2>
        <form method="POST">
            <input type="hidden" name="item_id" value="<?php echo $item['item_id']; ?>">

            <label>Item Name</label>
            <input type="text" name="item_name" value="<?php echo htmlspecialchars($item['item_name']); ?>" required>
            
            <label>Category</label>
            <select name="category">
                <option value="Medicine" <?php if($item['category'] == 'Medicine') echo 'selected'; ?>>Medicine</option>
                <option value="Capsule" <?php if($item['category'] == 'Capsule') echo 'selected'; ?>>Capsule</option>
                <option value="Supplies" <?php if($item['category'] == 'Supplies') echo 'selected'; ?>>Supplies</option>
            </select>
            
            <label>Stock Quantity</label>
            <input type="number" name="stock" value="<?php echo $item['stock_quantity']; ?>" required>
            
            <label>Unit</label>
            <input type="text" name="unit" value="<?php echo htmlspecialchars($item['unit']); ?>" required>
            
            <label>Expiry Date</label>
            <input type="date" name="expiry" value="<?php echo $item['expiry_date']; ?>" required>
            
            <button type="submit" class="btn-update">Update Item Details</button>
        </form>
    </div>
</div>

</body>
</html>