<?php
session_start();

// 🔴 BLOCK DIRECT ACCESS
if (!isset($_SESSION['voterdata']) || empty($_SESSION['voterdata'])) {
    header("Location: ../Voter login Form/login.html");
    exit();
}

$voterdata = $_SESSION['voterdata'];
$conn = mysqli_connect('localhost', 'root', '', 'voterdatabase');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$statusQuery = mysqli_query($conn, "SELECT status FROM voting_status WHERE id=1");
$statusRow = mysqli_fetch_assoc($statusQuery);

$votingClosed = ($statusRow['status'] == 0);

// 🔹 Total candidates
$totalCandidatesQuery = "SELECT COUNT(*) as total FROM addcandidate";
$totalResult = mysqli_query($conn, $totalCandidatesQuery);
$totalRow = mysqli_fetch_assoc($totalResult);
$totalCandidates = $totalRow['total'] ?? 0;

// 🔹 Search
$search = "";
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $query = "SELECT * FROM addcandidate WHERE cname LIKE '%$search%' OR cparty LIKE '%$search%'";
} else {
    $query = "SELECT * FROM addcandidate";
}

$result = mysqli_query($conn, $query);

// 🔹 Safe status check
$status = (isset($voterdata['status']) && $voterdata['status'] == 0) 
    ? '<b style="color:green;">Not Voted</b>' 
    : '<b style="color:red;">Voted</b>';
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    


                        <style>
                            
                                .nav-item a{
                                    color: whitesmoke;
                                }
                                .nav-item a:hover{
                                    color: whitesmoke;
                                    background: yellowgreen;
                                    border-radius: 7px;
                                }
                                        #main-sec{
                                            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.9);
                                        }
                    #main-bec{
                      box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.9);
                    }
                    .navbar {
                                                    padding-top: 6px;
                                                    padding-bottom: 6px;
                                                }

                                                .navbar-brand {
                                                    font-size: 18px;
                                                    font-weight: bold;
                                                }

                                                .navbar .nav-link {
                                                    font-size: 14px;
                                                    padding: 6px 10px;
                                                }
                                                .search-btn {
                                                  
                                                    color: white;
                                                    border: 1px solid darkslategray;
                                                    transition: background-color 0.5s, color 0.5s;

                                                }

                                                .search-btn:active {
                                                    background-color: yellowgreen;
                                                    border-color: yellowgreen;
                                                    color: floralwhite;
                                                }
                                                
                                                
                                                .total-btn {
                                                  
                                                    color: white;
                                                    border: 1px solid darkslategray;
                                                    transition: background-color 0.2s;
                                                }

                                                .total-btn:hover {
                                                    background-color: darkgreen;
                                                    border-color: darkgreen;
                                                }

.admin-btn {
    background: linear-gradient(45deg, #00c6ff, #0072ff);
    color: white;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: bold;
    text-decoration: none;
    transition: 0.3s;
    box-shadow: 0 2px 8px rgba(0,0,0,0.3);
}

.admin-btn:hover {
    background: linear-gradient(45deg, #ff7e5f, #feb47b);
    color: white;
    transform: scale(1.05);
}
/* MAIN CARD */
.pro-unique-card {
    border: none;
    border-radius: 18px;
    overflow: hidden;
    background: #ffffff;
    box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    transition: 0.3s;
}

.pro-unique-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 35px rgba(0,0,0,0.3);
}

/* HEADER */
.pro-header {
    background: linear-gradient(45deg, #ff6a00, #ee0979);
    color: white;
    text-align: center;
    padding: 12px;
    font-weight: 600;
    letter-spacing: 1px;
}

/* BODY */
.pro-body {
    padding: 20px;
    background: #f9fbfd;
}

/* PROFILE */
.profile-img {
    width: 85px;
    height: 85px;
    border-radius: 50%;
    border: 4px solid white;
    object-fit: cover;
    box-shadow: 0 4px 10px rgba(0,0,0,0.25);
}

.name {
    font-weight: 600;
    color: #333;
}

/* INFO BOX */
.info-box {
    background: white;
    border-radius: 12px;
    padding: 12px;
    box-shadow: inset 0 2px 6px rgba(0,0,0,0.05);
}

.info-item {
    display: flex;
    justify-content: space-between;
    font-size: 14px;
    margin-bottom: 6px;
}

/* STATUS */
.status-box {
    text-align: center;
    padding: 10px;
    border-radius: 10px;
    background: linear-gradient(45deg, #36d1dc, #5b86e5);
    color: white;
    font-weight: bold;
}

/* NOTE */
.note {
    text-align: center;
    font-size: 12px;
    color: #777;
    margin-top: 10px;
}/* TABLE CONTAINER */
#main-sec {
    background-color: #f8f9fa;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.25);
    transition: 0.3s;
}

/* HOVER SHADOW */
#main-sec:hover {
    box-shadow: 0 15px 40px rgba(0,0,0,0.35);
}

/* HEADER */
#main-sec thead {
    background: linear-gradient(45deg, #f5deb3, #ffe4b5);
}

#main-sec th {
    font-weight: 600;
    font-size: 15px;
}

