
<?php
session_start();
require_once "config/database.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET["id"]) || !ctype_digit($_GET["id"])) {
    die("Invalid note ID!");
}

$note_id = (int) $_GET["id"];

$stmt = $conn->prepare("SELECT file_name, title FROM notes WHERE id = ?");
$stmt->bind_param("i", $note_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("Notes not found!");
}

$note = $result->fetch_assoc();
$stmt->close();

$filePath = __DIR__ . "/uploads/" . basename($note["file_name"]);

if (!is_file($filePath)) {
    die("File not found on server!");
}

$extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

$mimeTypes = [
    "pdf" => "application/pdf",
    "doc" => "application/msword",
    "docx" => "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
    "ppt" => "application/vnd.ms-powerpoint",
    "pptx" => "application/vnd.openxmlformats-officedocument.presentationml.presentation"
];

if (!isset($mimeTypes[$extension])) {
    die("Invalid file type!");
}

header("Content-Type: " . $mimeTypes[$extension]);
header("Content-Disposition: attachment; filename=\"notes." . $extension . "\"");
header("Content-Length: " . filesize($filePath));

readfile($filePath);
exit;
?>
