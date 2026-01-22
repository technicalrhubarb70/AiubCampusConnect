<?php
session_start();
require_once("../Model/messageModel.php");

if (!isset($_SESSION['loginId'])) {
    header("Location: loginView.php");
    exit();
}

$sender_id   = $_SESSION['loginId'];
$receiver_id = $_SESSION['receiver_id'] ?? "";
$peers = getPeers($sender_id);

$messages = [];
if (!empty($receiver_id)) {
    $messages = getMessages($sender_id,$receiver_id);
}



$err = $_GET['err'] ?? "";
$success = $_GET['success'] ?? "";
?>


<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Chat</title>
  <style>
    html, body { height: 100%; margin: 0; }
    body { font-family: Arial, sans-serif; }
    main { height: 100%; display: flex; }
    aside {
      width: 25%;
      border-right: 1px solid #000;
      display: flex;
      flex-direction: column;
      padding: 8px;
      box-sizing: border-box;
    }
    section.chat {
      width: 75%;
      display: flex;
      flex-direction: column;
    }
    .left-top { border-bottom: 1px solid #000; padding-bottom: 8px; }
    .left-top > * { display: block; margin-bottom: 8px; }
    nav.users {
      flex: 1;
      overflow: auto;
      padding-top: 8px;
    }
    header.chat-head {
      padding: 8px;
      border-bottom: 1px solid #000;
    }
    article.messages {
      flex: 1;
      overflow: auto;
      padding: 8px;
      border-bottom: 1px solid #000;
      box-sizing: border-box;
    }
    .chat-foot {
         padding: 30px;
     }


    .message_receiver {
    text-align: left;
    }

    .message_sender {
    margin-left: auto;     
    text-align: right;
    }

    
  </style>
</head>

<body>
  <main>

    <!-- LEFT -->
    <aside>
      <div class="left-top">
        <button type="button">My Profile</button>

        <form onsubmit="return false;">
        <label>
            Search user
            <input type="search" id="searchuser" placeholder="enter user" onkeyup="liveSearchUsers()">
        </label>
        </form>
        <div id="searchResults"></div>


      </div>

      <nav class="users" aria-label="Connected users">
        <?php foreach ($peers as $peer){ ?>
          <form action="../Controller/messageController.php" method="GET">
        <input type="hidden" name="peer_receiver_id" value="<?= htmlspecialchars($peer['receiver_id']) ?>">
        <button type="submit"><?= htmlspecialchars($peer['receiver_id']) ?></button>
        </form>
   
        <?php
        }
        ?>
        
     </nav>
    </aside>

    <!-- RIGHT -->
    <section class="chat" aria-label="Chat panel">
      <header class="chat-head">
        <h1><?= $receiver_id ? "Chat with: " . htmlspecialchars($receiver_id) : "Select a user" ?></h1>
      </header>

      <article class="messages" id="messagesContainer" aria-label="Messages">
        
      </article>

      <footer class="chat-foot">

            <form action="../Controller/messageController.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="sender_id" value="<?php echo htmlspecialchars($_SESSION['loginId']); ?>">
                <input type="hidden" name="receiver_id" value="<?php echo htmlspecialchars($receiver_id); ?>">
                <label>Message</label>
                <textarea name="message" rows="2" cols="100" placeholder="Write message..."></textarea>
                <input type="file" name="attachment">
                <button type="submit" name="sendMessage">Send</button>
            </form>

      </footer>
    </section>
  </main>
  <script>
function loadMessages(){
    let xhr=new XMLHttpRequest();
    let container=document.getElementById("messagesContainer");
    xhr.open("GET","../Controller/ajaxMessage.php",true);
    xhr.onreadystatechange=function(){
        if(xhr.readyState===4&&xhr.status===200){
            container.innerHTML=xhr.responseText;
            container.scrollTop=container.scrollHeight;
        }
    };
    xhr.send();
}
loadMessages();
setInterval(loadMessages,3000);
</script>
<script>
function liveSearchUsers(){
    let q=document.getElementById("searchuser").value;
    let box=document.getElementById("searchResults");

    if(q.length===0){
        box.innerHTML="";
        return;
    }

    let xhr=new XMLHttpRequest();
    xhr.open("GET","../Controller/ajaxSearchUser.php?q="+encodeURIComponent(q),true);

    xhr.onreadystatechange=function(){
        if(xhr.readyState===4&&xhr.status===200){
            box.innerHTML=xhr.responseText;
        }
    };

    xhr.send();
}
</script>


</body>
</html>
