<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include 'config.php';

if (!isset($_GET['matric'])) {
    header("Location: display.php");
    exit();
}

$matric = mysqli_real_escape_string($conn, $_GET['matric']);
$sql = "SELECT * FROM users WHERE matric='$matric'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    echo "User not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update User</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Update User</h2>
        <form action="update_process.php" method="POST">
            <label>Matric (read only):</label>
            <input type="text" name="matric" value="<?php echo htmlspecialchars($user['matric']); ?>" readonly><br>

            <label>Name:</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required><br>

            <label>Access Level:</label>
            <select name="accessLevel">
                <option value="lecturer" <?php if($user['accessLevel'] == 'lecturer') echo 'selected'; ?>>Lecturer</option>
                <option value="student" <?php if($user['accessLevel'] == 'student') echo 'selected'; ?>>Student</option>
            </select><br>

            <label>New Password (leave blank to keep current):</label>
            <input type="password" name="new_password"><br>

            <button type="submit">Update</button>
        </form>
        <p><a href="display.php">Back to User List</a></p>
    </div>
</body>
</html>