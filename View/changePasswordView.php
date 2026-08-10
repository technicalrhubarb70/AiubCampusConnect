<?php
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forget Password| AIUB CampusConnect</title>
    <link rel="stylesheet" href="student/signup.css">
</head>
<body>

<div class="signup-container">

    <div class="signup-card">

        <div class="logo-wrapper">
            <img src="../Resources/logo.png" class="signup-image" alt="Signup Image">
            
        </div>

        <h2>Change Password</h2>

        <form action="../Controller/changePasswordControler.php" method="POST">

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="sPassword1">
                <span class="error"><?php if(isset($_GET["sPasswordErr1"])){echo $_GET["sPasswordErr1"];}?></span>
            </div>
             <div class="form-group">
                <label>Password</label>
                <input type="password" name="sPassword">
                <span class="error"><?php if(isset($_GET["sPasswordErr"])){echo $_GET["sPasswordErr"];}?></span>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirmPassword">
                <span class="error"><?php if(isset($_GET["confirmPasswordErr"])){echo $_GET["confirmPasswordErr"];}?></span>
            </div>

            <div class="button-group">
                <input type="submit" name="submit" value="Submit">
                <input type="reset" name="reset" value="Reset"> 
                <input type="submit" name="cancelSignup" value="Cancel">
            </div>

        </form>

    </div>
</div>

</body>
</html>
