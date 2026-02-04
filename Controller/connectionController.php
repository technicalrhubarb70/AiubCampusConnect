<?php
session_start();
require_once("../Model/connectionModel.php");

if (!isset($_SESSION['loginId'])) {
    header("Location:../View/loginView.php");
    exit();
}

$me = $_SESSION['loginId'];
$action = $_GET['action'] ?? "";
$other  = $_GET['other'] ?? "";

if ($action === "" || $other === "") {
    header("Location:../View/messageView.php");
    exit();
}

if ($action === "open_request_chat") {
    $_SESSION['receiver_id'] = $other;
    header("Location:../View/messageView.php?tab=requests");
    exit();
}

if ($action === "accept") {
    acceptRequest($other, $me);
    $_SESSION['receiver_id'] = $other;
    header("Location:../View/messageView.php");
    exit();
}

if ($action === "decline") {
    declineRequest($other, $me);
    header("Location:../View/messageView.php?tab=requests");
    exit();
}

if ($action === "block") {
    blockUser($me, $other);
    header("Location:../View/messageView.php");
    exit();
}

if ($action === "unblock") {
    unblockUser($me, $other);
    header("Location:../View/messageView.php");
    exit();
}

header("Location:../View/messageView.php");
exit();
