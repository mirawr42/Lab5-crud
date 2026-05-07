<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize inputs
    $matric = mysqli_real_escape_string($conn, $_POST['matric']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $accessLevel = mysqli_real_escape_string($conn, $_POST['accessLevel']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert query
    $sql = "INSERT INTO users (matric, name, accessLevel, password) 
            VALUES ('$matric', '$name', '$accessLevel', '$hashed_password')";

    if (mysqli_query($conn, $sql)) {
        echo "<p style='color:green;'>Registration successful! <a href='login.php'>Login now</a></p>";
    } else {
        echo "<p style='color:red;'>Error: " . mysqli_error($conn) . "</p>";
    }
}
?>