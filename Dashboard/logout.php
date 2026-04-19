<?php
session_start();

// 🔥 DESTROY ALL SESSION
session_unset();
session_destroy();

// REDIRECT TO LOGIN
header("Location: http://localhost/Online%20Voting%20System/");
exit();
?>