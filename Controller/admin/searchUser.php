<?php
require_once("../../Model/adminModel.php");


if(!isset($_GET['key'])){
  echo "NO_USER";
  exit();
}

$key = $_GET['key'];
$result = searchUser($key);

if(!$result){
  echo "ERR";
  exit();
}

if(mysqli_num_rows($result) == 0){
  echo "NO_USER";
  exit();
}

while($row = mysqli_fetch_assoc($result)){
  $id = $row['s_id'];
  $name = $row['s_name'];
  $email = $row['s_email'];
  $role = $row['role'];
  $status = $row['status'];

  echo "<div class='item' onclick=\"pickUser('$id')\">
          <b>$id</b> - $name<br>
          <span class='muted'>$email | Role: $role | Status: $status</span>
        </div>";
}
