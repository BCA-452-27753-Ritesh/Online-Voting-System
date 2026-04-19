<?php
session_start();

$conn = mysqli_connect("localhost","root","","voterdatabase");

$mobile = trim($_POST['mobile']);

// Check user
$result = mysqli_query($conn, "SELECT * FROM voterregistration WHERE mobile='$mobile'");

if(mysqli_num_rows($result) > 0){

    $otp = rand(100000,999999);
    $expire = date("Y-m-d H:i:s", strtotime("+5 minutes"));

    mysqli_query($conn, "UPDATE voterregistration 
    SET otp='$otp', otp_expire='$expire' 
    WHERE mobile='$mobile'");

    $_SESSION['mobile'] = $mobile;
?>
    
<!DOCTYPE html>
<html>
<head>
    <title>OTP Sent</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Auto Redirect -->
    <meta http-equiv="refresh" content="5;url=verify_otp.php">

    <style>
        body {
            background: linear-gradient(135deg, #1d2671, #c33764);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .box {
            width: 420px;
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }

        .otp {
            font-size: 28px;
            font-weight: bold;
            color: #28a745;
        }
    </style>
</head>
<body>

<div class="box">
    <h3>✅ OTP Sent Successfully</h3>
    <p class="mt-3">Your OTP is:</p>

    <div class="otp"><?php echo $otp; ?></div>

    <p class="mt-3 text-muted">Redirecting to verify page in 5 seconds...</p>

    <a href="verify_otp.php" class="btn btn-primary mt-3">Verify Now</a>
</div>

</body>
</html>

<?php

}else{
    echo "<h3 style='color:red;text-align:center;margin-top:50px;'>Mobile number not found ❌</h3>";
}
?>