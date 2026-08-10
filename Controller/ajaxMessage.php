<?php
session_start();
require_once("../Model/messageModel.php");

if (!isset($_SESSION['loginId'])) {
    exit();
}

$me = $_SESSION['loginId'];
$other = $_SESSION['receiver_id'] ?? "";

if ($other === "") {
    echo "<p>Select a user to see messages.</p>";
    exit();
}

$messages = getMessages($me, $other);

foreach ($messages as $m) {

    $isSender = ($m['sender_id'] === $me);

    $msgClass = $isSender ? "message_sender" : "message_receiver";
    $text = $m['message'] ?? "";
    $file = $m['file'] ?? "";
    $m_id = $m['m_id'];

    echo "<div class='{$msgClass}'>";

    echo "<div class='msg-row'>";

    // ===== SENDER: bubble THEN dots (dots on RIGHT) =====
    // ===== RECEIVER: dots THEN bubble (dots on LEFT) =====

    if ($isSender) {


         // dots after bubble
       echo "<details class='msg-menu'>";
        echo "<summary class='dots' aria-label='Message actions'>⋮</summary>";
        echo "<div class='menu-box'>";

        /* Delete (for me) */
        echo "<form action='../Controller/messageController.php' method='POST' style='margin:0 0 6px 0;'>";
        echo "  <input type='hidden' name='action' value='delete_single'>";
        echo "  <input type='hidden' name='m_id' value='".htmlspecialchars($m_id)."'>";
        echo "  <button type='submit' class='menu-item'>Delete (for me)</button>";
        echo "</form>";

        echo "<div class='menu-sep'></div>";

        /* Delete (for everyone) - sender only */
        echo "<form action='../Controller/messageController.php' method='POST'
                    onsubmit=\"return confirm('Delete this message for everyone?');\"
                    style='margin:6px 0 0 0;'>";
        echo "  <input type='hidden' name='action' value='delete_single_for_everyone'>";
        echo "  <input type='hidden' name='m_id' value='".htmlspecialchars($m_id)."'>";
        echo "  <button type='submit' class='menu-item'>Delete (for everyone)</button>";
        echo "</form>";

        echo "</div>";
        echo "</details>";


        // bubble first
        if ($text !== "") {
            echo "<div class='bubble'>".htmlspecialchars($text)."</div>";
        }

       

    } else {

        

        // bubble after dots
        if ($text !== "") {
            echo "<div class='bubble'>".htmlspecialchars($text)."</div>";
        }
        // receiver: dots first
        echo "<details class='msg-menu'>";
        echo "<summary class='dots' aria-label='Message actions'>⋮</summary>";
        echo "<div class='menu-box'>";
        echo "<form action='../Controller/messageController.php' method='POST' style='margin:0;'>";
        echo "  <input type='hidden' name='action' value='delete_single'>";
        echo "  <input type='hidden' name='m_id' value='".htmlspecialchars($m_id)."'>";
        echo "  <button type='submit' class='menu-item'>Delete (for me)</button>";
        echo "</form>";
        echo "</div>";
        echo "</details>";

    }

    echo "</div>"; // msg-row

    // ===== FILE (IMAGE/PDF) =====
    if ($file !== "") {
        // If DB stores only filename:
        $filePath = "../Resources/" . $file;

        // If DB stores "Resources/filename":
        // $filePath = "../" . $file;

        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        echo "<div class='file-box'>";

        if (in_array($ext, ["jpg", "jpeg", "png"])) {
            echo "<img src='".htmlspecialchars($filePath)."' alt='attachment'>";
        } else if ($ext === "pdf") {
            echo "<div class='file-row'>";
            echo "<span class='icon'>📄</span>";
            echo "<a href='".htmlspecialchars($filePath)."' target='_blank'>Open PDF</a>";
            echo "</div>";
        } else {
            echo "<a href='".htmlspecialchars($filePath)."' target='_blank'>Download file</a>";
        }

        echo "</div>";
    }

    echo "</div>"; // message_sender/receiver
}
