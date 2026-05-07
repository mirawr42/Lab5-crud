<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $matric = mysqli_real_escape_string($conn, $_POST['matric']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $accessLevel = mysqli_real_escape_string($conn, $_POST['accessLevel']);
    $new_password = $_POST['new_password'];

    if (!empty($new_password)) {
        $hashed = password_hash($new_password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET name='$name', accessLevel='$accessLevel', password='$hashed' WHERE matric='$matric'";
    } else {
        $sql = "UPDATE users SET name='$name', accessLevel='$accessLevel' WHERE matric='$matric'";
    }

    if (mysqli_query($conn, $sql)) {
        header("Location: display.php");
        exit();
    } else {
        echo "Error updating: " . mysqli_error($conn);
    }
}
?>