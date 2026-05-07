<?php
require_once 'config.php';
requireLogin();

$users = [];
$result = $conn->query("SELECT id, matric, name, accessLevel FROM users ORDER BY id");
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>User List</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>User List</h2>
        <p>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?></p>
        <a href="logout.php">Logout</a>
        <table border="1">
            <tr><th>Matric</th><th>Name</th><th>Access Level</th></tr>
            <?php foreach($users as $user): ?>
            <tr>
                <td><?php echo htmlspecialchars($user['matric']); ?></td>
                <td><?php echo htmlspecialchars($user['name']); ?></td>
                <td><?php echo htmlspecialchars($user['accessLevel']); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>