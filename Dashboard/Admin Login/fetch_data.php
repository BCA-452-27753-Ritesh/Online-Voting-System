<?php
header('Content-Type: application/json');

$conn = mysqli_connect('localhost','root','','voterdatabase');

if (!$conn) {
    echo json_encode(["error" => "DB connection failed"]);
    exit();
}

// ✅ TOTAL VOTERS
$totalQuery = mysqli_query($conn, "SELECT COUNT(*) as t FROM voterregistration");
$total = mysqli_fetch_assoc($totalQuery)['t'] ?? 0;

// ✅ VOTED
$votedQuery = mysqli_query($conn, "SELECT COUNT(*) as v FROM voterregistration WHERE status=1");
$voted = mysqli_fetch_assoc($votedQuery)['v'] ?? 0;

// ✅ TOTAL CANDIDATES
$candidateQuery = mysqli_query($conn, "SELECT COUNT(*) as c FROM addcandidate");
$candidates = mysqli_fetch_assoc($candidateQuery)['c'] ?? 0;

// ✅ TOTAL VOTES
$resVotes = mysqli_query($conn, "SELECT SUM(votes) as tv FROM addcandidate");
$rowVotes = mysqli_fetch_assoc($resVotes);
$totalVotes = ($rowVotes && $rowVotes['tv'] !== null) ? (int)$rowVotes['tv'] : 0;

// ✅ VOTING STATUS
$statusQuery = mysqli_query($conn, "SELECT status FROM voting_status WHERE id=1");
$statusRow = mysqli_fetch_assoc($statusQuery);
$status = ($statusRow) ? (int)$statusRow['status'] : 0;

// ✅ CANDIDATE LIST (🔥 FIXED WITH NAME)
$candidateList = [];
$result = mysqli_query($conn, "SELECT id, cname, votes FROM addcandidate");

while($row = mysqli_fetch_assoc($result)){
    $candidateList[] = [
        "id" => (int)$row['id'],
        "name" => $row['cname'],   // ✅ FIX ADDED
        "votes" => (int)$row['votes']
    ];
}

// ✅ FINAL JSON RESPONSE
echo json_encode([
    "total" => (int)$total,
    "voted" => (int)$voted,
    "candidates" => (int)$candidates,
    "totalVotes" => $totalVotes,
    "candidateList" => $candidateList,
    "status" => $status
]);
?>