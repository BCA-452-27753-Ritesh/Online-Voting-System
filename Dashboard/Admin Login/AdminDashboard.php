
<?php 
session_start();

// 🔒 BLOCK DIRECT URL ACCESS
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: /Online Voting System/Dashboard/dashboard.php");
    exit();
}

// 🔹 Database Connection
$conn = mysqli_connect('localhost', 'root', '', 'voterdatabase');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// ✅ VOTER STATS
$total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM voterregistration"))['t'];

$voted = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as v FROM voterregistration WHERE status=1"))['v'];

$notVoted = $total - $voted;

// 🔹 Get Voting Status (ONLY ONCE ✅)
$statusQuery = mysqli_query($conn, "SELECT status FROM voting_status WHERE id=1");
$statusRow = mysqli_fetch_assoc($statusQuery);
$status = $statusRow['status'];

// 🔹 Fetch candidates
$result = mysqli_query($conn, "SELECT * FROM addcandidate");

$names = [];
$votes = [];

$result2 = mysqli_query($conn, "SELECT cname, votes FROM addcandidate");
while($row2 = mysqli_fetch_assoc($result2)){
    $names[] = $row2['cname'];
    $votes[] = $row2['votes'];
}

// 🔹 Total votes
$totalVotesQuery = mysqli_query($conn, "SELECT SUM(votes) as total FROM addcandidate");
$totalVotesRow = mysqli_fetch_assoc($totalVotesQuery);

// 🔹 Winner logic
$maxVotes = 0;
$winners = [];

while ($row_check = mysqli_fetch_assoc($result)) {
    if ($row_check['votes'] > $maxVotes) {
        $maxVotes = $row_check['votes'];
        $winners = [$row_check['id']];
    } elseif ($row_check['votes'] == $maxVotes) {
        $winners[] = $row_check['id'];
    }
}

