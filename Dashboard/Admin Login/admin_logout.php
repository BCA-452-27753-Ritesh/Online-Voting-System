<?php
session_start();

// 🔒 REMOVE ONLY ADMIN SESSION
unset($_SESSION['admin']);

// 🔁 REDIRECT TO MAIN DASHBOARD
header("Location: ../dashboard.php");
exit();
?>