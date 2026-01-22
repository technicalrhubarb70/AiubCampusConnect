<?php
require_once("dbConnect.php");

function insertMessage( $sender_id, $receiver_id, $message, $file)
{
    $conn = dbConnect();
    $query = "INSERT INTO messages (sender_id, receiver_id, message, file, sent_at) VALUES ('$sender_id', '$receiver_id', '$message', '$file', NOW())";

    return mysqli_query($conn, $query);
}


function getMessages($login_id, $other_id){
    $conn = dbConnect();
    $query = "SELECT * FROM messages WHERE (sender_id = '$login_id' AND receiver_id = '$other_id') OR (sender_id = '$other_id' AND receiver_id = '$login_id') ORDER BY sent_at ASC ";
    $data = mysqli_query($conn, $query);
    if (!$data) {
        die('Query failed: ' . mysqli_error($conn));
    }

    $messages = [];
    while ($row = mysqli_fetch_assoc($data)) {
        $messages[] = $row;
    }
    return $messages;
}

function deleteStudentMessages($s_id)
{
    $conn=dbConnect();
    $query="DELETE FROM messages  WHERE receiver_id='$s_id' OR sender_id='$s_id'";
    mysqli_query($conn,$query);
    return true;
}


function getPeers($sender_id)
{
    $conn = dbConnect();
    $sender_id = mysqli_real_escape_string($conn, $sender_id);

    $query = "SELECT receiver_id  FROM messages  WHERE sender_id = '$sender_id'  GROUP BY receiver_id  ORDER BY MAX(sent_at) DESC ";
    $data = mysqli_query($conn, $query);
    if (!$data) {
        die("Query failed: " . mysqli_error($conn));
    }

    $peers = [];
    while ($row = mysqli_fetch_assoc($data)) {
        $peers[] = $row; 
    }
    return $peers;
}

function userExists($userId)
{
    $conn = dbConnect();
    $query = "SELECT COUNT(*) AS count FROM student WHERE s_id = '$userId'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['count'] > 0;
}



?>
