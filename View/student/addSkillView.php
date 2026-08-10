<?php
session_start();

if (!isset($_SESSION['loginId']) || !isset($_SESSION['role']) || $_SESSION['role'] != 2) {
    header("Location: ../loginView.php");
    exit();
}

$err = $_GET['err'] ?? "";
$ok  = $_GET['ok'] ?? "";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Add Skill</title>
</head>
<body>

  <h2>Add Skill</h2>

  <?php if ($err): ?>
    <p style="color:red;"><?php echo htmlspecialchars($err); ?></p>
  <?php endif; ?>

  <?php if ($ok): ?>
    <p style="color:green;"><?php echo htmlspecialchars($ok); ?></p>
  <?php endif; ?>

  <form method="POST" action="../../Controller/addSkillController.php">
    <label for="skill">Skill name</label><br>
    <input type="text" id="skill" name="skill" placeholder="e.g. C++, Java, SQL" required><br><br>

    <button type="submit">Save Skill</button>
    <a href="studentHome.php" style="margin-left:10px;">Back to Home</a>
  </form>

</body>
</html>