/* ROW HOVER */
#main-sec tbody tr {
    transition: 0.2s;
}

#main-sec tbody tr:hover {
    background-color: #eef6ff;
    transform: scale(1.01);
}

/* TEXT COLORS */
.cname {
    color: indigo;
    font-weight: bold;
}

.cparty {
    color: mediumseagreen;
}

.cvotes {
    color: #00008B;
    font-weight: bold;
}

/* BUTTON */
#main-sec .btn-danger {
    border-radius: 6px;
    box-shadow: 0 4px 10px rgba(255,0,0,0.3);
    transition: 0.3s;
}

#main-sec .btn-danger:hover {
    transform: scale(1.05);
}

/* IMAGES */
.symbol-img {
    width: 60%;
    border-radius: 15%;
    transition: 0.3s;
}

.candidate-img {
    width: 70%;
    border-radius: 10px;
    transition: 0.3s;
}

/* IMAGE HOVER */
.symbol-img:hover,
.candidate-img:hover {
    transform: scale(1.05);
}
body {
    background: slategray;
    color: #ecf0f1;
}

                            </style>


</head>
<body>
    <?php if($votingClosed): ?>
<div class="container mt-3">
    <div class="alert alert-danger text-center">
        ❌ Voting is Closed by Admin
    </div>
