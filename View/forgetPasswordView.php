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

        <!-- Logo -->
        <div class="logo-wrapper">
            <img src="../Resources/logo.png" class="signup-image" alt="Signup Image">
            
        </div>

        <h2>Forget Password</h2>

        <form action="../Controller/forgetPasswordControl.php" method="POST">

            <div class="form-group">
                <label>Email</label>
                <input type="text" name="sEmail" value="<?php if(isset($_GET["sEmail"])){echo $_GET["sEmail"];}?>">
                <span class="error"><?php if(isset($_GET["sEmailErr"])){echo $_GET["sEmailErr"];}?></span>
            </div>
            
            <input type="submit" name="resendOtp" value="Send OTP"><br><br>
            
            <label for="otp">Enter OTP: </label>
            <input type="number" name="otp" placeholder="OTP has been sent to your email."><br>
            <span class="error"><?php if(isset($_GET["otpErr"])){echo $_GET["otpErr"];}?></span><br>



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
