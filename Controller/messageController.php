<?php
session_start();
require_once("../Model/messageModel.php");

if (!isset($_SESSION['loginId'])) {
    header("Location:../View/loginView.php");
    exit();
}

$me = $_SESSION["loginId"];

/* ===== select peer ===== */
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["peer_receiver_id"])) {
    $_SESSION['receiver_id'] = $_GET["peer_receiver_id"];

    $fromTab = $_GET["from_tab"] ?? "";
    if ($fromTab === "requests") {
        header("Location:../View/messageView.php?tab=requests");
    } else {
        header("Location:../View/messageView.php");
    }
    exit();
}
/* ===== delete single message for everyone ===== */
if (isset($_POST['action']) && $_POST['action'] === 'delete_single_for_everyone') {

    if (!isset($_SESSION['loginId'])) {
        header("Location: ../View/loginView.php");
        exit();
    }

    $me  = $_SESSION['loginId'];
    $m_id = $_POST['m_id'] ?? '';

    if ($m_id !== '') {
        deleteSingleForEveryone($m_id, $me);
    }

    header("Location: ../View/messageView.php");
    exit();
}


/* ===== actions (delete / block) ===== */
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"])) {

    $action = $_POST["action"];

    // delete a single message for me
    if ($action === "delete_single") {
        $m_id = $_POST["m_id"] ?? "";
        if ($m_id !== "") deleteSingleMessageForMe($me, $m_id);
        header("Location:../View/messageView.php");
        exit();
    }

    // delete whole chat for me
    if ($action === "delete_chat") {
        $other = $_POST["other_id"] ?? "";
        if ($other !== "") deleteChatForMe($me, $other);
        header("Location:../View/messageView.php");
        exit();
    }

    // block/unblock
    if ($action === "toggle_block") {
        $other = $_POST["other_id"] ?? "";
        if ($other !== "") {
            if (didIBlock($me, $other)) {
                unblockUser($me, $other);
            } else {
                blockUser($me, $other);
            }
        }
        header("Location:../View/messageView.php");
        exit();
    }

    header("Location:../View/messageView.php");
    exit();
}

/* ===== send message ===== */
if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST["sendMessage"])) {
    header("Location:../View/messageView.php");
    exit();
}

$error = "";
$sender_id   = $me;
$receiver_id = trim($_POST["receiver_id"] ?? "");
$message     = trim($_POST["message"] ?? "");

if ($receiver_id === "") {
    header("Location:../View/messageView.php?err=" . "Select a user first");
    exit();
}

/* block check */
if (isBlockedEitherWay($sender_id, $receiver_id)) {
    header("Location:../View/messageView.php?err=" . "You cannot message this user (blocked).");
    exit();
}

/* upload */
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
            $uploadDir = "../Resources/";
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

            $fileName = uniqid() . "_" . basename($file["name"]);
            $relativePath = "Resources/" . $fileName;
            $targetPath   = "../" . $relativePath;

            if (!move_uploaded_file($file["tmp_name"], $targetPath)) {
                $error = "Failed to move uploaded file.";
                $fileName = "";
            }
        }
    }
}

if ($error !== "") {
    header("Location:../View/messageView.php?err=" . $error);
    exit();
}

if ($message === "" && $fileName === "") {
    header("Location:../View/messageView.php");
    exit();
}

$ok = insertMessage($sender_id, $receiver_id, $message, $fileName);

if (!$ok) {
    header("Location:../View/messageView.php?err=" . "Database error");
    exit();
}

header("Location:../View/messageView.php");
exit();
