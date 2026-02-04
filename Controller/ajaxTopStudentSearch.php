<?php
session_start();
require_once("../Model/studentModel.php");

if (!isset($_SESSION['loginId'])) {
    exit();
}

$me = $_SESSION['loginId'];
$q  = trim($_GET['q'] ?? "");

if ($q === "") {
    echo "";
    exit();
}

$res = topSearchStudentsByCourseSkillName($me, $q);

if (!$res || mysqli_num_rows($res) === 0) {
    echo "<small>No students found.</small>";
    exit();
}

while ($m = mysqli_fetch_assoc($res)) {

    $sid   = $m['s_id'] ?? '';
    $name  = $m['s_name'] ?? 'Unknown';
    $pic   = $m['s_propic'] ?? '';
    $label = $m['match_label'] ?? '';

   if ($pic === '') {
    $mAvatar = "../../Resources/default.png";
    } else {
        $mAvatar = "../../" . $pic;
    }


    ?>
    <div class="profile-btn" style="margin:8px 0; cursor:default;">
        <img
            class="avatar"
            src="<?= htmlspecialchars($mAvatar) ?>"
            alt="Profile picture"
            width="36"
            height="36"
            style="border-radius:50%; object-fit:cover;"
        />

        <span class="profile-meta">
            <strong class="profile-name"><?= htmlspecialchars($name) ?></strong>
            <small class="profile-role">
                ID: <?= htmlspecialchars($sid) ?><br>
                <?= htmlspecialchars($label) ?>
            </small>
        </span>

        <!-- ✅ same style as your skill/course match: go to chat -->
        <form action="../Controller/messageController.php" method="GET" style="margin-left:auto;">
            <input type="hidden" name="peer_receiver_id" value="<?= htmlspecialchars($sid) ?>">
            <button type="submit" class="menu-item">Send Message</button>
        </form>
    </div>
    <?php
}
