<?php
session_start();
if (!isset($_SESSION['user'])) { header("Location: login.php"); exit(); }

$conn = new mysqli("localhost", "root", "", "healthfile_db");

$sql = "SELECT * FROM inventory ORDER BY item_name ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inventory - HealthFile</title>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            margin: 0; 
            display: flex;
            background-color: #f0f2f5;
        }
        .sidebar { 
            width: 250px; 
            background-color: #003366;
            color: white; 
            height: 100vh; 
            padding: 20px; 
            position: fixed; 
            left: 0; 
            top: 0; 
            display: flex; 
            flex-direction: column; 
            box-sizing: border-box; 
            box-shadow: 2px 0 5px rgba(0,0,0,0.1); 
        }
        .sidebar h2 {
            color: #FFCC00;
            border-bottom: 2px solid #FFCC00; 
            padding-bottom: 15px;
            font-size: 1.5rem;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
        }
        .sidebar a { 
            color: white; 
            text-decoration: none; 
            display: flex; 
            align-items: center; 
            gap: 10px; 
            padding: 12px 10px;
            margin-bottom: 5px; 
            font-weight: 500; 
            border-radius: 6px;
            transition: 0.3s;
        }
        .sidebar a:hover {
            background: rgba(255, 204, 0, 0.1);
            color: #FFCC00;
        }
        .container { 
            margin-left: 250px; 
            padding: 40px; 
            width: calc(100% - 250px); 
            box-sizing: border-box; 
        }
        .table-container { 
            background: white;
            padding: 30px; 
            border-radius: 12px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.05); 
            border-top: 5px solid #003366; 
        }
        .status-low { 
            color: #d9534f; 
            font-weight: bold;  
        }
        .status-ok { 
            color: #28a745; 
            font-weight: bold; 
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px; 
        }
        th { 
            text-align: left; 
            background: #f8f9fa; 
            padding: 15px; 
            border-bottom: 2px solid #dee2e6; 
            font-size: 0.85rem; 
            color: #003366;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        td { 
            padding: 15px; 
            border-bottom: 1px solid #eee; 
            font-size: 0.9rem; 
            color: #333; 
        }
        tr:hover td {
            background-color: #fbfbfb;
        }
        .btn-add { 
            background: #FFCC00;
            color: #003366;
            padding: 12px 24px; 
            border-radius: 6px; 
            text-decoration: none; 
            font-weight: bold; 
            transition: 0.3s;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1); 
        }
        .btn-add:hover {
            background: #e6b800;
            transform: translateY(-1px);
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2 style="border-bottom: 1px solid rgba(255,255,255,0.2); padding-bottom: 10px;">HealthFile</h2>
    <ul style="list-style: none; padding: 0;">
        <li><a href="dashboard.php">📊 Dashboard</a></li>
        <li><a href="about.php">👥 About Us</a></li>
        <li><a href="inventory.php" style="background: rgba(255,255,255,0.1); border-radius: 5px; padding: 5px;">💊 Inventory</a></li>
    </ul>
</div>

<div class="container">
    <div class="table-container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2 style="margin: 0; color: #333;">Medical Inventory</h2>
            <a href="add_item.php" class="btn-add">+ Add New Item</a>
        </div>

        <table>
    <thead>
        <tr>
            <th>ID</th> 
            <th>Item Name</th>
            <th>Category</th>
            <th>Stock Level</th>
            <th>Expiry Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while($item = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $item['item_id']; ?></td> 
                <td><strong><?php echo htmlspecialchars($item['item_name']); ?></strong></td>
                <td><?php echo htmlspecialchars($item['category']); ?></td>
                <td>
                    <span class="<?php echo ($item['stock_quantity'] < 10) ? 'status-low' : 'status-ok'; ?>">
                        <?php echo $item['stock_quantity'] . " " . $item['unit']; ?>
                    </span>
                </td>
                <td><?php echo $item['expiry_date']; ?></td>
                <td>
                    <a href="edit_item.php?id=<?php echo $item['item_id']; ?>" 
                    style="color: #9A6F77; text-decoration: none; font-weight: bold;">Edit</a>
                    &nbsp; | &nbsp;
                    <a href="delete_item.php?id=<?php echo $item['item_id']; ?>" 
                    style="color: #d9534f; text-decoration: none; font-weight: bold;" 
                    onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="6" style="text-align: center;">No items found in inventory.</td></tr>
        <?php endif; ?>
    </tbody>
</table>
    </div>
</div>

</body>
</html>