</div>
<?php endif; ?>


            
    <div class="text-center py-2" style="
    background: linear-gradient(90deg, #0f2027, #203a43, #2c5364);
    backdrop-filter: blur(8px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.7);
    position: sticky;
    top: 0;
    z-index: 999;
">

    <h1 class="m-0 fw-bold" style="
        color:#ffffff;
        font-size:52px;   /* 🔥 bigger like before */
        letter-spacing:1px;
        text-shadow: 0 0 10px #00d4ff, 0 0 20px #00d4ff;
        animation: fadeIn 2s ease-in-out;
    ">
        <i class="fa-solid fa-check-to-slot" style="
            color:#00e5ff;
            text-shadow: 0 0 12px #00e5ff, 0 0 25px #00e5ff;
        "></i> 
        Online Voting System
    </h1>

   

</div>

<!-- 🔥 ANIMATION -->
<style>
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

               <nav class="navbar navbar-dark bg-dark shadow">
  <div class="container-fluid">

    <!-- BRAND -->
    <a class="navbar-brand fw-bold">
      <i class="fa fa-globe text-info"></i> Online Voting System
    </a>

    <ul class="nav justify-content-center align-items-center">

      <!-- HOME -->
      <li class="nav-item">
        <a class="nav-link text-light" href="#">
          <i class="fa fa-home"></i> Home
        </a>
      </li>

      <!-- SEARCH -->
      <li class="nav-item">
        <form class="d-flex align-items-center" method="GET">

          <input class="form-control form-control-sm me-2 rounded-pill"
                 type="search" name="search"
                 placeholder="Search Candidate"
                 style="width:170px;">

          <!-- SEARCH BUTTON -->
          <button class="btn btn-primary btn-sm rounded-pill me-2 shadow-sm">
            🔍 Search
          </button>

          <!-- TOTAL BUTTON -->
          <a href="#" onclick="scrollToCandidates()" 
   class="btn btn-success btn-sm rounded-pill shadow-sm">
            Total Candidate: <strong><?php echo $totalCandidates; ?></strong>
          </a>

        </form>
      </li>

      <!-- CONTACT -->
      <li class="nav-item">
        <a class="nav-link text-light" target="_blank"
           href="https://wa.me/916206972092?text=Hello">
          <i class="fa fa-whatsapp text-success"></i> Contact
        </a>
      </li>

      <!-- LOGOUT -->
      <li class="nav-item">
        <a class="nav-link text-light" href="./logout.php">
          <i class="fa fa-sign-out text-danger"></i> Logout
        </a>
      </li>

    </ul>

    <!-- ADMIN BUTTON -->
    
                      <a href="Admin Login/adminlogin.html" class="admin-btn">
                                        <i class="fa fa-user"></i> Admin Login
                                    </a>
                      </div>
                    </nav>



    <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
              <div class="carousel-indicators">
              </div>
              <div class="carousel-inner">
                <div class="carousel-item active">
                  <img src="Image/bg6.jpg" class="d-block w-100" height="350px" alt="...">
                  <div class="carousel-caption d-md-block">
                    <h1>WellCome to the Online Voting System</h1>
                    <p>An online voting system offers efficiency, speed, and convenience, but it must be designed with strong security and transparency to ensure trust and reliability.</p>
                  </div>
                </div>
              </div>
            </div>
            <br><br><br>

            <div class="container-fluid">
                <div class="row">
                        
                        <div class="col-sm-4">
    <div class="card pro-unique-card">

        <!-- Top Gradient Header -->
        <div class="pro-header">
            🗳️ Voter Panel
        </div>

        <div class="pro-body">

            <!-- Profile Section -->
            <div class="profile-section text-center">
                <img src="../VoterImg/<?php echo $voterdata['photo'] ?>" class="profile-img">

                <h5 class="mt-2 name">
                    <?php echo $voterdata['name'] ?>
                </h5>

                <small class="text-muted">
                    ID: <?php echo $voterdata['adhar'] ?>
                </small>
            </div>

            <!-- Info Box -->
            <div class="info-box mt-3">

                <div class="info-item">
                    <span>📱 Mobile</span>
                    <span><?php echo $voterdata['mobile'] ?></span>
                </div>

                <div class="info-item">
                    <span>🆔 Aadhar</span>
                    <span><?php echo $voterdata['adhar'] ?></span>
                </div>

            </div>

            <!-- Status -->
            <div class="status-box mt-3">
                <?php echo $status ?>
            </div>

            <!-- Footer Note -->
            <div class="note">
                You can vote only once
                <?php if ($_SESSION['voterdata']['status'] == 1) { ?>
    
    <div class="text-center mt-3">
        <a href="../receipt.php" class="btn btn-success w-100">
            📥 Download your Receipt
        </a>
    </div>

<?php } ?>
            </div>

        </div>
    </div>
</div>
                                                <div class="col-sm-8">
    <div class="table-responsive" id="candidateSection">
        <table class="table table-bordered table-hover text-center" id="main-sec">

            <thead>
                <tr>
                    <th style="width:50%;">Candidate Detail</th>
                    <th style="width:25%;">Symbol</th>
                    <th style="width:25%;">Photo</th>
                </tr>
            </thead>

            <tbody>

            <?php while ($row = mysqli_fetch_assoc($result)) { ?>

            <tr>

                <!-- DETAILS -->
                <td class="text-start">

                    <ul class="mb-2">
                        <li><b>Candidate Name:</b> 
                            <span class="cname"><?php echo $row['cname']; ?></span>
                        </li>

                        <li><b>Party Name:</b> 
                            <span class="cparty"><?php echo $row['cparty']; ?></span>
                        </li>

                        <li><b>Total Votes:</b> 
                            <span class="cvotes"><?php echo $row['votes']; ?></span>
                        </li>
                    </ul>

                 <form action="../Dashboard/Admin Login/vote.php" method="post">
                        <input type="hidden" name="gid" value="<?php echo $row['id']; ?>">
                        <input type="hidden" name="voter_id" value="<?php echo $_SESSION['voterdata']['id']; ?>">

                        <?php if ($_SESSION['voterdata']['status'] == 0 && !$votingClosed) { ?>
                            <button type="submit" class="btn btn-danger btn-sm w-100">Vote</button>
                        <?php } else { ?>
                            <button disabled class="btn btn-secondary btn-sm w-100">Voted</button>
                        <?php } ?>
                    </form>

                </td>

                <!-- SYMBOL -->
                <td>
                    <img src="Admin Login/Image/<?php echo $row['symbol'] ?>" class="symbol-img">
                </td>

                <!-- PHOTO -->
                <td>
                    <img src="Admin Login/Image/<?php echo $row['photo'] ?>" class="candidate-img">
                </td>

            </tr>

            <?php } ?>

            </tbody>
        </table>
    </div>
</div>



<script>
document.addEventListener("DOMContentLoaded", function () {

    setInterval(() => {
        fetch('./Admin Login/fetch_data.php')
        .then(res => res.json())
        .then(data => {

            console.log(data);

            // 🔴 IF CLOSED → LOGOUT
            if (data.status == 0) {
                window.location.href = "./logout.php";
            }

            // 🔥 UPDATE VOTES LIVE (MAIN FIX)
            document.querySelectorAll("table tbody tr").forEach((row, index) => {
                let voteText = row.querySelector("li:nth-child(3) span span");
                if (voteText && data.candidateList[index]) {
                    voteText.innerText = data.candidateList[index].votes;
                }
            });

        })
        .catch(err => console.error(err));

    }, 1000);

});

document.addEventListener("DOMContentLoaded", function () {

    const url = new URL(window.location.href);
    const hasSearch = url.searchParams.has("search");

    if (hasSearch) {

        // Optional: show small message in console
        console.log("Search active - will reset in 5 sec");

        setTimeout(() => {

            // Remove search
            url.searchParams.delete("search");

            // Show total candidates
            url.searchParams.set("show", "all");

            // Redirect
            window.location.href = url.toString();

        }, 5000); // 5 sec
    }

});
function scrollToCandidates() {
    const section = document.getElementById("candidateSection");

    if (section) {
        section.scrollIntoView({
            behavior: "smooth"
        });
    }
}

</script>



</body>
</html>