<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Registration</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Register New User</h2>
        <form action="register_process.php" method="POST">
            <label>Matric:</label>
            <input type="text" name="matric" required><br>

            <label>Name:</label>
            <input type="text" name="name" required><br>

            <label>Access Level:</label>
            <select name="accessLevel">
                <option value="lecturer">Lecturer</option>
                <option value="student">Student</option>
            </select><br>

            <label>Password:</label>
            <input type="password" name="password" required><br>

            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>