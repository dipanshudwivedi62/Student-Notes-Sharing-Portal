

<?php
session_start();
require_once "config/database.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$userName = $_SESSION["user_name"];
$userId = $_SESSION["user_id"];

// Total uploaded notes
$totalResult = $conn->query("SELECT COUNT(*) AS total FROM notes");
$totalNotes = $totalResult->fetch_assoc()["total"];

// Notes uploaded by current student
$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM notes WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$myNotes = $stmt->get_result()->fetch_assoc()["total"];
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - NoteShare</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="dashboard-page">

<nav class="navbar">
    <div class="logo">📚 NoteShare</div>

    <div class="nav-links">
        <a href="dashboard.php">Dashboard</a>
        <a href="upload.php">Upload</a>
        <a href="search.php">Search</a>
        <a href="logout.php" class="nav-register">Logout</a>
    </div>
</nav>

<main class="dashboard-container">

    <section class="dashboard-welcome">
        <div>
            <span class="dashboard-tag">STUDENT DASHBOARD</span>

            <h1>Hello, <?php echo htmlspecialchars($userName); ?>! 👋</h1>

            <p>
                Welcome to NoteShare. Manage your study materials
                and explore notes shared by other students.
            </p>
        </div>

        <div class="welcome-icon">📚</div>
    </section>

    <section class="dashboard-stats">

        <div class="stat-card">
            <div class="stat-icon">📚</div>
            <h2><?php echo $totalNotes; ?></h2>
            <p>Total Available Notes</p>
        </div>

        <div class="stat-card">
            <div class="stat-icon">📤</div>
            <h2><?php echo $myNotes; ?></h2>
            <p>My Uploaded Notes</p>
        </div>

        <div class="stat-card">
            <div class="stat-icon">👤</div>
            <h2>Active</h2>
            <p>Account Status</p>
        </div>

    </section>

    <section class="dashboard-actions">

        <div class="dashboard-heading">
            <h2>Quick Actions</h2>
            <p>Choose what you want to do today.</p>
        </div>

        <div class="action-grid">

            <div class="action-card">
                <div class="action-icon">📤</div>
                <h3>Upload Notes</h3>
                <p>Share your PDF, DOC and PPT study materials.</p>
                <a href="upload.php" class="action-button">
                    Upload Now →
                </a>
            </div>

            <div class="action-card">
                <div class="action-icon">🔍</div>
                <h3>Search Notes</h3>
                <p>Find study materials by title or subject.</p>
                <a href="search.php" class="action-button">
                    Search Now →
                </a>
            </div>

            <div class="action-card">
                <div class="action-icon">🏠</div>
                <h3>Home Page</h3>
                <p>Visit the main Student Notes Sharing Portal.</p>
                <a href="index.php" class="action-button">
                    Visit Home →
                </a>
            </div>

        </div>

    </section>

</main>

<footer>
    <h3>📚 NoteShare</h3>
    <p>Learn Together. Grow Together.</p>
    <p>© 2026 Student Notes Sharing Portal</p>
</footer>

</body>
</html>
