<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <?php if (isset($_GET['error'])): ?>
            <p class="error">Invalid matric or password!</p>
        <?php endif; ?>
        <form action="login_process.php" method="POST">
            <label>Matric:</label>
            <input type="text" name="matric" required><br>
            <label>Password:</label>
            <input type="password" name="password" required><br>
            <button type="submit">Login</button>
        </form>
        <p>Not registered? <a href="register.php">Register here</a></p>
    </div>
</body>
</html>