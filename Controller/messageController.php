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

$sender_id  = $_SESSION["loginId"];
$receiver_id = trim($_POST["receiver_id"] ?? "");
$message     = trim($_POST["message"] ?? "");

$error = "";

if ($receiver_id === "") {
    $error = "Receiver ID required";
} elseif (!preg_match("/^[0-9-]+$/", $receiver_id)) {
    $error = "Invalid Receiver ID format";
} elseif ($receiver_id === $sender_id) {
    $error = "You cannot message yourself";
} elseif (!userExists($receiver_id)) {
    $error = "Receiver not found";
}

$fileName = "";
if ($error === "" && isset($_FILES["attachment"]) && $_FILES["attachment"]["error"] !== UPLOAD_ERR_NO_FILE) {
    $file = $_FILES["attachment"];
    if ($file["error"] !== UPLOAD_ERR_OK) {
        $error = "File upload error";
    } else {
        $allowedTypes = ["image/jpeg", "image/png", "application/pdf"];
        if (!in_array($file["type"], $allowedTypes)) {
            $error = "Invalid file type. Only JPG, PNG, and PDF allowed.";
        } else {
            $uploadDir = "../Resourses/";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $fileName = uniqid() . "_" . basename($file["name"]);
            $targetPath = $uploadDir . $fileName;
            if (!move_uploaded_file($file["tmp_name"], $targetPath)) {
                $error = "Failed to move uploaded file.";
            }
        }
    }
}

if ($error !== "") {
    header("Location:../View/messageView.php?err=" . urlencode($error));
    exit();
}

if ($message === "" && $fileName === "") {
    header("Location:../View/messageView.php?err=" . urlencode("Message or file required"));
    exit();
}
$ok = insertMessage($sender_id, $receiver_id, $message, $fileName);

if (!$ok) {
    header("Location:../View/messageView.php?err=" . urlencode("Database error"));
    exit();
}

header("Location:../View/messageView.php?success=" . urlencode("Message sent successfully"));
exit();


?>
