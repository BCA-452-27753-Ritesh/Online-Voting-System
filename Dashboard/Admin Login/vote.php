<?php
session_start();

$conn = mysqli_connect('localhost','root','','voterdatabase');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// ✅ CHECK LOGIN
if (!isset($_SESSION['voterdata'])) {
    header("Location: ../Voter login Form/login.html");
    exit();
}

// ✅ CHECK POST
if (!isset($_POST['voter_id']) || !isset($_POST['gid'])) {
    die("POST data missing ❌");
}

$voter_id = intval($_POST['voter_id']);
$candidate_id = intval($_POST['gid']);

// 🔐 SECURITY CHECK
if ($_SESSION['voterdata']['id'] != $voter_id) {
    die("Unauthorized access ❌");
}

// ✅ CHECK ALREADY VOTED
$check = mysqli_query($conn, "SELECT status FROM voterregistration WHERE id = $voter_id");

if(!$check){
    die("Check Query Error: " . mysqli_error($conn));
}

$row = mysqli_fetch_assoc($check);

if ($row && $row['status'] == 1) {
    die("Already voted ❌ (reset DB for testing)");
}

// ✅ START TRANSACTION
mysqli_begin_transaction($conn);

try {

    // 🔹 INSERT vote history
    $stmt1 = $conn->prepare("INSERT INTO votes (voter_id, candidate_id) VALUES (?, ?)");
    $stmt1->bind_param("ii", $voter_id, $candidate_id);

    if (!$stmt1->execute()) {
        throw new Exception("Insert failed: " . $stmt1->error);
    }

    // 🔹 UPDATE candidate vote count
    $stmt2 = $conn->prepare("UPDATE addcandidate SET votes = votes + 1 WHERE id = ?");
    $stmt2->bind_param("i", $candidate_id);

    if (!$stmt2->execute()) {
        throw new Exception("Vote update failed: " . $stmt2->error);
    }

    // 🔥 🔥 FINAL FIX (vote force update)
    $stmt3 = $conn->prepare("UPDATE voterregistration SET status = 1, vote = ? WHERE id = ?");
    $stmt3->bind_param("ii", $candidate_id, $voter_id);

    if (!$stmt3->execute()) {
        throw new Exception("User update failed: " . $stmt3->error);
    }

    // 🔥 CHECK AFFECTED ROW (IMPORTANT)
    if($stmt3->affected_rows == 0){
        throw new Exception("Vote not updated ❌ (Check voter_id or column)");
    }

    // ✅ COMMIT
    mysqli_commit($conn);

    // ✅ SESSION UPDATE
    $_SESSION['voterdata']['status'] = 1;
    $_SESSION['voterdata']['vote'] = $candidate_id;

    // 🔥 FINAL DEBUG OUTPUT
    $result = mysqli_query($conn, "SELECT vote FROM voterregistration WHERE id=$voter_id");
    $row = mysqli_fetch_assoc($result);

    // 👉 FINAL (after testing)
    header("Location: ../dashboard.php?msg=success");
    exit();

} catch (Exception $e) {

    mysqli_rollback($conn);
    die("<h3 style='color:red;'>Error: " . $e->getMessage() . "</h3>");
}
?>