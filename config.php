<?php
session_start();

$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'lab5_bit21503';   // <-- change to your actual database name

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function requireLogin() {
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header("Location: login.php");
        exit();
    }
}
?>