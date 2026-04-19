<?php
session_start();

$conn = mysqli_connect('localhost', 'root', '', 'voterdatabase');

// ❌ Check DB connection first
if (!$conn) {
    die("Database connection failed ❌");
}

// 🔍 Check voting status safely
$statusQuery = mysqli_query($conn, "SELECT status FROM voting_status WHERE id=1");
$statusRow = mysqli_fetch_assoc($statusQuery);

if ($statusRow && $statusRow['status'] == 0) {
    echo "<script>
        alert('🚫 Voting Closed by Admin!');
        window.location='login.html';
    </script>";
    exit();
}

// ✅ Get & clean input
$adhar = trim($_POST['adhar'] ?? '');
$mobile = trim($_POST['mobile'] ?? '');
$pass   = trim($_POST['pass'] ?? '');

// ✅ Validate input
if (!preg_match("/^[0-9]{12}$/", $adhar)) {
    die("Invalid Aadhar number ❌");
}

if (!preg_match("/^[0-9]{10}$/", $mobile)) {
    die("Invalid mobile number ❌");
}

if (strlen($pass) < 4) {
    die("Password too short ❌");
}

// ✅ Prepared Statement (secure)
$stmt = $conn->prepare("SELECT * FROM voterregistration WHERE adhar=? AND mobile=?");

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("ss", $adhar, $mobile);
$stmt->execute();

$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {

    $voterdata = $result->fetch_assoc();

    // 🔐 Verify password
    if (password_verify($pass, $voterdata['password'])) {

        // ✅ IMPORTANT FIX 🔥 (session set properly)
        $_SESSION['voterdata'] = $voterdata;
        $_SESSION['mobile'] = $mobile; // 🔥 ADD THIS (important for OTP + receipt)

        echo "<script>
            location='../Dashboard/dashboard.php';
        </script>";

    } else {
        echo "<script>
            alert('❌ Wrong Password!');
            location='login.html';
        </script>";
    }

} else {
    echo "<script>
        alert('❌ User not found!');
        location='login.html';
    </script>";
}

// ✅ Close properly
$stmt->close();
$conn->close();
?>