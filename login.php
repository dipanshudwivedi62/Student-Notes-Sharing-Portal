

<?php
session_start();
require_once "config/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $stmt = $conn->prepare(
        "SELECT id, name, password FROM users WHERE email = ?"
    );

    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

        $user = $result->fetch_assoc();

        if (password_verify($password, $user["password"])) {

            session_regenerate_id(true);

            $_SESSION["user_id"] = $user["id"];
            $_SESSION["user_name"] = $user["name"];

            header("Location: dashboard.php");
            exit;

        } else {
            $message = "Invalid email or password!";
        }

    } else {
        $message = "Invalid email or password!";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - NoteShare</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="auth-page">

<nav class="navbar">
    <div class="logo">📚 NoteShare</div>

    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="register.php">Register</a>
    </div>
</nav>

<div class="auth-container">

    <div class="auth-card">

        <div class="auth-icon">🔐</div>

        <h1>Welcome Back!</h1>

        <p class="auth-subtitle">
            Login to access your study materials
        </p>

        <?php if ($message != ""): ?>
            <div class="message">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form method="POST">

            <label>Email Address</label>

            <input
                type="email"
                name="email"
                placeholder="Enter your email"
                required
            >

            <label>Password</label>

            <input
                type="password"
                name="password"
                placeholder="Enter your password"
                required
            >

            <button type="submit" class="auth-button">
                Login to Account →
            </button>

        </form>

        <p class="auth-footer">
            Don't have an account?
            <a href="register.php">Create Account</a>
        </p>

        <p class="auth-footer">
            <a href="index.php">← Back to Home</a>
        </p>

    </div>

</div>

</body>
</html>
