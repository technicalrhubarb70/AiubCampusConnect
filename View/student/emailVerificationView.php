<?php
session_start();

?>

<!doctype
<html> 
    <head>

    </head>
    <body>
        <form action="../../Controller/emailVerificationtoDB.php" method="POST" >
            <label for="otp">Enter OTP: </label>
            <input type="number" name="otp" placeholder="OTP has been sent to your email.">
            <span name="otpErr"><?php if(isset($_GET["otpErr"])){echo $_GET["otpErr"];}?></span><br>
            <input type="reset"  name="reset" value="clear"><br>
            <input type="submit" name="submit" value="submit"><br>
            <input type="submit" name="resendOtp" value="Resend OTP">
        </form>
    </body>
</html>
