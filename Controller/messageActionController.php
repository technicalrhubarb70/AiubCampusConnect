<?php
session_start();
require_once("../Model/messageModel.php");

if (!isset($_SESSION['loginId'])) {
    header("Location:../View/loginView.php");
    exit();
}

$me = $_SESSION['loginId'];

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location:../View/messageView.php");
    exit();
}

/* ===== DELETE SINGLE MESSAGE (FOR ME ONLY) ===== */
if (isset($_POST['delete_one']) && isset($_POST['m_id'])) {
    $m_id = $_POST['m_id'];

    deleteSingleMessageForMe($me, $m_id);

    header("Location:../View/messageView.php");
    exit();
}

/* ===== DELETE WHOLE CHAT (FOR ME ONLY) ===== */
if (isset($_POST['delete_chat']) && isset($_POST['other_id'])) {
    $other = $_POST['other_id'];

    deleteConversationForMe($me, $other);

    // If I deleted the currently open chat, close it
    if (isset($_SESSION['receiver_id']) && $_SESSION['receiver_id'] === $other) {
        unset($_SESSION['receiver_id']);
    }

    header("Location:../View/messageView.php");
    exit();
}

header("Location:../View/messageView.php");
exit();
