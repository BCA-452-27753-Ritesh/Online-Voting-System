<?php
session_start();

$conn = mysqli_connect('localhost', 'root', '', 'voterdatabase');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// ✅ CHECK FORM SUBMIT
if (isset($_POST['name']) && isset($_POST['password'])) {

    $name = $_POST['name'];
    $password = $_POST['password'];

    // ✅ QUERY ADDED (MISSING IN YOUR CODE)
    $check = mysqli_query($conn, "SELECT * FROM adminlogin WHERE name='$name' AND password='$password'");

    if ($check && mysqli_num_rows($check) > 0) {

        // 🔒 SET ADMIN SESSION
        $_SESSION['admin'] = true;

        echo "<script>
                alert('Login Successful');
                window.location.href='AdminDashboard.php';
              </script>";

    } else {

        // ❌ NOT ADMIN
        echo "<script>
                alert('You are not admin');
                window.location.href='http://localhost/Online%20Voting%20System/';
              </script>";
    }

} else {

    // ❌ DIRECT ACCESS
    header("Location: ../dashboard.php");
    exit();
}
?>