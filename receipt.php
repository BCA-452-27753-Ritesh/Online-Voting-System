<?php
session_start();
$conn = mysqli_connect("localhost","root","","voterdatabase");

require_once('tcpdf/tcpdf.php');

// 🔐 Session check
if(!isset($_SESSION['mobile'])){
    die("Session expired ❌ Please login again");
}

$mobile = $_SESSION['mobile'];

// 🔍 Voter data
$query = mysqli_query($conn, "SELECT * FROM voterregistration WHERE mobile='$mobile'");
$data = mysqli_fetch_assoc($query);

$name = $data['name'] ?? "N/A";
$mobile = $data['mobile'] ?? "N/A";
$vote_id = $data['vote'] ?? 0;

// 🔍 Candidate data
$candidate_name = "Not Voted";
$candidate_party = "-";
$candidate_photo = "";
$candidate_symbol = "";

if($vote_id){
    $cquery = mysqli_query($conn, "SELECT * FROM addcandidate WHERE id='$vote_id'");
    $cdata = mysqli_fetch_assoc($cquery);

    if($cdata){
        $candidate_name = $cdata['cname'];
        $candidate_party = $cdata['cparty'];
        $candidate_photo = $cdata['photo'];
        $candidate_symbol = $cdata['symbol'];
    }
}

$date = date("d-m-Y H:i");
$txn = "TXN".rand(100000,999999);

// 🧾 PDF
$pdf = new TCPDF();
$pdf->SetMargins(10, 10, 10);
$pdf->AddPage();


// 🎨 HEADER BOX
$pdf->SetFillColor(0, 150, 255);
$pdf->SetTextColor(255,255,255);
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 12, 'ONLINE VOTING RECEIPT', 0, 1, 'C', 1);

$pdf->Ln(5);

// RESET TEXT COLOR
$pdf->SetTextColor(0,0,0);
$pdf->SetFont('helvetica', '', 12);

// 🎨 TABLE
$pdf->SetFillColor(240,248,255);

$pdf->Cell(50,10,'Name',1,0,'L',1);
$pdf->Cell(130,10,$name,1,1);

$pdf->Cell(50,10,'Mobile',1,0,'L',1);
$pdf->Cell(130,10,$mobile,1,1);

$pdf->Cell(50,10,'Voted For',1,0,'L',1);
$pdf->SetTextColor(0,102,204);
$pdf->Cell(130,10,$candidate_name,1,1);
$pdf->SetTextColor(0,0,0);

$pdf->Cell(50,10,'Party',1,0,'L',1);
$pdf->SetTextColor(153,0,153);
$pdf->Cell(130,10,$candidate_party,1,1);
$pdf->SetTextColor(0,0,0);

$pdf->Cell(50,10,'Date',1,0,'L',1);
$pdf->Cell(130,10,$date,1,1);

$pdf->Cell(50,10,'Transaction ID',1,0,'L',1);
$pdf->SetTextColor(255,87,34);
$pdf->Cell(130,10,$txn,1,1);
$pdf->SetTextColor(0,0,0);

$pdf->Cell(50,10,'Status',1,0,'L',1);
$pdf->SetTextColor(0,150,0);
$pdf->Cell(130,10,' Vote Submitted Successfully',1,1);
$pdf->SetTextColor(0,0,0);

// 🖼️ IMAGES
if($candidate_photo){
    $pdf->Image($candidate_photo, 20, 120, 40, 40);
}

if($candidate_symbol){
    $pdf->Image($candidate_symbol, 80, 120, 30, 30);
}

// 🔥 FOOTER
$pdf->Ln(60);
$pdf->SetTextColor(100,100,100);
$pdf->Cell(0,10,'Thank you for voting 🙏',0,1,'C');

// 📥 DOWNLOAD
$pdf->Output('Voting_Receipt.pdf', 'D');
?>