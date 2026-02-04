<?php

session_start();
require_once("../Model/messageModel.php");

if (!isset($_SESSION['loginId'])) {
    header("Location:../View/loginView.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["peer_receiver_id"])) {
    $_SESSION['receiver_id'] = $_GET["peer_receiver_id"];
    header("Location:../View/messageView.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST["sendMessage"])) {
    header("Location:../View/messageView.php");
    exit();
}

$error = "";
$sender_id   = $_SESSION["loginId"];
$receiver_id = trim($_POST["receiver_id"] ?? "");
$message     = trim($_POST["message"] ?? "");

if ($receiver_id === "") {
    header("Location:../View/messageView.php?err=" . "Select a user first");
    exit();
}

$fileName = "";
if (isset($_FILES["attachment"]) && $_FILES["attachment"]["error"] !== UPLOAD_ERR_NO_FILE) {
    $file = $_FILES["attachment"];

    if ($file["error"] !== UPLOAD_ERR_OK) {
        $error = "File upload error";
    } else {
        $allowedTypes = ["image/jpeg", "image/png", "application/pdf"];
        if (!in_array($file["type"], $allowedTypes)) {
            $error = "Invalid file type. Only JPG, PNG, and PDF allowed.";
        } else {
            // FIX: correct folder name Resources
            $uploadDir = "../Resources/";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $fileName = uniqid() . "_" . basename($file["name"]);

            // store relative (DB-friendly) and move using real path
            $relativePath = "Resources/" . $fileName;
            $targetPath   = "../" . $relativePath;

            if (!move_uploaded_file($file["tmp_name"], $targetPath)) {
                $error = "Failed to move uploaded file.";
                $fileName = "";
            } else {
                // If your DB stores only filename, keep $fileName as is.
                // If you want DB to store full relative path, use:
                // $fileName = $relativePath;
            }
        }
    }
}

if ($error !== "") {
    header("Location:../View/messageView.php?err=" . $error);
    exit();
}

if ($message === "" && $fileName === "") {
    header("Location:../View/messageView.php?");
    exit();
}

$ok = insertMessage($sender_id, $receiver_id, $message, $fileName);

if (!$ok) {
    header("Location:../View/messageView.php?err=" . "Database error");
    exit();
}

header("Location:../View/messageView.php?");
exit();
?>
