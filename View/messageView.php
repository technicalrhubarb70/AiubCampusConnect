<?php
session_start();
require_once("../Model/messageModel.php");
require_once("../Model/studentModel.php");

if (!isset($_SESSION['loginId'])) {
    header("Location: loginView.php");
    exit();
}

$sender_id   = $_SESSION['loginId'];
$receiver_id = $_SESSION['receiver_id'] ?? "";
$peers       = getPeers($sender_id);
$err         = $_GET['err'] ?? "";

/* ===== Logged-in user info for My Profile button ===== */
$meRes = getStudentById($sender_id);
$me = mysqli_fetch_assoc($meRes);
if (!$me) {
    $me = ['s_name' => 'Unknown', 's_propic' => ''];
}
$mePic = $me['s_propic'] ?? '';
if ($mePic === '') {
    $myAvatar = "../Resources/default.png";
} else {
    $myAvatar = "../" . $mePic; // DB stores: Resources/xxx.png
}

/* ===== Receiver info for chat header ===== */
$chatName   = "";
$chatAvatar = "";
if (!empty($receiver_id)) {
    $rRes = getStudentById($receiver_id);
    $r = mysqli_fetch_assoc($rRes);

    if (!$r) {
        $chatName = "Unknown";
        $chatAvatar = "../Resources/default.png";
    } else {
        $chatName = $r['s_name'] ?? "Unknown";
        $rPic = $r['s_propic'] ?? '';

        if ($rPic === '') {
            $chatAvatar = "../Resources/default.png";
        } else {
            $chatAvatar = "../" . $rPic;
        }
    }
}
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

    .chat-foot { padding: 16px; }

    /* ===== profile rows (left list + chat header + my profile) ===== */
    .profile-btn{
      width: 100%;
      border: 1px solid #000;
      background: #fff;
      padding: 8px;
      border-radius: 8px;
      display: flex;
      align-items: center;
      gap: 10px;
      cursor: pointer;
      box-sizing: border-box;
    }
    .profile-btn:hover{ background: #f2f2f2; }
    .avatar{ flex: 0 0 auto; }
    .profile-meta{ line-height: 1.2; text-align: left; }
    .profile-name{ font-size: 14px; }
    .profile-role{ font-size: 12px; opacity: .8; }

    /* header row should NOT look clickable */
    .chat-header-row{ cursor: default; }
    .chat-header-row:hover{ background: #fff; }

    /* ===== dropdown styles for My Profile ===== */
    details.profile { position: relative; }
    details.profile > summary { list-style: none; }
    details.profile > summary::-webkit-details-marker { display: none; }

    .dropdown {
      position: absolute;
      top: calc(100% + 6px);
      left: 0;
      width: 100%;
      background: #fff;
      border: 1px solid #000;
      border-radius: 8px;
      padding: 6px;
      margin: 0;
      box-sizing: border-box;
      z-index: 20;
    }
    .dropdown li { list-style: none; margin: 0; padding: 0; }
    .menu-item {
      width: 100%;
      border: 1px solid #000;
      background: #fff;
      border-radius: 8px;
      padding: 8px;
      cursor: pointer;
      text-align: left;
      box-sizing: border-box;
    }
    .menu-item:hover { background: #f2f2f2; }
    .menu-item.danger { border-color: #000; }

    .menu-sep { height: 1px; background: #000; margin: 6px 0; }

    /* ===== message alignment ===== */
    .message_sender {
      display: flex;
      flex-direction: column;
      align-items: flex-end;
      margin: 8px 0;
    }
    .message_receiver {
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      margin: 8px 0;
    }

    /* bubble */
    .bubble {
      max-width: 60%;
      padding: 8px 12px;
      border-radius: 10px;
      border: 1px solid #000;
      background: #e6f3ff;
      white-space: pre-wrap;
      word-wrap: break-word;
    }

    /* file box */
    .file-box {
      margin-top: 6px;
      max-width: 60%;
      padding: 8px;
      border: 1px solid #000;
      border-radius: 10px;
      background: #fff;
      box-sizing: border-box;
    }

    /* Fixed image preview box */
    .file-box img {
      width: 350px;
      height: 180px;
      object-fit: cover;
      border-radius: 6px;
      cursor: pointer;
      display: block;
    }

    .file-row {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .icon { font-size: 24px; }
  </style>
</head>

<body>
<main>

  <!-- LEFT -->
  <aside>
    <div class="left-top">

      <!-- ✅ My Profile dropdown (like studentHome) -->
      <details class="profile">
        <summary class="profile-btn">
          <img class="avatar"
            src="<?= htmlspecialchars($myAvatar) ?>"
            alt="Profile picture"
            width="36" height="36"
            style="border-radius:50%; object-fit:cover;"
          />

          <span class="profile-meta">
            <strong class="profile-name"><?= htmlspecialchars($me['s_name']) ?></strong><br>
            <small class="profile-role">ID: <?= htmlspecialchars($sender_id) ?></small>
          </span>
        </summary>

        <menu class="dropdown" aria-label="My Profile menu">
          <li>
            <!-- change this link to your request page/controller -->
            <form action="../View/student/studentHome.php" method="GET">
              <button type="submit" class="menu-item">Message Request</button>
            </form>
          </li>

          <li class="menu-sep"></li>

          <li>
            <button class="menu-item danger" type="button"
              onclick="window.location.href='../Controller/logout.php'">Logout</button>
          </li>
        </menu>
      </details>

      <!-- Search -->
      <form onsubmit="return false;">
        <label>
          Search user
          <input type="search" id="searchuser" placeholder="enter user" onkeyup="liveSearchUsers()">
        </label>
      </form>

      <div id="searchResults"></div>
    </div>

    <!-- USERS LIST -->
    <nav class="users" aria-label="Connected users">
      <?php foreach ($peers as $peer) {
          $pid = $peer['receiver_id'];

          $pRes = getStudentById($pid);
          $p = mysqli_fetch_assoc($pRes);

          if (!$p) {
              $p = ['s_name' => 'Unknown', 's_propic' => ''];
          }

          $pPic = $p['s_propic'] ?? '';
          if ($pPic === '') {
              $pAvatar = "../Resources/default.png";
          } else {
              $pAvatar = "../" . $pPic;
          }
      ?>
        <form action="../Controller/messageController.php" method="GET" style="margin:0 0 8px 0;">
          <input type="hidden" name="peer_receiver_id" value="<?= htmlspecialchars($pid) ?>">

          <button type="submit" class="profile-btn">
            <img class="avatar"
              src="<?= htmlspecialchars($pAvatar) ?>"
              alt="Profile picture"
              width="36" height="36"
              style="border-radius:50%; object-fit:cover;"
            />

            <span class="profile-meta">
              <strong class="profile-name"><?= htmlspecialchars($p['s_name']) ?></strong><br>
              <small class="profile-role">ID: <?= htmlspecialchars($pid) ?></small>
            </span>
          </button>
        </form>
      <?php } ?>
    </nav>
  </aside>

  <!-- RIGHT -->
  <section class="chat" aria-label="Chat panel">
    <header class="chat-head">

      <?php if (!empty($receiver_id)) { ?>
        <div class="profile-btn chat-header-row">
          <img class="avatar"
            src="<?= htmlspecialchars($chatAvatar) ?>"
            alt="Profile picture"
            width="36" height="36"
            style="border-radius:50%; object-fit:cover;"
          />
          <span class="profile-meta">
            <strong class="profile-name"><?= htmlspecialchars($chatName) ?></strong><br>
            <small class="profile-role">ID: <?= htmlspecialchars($receiver_id) ?></small>
          </span>
        </div>
      <?php } else { ?>
        <h1>Select a user</h1>
      <?php } ?>

      <?php if ($err !== "") { ?>
        <p style="color:red; margin:6px 0 0 0;"><?= htmlspecialchars($err) ?></p>
      <?php } ?>

    </header>

    <article class="messages" id="messagesContainer" aria-label="Messages">
      <!-- ajax loads messages -->
    </article>

    <footer class="chat-foot">
      <form action="../Controller/messageController.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="sender_id" value="<?= htmlspecialchars($_SESSION['loginId']); ?>">
        <input type="hidden" name="receiver_id" value="<?= htmlspecialchars($receiver_id); ?>">

        <label>Message</label><br>
        <textarea name="message" rows="2" cols="100" placeholder="Write message..."></textarea><br>
        <input type="file" name="attachment">
        <button type="submit" name="sendMessage">Send</button>
      </form>
    </footer>
  </section>

</main>

<script>
let lastHTML = "";

function isAtBottom(el) {
  return (el.scrollTop + el.clientHeight) >= (el.scrollHeight - 10);
}

function loadMessages(){
  const container = document.getElementById("messagesContainer");
  if (!container) return;

  const wasAtBottom = isAtBottom(container);

  const xhr = new XMLHttpRequest();
  xhr.open("GET", "../Controller/ajaxMessage.php?t=" + Date.now(), true);

  xhr.onreadystatechange = function(){
    if(xhr.readyState === 4 && xhr.status === 200){
      const newHTML = xhr.responseText;

      if (newHTML !== lastHTML) {
        const oldScrollTop = container.scrollTop;

        container.innerHTML = newHTML;
        lastHTML = newHTML;

        if (wasAtBottom) {
          container.scrollTop = container.scrollHeight;
        } else {
          container.scrollTop = oldScrollTop;
        }
      }
    }
  };

  xhr.send();
}

loadMessages();
setInterval(loadMessages, 1500);
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
