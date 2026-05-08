<?php
session_start();
if (!isset($_SESSION['user'])) { header("Location: login.php"); exit(); }

$conn = new mysqli("localhost", "root", "", "healthfile_db");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $sql = "DELETE FROM inventory WHERE item_id = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: inventory.php?msg=DeletedSuccessfully");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}
?>