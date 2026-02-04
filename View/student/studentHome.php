<?php
session_start();
require_once("../../Model/studentModel.php");
require_once("../../Model/skillsModel.php");
require_once("../../Model/setFreeTimeModel.php");

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
$freeTimes = getFreeTimeByStudentId($_SESSION['loginId']);

/* ===== Profile picture path ===== */
$pic = $student['s_propic'] ?? '';
if ($pic === '') {
    $avatarSrc = "../../Resources/default.png";
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

          <!-- ✅ NEW TUTOR BUTTON ADDED -->
          <li>
            <form action="tutorHomeView.php" method="GET">
              <button type="submit" class="menu-item">Tutor</button>
            </form>
          </li>

        

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

      <form class="search" action="#" method="get" onsubmit="event.preventDefault(); topStudentSearch(true);">
          <input
            type="search"
            id="topSearch"
            placeholder="Search by course (e.g., Web Technologies) or skill (e.g., C++)..."
            autocomplete="off"
            onkeyup="topStudentSearch(false)"
          />
          <button type="submit">Search</button>
        </form>

        <!-- ✅ results will appear just under the top search -->
        <div id="topSearchResults" style="margin-top:10px;"></div>

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

                $mRes = getStudentById($sid);
                $m = mysqli_fetch_assoc($mRes);

                if (!$m) {
                    $m = [
                        's_name' => ($sm['s_name'] ?? 'Unknown'),
                        's_propic' => ''
                    ];
                } else {
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

    <!-- ===================== CONTENT AREA ===================== -->
    <section class="content">

      <!-- ===================== TUTOR MATCH ===================== -->
      <article class="card">
        <header class="card-head">
          <h2>Tutor Search</h2>
          <p>Find tutors by course</p>
        </header>

        <!-- ✅ Search input + button side-by-side -->
        <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap; width:100%; margin-bottom:10px;">
          <input
            type="text"
            id="tutorSearch"
            placeholder="Type course name (e.g. CSC 1102)"
            onkeyup="liveTutorSearch(false)"
            autocomplete="off"
            style="flex:1; min-width:240px; padding:8px;"
          />
          <button
            type="button"
            class="menu-item"
            style="white-space:nowrap;"
            onclick="liveTutorSearch(true)"
          >
            Search
          </button>
        </div>

        <!-- ✅ Results list stays at the bottom of this card -->
        <div id="tutorResults" style="width:100%; margin-top:12px;"></div>

        <div style="margin-top:14px;">
          <small>
            Tip: You can connect with a tutor after searching.
          </small>
        </div>
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

    <!-- ✅ MOVED PROFILE SECTION TO BOTTOM + FULL WIDTH -->
    <section style="width:100%; margin-top:16px;">
      <article class="panel" style="width:100%; min-height: calc(100vh - 180px);">
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

          <li>
            <span class="k">Tutor Courses</span>
            <span class="v">
              <?php
                require_once("../../Model/tutorModel.php");
                $tutorList = getTutorCoursesByStudent($_SESSION['loginId']);

                if (empty($tutorList)) {
                    echo "Not a tutor yet";
                } else {
                    foreach ($tutorList as $t) {
                        echo "<div>";
                        echo "<strong>" . htmlspecialchars($t['course']) . "</strong>";
                        echo " (" . htmlspecialchars($t['help_type']) . ")";
                        echo "</div>";
                    }
                }
              ?>
            </span>
          </li>

          <li>
            <span class="k">Break Time</span>
            <span class="v">
              <?php
                if (empty($freeTimes)) {
                    echo "Not set";
                } else {
                    foreach ($freeTimes as $ft) {
                        $day = $ft['day'] ?? '';
                        $times = $ft['free_times'] ?? '';

                        if ($day !== '' && $times !== '') {
                            $prettyTimes = str_replace(",", " | ", $times);

                            echo "<div style='margin-bottom:6px;'>";
                            echo "<strong>" . htmlspecialchars($day) . ":</strong> " . htmlspecialchars($prettyTimes);
                            echo "</div>";
                        }
                    }
                }
              ?>
            </span>
          </li>
        </ul>
      </article>
    </section>

    <footer class="footer">
      <small>© AIUB CampusConnect</small>
    </footer>
  </main>

  <script>
    // ✅ Debounced live search + optional button search
    let tutorSearchTimer = null;
    let lastTutorQuery = "";

    function liveTutorSearch(force) {
      let q = document.getElementById("tutorSearch").value || "";
      let box = document.getElementById("tutorResults");

      // keep results area at bottom (do not shift layout elsewhere)
      if (q.trim().length === 0) {
        box.innerHTML = "";
        lastTutorQuery = "";
        return;
      }

      // debounce when typing
      if (!force) {
        clearTimeout(tutorSearchTimer);
        tutorSearchTimer = setTimeout(function () {
          doTutorSearch(q);
        }, 250);
      } else {
        doTutorSearch(q);
      }
    }

    function doTutorSearch(q) {
      let box = document.getElementById("tutorResults");
      q = (q || "").trim();

      if (q.length === 0) {
        box.innerHTML = "";
        lastTutorQuery = "";
        return;
      }

      // avoid duplicate calls for same query
      if (q === lastTutorQuery) return;
      lastTutorQuery = q;

      box.innerHTML = "<small>Searching...</small>";

      let xhr = new XMLHttpRequest();
      xhr.open(
        "GET",
        "../../Controller/ajaxSearchTutor.php?q=" + encodeURIComponent(q),
        true
      );

      xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
          if (xhr.status === 200) {
            // NOTE: ajaxSearchTutor.php should output rows like:
            // <div class="profile-btn"> ... <form style="margin-left:auto">Send Message</form></div>
            // so message button stays side-by-side (same as other sections).
            box.innerHTML = xhr.responseText;
          } else {
            box.innerHTML = "<small>Failed to load results.</small>";
          }
        }
      };

      xhr.send();
    }
  </script>


  <script>
      let topSearchTimer = null;
      let lastTopQuery = "";

      function topStudentSearch(force){
        let q = document.getElementById("topSearch").value || "";
        let box = document.getElementById("topSearchResults");

        if (q.trim().length === 0){
          box.innerHTML = "";
          lastTopQuery = "";
          return;
        }

        if (!force){
          clearTimeout(topSearchTimer);
          topSearchTimer = setTimeout(function(){
            doTopStudentSearch(q);
          }, 250);
        } else {
          doTopStudentSearch(q);
        }
      }

      function doTopStudentSearch(q){
        let box = document.getElementById("topSearchResults");
        q = (q || "").trim();

        if (q.length === 0){
          box.innerHTML = "";
          lastTopQuery = "";
          return;
        }

        if (q === lastTopQuery) return;
        lastTopQuery = q;

        box.innerHTML = "<small>Searching...</small>";

        let xhr = new XMLHttpRequest();
        xhr.open("GET", "../../Controller/ajaxTopStudentSearch.php?q=" + encodeURIComponent(q), true);

        xhr.onreadystatechange = function(){
          if (xhr.readyState === 4){
            if (xhr.status === 200){
              box.innerHTML = xhr.responseText;
            } else {
              box.innerHTML = "<small>Failed to load results.</small>";
            }
          }
        };

        xhr.send();
      }
    </script>

  <script src="studentHome.js"></script>
</body>
</html>
