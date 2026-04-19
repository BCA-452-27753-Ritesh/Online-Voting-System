<?php
ob_start();

// DB Connection
$conn = mysqli_connect("localhost","root","","voterdatabase");
if(!$conn){
    die("Connection Failed: " . mysqli_connect_error());
}

require_once(__DIR__ . '/tcpdf/tcpdf.php');

$pdf = new TCPDF();
$pdf->SetAutoPageBreak(TRUE, 10);
$pdf->AddPage();


// =====================
// 🎨 BACKGROUND
// =====================
$pdf->SetFillColor(240,248,255);
$pdf->Rect(0, 0, $pdf->getPageWidth(), $pdf->getPageHeight(), 'F');


// =====================
// 🔷 TITLE
// =====================
$pdf->SetFont('helvetica', 'B', 18);
$pdf->SetTextColor(0,102,204);
$pdf->Cell(0, 12, 'ONLINE VOTING REPORT', 0, 1, 'C');

$pdf->SetFont('helvetica', '', 10);
$pdf->SetTextColor(100,100,100);
$pdf->Cell(0, 8, 'Generated on: '.date("d-m-Y H:i"), 0, 1, 'C');

$pdf->Ln(5);


// =====================
// 🔹 SECTION FUNCTION
// =====================
function sectionTitle($pdf, $title){
    $pdf->SetFillColor(0,102,204);
    $pdf->SetTextColor(255,255,255);
    $pdf->SetFont('helvetica','B',12);
    $pdf->Cell(0,10,$title,0,1,'L',true);

    $pdf->SetTextColor(0,0,0); // reset
    $pdf->Ln(2);
}


// =====================
// 🟢 1. VOTED VOTERS
// =====================
sectionTitle($pdf, "Voted Voters");

$pdf->SetFont('helvetica','B',10);
$pdf->SetFillColor(200,220,255);
$pdf->SetTextColor(0,0,0);

$pdf->Cell(45,10,'Name',1,0,'C',true);
$pdf->Cell(45,10,'Mobile',1,0,'C',true);
$pdf->Cell(45,10,'Candidate',1,0,'C',true);
$pdf->Cell(45,10,'Party',1,1,'C',true);

$sql1 = "
SELECT v.name, v.mobile,
       c.cname AS candidate_name,
       c.cparty AS party_name
FROM votes vt
JOIN voterregistration v ON vt.voter_id = v.id
JOIN addcandidate c ON vt.candidate_id = c.id
";

$q1 = mysqli_query($conn, $sql1);
if(!$q1){
    die(mysqli_error($conn));
}

$pdf->SetFont('helvetica','',9);
$fill = false;

while($row = mysqli_fetch_assoc($q1)){
    $pdf->SetFillColor(245,245,245);

    $pdf->Cell(45,10,$row['name'],1,0,'L',$fill);
    $pdf->Cell(45,10,$row['mobile'],1,0,'L',$fill);
    $pdf->Cell(45,10,$row['candidate_name'],1,0,'L',$fill);
    $pdf->Cell(45,10,$row['party_name'],1,1,'L',$fill);

    $fill = !$fill;
}


// =====================
// 🟡 2. TOTAL CANDIDATES + RESULT
// =====================
$pdf->Ln(5);
sectionTitle($pdf, "Total Candidates (Results)");

$pdf->SetFont('helvetica','B',10);
$pdf->SetFillColor(200,220,255);

$pdf->Cell(60,10,'Candidate Name',1,0,'C',true);
$pdf->Cell(60,10,'Party',1,0,'C',true);
$pdf->Cell(30,10,'Votes',1,0,'C',true);
$pdf->Cell(30,10,'Result',1,1,'C',true);

// Query
$sql2 = "
SELECT c.id, c.cname, c.cparty, COUNT(vt.id) AS total_votes
FROM addcandidate c
LEFT JOIN votes vt ON c.id = vt.candidate_id
GROUP BY c.id
ORDER BY total_votes DESC
";

$q2 = mysqli_query($conn, $sql2);

if(!$q2){
    die(mysqli_error($conn));
}

// Find winner
$maxVotes = 0;
$data = [];

while($row = mysqli_fetch_assoc($q2)){
    if($row['total_votes'] > $maxVotes){
        $maxVotes = $row['total_votes'];
    }
    $data[] = $row;
}

$pdf->SetFont('helvetica','',9);

if(count($data) == 0){
    $pdf->Cell(180,10,'No Candidates Found',1,1,'C');
}else{
    foreach($data as $row){

        if($row['total_votes'] == $maxVotes && $maxVotes > 0){
            $pdf->SetFillColor(144,238,144); // green
            $result = "Winner ";
        }else{
            $pdf->SetFillColor(245,245,245);
            $result = "-";
        }

        $pdf->Cell(60,10,$row['cname'],1,0,'L',true);
        $pdf->Cell(60,10,$row['cparty'],1,0,'L',true);
        $pdf->Cell(30,10,$row['total_votes'],1,0,'C',true);
        $pdf->Cell(30,10,$result,1,1,'C',true);
    }
}


// =====================
// 🔵 3. NOT VOTED VOTERS
// =====================
$pdf->Ln(5);
sectionTitle($pdf, "Not Voted Voters");

$pdf->SetFont('helvetica','B',10);
$pdf->SetFillColor(200,220,255);

$pdf->Cell(90,10,'Name',1,0,'C',true);
$pdf->Cell(90,10,'Mobile',1,1,'C',true);

$sql3 = "
SELECT v.name, v.mobile
FROM voterregistration v
LEFT JOIN votes vt ON v.id = vt.voter_id
WHERE vt.voter_id IS NULL
";

$q3 = mysqli_query($conn, $sql3);

if(!$q3){
    die(mysqli_error($conn));
}

$pdf->SetFont('helvetica','',9);

if(mysqli_num_rows($q3) == 0){
    $pdf->Cell(180,10,'All voters have voted',1,1,'C');
}else{
    $fill = false;
    while($row = mysqli_fetch_assoc($q3)){
        $pdf->SetFillColor(245,245,245);

        $pdf->Cell(90,10,$row['name'],1,0,'L',$fill);
        $pdf->Cell(90,10,$row['mobile'],1,1,'L',$fill);

        $fill = !$fill;
    }
}


// =====================
// FOOTER
// =====================
$pdf->Ln(10);
$pdf->SetFont('helvetica','I',9);
$pdf->SetTextColor(120,120,120);
$pdf->Cell(0,10,'© Online Voting System Report',0,1,'C');


// =====================
// OUTPUT
// =====================
ob_end_clean();
$pdf->Output('voting_report.pdf','I');
exit;
?>