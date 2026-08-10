<?php
session_start();

require_once("../Model/studentModel.php");
require_once("../Model/tutorModel.php");

if (!isset($_SESSION['loginId'])) {
    exit();
}

$q = $_GET['q'] ?? '';
$q = trim($q);

if ($q === '') {
    echo "";
    exit();
}

/*
  IMPORTANT:
  Whatever function you use here must return tutor rows.
  Each row should at least contain:
    - s_id (student id of tutor)
    - course
    - help_type (optional)
    - s_name / s_propic (optional)
*/

$tutors = searchTutorByCourse($q); // <-- keep your existing function name if different

if (empty($tutors)) {
    echo "<small>No tutors found.</small>";
    exit();
}

foreach ($tutors as $t) {

    // ✅ Use student id to message (main fix)
    $sid = $t['s_id'] ?? ($t['student_id'] ?? '');

    if ($sid === '') {
        // if somehow id missing, skip to avoid breaking UI
        continue;
    }

    // name + course + help_type
    $course    = $t['course'] ?? '';
    $help_type = $t['help_type'] ?? '';

    // Get student info (if not already joined in query)
    $mRes = getStudentById($sid);
    $m = mysqli_fetch_assoc($mRes);

    if (!$m) {
        $m = [
            's_name' => ($t['s_name'] ?? 'Unknown'),
            's_propic' => ''
        ];
    }

    $mPic = $m['s_propic'] ?? '';
    if ($mPic === '') {
        $mAvatar = "../../Resources/default.png";
    } else {
        $mAvatar = "../../" . $mPic;
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
            <strong class="profile-name"><?= htmlspecialchars($m['s_name'] ?? 'Unknown') ?></strong>
            <small class="profile-role">
                ID: <?= htmlspecialchars($sid) ?><br>
                <?php if ($course !== '') { ?>
                    course: <?= htmlspecialchars($course) ?>
                <?php } ?>
                <?php if ($help_type !== '') { ?>
                    <br><?= htmlspecialchars($help_type) ?>
                <?php } ?>
            </small>
        </span>

        <!-- ✅ Send message button on the RIGHT -->
        <form action="../../Controller/messageController.php" method="GET" style="margin-left:auto;">
            <input type="hidden" name="peer_receiver_id" value="<?= htmlspecialchars($sid) ?>">
            <button type="submit" class="menu-item">Send Message</button>
        </form>
    </div>

    <?php
}
