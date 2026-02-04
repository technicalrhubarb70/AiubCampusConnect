<?php
require_once("dbConnect.php");

/*
status:
0 = request
1 = chat
2 = blocked
*/

function getConnection($a, $b)
{
    $conn = dbConnect();
    $a = mysqli_real_escape_string($conn, $a);
    $b = mysqli_real_escape_string($conn, $b);

    $q = "
      SELECT *
      FROM connections
      WHERE (requester_id='$a' AND receiver_id='$b')
         OR (requester_id='$b' AND receiver_id='$a')
      ORDER BY created_at DESC, peer_id DESC
      LIMIT 1
    ";

    $res = mysqli_query($conn, $q);
    return $res ? mysqli_fetch_assoc($res) : null;
}

/* ===== lists ===== */

function getChatList($me)
{
    $conn = dbConnect();
    $me = mysqli_real_escape_string($conn, $me);

    $q = "
      SELECT *
      FROM connections
      WHERE status = 1
        AND (requester_id='$me' OR receiver_id='$me')
      ORDER BY created_at DESC
    ";

    $res = mysqli_query($conn, $q);
    $rows = [];
    while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
    return $rows;
}

function getRequestList($me)
{
    $conn = dbConnect();
    $me = mysqli_real_escape_string($conn, $me);

    $q = "
      SELECT *
      FROM connections
      WHERE status = 0 AND receiver_id='$me'
      ORDER BY created_at DESC
    ";

    $res = mysqli_query($conn, $q);
    $rows = [];
    while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
    return $rows;
}

function getBlockedList($me)
{
    $conn = dbConnect();
    $me = mysqli_real_escape_string($conn, $me);

    $q = "
      SELECT *
      FROM connections
      WHERE status = 2 AND requester_id='$me'
      ORDER BY created_at DESC
    ";

    $res = mysqli_query($conn, $q);
    $rows = [];
    while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
    return $rows;
}

/* ===== request logic ===== */

function createRequest($sender, $receiver)
{
    $conn = dbConnect();
    $sender = mysqli_real_escape_string($conn, $sender);
    $receiver = mysqli_real_escape_string($conn, $receiver);

    $q = "INSERT INTO connections (requester_id, receiver_id, status, created_at)
          VALUES ('$sender', '$receiver', 0, NOW())";
    return mysqli_query($conn, $q);
}

function acceptRequest($a, $b)
{
    $conn = dbConnect();
    $row = getConnection($a, $b);
    if (!$row) return false;

    $pid = (int)$row['peer_id'];
    return mysqli_query($conn, "UPDATE connections SET status=1 WHERE peer_id=$pid");
}

/* ===== block logic ===== */

function didIBlock($me, $other)
{
    $row = getConnection($me, $other);
    return ($row && (int)$row['status'] === 2 && $row['requester_id'] === $me);
}

function isBlockedEitherWay($a, $b)
{
    $row = getConnection($a, $b);
    return ($row && (int)$row['status'] === 2);
}

function blockUser($me, $other)
{
    $conn = dbConnect();
    $row = getConnection($me, $other);

    if ($row) {
        $pid = (int)$row['peer_id'];
        return mysqli_query($conn,
            "UPDATE connections
             SET requester_id='$me', receiver_id='$other', status=2
             WHERE peer_id=$pid"
        );
    }

    return mysqli_query($conn,
        "INSERT INTO connections (requester_id, receiver_id, status)
         VALUES ('$me','$other',2)"
    );
}

function unblockUser($me, $other)
{
    $conn = dbConnect();
    $row = getConnection($me, $other);
    if (!$row) return false;

    $pid = (int)$row['peer_id'];
    return mysqli_query($conn, "UPDATE connections SET status=1 WHERE peer_id=$pid");
}
