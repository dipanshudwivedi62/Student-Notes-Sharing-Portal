

<?php
session_start();
require_once "config/database.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$search = trim($_GET["search"] ?? "");

$stmt = $conn->prepare(
    "SELECT notes.id, notes.title, notes.subject,
            notes.description, notes.uploaded_at, users.name
     FROM notes
     JOIN users ON notes.user_id = users.id
     WHERE notes.title LIKE ? OR notes.subject LIKE ?
     ORDER BY notes.uploaded_at DESC"
);

$keyword = "%" . $search . "%";
$stmt->bind_param("ss", $keyword, $keyword);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Notes - NoteShare</title>
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

<main class="search-container">

    <section class="search-heading">
        <span>EXPLORE STUDY MATERIALS</span>
        <h1>Find Your Study Notes</h1>
        <p>Search and download notes shared by students.</p>

        <form method="GET" class="search-form">
            <input
                type="text"
                name="search"
                placeholder="Search by title or subject..."
                value="<?php echo htmlspecialchars($search); ?>"
            >
            <button type="submit">🔍 Search</button>
        </form>
    </section>

    <section class="notes-section">

        <div class="notes-heading">
            <h2>
                <?php echo $search !== "" ? "Search Results" : "Available Notes"; ?>
            </h2>
            <span><?php echo $result->num_rows; ?> Notes Found</span>
        </div>

        <div class="notes-grid">

            <?php if ($result->num_rows > 0): ?>

                <?php while ($note = $result->fetch_assoc()): ?>

                    <div class="note-card">

                        <div class="note-card-icon">📄</div>

                        <span class="subject-badge">
                            <?php echo htmlspecialchars($note["subject"]); ?>
                        </span>

                        <h3><?php echo htmlspecialchars($note["title"]); ?></h3>

                        <p class="note-description">
                            <?php
                            echo htmlspecialchars(
                                $note["description"] ?: "No description available."
                            );
                            ?>
                        </p>

                        <div class="note-meta">
                            <p>👤 <?php echo htmlspecialchars($note["name"]); ?></p>
                            <p>📅 <?php echo date("d M Y", strtotime($note["uploaded_at"])); ?></p>
                        </div>

                        <a href="download.php?id=<?php echo (int)$note["id"]; ?>"
                           class="download-button">
                            Download Notes ↓
                        </a>

                    </div>

                <?php endwhile; ?>

            <?php else: ?>

                <div class="no-notes">
                    <div>📭</div>
                    <h3>No Notes Found</h3>
                    <p>Try searching with another title or subject.</p>
                </div>

            <?php endif; ?>

        </div>

    </section>

    <a href="dashboard.php" class="search-back">
        ← Back to Dashboard
    </a>

</main>

<footer>
    <h3>📚 NoteShare</h3>
    <p>Learn Together. Grow Together.</p>
    <p>© 2026 Student Notes Sharing Portal</p>
</footer>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
