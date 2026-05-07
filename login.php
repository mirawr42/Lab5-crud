<?php
require_once 'config.php';

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: display.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $matric = trim($_POST['matric']);
    $pass   = $_POST['password'];

    if (!empty($matric) && !empty($pass)) {
        $stmt = $conn->prepare("SELECT id, name, password, accessLevel FROM users WHERE matric = ?");
        $stmt->bind_param("s", $matric);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows === 1) {
            $stmt->bind_result($id, $name, $hashed, $accessLevel);
            $stmt->fetch();
            if (password_verify($pass, $hashed)) {
                $_SESSION['loggedin'] = true;
                $_SESSION['user_id'] = $id;
                $_SESSION['matric'] = $matric;
                $_SESSION['name'] = $name;
                $_SESSION['accessLevel'] = $accessLevel;
                header("Location: display.php");
                exit();
            } else {
                $error = "Invalid Matric or Password.";
            }
        } else {
            $error = "Invalid Matric or Password.";
        }
        $stmt->close();
    } else {
        $error = "Please fill in both fields.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <?php if($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="post">
            <label>Matric:</label>
            <input type="text" name="matric" required>
            <label>Password:</label>
            <input type="password" name="password" required>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</body>
</html>