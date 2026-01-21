<?php
session_start();
require_once("../Model/messageModel.php");

if(!isset($_SESSION['loginId'])||!isset($_SESSION['receiver_id'])){
    exit();
}

$loginId=$_SESSION['loginId'];
$receiverId=$_SESSION['receiver_id'];

$messages=getMessages($loginId,$receiverId);

foreach($messages as $msg){

    if($msg['sender_id']==$loginId){
        echo '<div class="message_sender">';
        echo '<p>'.htmlspecialchars($msg['message']).'</p>';
        if(!empty($msg['file'])){
            echo '<p><a href="../Resourses/'.htmlspecialchars($msg['file']).'" target="_blank">View file</a></p>';
        }
        echo '</div>';
    }else{
        echo '<div class="message_receiver">';
        echo '<p>'.htmlspecialchars($msg['message']).'</p>';
        if(!empty($msg['file'])){
            echo '<p><a href="../Resourses/'.htmlspecialchars($msg['file']).'" target="_blank">View file</a></p>';
        }
        echo '</div>';
    }
}
