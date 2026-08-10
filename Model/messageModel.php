<?php
require_once("dbConnect.php");

/* ===================== MESSAGES ===================== */

function insertMessage($sender_id, $receiver_id, $message, $file)
{
    $conn = dbConnect();

    $sender_id   = mysqli_real_escape_string($conn, $sender_id);
    $receiver_id = mysqli_real_escape_string($conn, $receiver_id);
    $message     = mysqli_real_escape_string($conn, $message);
    $file        = mysqli_real_escape_string($conn, $file);

    $query = "INSERT INTO messages (sender_id, receiver_id, message, file, sent_at)
              VALUES ('$sender_id', '$receiver_id', '$message', '$file', NOW())";

    return mysqli_query($conn, $query);
}

function getMessages($login_id, $other_id)
{
    $conn = dbConnect();
    $login_id = mysqli_real_escape_string($conn, $login_id);
    $other_id = mysqli_real_escape_string($conn, $other_id);

    // Hide messages deleted for current viewer only
    $query = "
        SELECT *
        FROM messages
        WHERE
          (
            (sender_id = '$login_id' AND receiver_id = '$other_id' AND deleted_by_sender = 0)
            OR
            (sender_id = '$other_id' AND receiver_id = '$login_id' AND deleted_by_receiver = 0)
          )
        AND deleted_for_everyone = 0
        ORDER BY sent_at ASC
    ";

    $data = mysqli_query($conn, $query);
    if (!$data) die('Query failed: ' . mysqli_error($conn));

    $messages = [];
    while ($row = mysqli_fetch_assoc($data)) $messages[] = $row;
    return $messages;
}

function deleteSingleForEveryone($m_id, $me){
    $conn = dbConnect();

    $m_id = mysqli_real_escape_string($conn, $m_id);
    $me   = mysqli_real_escape_string($conn, $me);

    // only delete if logged-in user is the sender
    $sql = "
        UPDATE messages
        SET deleted_for_everyone = 1
        WHERE m_id = '$m_id'
          AND sender_id = '$me'
    ";

    return mysqli_query($conn, $sql);
}

function getMessageRequests($me)
{
    $conn = dbConnect();
    $me = mysqli_real_escape_string($conn, $me);

    /*
      Requesters = people who sent me at least 1 message
      BUT I never sent them any message yet.
      Also respect: deleted_for_everyone + deleted_by_receiver (for me)
      Also exclude if blocked either way.
    */

    $q = "
      SELECT
        m.sender_id,
        MAX(m.sent_at) AS last_time
      FROM messages m
      WHERE
        m.receiver_id = '$me'
        AND m.deleted_for_everyone = 0
        AND m.deleted_by_receiver = 0
        AND m.sender_id NOT IN (
          SELECT DISTINCT receiver_id
          FROM messages
          WHERE sender_id = '$me'
        )
      GROUP BY m.sender_id
      ORDER BY last_time DESC
    ";

    $res = mysqli_query($conn, $q);
    if (!$res) die('Query failed: ' . mysqli_error($conn));

    $out = [];
    while ($row = mysqli_fetch_assoc($res)) {
        // block filter (use your existing functions)
        if (!isBlockedEitherWay($me, $row['sender_id'])) {
            $out[] = $row;
        }
    }

    return $out;
}


