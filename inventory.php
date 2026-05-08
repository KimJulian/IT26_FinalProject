<?php
session_start();
if (!isset($_SESSION['user'])) { header("Location: login.php"); exit(); }

$conn = new mysqli("localhost", "root", "", "healthfile_db");

// Fetch all inventory items
$sql = "SELECT * FROM inventory ORDER BY item_name ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inventory - HealthFile</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; margin: 0; display: flex; background-color: #f4f4f4; }
        
        .sidebar { width: 250px; background-color: #9A6F77; color: white; height: 100vh; padding: 20px; position: fixed; left: 0; top: 0; display: flex; flex-direction: column; box-sizing: border-box; }
        .sidebar a { color: white; text-decoration: none; display: flex; align-items: center; gap: 10px; margin-bottom: 15px; font-weight: 500; }
        
        .container { margin-left: 250px; padding: 40px; width: calc(100% - 250px); box-sizing: border-box; }
        
        .table-container { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .status-low { color: #d9534f; font-weight: bold; } /* Red for low stock */
        .status-ok { color: #5cb85c; font-weight: bold; }  /* Green for healthy stock */
        
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; background: #f8f9fa; padding: 15px; border-bottom: 2px solid #dee2e6; font-size: 0.85rem; }
        td { padding: 15px; border-bottom: 1px solid #eee; font-size: 0.9rem; }
        .btn-add { background: #9A6F77; color: white; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2 style="border-bottom: 1px solid rgba(255,255,255,0.2); padding-bottom: 10px;">HealthFile</h2>
    <ul style="list-style: none; padding: 0;">
        <li><a href="dashboard.php">📊 Dashboard</a></li>
        <li><a href="about.php">👥 About Us</a></li>
        <li><a href="appointments.php">📅 Appointments</a></li>
        <li><a href="inventory.php" style="background: rgba(255,255,255,0.1); border-radius: 5px; padding: 5px;">💊 Inventory</a></li>
    </ul>
    <div style="margin-top: auto; border-top: 1px solid rgba(255,255,255,0.2); padding-top: 20px;">
        <a href="logout.php" style="color: #ffb3b3;">🚪 Logout</a>
    </div>
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
                    <a href="edit_item.php?id=<?php echo $item['item_id']; ?>" style="color: #9A6F77; text-decoration: none; font-weight: bold;">Edit</a>
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