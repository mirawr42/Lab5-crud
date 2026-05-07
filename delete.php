<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include 'config.php';

if (isset($_GET['matric'])) {
    $matric = mysqli_real_escape_string($conn, $_GET['matric']);
    $sql = "DELETE FROM users WHERE matric='$matric'";
    if (mysqli_query($conn, $sql)) {
        header("Location: display.php");
        exit();
    } else {
        echo "Error deleting: " . mysqli_error($conn);
    }
} else {
    header("Location: display.php");
}
?>