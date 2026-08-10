<?php
session_start();
require_once("../Model/messageModel.php");
require_once("../Model/studentModel.php");
require_once("../Model/tutorModel.php");

if (isset($_GET['tutor_id'])) {
    $tutor_id = (int)$_GET['tutor_id'];

    $receiver_id = getTutorStudentId($tutor_id); // REAL student ID
    $_SESSION['receiver_id'] = $receiver_id;
    $_SESSION['receiver_tutor_id'] = $tutor_id;
}


if (!isset($_SESSION['loginId'])) {
    header("Location: loginView.php");
    exit();
}

$sender_id   = $_SESSION['loginId'];
$receiver_id = $_SESSION['receiver_id'] ?? "";
$peers       = getPeers($sender_id);
$err         = $_GET['err'] ?? "";

/* ===== TAB (chats / requests) ===== */
$tab = $_GET['tab'] ?? "chats"; // "chats" or "requests"
$requests = [];
if ($tab === "requests") {
    // this function must exist in messageModel.php
    $requests = getMessageRequests($sender_id);
}

/* my profile */
$meRes = getStudentById($sender_id);
$me = mysqli_fetch_assoc($meRes);
if (!$me) $me = ['s_name' => 'Unknown', 's_propic' => ''];

$mePic = $me['s_propic'] ?? '';
$myAvatar = ($mePic === '') ? "../Resources/default.png" : "../" . $mePic;

/* receiver header */
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
        $chatAvatar = ($rPic === '') ? "../Resources/default.png" : "../" . $rPic;
    }
}

