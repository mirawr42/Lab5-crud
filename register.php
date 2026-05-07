<?php
require_once 'config.php';

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: list_users.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $matric = trim($_POST['matric']);
    $name   = trim($_POST['name']);
    $pass   = $_POST['password'];
    $confirm= $_POST['confirm_password'];

    if (empty($matric) || empty($name) || empty($pass)) {
        $error = "All fields are required.";
    } elseif ($pass !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        // Check if matric exists
        $check = $conn->prepare("SELECT id FROM users WHERE matric = ?");
        if ($check === false) {
            $error = "Prepare failed (1): " . $conn->error;
        } else {
            $check->bind_param("s", $matric);
            $check->execute();
            $check->store_result();
            if ($check->num_rows > 0) {
                $error = "Matric number already registered.";
            } else {
                $hashed = password_hash($pass, PASSWORD_DEFAULT);
                $accessLevel = 'User';
                $stmt = $conn->prepare("INSERT INTO users (matric, name, password, accessLevel) VALUES (?, ?, ?, ?)");
                if ($stmt === false) {
                    $error = "Prepare failed (2): " . $conn->error;
                } else {
                    $stmt->bind_param("ssss", $matric, $name, $hashed, $accessLevel);
                    if ($stmt->execute()) {
                        $success = "Registration successful. You can now <a href='login.php'>login</a>.";
                    } else {
                        $error = "Insert failed: " . $stmt->error;
                    }
                    $stmt->close();
                }
            }
            $check->close();
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Registration Form</h2>
        <?php if($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        <form method="post">
            <label>Matric:</label>
            <input type="text" name="matric" required>
            <label>Name:</label>
            <input type="text" name="name" required>
            <label>Password:</label>
            <input type="password" name="password" required>
            <label>Confirm Password:</label>
            <input type="password" name="confirm_password" required>
            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>