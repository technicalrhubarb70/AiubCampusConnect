<?php
session_start();
require_once("../../Model/studentModel.php");
require_once("../../Model/skillsModel.php");

if (!isset($_SESSION['loginId']) || !isset($_SESSION['role'])) {
    header("Location:../loginView.php");
    exit();
}

if ($_SESSION['role'] != 2) {
    header("Location:../loginView.php");
    exit();
}

/* FIX WARNING */
$studentIds = $_SESSION['matchedStudentIds'] ?? [];

/* Load student data */
$studentRes = getStudentById($_SESSION['loginId']);
$student = mysqli_fetch_assoc($studentRes);

if (!$student) {
    $student = [
        's_name' => 'Unknown',
        'status' => 0,
        's_propic' => ''
    ];
}

$skillsRes = getSkillsByStudent($_SESSION['loginId']);

/* ===== Profile picture path =====
   In DB we expect: "Resources/filename.png"
   studentHome.php is in View/student/
   So to reach root: ../../
*/
$pic = $student['s_propic'] ?? '';
if ($pic === '') {
    $avatarSrc = "../../Resources/default.png"; // put a default.png there
} else {
    $avatarSrc = "../../" . $pic;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>AIUB CampusConnect | Home</title>
  <link rel="stylesheet" href="studentHome.css" />
</head>

<body>

  <header class="topbar">
    <a class="brand" href="#">
      <span class="brand-mark">AC</span>
      <span class="brand-text">
        <strong class="brand-title">AIUB CampusConnect</strong>
        <small class="brand-sub">Home</small>
      </span>
    </a>

    <nav class="actions">
      <button class="icon-btn" id="themeToggle" type="button">🌙 Dark</button>

      <details class="profile" id="profileBox">
        <summary class="profile-btn">

          <!--PROFILE PIC SHOWS HERE -->
          <img
            class="avatar"
            src="<?php echo htmlspecialchars($avatarSrc); ?>"
            alt="Profile picture"
            width="36"
            height="36"
            style="border-radius:50%; object-fit:cover;"
          />

          <span class="profile-meta">
            <strong class="profile-name"><?php echo htmlspecialchars($student['s_name']); ?></strong>
            <small class="profile-role">ID: <?php echo htmlspecialchars($_SESSION['loginId']); ?></small>
          </span>
          <span class="chev" aria-hidden="true"></span>
        </summary>

        <menu class="dropdown" aria-label="Profile menu">
          <li>
            <form action="studentProfileView.php" method="GET">
              <input type="submit" class="menu-item" name="edit_Profile" value="Edit Profile">
            </form>
          </li>

          <li>
            <form action="addSkillView.php" method="GET">
              <button type="submit" class="menu-item">Add skills</button>
            </form>
          </li>

          <li>
            <form method="GET" action="../setFreeTimeView.php">
              <input type="submit" class="menu-item" name="viewFreeTime" value="Add breaktime">
            </form>
          </li>

          <li class="menu-sep"></li>

          <li>
            <form action="studentCourseView.php" method="GET">
              <input type="submit" class="menu-item" value="Add Courses">
            </form>
          </li>

          <li>
            <button class="menu-item danger" type="button"
              onclick="window.location.href='../../Controller/logout.php'">Logout</button>
          </li>
        </menu>
      </details>
    </nav>
  </header>

  <main class="layout">
    <section class="hero">
      <h1>Welcome back <?php echo htmlspecialchars($student['s_name']); ?></h1>
      <p>Match breaks, skills, or find course help all in one place!</p>

      <form class="search" action="#" method="get">
        <input type="search" placeholder="Search by course (e.g., CSC 1102) or name..." />
        <button type="submit">Search</button>
      </form>
    </section>

    <section class="grid">
      <!-- ===================== BREAK MATCH ===================== -->
      <article class="card">
        <header class="card-head">
          <h2>Break Match</h2>
          <p>Find someone free at the same time for adda.</p>
        </header>

        <?php
        echo "<h3>Matched Student IDs</h3>";
        if (empty($studentIds)) {
            echo "No matched students.<br>";
        } else {
            foreach ($studentIds as $id) {

                $mRes = getStudentById($id);
                $m = mysqli_fetch_assoc($mRes);

                if (!$m) {
                    $m = [
                        's_name' => 'Unknown',
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
                        <strong class="profile-name"><?= htmlspecialchars($m['s_name']) ?></strong>
                        <small class="profile-role">ID: <?= htmlspecialchars($id) ?></small>
                    </span>

                    <form action="../../Controller/studentDashboardController.php" method="GET" style="margin-left:auto;">
                        <input type="hidden" name="receiver_id" value="<?= htmlspecialchars($id) ?>">
                        <button type="submit" class="menu-item">Send Message</button>
                    </form>
                </div>

                <?php
            }
        }
        ?>

        <form method="GET" action="../../Controller/studentHomeBreakTimeController.php">
          <input type="submit" class="card-btn" name="viewFreeTime" value="Suggest matches">
        </form>
      </article>

      <!-- ===================== SKILL MATCH ===================== -->
      <article class="card">
        <header class="card-head">
          <h2>Skill Match</h2>
          <p>Connect with people with similar skillsets.</p>
        </header>

        <?php
        $msg = $_SESSION['skill_match_msg'] ?? "";
        if ($msg !== "") {
            echo "<p>" . htmlspecialchars($msg) . "</p>";
            unset($_SESSION['skill_match_msg']);
        }

        $skillmatches = $_SESSION['skill_matches'] ?? [];

        if (empty($skillmatches)) {
            echo "<small>No skill matches yet.</small>";
        } else {
            foreach ($skillmatches as $sm) {

                $sid = $sm['s_id'] ?? '';
                $common = $sm['common_skills'] ?? '';

                // Load student for photo (in case controller didn't send propic)
                $mRes = getStudentById($sid);
                $m = mysqli_fetch_assoc($mRes);

                if (!$m) {
                    $m = [
                        's_name' => ($sm['s_name'] ?? 'Unknown'),
                        's_propic' => ''
                    ];
                } else {
                    // If controller already provides name, keep DB name anyway
                    if (empty($m['s_name']) && isset($sm['s_name'])) {
                        $m['s_name'] = $sm['s_name'];
                    }
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
                            <?php if ($common !== '') { ?>
                                common: <?= htmlspecialchars($common) ?>
                            <?php } ?>
                        </small>
                    </span>

                    <form action="../../Controller/messageController.php" method="GET" style="margin-left:auto;">
                        <input type="hidden" name="peer_receiver_id" value="<?= htmlspecialchars($sid) ?>">
                        <button type="submit" class="menu-item">Send Message</button>
                    </form>
                </div>

                <?php
            }
        }
        ?>

        <form method="GET" action="../../Controller/skillMatch.php">
          <input type="submit" class="card-btn" value="Suggest matches">
        </form>
      </article>

      <!-- ===================== COURSE MATCH ===================== -->
      <article class="card">
        <header class="card-head">
          <h2>Course Match</h2>
          <p>Find students taking the same course.</p>
        </header>

        <?php
        $courseMatches = $_SESSION['course_matches'] ?? [];

        if (empty($courseMatches)) {
            echo "<small>No course matches yet.</small>";
        } else {
            foreach ($courseMatches as $cm) {

                $sid = $cm['s_id'] ?? '';
                $course = $cm['course'] ?? '';

                $mRes = getStudentById($sid);
                $m = mysqli_fetch_assoc($mRes);

                if (!$m) {
                    $m = [
                        's_name' => 'Unknown',
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
                        <strong class="profile-name"><?= htmlspecialchars($m['s_name']) ?></strong>
                        <small class="profile-role">
                            ID: <?= htmlspecialchars($sid) ?><br>
                            <?php if ($course !== '') { ?>
                                course: <?= htmlspecialchars($course) ?>
                            <?php } ?>
                        </small>
                    </span>

                    <form action="../../Controller/messageController.php" method="GET" style="margin-left:auto;">
                        <input type="hidden" name="peer_receiver_id" value="<?= htmlspecialchars($sid) ?>">
                        <button type="submit" class="menu-item">Send Message</button>
                    </form>
                </div>

                <?php
            }
        }
        ?>

        <form method="GET" action="../../Controller/studentHomeCourseController.php">
          <input type="submit" class="card-btn" value="Suggest matches">
        </form>
      </article>
    </section>

    <section class="content">
      <article class="panel">
        <header class="panel-head">
          <h2>Your Profile</h2>
          <p>Active/Inactive • tutor profile controls</p>
        </header>

        <ul class="list">
          <li><span class="k">Name</span><span class="v"><?php echo htmlspecialchars($student['s_name']); ?></span></li>
          <li><span class="k">Status</span><span class="v"><?php echo ($student['status'] == 1 ? 'Active' : 'Inactive'); ?></span></li>
          <li>
            <span class="k">Skills</span>
            <span class="v">
              <?php
              $skills = [];
              while ($r = mysqli_fetch_assoc($skillsRes)) {
                  $skills[] = $r['skill_name'];
              }
              echo htmlspecialchars(count($skills) ? implode(", ", $skills) : "None");
              ?>
            </span>
          </li>
        </ul>
      </article>

      <aside class="panel">
        <header class="panel-head">
          <h2>How Chat Works</h2>
          <p>Only after you connect</p>
        </header>

        <ul class="bullets">
          <li>Text messages</li>
          <li>Submit files</li>
          <li>No call/video for now</li>
        </ul>

        <footer class="panel-foot">
          <form action="../messageView.php" method="GET">
            <input type="submit" class="ghost" value="Send a message">
          </form>
        </footer>
      </aside>
    </section>

    <footer class="footer">
      <small>© AIUB CampusConnect</small>
    </footer>
  </main>

  <script src="studentHome.js"></script>
</body>
</html>
