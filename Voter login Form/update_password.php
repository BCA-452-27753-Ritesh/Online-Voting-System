<?php
session_start();

$conn = mysqli_connect("localhost","root","","voterdatabase");

if(!$conn){
    die("Database connection failed ❌");
}

// 🔥 IMPORTANT: Session se mobile lo (secure)
if(!isset($_SESSION['mobile'])){
    header("Location: login.html");
    exit();
}

$mobile = $_SESSION['mobile'];

// ✅ Get password
$new_pass = $_POST['new_pass'] ?? '';
$confirm_pass = $_POST['confirm_pass'] ?? '';

// ✅ Validation
if(strlen($new_pass) < 4){
    die("Password too short ❌");
}

if($new_pass !== $confirm_pass){
    die("Passwords do not match ❌");
}

// 🔐 Hash password
$hashed_pass = password_hash($new_pass, PASSWORD_DEFAULT);

// ✅ Prepared statement (secure)
$stmt = $conn->prepare("UPDATE voterregistration SET password=? WHERE mobile=?");

if(!$stmt){
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("ss", $hashed_pass, $mobile);
$update = $stmt->execute();

$status = "";
$redirect = false;

if($update){
    $status = "Password Updated Successfully ✅";
    $redirect = true;

    // 🔥 Clear OTP after use
    mysqli_query($conn, "UPDATE voterregistration SET otp=NULL, otp_expire=NULL WHERE mobile='$mobile'");

}else{
    $status = "Error updating password ❌";
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Status</title>

    <!-- 🔥 2 sec redirect -->
    <?php if($redirect){ ?>
    <meta http-equiv="refresh" content="2;url=login.html">
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
            background: #fff;
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
    <h3>🔑 Update Password</h3>

    <div class="msg mt-3">
        <?php echo $status; ?>
    </div>

    <?php if($redirect){ ?>
        <p class="text-muted mt-2">Redirecting to login...</p>
    <?php } else { ?>
        <a href="reset_password.php" class="btn btn-primary mt-3">Try Again</a>
    <?php } ?>
</div>

</body>
</html>