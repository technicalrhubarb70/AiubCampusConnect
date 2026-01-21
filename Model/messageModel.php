<?php
require_once("dbConnect.php");

function insertMessage( $sender_id, $receiver_id, $message, $file)
{
    $conn = dbConnect();

    // Your table columns: m_id, sender_id, receiver_id, message, file, sent_at
    $query = "INSERT INTO messages (m_id, sender_id, receiver_id, message, file, sent_at)
              VALUES (?, ?, ?, ?, ?, NOW())";

    $stmt = mysqli_prepare($conn, $query);
    if (!$stmt) {
        return false;
    }

    mysqli_stmt_bind_param($stmt, "sssss", $m_id, $sender_id, $receiver_id, $message, $file);

    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    return $ok;
}

/* Validation: check user exists in login table */
function userExists($id)
{
    $conn = dbConnect();

    $sql = "SELECT login_id FROM login WHERE login_id = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) return false;

    mysqli_stmt_bind_param($stmt, "s", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    $exists = mysqli_stmt_num_rows($stmt) > 0;

    mysqli_stmt_close($stmt);
    return $exists;
}
