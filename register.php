

<?php
session_start();
require_once "config/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if ($name == "" || $email == "" || $password == "") {
        $message = "All fields are required!";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare(
            "INSERT INTO users (name, email, password) VALUES (?, ?, ?)"
        );

        $stmt->bind_param("sss", $name, $email, $hashedPassword);

        if ($stmt->execute()) {
            $message = "Registration successful! You can now login.";
        } else {
            $message = "Email already registered or something went wrong.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - NoteShare</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="auth-page">

<nav class="navbar">
    <div class="logo">📚 NoteShare</div>
    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="login.php">Login</a>
    </div>
</nav>

<div class="auth-container">

    <div class="auth-card">
        <div class="auth-icon">📚</div>

        <h1>Create Account</h1>
        <p class="auth-subtitle">Join our student learning community</p>

        <?php if ($message != ""): ?>
            <div class="message">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form method="POST">

            <label>Full Name</label>
            <input type="text" name="name"
                   placeholder="Enter your full name" required>

            <label>Email Address</label>
            <input type="email" name="email"
                   placeholder="Enter your email" required>

            <label>Password</label>
            <input type="password" name="password"
                   placeholder="Create a password" required>

            <button type="submit" class="auth-button">
                Create Account
            </button>

        </form>

        <p class="auth-footer">
            Already have an account?
            <a href="login.php">Login here</a>
        </p>
    </div>

</div>

</body>
</html>
