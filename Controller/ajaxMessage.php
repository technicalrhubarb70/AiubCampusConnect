<?php
session_start();
require_once("../Model/messageModel.php");

if (!isset($_SESSION['loginId']) || !isset($_SESSION['receiver_id'])) {
    exit();
}

$loginId    = $_SESSION['loginId'];
$receiverId = $_SESSION['receiver_id'];

$messages = getMessages($loginId, $receiverId);

foreach ($messages as $msg) {

    $isSender = ($msg['sender_id'] == $loginId);
    $alignClass = $isSender ? "message_sender" : "message_receiver";

    echo '<div class="'.$alignClass.'">';

    /* ===== Message text ===== */
    if (!empty($msg['message'])) {
        echo '<div class="bubble">'.htmlspecialchars($msg['message']).'</div>';
    }

    /* ===== File preview ===== */
    if (!empty($msg['file'])) {

        $safeFile = htmlspecialchars($msg['file']);
        $filePath = "../Resources/" . $safeFile;
        $ext = strtolower(pathinfo($msg['file'], PATHINFO_EXTENSION));

        echo '<div class="file-box">';

        /* IMAGE PREVIEW (fixed size) */
        if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
            echo '
              <a href="'.$filePath.'" target="_blank">
                <img src="'.$filePath.'" alt="image preview">
              </a>
            ';
        }

        /* PDF PREVIEW */
        else if ($ext === 'pdf') {
            echo '
              <div class="file-row">
                <span class="icon">📄</span>
                <a href="'.$filePath.'" target="_blank">Open PDF</a>
              </div>
            ';
        }

        /* OTHER FILES */
        else {
            echo '
              <div class="file-row">
                <span class="icon">📎</span>
                <a href="'.$filePath.'" download>Download file</a>
              </div>
            ';
        }

        echo '</div>';
    }

    echo '</div>';
}
?>