// Reset pointer
mysqli_data_seek($result, 0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">


   
    <!-- 🔹 css block for styleing -->

    <style>
        .download-btn:hover {
    transform: scale(1.05);
    background: linear-gradient(45deg,#00c851,#28a745);
}
        .admin-icon { fill: floralwhite; }

        .nav-item a {
            font-family: 'Segoe UI', sans-serif;
            color: #004080; /* Dark Navy */
            transition: 0.3s;
        }
        .nav-item a:hover {
            background: #ff6f61;
            color: floralwhite;
            border-radius: 7px;
        }

        #header h1 {
            font-family: 'Segoe UI', sans-serif;
            color: #ffffff;
        }

        #AddCandidate {
            box-shadow: 2px 2px 15px rgba(0,0,0,0.2);
            padding: 40px;
            border-radius: 10px;
            background-color: #f0f8ff; /* Light Blue */
        }
        #AddCandidate h2 span {
            background: #004080; /* Dark Navy */
            color: #ffffff;
            padding: 10px;
            border-radius: 10px;
        }

        #Total h2 span {
            background-color: #ff6f61; /* Coral */
            color: light;
            padding: 10px;
            border-radius: 10px;
        }

        table {
            background-color: #e6e6e6; /* Light Gray */
        }
        table th {
            background-color: #004080; /* Dark Navy */
            color: #ffffff;
        }
        table td img { border-radius: 5px; }

        .nav-link.logout:hover { color: black; }

        .navbar .nav-link.text-info { color: lightgray !important; }
        .navbar .nav-link.text-info:hover {
            color: #FFFAF0 !important;
            background: yellowgreen;
            border-radius: 7px;
        }

        .circle-img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }

       
         /* Winner badge unique style */
        .winner-badge {
            display: inline-block;
            padding: 8px 15px;
            border-radius: 12px;
            font-weight: bold;
            font-size: 1rem;
            color: #fff;
            background: linear-gradient(45deg, #FFD700, #FFA500);
            text-shadow: 0 0 5px #fff, 0 0 10px #ffeb3b;
            box-shadow: 0 0 15px #FFD700, 0 0 25px #FFA500;
            animation: winner-glow 1.5s infinite alternate;
        }

      
            .winner-glow {
                    background: linear-gradient(90deg, #fff3cd, #ffe066, #ffd700) !important;
                    animation: winnerGlow 1.5s infinite alternate;
                    box-shadow: 0 0 20px rgba(255, 215, 0, 0.8);
                }

                @keyframes winnerGlow {
                    from { box-shadow: 0 0 10px #ffd700; }
                    to { box-shadow: 0 0 25px #ffcc00; }
                }

                .tie-glow {
                    background: linear-gradient(90deg, #d4edda, #28a745, #1e7e34) !important;
                    animation: tieGlow 1.5s infinite alternate;
                    color: white;
                }

                @keyframes tieGlow {
                    from { box-shadow: 0 0 10px #28a745; }
                    to { box-shadow: 0 0 25px #00ff88; }
                }
          
           
                     /* Voter Table Styling */
            .table-striped > tbody > tr:nth-of-type(odd) {
                background-color: #f8f9fa;
            }
            .table-bordered th, .table-bordered td {
                vertical-align: middle;
                text-align: center;
            }
            form button {
                    border-radius: 25px;
                    font-weight: bold;
                    transition: 0.3s;
                }

                form button:hover {
                    transform: scale(1.05);
                }
                table tbody tr:hover {
                    background: #f1f9ff;
                }

                table td, table th {
                    vertical-align: middle !important;
                }

                .badge {
                    font-size: 0.9rem;
                    border-radius: 10px;
                }

                .btn-outline-danger:hover {
                    background: red;
                    color: white;
                }
                .custom-input {
                    border-radius: 10px;
                    padding: 10px;
                    transition: 0.3s;
                }

                .custom-input:focus {
                    border-color: #007bff;
                    box-shadow: 0 0 10px rgba(0,123,255,0.5);
                }

                .btn-gradient {
                    background: linear-gradient(45deg,#28a745,#00c851);
                    color: white;
                    font-weight: bold;
                    border-radius: 25px;
                    transition: 0.3s;
                }

                .btn-gradient:hover {
                    transform: scale(1.05);
                    background: linear-gradient(45deg,#00c851,#28a745);
                }

                .preview-img {
                    display:none;
                    max-height:120px;
                    border-radius:10px;
                    border:2px solid #007bff;
                }
             body {
    background: #f4f7fb;
}
                    body {
                        background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
                        font-family: 'Poppins', sans-serif;
                    }

                    /* FULL WIDTH SLIM HEADER */
                    .voting-header {
                        width: 100%;
                        margin: 0;
                        padding: 12px 20px;   /* 🔥 height kam kar diya */
                        border-radius: 0;     /* full width feel */

                        background: rgba(255, 255, 255, 0.05);
                        backdrop-filter: blur(10px);

                        box-shadow: 0 5px 20px rgba(0,0,0,0.3);
                        position: relative;
                        overflow: hidden;
                    }

                    /* glow border */
                    .voting-header::before {
                        content: "";
                        position: absolute;
                        inset: 0;
                        padding: 1.5px;
                        background: linear-gradient(120deg, #00f2fe, #4facfe, #00ff9d);
                        -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
                        -webkit-mask-composite: xor;
                    }

                    /* FLEX LAYOUT (horizontal) */
                    .header-inner {
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        gap: 12px;
                    }

                    /* ICON small */
                    .header-icon i {
                        font-size: 22px;   /* 🔽 size kam */
                        color: #00ffcc;
                    }

                    /* TITLE */
                    .header-title {
                        font-size: 60px;   /* 🔽 slim look */
                        font-weight: 600;
                        color: #ffffff;
                    }

                    /* SUBTITLE small */
                    .header-subtitle {
                        font-size: 12px;
                        color: #cccccc;
                    }
                    .card:hover {
                        transform: scale(1.05);
                        transition: 0.3s;
                    }
                    /* 🔥 Modern Card Base */
                    .modern-card {
                        border-radius: 20px;
                        backdrop-filter: blur(15px);
                        background: rgba(255,255,255,0.08);
                        color: white;
                        box-shadow: 0 10px 30px rgba(0,0,0,0.4);
                        transition: 0.4s;
                        position: relative;
                        overflow: hidden;
                    }

                    /* Glow effect */
                    .modern-card::before {
                        content: "";
                        position: absolute;
                        inset: 0;
                        padding: 2px;
                        background: linear-gradient(120deg,#00f2fe,#4facfe,#00ff9d);
                        -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
                        -webkit-mask-composite: xor;
                    }

                    /* Hover effect */
                    .modern-card:hover {
                        transform: translateY(-10px) scale(1.03);
                    }

                    /* Icons */
                    .icon-box i {
                        font-size: 28px;
                    }

                    /* Different colors */
                    .voters-card { border-left: 5px solid #00f2fe; }
                    .candidate-card { border-left: 5px solid #ff6f61; }
                    .vote-card { border-left: 5px solid #00ff9d; }

                    /* Text */
                    .modern-card h6 {
                        opacity: 0.8;
                        margin-bottom: 10px;
                    }

                    .modern-card h2 {
                        font-size: 32px;
                        font-weight: bold;
                    }

                    h3 {
                            transition: 0.3s;
                        }

    </style>
</head>
<body>

<!-- 🔹 Header -->

<div class="voting-header">
    <div class="header-inner">
        <div class="header-icon">
            <i class="fa-solid fa-check-to-slot"></i>
        </div>
        <h1 class="header-title">Online Voting System</h1>
        <p class="header-subtitle">Secure • Transparent • Digital Democracy</p>
    </div>
</div>


<!-- 🔹 Navbar -->

<nav class="navbar navbar-expand-lg" style="background-color:#0b1c2d;">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center gap-2 fw-bold" href="#">
            <img src="Image/8.jpg" width="60" class="circle-img admin-icon">
            <span style="color:#ffffff;">Admin Panel</span>
        </a>

        <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav gap-4 fw-bold">
                <li class="nav-item"><a class="nav-link text-info" href="#Header"><i class="fa-solid fa-house"></i> Home</a></li>
                <li class="nav-item"><a class="nav-link text-info" href="#AddCandidate"><i class="fa-solid fa-user-plus"></i> Add Candidate</a></li>
                <li class="nav-item"><a class="nav-link text-info" href="#Total"><i class="fa-solid fa-users"></i> Total Candidates</a></li>
                <li class="nav-item"><a class="nav-link text-info" href="../logout.php">
    <i class="fa-solid fa-right-from-bracket"></i> Logout
</a></li>
            </ul>
        </div>
    </div>
</nav>
<!-- 🔹 Modern Dashboard Cards -->
<div class="container mt-5">
    <div class="row g-4 text-center">

        <!-- Total Voters -->
       <div class="col-md-4 d-flex">
    <div class="card modern-card voters-card p-2 h-90 w-100 d-flex flex-column justify-content-between">
        <div>
            <div class="icon-box mb-2">
                <i class="fa-solid fa-users"></i>
            </div>

            <h6 class="mb-1">Total Voters</h6>
            <h3 class="mb-1" id="totalVoters"><?php echo $total; ?></h3>

            <p class="text-success mb-0 small">
                Voted: <span id="votedCount"><?php echo $voted; ?></span>
            </p>

            <p class="text-warning mb-1 small">
                Not Voted: <span id="notVotedCount"><?php echo $notVoted; ?></span>
            </p>
        </div>

        <div class="progress mt-1" style="height: 6px;">
            <div id="progressBar" class="progress-bar bg-success"
                 style="width: <?php echo ($total > 0) ? round(($voted/$total)*100) : 0; ?>%">
            </div>
        </div>
    </div>
</div>
      <!-- Total Candidates -->
<div class="col-md-4 d-flex">
    <div class="card modern-card candidate-card p-3 h-100 w-100 text-center">
        <div class="icon-box mb-2">
            <i class="fa-solid fa-user-tie"></i>
        </div>
        <h6>Total Candidates</h6>
        <h3 id="totalCandidates">
            <?php 
            $res = mysqli_query($conn, "SELECT COUNT(*) as total FROM addcandidate");
            $row = mysqli_fetch_assoc($res);
            echo $row['total'];
            ?>
        </h3>
    </div>
</div>

<!-- Total Votes -->
<div class="col-md-4 d-flex">
    <div class="card modern-card vote-card p-3 h-100 w-100 text-center">
        <div class="icon-box mb-2">
            <i class="fa-solid fa-vote-yea"></i>
        </div>
        <h6>Total Votes</h6>
        <h3 id="totalVotes"><?php echo $totalVotesRow['total'] ?? 0; ?></h3>
    </div>
</div>

<!-- 🔹 Total Votes  casting -->



<!-- 🔹 Carousel -->
<div id="Header" class="carousel slide position-relative" data-bs-ride="carousel">

    <!-- Background Image -->
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="Image/background28.webp" class="d-block w-100" height="500px" alt="Background">
        </div>
    </div>

    <!-- 🔥 Overlay -->
 <div style="
    position:absolute;
    top:0;
    left:0;
    width:100%;
    height:100%;
    display:flex;
    flex-direction:column;
    justify-content:flex-start;  /* 🔥 FIX */
    align-items:center;
    background: rgba(0,0,0,0.5);
    padding-top:30px;
    gap:20px;
">

        <!-- ✅ CHART FIRST (TOP) -->
      <div style="
    width:80%; 
    height:300px; 
    background: rgba(255,255,255,0.7); 
      backdrop-filter: blur(8px); /* ✅ 70% transparency */
    border-radius:15px;
    padding:15px;
    box-shadow:0 8px 25px rgba(0,0,0,0.3);
">
    <canvas id="voteChart" style="width:100%; height:100%;"></canvas>
</div>

        <!-- ✅ TEXT BELOW -->
        <div class="text-center">
            <h1 style="color:white;">Welcome to the Online Voting System</h1>
            <p style="color:white;">
                Efficiency, speed, and convenience, with strong security and transparency.
            </p>
        </div>

    </div>

</div>
<br><br>

<!-- 🔹 Add Candidate Form -->

<div class="container-fluid d-flex justify-content-center mt-5" id="AddCandidate">

    <div class="row w-75" style="
        background: rgba(255,255,255,0.9);
        backdrop-filter: blur(10px);
        border-radius:20px;
        box-shadow:0 10px 30px rgba(0,0,0,0.3);
        overflow:hidden;
    ">

        <!-- LEFT FORM -->
        <div class="col-md-7 p-4">

            <h2 class="text-center mb-4" style="
                background: linear-gradient(45deg,#007bff,#00c6ff);
                color:white;
                padding:10px;
                border-radius:10px;
            ">
                ➕ Add Candidate
            </h2>

            <form action="AddCandidate.php" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>

                <div class="mb-3">
                    <label class="form-label fw-bold">Candidate Name</label>
                    <input type="text" class="form-control custom-input" name="cname" placeholder="Enter name" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Party Name</label>
                    <input type="text" class="form-control custom-input" name="cparty" placeholder="Enter party" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Symbol</label>
                    <input type="file" class="form-control custom-input" id="symbol" name="symbol" accept="image/*" required>
                    <img id="symbolPreview" class="img-fluid mt-2 preview-img">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Photo</label>
                    <input type="file" class="form-control custom-input" id="photo" name="photo" accept="image/*" required>
                    <img id="photoPreview" class="img-fluid mt-2 preview-img">
                </div>

                <button type="submit" class="btn btn-gradient w-100 mt-3">
                    🚀 Submit Candidate
                </button>

            </form>
        </div>

        <!-- RIGHT IMAGE -->
        <div class="col-md-5 d-flex align-items-center justify-content-center p-3" style="
            background: linear-gradient(45deg,#0b1c2d,#004080);
        ">
            <img src="Image/header3.png" style="width:90%; border-radius:15px;">
        </div>

    </div>

</div>


<!-- 🔹 JS: Form Validation  -->

<script>
(function () {
    'use strict';
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
})();

function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => { preview.src = e.target.result; preview.style.display = 'block'; }
        reader.readAsDataURL(input.files[0]);
    }
}

document.getElementById('symbol').addEventListener('change', function() { previewImage(this, 'symbolPreview'); });
document.getElementById('photo').addEventListener('change', function() { previewImage(this, 'photoPreview'); });
</script>


<!-- 🔹 Total Candidates Table -->
<div class="container" id="Total">
    <div class="row">
        <div class="col-sm-20">
            <h2 class="text-center">
                <span style="color: brown; background-color: mediumorchid; color: floralwhite; padding: 10px; border-radius: 10px;">
                    Total List of Candidate
                </span>
            </h2>
            <br><br>

            <table class="table table-hover align-middle text-center shadow-lg" style="border-radius:15px; overflow:hidden;">

    <thead style="background: linear-gradient(45deg,#004080,#007bff); color:white;">
        <tr>
            <th>👤 Candidate</th>
            <th>🏛️ Party</th>
            <th>🖼️ Photo</th>
            <th>⚙️ Action</th>
            <th>📊 Votes</th>
            <th>📡 Status</th>
            <th>🏆 Result</th>
        </tr>
    </thead>

    <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>

            <tr class="<?php 
                if (in_array($row['id'], $winners)) {
                    echo (count($winners) > 1) ? 'tie-glow' : 'winner-glow';
                }
            ?>" style="transition:0.3s;"
            onmouseover="this.style.transform='scale(1.01)'"
            onmouseout="this.style.transform='scale(1)'">

            <td><strong><?php echo htmlspecialchars($row['cname']); ?></strong></td>

            <td>
                <span class="badge bg-info text-dark px-3 py-2">
                    <?php echo htmlspecialchars($row['cparty']); ?>
                </span>
            </td>

            <td>
                <img src="Image/<?php echo htmlspecialchars($row['photo']); ?>" 
                style="width:60px; height:60px; object-fit:cover; border-radius:50%;">
            </td>

            <td>
                <form action="delete_candidate.php" method="post">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <button class="btn btn-outline-danger btn-sm">🗑 Delete</button>
                </form>
            </td>

            <td>
                <span id="vote_<?php echo $row['id']; ?>" class="badge bg-success px-3 py-2">
                    <?php echo $row['votes']; ?>
                </span>
            </td>

           <td>
                <span class="badge px-3 py-2 tableStatus <?php echo ($status == 1) ? 'bg-success' : 'bg-danger'; ?>">
                    <?php echo ($status == 1) ? "🟢 Open" : "🔴 Closed"; ?>
                </span>
            </td>
            <td>
            <?php if (in_array($row['id'], $winners)): ?>
                <?php if (count($winners) > 1): ?>
                    <span class="badge bg-warning text-dark px-3 py-2">🤝 TIE</span>
                <?php else: ?>
                    <span class="winner-badge">👑 WINNER</span>
                <?php endif; ?>
            <?php else: ?>
                <span class="text-muted">—</span>
            <?php endif; ?>
            </td>

            </tr>

            <?php } ?>
            </tbody>
            </table>
        </div>
    </div>
</div><div class="text-center mt-4">
    
    <!-- 🔥 DOWNLOAD RECEIPT BUTTON -->
    <a href="/Online%20Voting%20System/generate_receipt.php" target="_blank" 
       class="btn btn-lg btn-success px-5 py-2 shadow"
       style="
            border-radius:30px;
            font-weight:bold;
            background: linear-gradient(45deg,#28a745,#00c851);
            border:none;
       ">
        📄 Download Voting Report
    </a>

</div>
<div class="container mt-4 d-flex justify-content-center">
    <div style="
        width:500px;
        background: rgba(255,255,255,0.9);
        border-radius:15px;
        padding:25px;
        box-shadow:0 8px 25px rgba(0,0,0,0.3);
    ">

        <!-- 🔥 HEADING -->
        <h3 class="text-center mb-4" style="font-weight:bold;">
            🎛️ Voting Control Button
        </h3>

        <!-- 🔥 FORM -->
     <form id="voteForm" class="d-flex justify-content-between align-items-center">

    <?php
    $statusQuery = mysqli_query($conn, "SELECT status FROM voting_status WHERE id=1");
    $statusRow = mysqli_fetch_assoc($statusQuery);
    $status = $statusRow['status'];
    ?>

    <!-- STATUS -->
    <span id="votingStatus" class="badge p-2 <?php echo ($status == 1) ? 'bg-success' : 'bg-danger'; ?>">
        <?php echo ($status == 1) ? "🟢 Voting Open" : "🔴 Voting Closed"; ?>
    </span>

    <!-- BUTTON -->
    <button type="button" id="toggleBtn"
        class="btn px-4 <?php echo ($status == 1) ? 'btn-danger' : 'btn-success'; ?>">
        <?php echo ($status == 1) ? "⛔ Stop" : "▶ Start"; ?>
    </button>

</form>

    </div>
</div>

<script>
let voteChart; // 🔥 global chart

document.addEventListener("DOMContentLoaded", function () {

    // 🔹 INIT CHART (ONLY ONCE)
    const canvas = document.getElementById("voteChart");

    if (!canvas) {
        console.error("Canvas not found");
        return;
    }

    const ctx = canvas.getContext("2d");

    voteChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($names); ?>,
            datasets: [{
                label: "Votes",
                data: <?php echo json_encode($votes); ?>,
                backgroundColor: [
                    '#ff6f61','#6a5acd','#20c997','#ffc107','#007bff'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // 🔹 TOGGLE BUTTON
    const toggleBtn = document.getElementById("toggleBtn");
    if (toggleBtn) {
        toggleBtn.addEventListener("click", function() {
            fetch("toggle_vote.php", { method: "POST" })
            .then(res => res.text())
            .then(() => console.log("Voting toggled"));
        });
    }

    // 🔹 LIVE UPDATE (EVERY 1 SEC)
    setInterval(() => {
        fetch('fetch_data.php')
        .then(res => res.json())
        .then(data => {

            console.log("LIVE DATA:", data);

            // ✅ Cards update
            document.getElementById("totalVoters").innerText = data.total;
            document.getElementById("votedCount").innerText = data.voted;
            document.getElementById("totalCandidates").innerText = data.candidates;
            document.getElementById("totalVotes").innerText = data.totalVotes;

            let notVoted = data.total - data.voted;
            document.getElementById("notVotedCount").innerText = notVoted;

            let percent = data.total > 0 ? (data.voted / data.total) * 100 : 0;
            document.getElementById("progressBar").style.width = percent + "%";

            // ✅ Table votes update
            data.candidateList.forEach(c => {
                let voteEl = document.getElementById("vote_" + c.id);
                if (voteEl) {
                    voteEl.innerText = c.votes;
                }
            });

            // 🔥 ✅ LIVE CHART UPDATE
            if (voteChart) {
                voteChart.data.labels = data.candidateList.map(c => c.name);
                voteChart.data.datasets[0].data = data.candidateList.map(c => c.votes);
                voteChart.update();
            }

            // ✅ Header status update
            let statusEl = document.getElementById("votingStatus");
            let btn = document.getElementById("toggleBtn");

            if (statusEl && btn) {
                if (data.status == 1) {
                    statusEl.innerText = "🟢 Voting Open";
                    statusEl.className = "badge bg-success p-2";

                    btn.innerText = "⛔ Stop";
                    btn.className = "btn btn-danger px-4";
                } else {
                    statusEl.innerText = "🔴 Voting Closed";
                    statusEl.className = "badge bg-danger p-2";

                    btn.innerText = "▶ Start";
                    btn.className = "btn btn-success px-4";
                }
            }

            // 🔥 ✅ FIX: UPDATE ALL TABLE STATUS (MAIN ISSUE SOLVED)
            document.querySelectorAll(".tableStatus").forEach(el => {
                if (data.status == 1) {
                    el.innerText = "🟢 Open";
                    el.className = "badge bg-success px-3 py-2 tableStatus";
                } else {
                    el.innerText = "🔴 Closed";
                    el.className = "badge bg-danger px-3 py-2 tableStatus";
                }
            });

        })
        .catch(err => console.error("Fetch error:", err));

    }, 1000);

});
</script>


</body>
</html>