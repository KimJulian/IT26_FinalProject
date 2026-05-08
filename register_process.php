<?php
$conn = new mysqli("localhost", "root", "", "healthfile_db");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pass = $_POST['password']; // In a real app, use password_hash()

    $sql = "INSERT INTO users (username, password) VALUES ('$user', '$pass')";

    if ($conn->query($sql) === TRUE) {
        header("Location: login.php?msg=Registered");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>