function deleteChatForMe($me, $other)
{
    $conn = dbConnect();
    $me    = mysqli_real_escape_string($conn, $me);
    $other = mysqli_real_escape_string($conn, $other);

    // messages I sent
    mysqli_query($conn, "
        UPDATE messages
        SET deleted_by_sender = 1
        WHERE sender_id = '$me' AND receiver_id = '$other'
    ");

    // messages I received
    mysqli_query($conn, "
        UPDATE messages
        SET deleted_by_receiver = 1
        WHERE sender_id = '$other' AND receiver_id = '$me'
    ");

    return true;
}

function deleteSingleMessageForMe($me, $m_id)
{
    $conn = dbConnect();
    $me   = mysqli_real_escape_string($conn, $me);
    $m_id = (int)$m_id;

    $query = "
        UPDATE messages
        SET
          deleted_by_sender   = IF(sender_id = '$me', 1, deleted_by_sender),
          deleted_by_receiver = IF(receiver_id = '$me', 1, deleted_by_receiver)
        WHERE m_id = $m_id
          AND (sender_id = '$me' OR receiver_id = '$me')
    ";

    return mysqli_query($conn, $query);
}

/* ===================== PEERS (your existing logic) ===================== */

function getPeers($sender_id)
{
    $conn = dbConnect();
    $sender_id = mysqli_real_escape_string($conn, $sender_id);

    $query = "SELECT receiver_id
              FROM messages
              WHERE sender_id = '$sender_id'
              GROUP BY receiver_id
              ORDER BY MAX(sent_at) DESC";

    $data = mysqli_query($conn, $query);
    if (!$data) die("Query failed: " . mysqli_error($conn));

    $peers = [];
    while ($row = mysqli_fetch_assoc($data)) $peers[] = $row;
    return $peers;
}

/* ===================== BLOCK USING CONNECTIONS.STATUS ===================== */
/*
status = 1  => normal
status = 2  => blocked

When blocked:
requester_id = blocker
receiver_id  = blocked
*/

function getLatestConnectionRow($a, $b)
{
    $conn = dbConnect();
    $a = mysqli_real_escape_string($conn, $a);
    $b = mysqli_real_escape_string($conn, $b);

    $q = "
      SELECT *
      FROM connections
      WHERE (requester_id = '$a' AND receiver_id = '$b')
         OR (requester_id = '$b' AND receiver_id = '$a')
      ORDER BY created_at DESC, peer_id DESC
      LIMIT 1
    ";

    $res = mysqli_query($conn, $q);
    if (!$res) return null;

    return mysqli_fetch_assoc($res) ?: null;
}

function isBlockedEitherWay($me, $other)
{
    $row = getLatestConnectionRow($me, $other);
    if (!$row) return false;
    return ((int)$row['status'] === 2);
}

function didIBlock($me, $other)
{
    $row = getLatestConnectionRow($me, $other);
    if (!$row) return false;

    return ((int)$row['status'] === 2 && $row['requester_id'] === $me && $row['receiver_id'] === $other);
}

function blockUser($me, $other)
{
    $conn = dbConnect();
    $me    = mysqli_real_escape_string($conn, $me);
    $other = mysqli_real_escape_string($conn, $other);

    $row = getLatestConnectionRow($me, $other);

    if ($row && isset($row['peer_id'])) {
        $pid = (int)$row['peer_id'];

        // overwrite row so requester=blocker, receiver=blocked, status=2
        $q = "
          UPDATE connections
          SET requester_id = '$me', receiver_id = '$other', status = 2, created_at = NOW()
          WHERE peer_id = $pid
        ";
        return mysqli_query($conn, $q);
    }

    // no row exists, insert new
    $q = "INSERT INTO connections (requester_id, receiver_id, status, created_at)
          VALUES ('$me', '$other', 2, NOW())";
    return mysqli_query($conn, $q);
}

function unblockUser($me, $other)
{
    $conn = dbConnect();
    $me    = mysqli_real_escape_string($conn, $me);
    $other = mysqli_real_escape_string($conn, $other);

    // only the blocker can unblock
    $row = getLatestConnectionRow($me, $other);
    if (!$row) return false;

    if ((int)$row['status'] === 2 && $row['requester_id'] === $me && $row['receiver_id'] === $other) {
        $pid = (int)$row['peer_id'];
        $q = "UPDATE connections SET status = 1, created_at = NOW() WHERE peer_id = $pid";
        return mysqli_query($conn, $q);
    }

    return false; // other person blocked you
}
