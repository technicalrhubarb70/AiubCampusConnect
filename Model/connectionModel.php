<?php
require_once("dbConnect.php");

function deleteConnectionsByStudentId($id)
{
    $conn = dbConnect();

    // Delete where student is sender OR receiver
    $query = "DELETE FROM connections 
              WHERE receiver_id='$id' 
                 OR sender_id='$id'";

    return mysqli_query($conn, $query);
}
?>
