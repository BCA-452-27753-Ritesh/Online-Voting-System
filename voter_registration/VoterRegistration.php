<?php
session_start();

// ✅ DATABASE CONNECTION
$conn = mysqli_connect('localhost', 'root', '', 'voterdatabase');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// ✅ CHECK REQUEST METHOD
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: index.html");
    exit();
}

// ✅ GET DATA (SAFE)
$name   = mysqli_real_escape_string($conn, $_POST['name']);
$dob    = $_POST['dob'];
$email  = mysqli_real_escape_string($conn, $_POST['email']);
$mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
$gender = $_POST['gender'];
$idtype = $_POST['idtype'];
$adhar  = mysqli_real_escape_string($conn, $_POST['adhar']);
$issue  = $_POST['issue'];
$expire = $_POST['expire'];

$pass  = $_POST['pass'];
$cpass = $_POST['cpass'];

// ✅ PASSWORD MATCH CHECK
if ($pass !== $cpass) {
    echo "<script>
        alert('❌ Password and Confirm Password do not match!');
        window.history.back();
    </script>";
    exit();
}

// ✅ DUPLICATE CHECK (EMAIL / MOBILE / AADHAAR)
$errors = [];

// Email check
$emailCheck = mysqli_query($conn, "SELECT id FROM voterregistration WHERE email='$email'");
if (mysqli_num_rows($emailCheck) > 0) {
    $errors[] = "Email already registered!";
}

// Mobile check
$mobileCheck = mysqli_query($conn, "SELECT id FROM voterregistration WHERE mobile='$mobile'");
if (mysqli_num_rows($mobileCheck) > 0) {
    $errors[] = "Mobile number already registered!";
}

// Aadhaar check
$adharCheck = mysqli_query($conn, "SELECT id FROM voterregistration WHERE adhar='$adhar'");
if (mysqli_num_rows($adharCheck) > 0) {
    $errors[] = "Aadhaar number already registered!";
}

// If any error → show all
if (!empty($errors)) {
    $msg = implode("\\n", $errors);

    echo "<script>
        alert('❌ " . $msg . "');
        window.history.back();
    </script>";
    exit();
}

// ✅ PASSWORD HASHING
$hashed = password_hash($pass, PASSWORD_DEFAULT);

// ✅ IMAGE UPLOAD
$image = $_FILES['photo']['name'];
$tmp   = $_FILES['photo']['tmp_name'];

$uploadPath = "../VoterImg/" . $image;

if (!empty($image)) {
    move_uploaded_file($tmp, $uploadPath);
} else {
    $image = "default.png"; // optional default
}

// ✅ INSERT DATA
$insert = mysqli_query($conn, "
INSERT INTO voterregistration
(name, dob, email, mobile, gender, photo, idtype, adhar, issue, expire, password, status)
VALUES
('$name', '$dob', '$email', '$mobile', '$gender', '$image', '$idtype', '$adhar', '$issue', '$expire', '$hashed', 0)
");

// ✅ RESPONSE
if ($insert) {
    echo "<script>
        alert('✅ Registration Successful!');
        window.location='../Voter login Form/login.html';
    </script>";
} else {
    echo "<script>
        alert('❌ Registration Failed!');
        window.history.back();
    </script>";
}
?>