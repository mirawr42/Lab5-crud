<?php
// update_user.php
require_once 'config.php';
requireLogin();

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    header("Location: list_users.php");
    exit();
}

// Fetch current user data
$stmt = $conn->prepare("SELECT id, matric, name, accessLevel FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    header("Location: list_users.php");
    exit();
}
$user = $result->fetch_assoc();
$stmt->close();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $matric = trim($_POST['matric']);
    $name   = trim($_POST['name']);
    $access = trim($_POST['accessLevel']);
    $new_pass = $_POST['password'];

    // Validation
    if (empty($matric) || empty($name) || empty($access)) {
        $error = "Matric, Name and Access Level are required.";
    } else {
        // Check unique matric (ignore current user)
        $check = $conn->prepare("SELECT id FROM users WHERE matric = ? AND id != ?");
        $check->bind_param("si", $matric, $id);
        $check->execute();
        $check->store_result();
        if ($check->num_rows > 0) {
            $error = "Matric number already used by another user.";
        } else {
            // Update query
            if (!empty($new_pass)) {
                $hashed = password_hash($new_pass, PASSWORD_DEFAULT);
                $sql = "UPDATE users SET matric=?, name=?, password=?, accessLevel=? WHERE id=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssi", $matric, $name, $hashed, $access, $id);
            } else {
                $sql = "UPDATE users SET matric=?, name=?, accessLevel=? WHERE id=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssi", $matric, $name, $access, $id);
            }

            if ($stmt->execute()) {
                $success = "User updated successfully.";
                // Refresh displayed data
                $user['matric'] = $matric;
                $user['name'] = $name;
                $user['accessLevel'] = $access;
                // If the logged-in user updates his own data, refresh session
                if ($_SESSION['user_id'] == $id) {
                    $_SESSION['name'] = $name;
                    $_SESSION['accessLevel'] = $access;
                }
            } else {
                $error = "Update failed.";
            }
            $stmt->close();
        }
        $check->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Update User</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Update User</h2>
        <?php if($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="post">
            <label>Matric:</label>
            <input type="text" name="matric" value="<?php echo htmlspecialchars($user['matric']); ?>" required>

            <label>Name:</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>

            <label>Access Level:</label>
            <select name="accessLevel">
                <option value="User" <?php echo ($user['accessLevel'] == 'User') ? 'selected' : ''; ?>>User</option>
                <option value="Admin" <?php echo ($user['accessLevel'] == 'Admin') ? 'selected' : ''; ?>>Admin</option>
            </select>

            <label>New Password (leave empty to keep current):</label>
            <input type="password" name="password">

            <button type="submit">Update User</button>
            <a href="list_users.php" class="btn-cancel">Cancel</a>
        </form>
    </div>
</body>
</html>