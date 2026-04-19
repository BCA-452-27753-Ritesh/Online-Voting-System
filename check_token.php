<?php
ob_start();
session_start();

date_default_timezone_set("Asia/Kolkata");

$conn = mysqli_connect("localhost","root","","voterdatabase");

if(!$conn){
    die("Database error ❌");
}

if(!isset($_GET['token']) || empty($_GET['token'])){
    die("Invalid access ❌");
}

$token = $_GET['token'];

// secure query
$stmt = mysqli_prepare($conn, "SELECT * FROM voterregistration WHERE token=? LIMIT 1");
mysqli_stmt_bind_param($stmt, "s", $token);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if(!$result || mysqli_num_rows($result) == 0){
    die("Invalid token ❌");
}

$data = mysqli_fetch_assoc($result);

// expiry check
if(empty($data['expiry_date']) || strtotime($data['expiry_date']) < time()){
    die("❌ Plan expired. Buy again.");
}

// ✅ store session
$_SESSION['user_token'] = $token;

// redirect to main page
header("Location: index.php");
exit();
?>