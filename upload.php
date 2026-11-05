

<?php
session_start();
require_once "config/database.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = trim($_POST["title"]);
    $subject = trim($_POST["subject"]);
    $description = trim($_POST["description"]);

    if ($title === "" || $subject === "") {
        $message = "Title and subject are required!";
        $messageType = "error";

    } elseif (!isset($_FILES["note_file"]) ||
              $_FILES["note_file"]["error"] !== UPLOAD_ERR_OK) {

        $message = "Please select a valid file!";
        $messageType = "error";

    } else {

        $file = $_FILES["note_file"];
        $extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));

        $allowed = ["pdf", "doc", "docx", "ppt", "pptx"];

        if (!in_array($extension, $allowed)) {
            $message = "Only PDF, DOC, DOCX, PPT and PPTX files allowed!";
            $messageType = "error";

        } else {

            $newFileName = bin2hex(random_bytes(16)) . "." . $extension;
            $uploadPath = __DIR__ . "/uploads/" . $newFileName;

            if (move_uploaded_file($file["tmp_name"], $uploadPath)) {

                $stmt = $conn->prepare(
                    "INSERT INTO notes
                    (user_id, title, subject, description, file_name)
                    VALUES (?, ?, ?, ?, ?)"
                );

                $stmt->bind_param(
                    "issss",
                    $_SESSION["user_id"],
                    $title,
                    $subject,
                    $description,
                    $newFileName
                );

                if ($stmt->execute()) {
                    $message = "Notes uploaded successfully!";
                    $messageType = "success";
                } else {
                    unlink($uploadPath);
                    $message = "Database error. Please try again!";
                    $messageType = "error";
                }

                $stmt->close();

            } else {
                $message = "File upload failed!";
                $messageType = "error";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Notes - NoteShare</title>
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

<div class="upload-container">

    <div class="upload-heading">
        <span>SHARE YOUR KNOWLEDGE</span>
        <h1>Upload Study Notes</h1>
        <p>Share your study materials with other students.</p>
    </div>

    <div class="upload-card">

        <?php if ($message != ""): ?>
            <div class="upload-message <?php echo $messageType; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">

            <label>Notes Title</label>
            <input type="text" name="title"
                   placeholder="Enter notes title" required>

            <label>Subject Name</label>
            <input type="text" name="subject"
                   placeholder="Example: Computer Science" required>

            <label>Description</label>
            <textarea name="description"
                      placeholder="Write a short description..."
                      rows="4"></textarea>

            <label>Select Study Material</label>

            <div class="file-upload-box">
                <div class="file-upload-icon">📁</div>
                <p>Choose your study material</p>
                <span>PDF, DOC, DOCX, PPT, PPTX (Maximum 5 MB)</span>

                <input type="file" name="note_file"
                       accept=".pdf,.doc,.docx,.ppt,.pptx" required>
            </div>

            <button type="submit" class="upload-submit">
                Upload Notes →
            </button>

        </form>

        <a href="dashboard.php" class="back-link">
            ← Back to Dashboard
        </a>

    </div>
</div>

</body>
</html>
