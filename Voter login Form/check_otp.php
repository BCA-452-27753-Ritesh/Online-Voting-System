<?php
session_start();

$conn = mysqli_connect("localhost","root","","voterdatabase");

if(!$conn){
    die("Database connection failed ❌");
}

// 🔥 Use session (not POST)
if(!isset($_SESSION['mobile'])){
    header("Location: forgot_password.php");
    exit();
}

$mobile = $_SESSION['mobile'];
$otp = trim($_POST['otp'] ?? '');

$status = "";
$redirect = false;

// ❌ Empty OTP check
if(empty($otp)){
    $status = "Please enter OTP ❌";
}else{

    // ✅ Prepared statement (secure)
    $stmt = $conn->prepare("SELECT otp, otp_expire FROM voterregistration WHERE mobile=?");
    
    if(!$stmt){
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("s", $mobile);
    $stmt->execute();

    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if($data){

        if($data['otp'] == $otp){

            if(strtotime($data['otp_expire']) > time()){

                // ✅ Session already set
                $_SESSION['mobile'] = $mobile;

                $status = "OTP Verified Successfully ✅";
                $redirect = true;

            }else{
                $status = "OTP Expired ❌";
            }

        }else{
            $status = "Invalid OTP ❌";
        }

    }else{
        $status = "User not found ❌";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>OTP Status</title>

    <!-- 🔥 1 sec redirect -->
    <?php if($redirect){ ?>
    <meta http-equiv="refresh" content="1;url=reset_password.php">
    <?php } ?>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

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
            background: white;
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0px 10px 25px rgba(0,0,0,0.2);
        }

        .msg {
            font-size: 20px;
            font-weight: bold;
            color: <?php echo $redirect ? 'green' : 'red'; ?>;
        }
    </style>
</head>
<body>

<div class="box">
    <h3>🔐 OTP Verification</h3>

    <div class="msg mt-3">
        <?php echo $status; ?>
    </div>

    <?php if($redirect){ ?>
        <p class="text-muted mt-2">Redirecting...</p>
    <?php } else { ?>
        <a href="verify_otp.php" class="btn btn-primary mt-3">Try Again</a>
    <?php } ?>
</div>

</body>
</html>