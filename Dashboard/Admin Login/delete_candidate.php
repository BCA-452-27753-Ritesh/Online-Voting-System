<?php


$conn = mysqli_connect('localhost', 'root', '', 'voterdatabase');
if (!$conn) die("Connection failed: " . mysqli_connect_error());

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // Get file names
    $result = mysqli_query($conn, "SELECT photo, symbol FROM addcandidate WHERE id=$id");
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        if (!empty($row['photo']) && file_exists("Image/".$row['photo'])) unlink("Image/".$row['photo']);
        if (!empty($row['symbol']) && file_exists("Image/".$row['symbol'])) unlink("Image/".$row['symbol']);

        // Delete record
        mysqli_query($conn, "DELETE FROM addcandidate WHERE id=$id");
    }

    // ✅ Redirect back to AdminDashboard.php and scroll to #Total
    header("Location: AdminDashboard.php#Total");
    exit();
}
?>
