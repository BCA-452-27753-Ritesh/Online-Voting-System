<?php
$conn = mysqli_connect("localhost", "root", "", "voterdatabase");

$q = mysqli_query($conn, "SELECT status FROM voting_status WHERE id=1");
$row = mysqli_fetch_assoc($q);

if($row['status'] == 1){
    mysqli_query($conn, "UPDATE voting_status SET status=0 WHERE id=1");
    echo "off";
} else {
    mysqli_query($conn, "UPDATE voting_status SET status=1 WHERE id=1");
    echo "on";
}
?>