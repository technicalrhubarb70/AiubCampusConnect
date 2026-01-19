<?php
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up | AIUB CampusConnect</title>
    <link rel="stylesheet" href="signup.css">
</head>
<body>

<div class="signup-container">

    <div class="signup-card">

        <!-- Logo -->
        <div class="logo-wrapper">
            <img src="../../Model/Resources/signup.png" class="signup-image" alt="Signup Image">

        </div>

        <h2>Create Account</h2>

        <form action="../../Controller/signUpValidation.php" method="POST" enctype="multipart/form-data">

            <div class="form-group">
                <label>Name</label>
                <input type="text" name="sName" placeholder="Enter your full name">
                <span class="error"><?php if(isset($_GET["sNameErr"])){echo $_GET["sNameErr"];}?></span>
            </div>

            <div class="form-group">
                <label>Gender</label>
                <div class="radio-group">
                    <label><input type="radio" name="sGender" value="male"> Male</label>
                    <label><input type="radio" name="sGender" value="female"> Female</label>
                </div>
                <span class="error"><?php if(isset($_GET["sGenderErr"])){echo $_GET["sGenderErr"];}?></span>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="sPassword">
                <span class="error"><?php if(isset($_GET["sPasswordErr"])){echo $_GET["sPasswordErr"];}?></span>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="text" name="sEmail" placeholder="Enter AIUB email">
                <span class="error"><?php if(isset($_GET["sEmailErr"])){echo $_GET["sEmailErr"];}?></span>
            </div>

            <div class="form-group">
                <label>Profile Picture</label>
                <input type="file" name="sProPic">
                <span class="error"><?php if(isset($_GET["sProPicErr"])){echo $_GET["sProPicErr"];}?></span>
            </div>

            <div class="button-group">
                <input type="submit" name="submit" value="Submit">
                <input type="reset" name="reset" value="Reset">
            </div>

        </form>

    </div>
</div>

</body>
</html>