/* block status */
$blockedEither = false;
$iBlocked = false;
if (!empty($receiver_id)) {
    $blockedEither = isBlockedEitherWay($sender_id, $receiver_id);
    $iBlocked = didIBlock($sender_id, $receiver_id);
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

    nav.users { flex: 1; overflow: auto; padding-top: 8px; }

    header.chat-head {
      padding: 8px;
      border-bottom: 1px solid #000;
      display: flex;
      justify-content: space-between;
      gap: 10px;
    }

    .chat-head-left { flex: 1; }

    article.messages {
      flex: 1;
      overflow: auto;
      padding: 8px;
      border-bottom: 1px solid #000;
      box-sizing: border-box;
    }

    .chat-foot { padding: 16px; }

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

    .chat-header-row{ cursor: default; }
    .chat-header-row:hover{ background: #fff; }

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
    .menu-sep { height: 1px; background: #000; margin: 6px 0; }

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

    .bubble {
      max-width: 60%;
      padding: 8px 12px;
      border-radius: 10px;
      border: 1px solid #000;
      background: #e6f3ff;
      white-space: pre-wrap;
      word-wrap: break-word;
    }

    .file-box {
      margin-top: 6px;
      max-width: 60%;
      padding: 8px;
      border: 1px solid #000;
      border-radius: 10px;
      background: #fff;
      box-sizing: border-box;
    }

    .file-box img {
      width: 350px;
      height: 180px;
      object-fit: cover;
      border-radius: 6px;
      cursor: pointer;
      display: block;
    }

    .file-row { display: flex; align-items: center; gap: 8px; }
    .icon { font-size: 24px; }

    /* message dots */
    .msg-row{ display: flex; align-items: flex-start; gap: 6px; max-width: 75%; }
    .message_sender .msg-row{ justify-content: flex-end; }
    .message_receiver .msg-row{ justify-content: flex-start; }

    .msg-menu{ position: relative; }
    .msg-menu > summary { list-style: none; cursor: pointer; }
    .msg-menu > summary::-webkit-details-marker { display:none; }

    .dots{
      border: 1px solid #000;
      border-radius: 8px;
      padding: 2px 8px;
      background: #fff;
      user-select: none;
    }
    .dots:hover{ background:#f2f2f2; }

    .menu-box{
      position: absolute;
      top: calc(100% + 6px);
      right: 0;
      width: 170px;
      background: #fff;
      border: 1px solid #000;
      border-radius: 8px;
      padding: 6px;
      z-index: 50;
      box-sizing: border-box;
    }

    .head-actions{ position: relative; }
    .head-actions .menu-box{ width: 190px; }

    .notice{
      margin-top: 6px;
      border: 1px solid #000;
      padding: 8px;
      border-radius: 8px;
      background: #fff;
    }

    /* ===== FIXED AS YOU ASKED ===== */
    /* Sender dropdown opens on LEFT side of dots */
    .message_sender .msg-menu .menu-box{
      right: 0;
      left: auto;
      transform: none;
      margin-right: 6px;
    }

    /* Receiver dropdown opens on RIGHT side of dots (default) */
    .message_receiver .msg-menu .menu-box{
      right: auto;
      left: 0;
      transform: none;
      margin-right: 0;
    }

    /* small tab label */
    .tab-title{
      font-size: 13px;
      margin: 8px 0 0 0;
      opacity: .8;
    }
  </style>
</head>

<body>
<main>

  <!-- LEFT -->
  <aside>
    <div class="left-top">

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
            <form action="../View/messageView.php" method="GET" style="margin:0;">
              <input type="hidden" name="tab" value="requests">
              <button type="submit" class="menu-item">Message Request</button>
            </form>
          </li>

          <li style="margin-top:6px;">
            <form action="../View/messageView.php" method="GET" style="margin:0;">
              <button type="submit" class="menu-item">Back to Chats</button>
            </form>
          </li>

          <li class="menu-sep"></li>

          <li>
            <button class="menu-item" type="button"
              onclick="window.location.href='../Controller/logout.php'">Logout</button>
          </li>
        </menu>
      </details>

      <form onsubmit="return false;">
        <label>
          Search user
          <input type="search" id="searchuser" placeholder="enter user" onkeyup="liveSearchUsers()">
        </label>
      </form>

      <div id="searchResults"></div>

      <div class="tab-title">
        <?= ($tab === "requests") ? "Showing: Message Requests" : "Showing: Chats"; ?>
      </div>
    </div>

    <!-- USERS LIST -->
    <nav class="users" aria-label="Users list">

      <?php if ($tab === "requests") { ?>

        <?php if (count($requests) === 0) { ?>
          <div class="notice">No new message requests.</div>
        <?php } ?>

        <?php foreach ($requests as $req) {
            $pid = $req['sender_id'];

            $pRes = getStudentById($pid);
            $p = mysqli_fetch_assoc($pRes);
            if (!$p) $p = ['s_name' => 'Unknown', 's_propic' => ''];

            $pPic = $p['s_propic'] ?? '';
            $pAvatar = ($pPic === '') ? "../Resources/default.png" : "../" . $pPic;
        ?>
          <form action="../Controller/messageController.php" method="GET" style="margin:0 0 8px 0;">
            <input type="hidden" name="peer_receiver_id" value="<?= htmlspecialchars($pid) ?>">
            <input type="hidden" name="from_tab" value="requests">
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

      <?php } else { ?>

        <?php foreach ($peers as $peer) {
            $pid = $peer['receiver_id'];

            $pRes = getStudentById($pid);
            $p = mysqli_fetch_assoc($pRes);
            if (!$p) $p = ['s_name' => 'Unknown', 's_propic' => ''];

            $pPic = $p['s_propic'] ?? '';
            $pAvatar = ($pPic === '') ? "../Resources/default.png" : "../" . $pPic;
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

      <?php } ?>

    </nav>
  </aside>

  <!-- RIGHT -->
  <section class="chat" aria-label="Chat panel">
    <header class="chat-head">

      <div class="chat-head-left">
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
      </div>

      <!-- header 3 dots -->
      <?php if (!empty($receiver_id)) { ?>
      <details class="head-actions">
        <summary class="dots" aria-label="Chat actions">⋮</summary>
        <div class="menu-box">

          <form action="../Controller/messageController.php" method="POST" style="margin:0 0 6px 0;">
            <input type="hidden" name="action" value="delete_chat">
            <input type="hidden" name="other_id" value="<?= htmlspecialchars($receiver_id) ?>">
            <button type="submit" class="menu-item">Delete chat (for me)</button>
          </form>

          <div class="menu-sep"></div>

          <form action="../Controller/messageController.php" method="POST" style="margin:6px 0 0 0;">
            <input type="hidden" name="action" value="toggle_block">
            <input type="hidden" name="other_id" value="<?= htmlspecialchars($receiver_id) ?>">
            <button type="submit" class="menu-item">
              <?= $iBlocked ? "Unblock user" : "Block user" ?>
            </button>
          </form>

        </div>
      </details>
      <?php } ?>

    </header>

    <article class="messages" id="messagesContainer" aria-label="Messages"></article>

    <!-- block notice bottom + hide input when blocked -->
    <footer class="chat-foot">

      <?php if (!empty($receiver_id) && $blockedEither) { ?>
        <div class="notice">
          <?php if ($iBlocked) { ?>
            You blocked this user. Unblock to send messages.
          <?php } else { ?>
            You are blocked by this user. You cannot send messages.
          <?php } ?>
        </div>
      <?php } ?>

      <?php if (!empty($receiver_id) && !$blockedEither) { ?>
        <form action="../Controller/messageController.php" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="sender_id" value="<?= htmlspecialchars($_SESSION['loginId']); ?>">
          <input type="hidden" name="receiver_id" value="<?= htmlspecialchars($receiver_id); ?>">

          <label>Message</label><br>
          <textarea name="message" rows="2" cols="100" placeholder="Write message..."></textarea><br>
          <input type="file" name="attachment">
          <button type="submit" name="sendMessage">Send</button>
        </form>
      <?php } ?>

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
