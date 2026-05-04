<?php
session_start();

// Establish connection to XAMPP MySQL
$conn = new mysqli("localhost", "root", "", "clinic_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // SQL Query to find the user
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $_SESSION['user'] = $username;
        header("Location: dashboard.php");
        exit(); // Always use exit after a header redirect
    } else {
        header("Location: login.php?error=Invalid Credentials");
        exit();
    }
}
?>