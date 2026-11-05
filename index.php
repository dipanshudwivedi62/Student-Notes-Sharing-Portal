

<?php
session_start();

if (isset($_SESSION["user_id"])) {
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Notes Sharing Portal</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<nav class="navbar">
    <div class="logo">📚 NoteShare</div>

    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="#features">Features</a>
        <a href="login.php" class="nav-login">Login</a>
        <a href="register.php" class="nav-register">Get Started</a>
    </div>
</nav>

<section class="hero">
    <div class="hero-content">
        <span class="tag">YOUR DIGITAL LEARNING PARTNER</span>

        <h1>Share Knowledge.<br>
            <span>Learn Together.</span>
        </h1>

        <p>
            A simple platform for students to upload, discover,
            share and download study notes anytime, anywhere.
        </p>

        <div class="hero-buttons">
            <a href="register.php" class="primary-btn">Get Started →</a>
            <a href="login.php" class="secondary-btn">Student Login</a>
        </div>

        <div class="hero-stats">
            <div>
                <h3>Easy</h3>
                <p>Note Sharing</p>
            </div>
            <div>
                <h3>Quick</h3>
                <p>Downloads</p>
            </div>
            <div>
                <h3>Free</h3>
                <p>For Students</p>
            </div>
        </div>
    </div>

    <div class="hero-visual">
        <div class="document-card">
            <div class="document-icon">📘</div>
            <h3>Study Materials</h3>
            <p>Organize your learning resources</p>
            <div class="file-item">📄 Computer Science Notes</div>
            <div class="file-item">📄 Database Management</div>
            <div class="file-item">📄 Web Technology</div>
            <span class="success-label">✓ Ready to Learn</span>
        </div>
    </div>
</section>

<section class="features" id="features">
    <div class="section-heading">
        <span>OUR FEATURES</span>
        <h2>Everything You Need to Study Better</h2>
        <p>Simple tools to make learning and sharing easier.</p>
    </div>

    <div class="feature-grid">
        <div class="feature-card">
            <div class="feature-icon">📤</div>
            <h3>Upload Notes</h3>
            <p>Upload your PDF, DOC and PPT study materials easily.</p>
        </div>

        <div class="feature-card">
            <div class="feature-icon">🔍</div>
            <h3>Search Notes</h3>
            <p>Find useful study materials by title or subject.</p>
        </div>

        <div class="feature-card">
            <div class="feature-icon">📥</div>
            <h3>Download Notes</h3>
            <p>Download educational resources shared by students.</p>
        </div>
    </div>
</section>

<footer>
    <h3>📚 NoteShare</h3>
    <p>Learn Together. Grow Together.</p>
    <p>© 2026 Student Notes Sharing Portal. All Rights Reserved.</p>
</footer>

</body>
</html>
