<?php
$conn = mysqli_connect("localhost","root","","voterdatabase");

$token = $_GET['token'];

$result = mysqli_query($conn, "SELECT * FROM voterregistration WHERE token='$token'");
$data = mysqli_fetch_assoc($result);

if(!$data){
    echo "invalid";
    exit();
}

if(strtotime($data['expiry_date']) < time()){
    echo "expired";
} else {
    echo "valid";
}
?>