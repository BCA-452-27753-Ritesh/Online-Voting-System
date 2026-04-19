<?php
// Database connection
$conn = mysqli_connect('localhost', 'root', '', 'voterdatabase');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check form submit
if (isset($_POST['name']) && isset($_POST['password'])) {

    $name = $_POST['name'];
    $password = $_POST['password'];

    // Query check
    $sql = "SELECT * FROM adminlogin WHERE name='$name' AND password='$password'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        // ✅ SUCCESS LOGIN
        echo "<script>
                alert('Login Successful');
                window.location.href='Dashboard/Admin Login/AdminDashboard.php';
              </script>";
    } else {
        // ❌ WRONG LOGIN
        echo "<script>
                alert('Incorrect Username or Password');
                window.location.href='Dashboard/Admin Login/adminlogin.html';
              </script>";
    }

} else {
    // If directly accessed
    echo "<script>
            window.location.href='Dashboard/Admin Login/adminlogin.html';
          </script>";
}
?>