<?php
session_start();
require_once("../../Model/studentModel.php");
require_once("../../Model/skillsModel.php");
$studentIds=$_SESSION['matchedStudentIds'];
$student = mysqli_fetch_assoc(getStudentById($_SESSION['loginId']));

if ($_SESSION['role'] != 2) {
    header("Location:../loginView.php");
    exit();
}

if(!isset($_SESSION['loginId'])||!isset($_SESSION['role'])){
    header("Location:../loginView.php");
    exit();
}

if (!$student) {
  $student = [
    's_name' => 'Unknown',
    'status' => 0
  ];
}
$skillsRes = getSkillsByStudent($_SESSION['loginId']);
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
          <span class="avatar" aria-hidden="true"></span>
          <span class="profile-meta">
            <strong class="profile-name"><?php echo htmlspecialchars($student['s_name']); ?></strong>
            <small class="profile-role">ID: <?php echo htmlspecialchars($_SESSION['loginId']); ?></small>

          </span>
          <span class="chev" aria-hidden="true"></span>
        </summary>

        <menu class="dropdown" aria-label="Profile menu">
          
          <li>
            <form action="studentProfileView.php" method="GET">
            <input type="submit" class="menu-item" name='edit_Profile ' value="Edit Profile "></li>
           </form>
          <li>
            <form action="addSkillView.php" method="get">
              <button type="submit" class="menu-item">Add skills</button>
            </form>
          </li>
          <form method="GET" action="../setFreeTimeView.php">
            <input type="submit" class="menu-item" name="viewFreeTime" value="Add breaktime">
          </form>
          </li>
          <li class="menu-sep"></li>
          <li>
            <li>
              <form action="studentCourseView.php" method="GET">
             <input type="submit" class="menu-item" value="Add Courses">
            </form>
            </li>

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

    <?php if (isset($_SESSION['search_results'])): ?>
    <section class="search-results">
      <h2>Search Results</h2>
      <ul>
        <?php while ($row = mysqli_fetch_assoc($_SESSION['search_results'])): ?>
        <li>
          <strong><?php echo htmlspecialchars($row['s_name']); ?></strong> (<?php echo htmlspecialchars($row['s_id']); ?>) - Courses: <?php echo htmlspecialchars($row['courses'] ?: 'None'); ?>
        </li>
        <?php endwhile; ?>
      </ul>
    </section>
    <?php unset($_SESSION['search_results']); endif; ?>

    <section class="grid">
      <article class="card">
        <header class="card-head">
          <h2>Break Match</h2>
          <p>Find someone free at the same time for adda.</p>
        </header>
        <span ><?php
              $studentIds=$_SESSION['matchedStudentIds']??[];
              echo "<h3>Matched Student IDs</h3>";
              if(empty($studentIds)){
                  echo "No matched students.<br>";
              }else{
                  foreach ($studentIds as $id) {
                  ?>
                          <?php echo "ID: ".$id ; ?>
                          <form action="../../Controller/studentDashboardController.php" method="GET" style="display:inline;">
                            <input type="hidden" name="receiver_id" value="<?= htmlspecialchars($id) ?>">
                            <input type="submit"  value="Send Message to <?= htmlspecialchars($id) ?>">
                          </form>
                  <?php
                  }
              }

              ?>
      </span><br><br>
      <form method="GET" action="../../Controller/studentHomeBreakTimeController.php">
        <input type="submit" class="card-btn" name="viewFreeTime" value="Suggest matches">
        </form>
        </article>

      <article class="card">
  <header class="card-head">
    <h2>Skill Match</h2>
    <p>Connect with people with similar skillsets.</p>
  </header>

  <?php
  $msg = $_SESSION['skill_match_msg'] ?? "";
  if ($msg != "") {
      echo "<p>" . htmlspecialchars($msg) . "</p>";
      unset($_SESSION['skill_match_msg']);
  }

  $skillmatches = $_SESSION['skill_matches'] ?? [];
  if (!empty($skillmatches)) {
      foreach ($skillmatches as $m) {
          echo "<strong>" . htmlspecialchars($m['s_name']) . "</strong> (" . htmlspecialchars($m['s_id']) . ")<br>";
          echo "<small>common: " . htmlspecialchars($m['common_skills']) . "</small><br><br>";
      }
  } else {
      echo "<small>No skill matches yet.</small>";
  }
  ?>

  

  <!-- keep match button working -->
   <form method="get" action="../../Controller/skillMatch.php">
    <input type="submit" class="card-btn" value="Suggest matches">
  </form>

</article>

<article class="card">
  <header class="card-head">
    <h2>Course Match</h2>
    <p>Find students taking the same course.</p>
  </header>

  <?php
  $courseMatches=$_SESSION['course_matches']??[];

  if(empty($courseMatches)){
      echo "<small>No course matches yet.</small>";
  }else{
      foreach($courseMatches as $m){
          ?>
          <strong>Course: <?= htmlspecialchars($m['course']) ?></strong><br>
          Student ID: <?= htmlspecialchars($m['s_id']) ?>

          <form action="../../Controller/messageController.php" method="GET" style="display:inline;">
          <input type="hidden" name="peer_receiver_id" value="<?= htmlspecialchars($m['s_id']) ?>">
          <button type="submit">Send Message</button>
          </form>

          <br><br>
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
          <li><span class="k">Name</span><span class="v"><?php echo $student['s_name']; ?></span></li>
          <li><span class="k">Status</span><span class="v"><?php echo $student['status'] == 1 ? 'Active' : 'Inactive'; ?></span></li>
          <li>
  <span class="k">Skills</span>
  <span class="v">
    <?php
      $skills = [];
      while($r = mysqli_fetch_assoc($skillsRes)){ $skills[] = $r['skill_name']; }
      echo htmlspecialchars(count($skills) ? implode(", ", $skills) : "None");
    ?>
  </span>
</li>

<li>
  <span class="k">Free time</span>
  <?php
  require_once("../../Model/setFreeTimeModel.php");
  $freeTimes = getFreeTimeByStudentId($_SESSION['loginId']);

if (empty($freeTimes)) {
    echo "None";
} else {
    foreach ($freeTimes as $ft) {
        echo htmlspecialchars($ft['day']) . ": " . htmlspecialchars($ft['free_times']) . "<br>";
    }
}
?>

    
  </span>
</li>



        </ul>

        <footer class="panel-foot">
          <form action="studentProfileView.php" method="GET">
            <input type="submit" class="ghost" value="Open profile editor">
          </form>
        </footer>
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
