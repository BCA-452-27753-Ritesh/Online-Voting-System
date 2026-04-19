<?php
$conn = mysqli_connect("localhost","root","","voterdatabase");

$plan = isset($_GET['plan']) ? $_GET['plan'] : 1;
$message = "";
$link = "";

if(isset($_POST['pay'])){

    $mobile = $_POST['mobile'];

    // check mobile exist
    $check = mysqli_query($conn, "SELECT * FROM voterregistration WHERE mobile='$mobile'");
    if(mysqli_num_rows($check) == 0){
        $message = "❌ Mobile not registered";
    } else {

        // generate token
        $token = md5(time().rand());

        // expiry
        $expiry = date("Y-m-d H:i:s", strtotime("+$plan days"));

        // update DB
        mysqli_query($conn, "UPDATE voterregistration SET 
            payment_status='paid',
            token='$token',
            plan_days='$plan',
            expiry_date='$expiry',
            is_used=0
        WHERE mobile='$mobile'");

        // link
        $link = "http://localhost/Online%20Voting%20System/index.html?token=$token";
        $message = "✅ Payment Successful";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Secure Payment</title>

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: white;
        }

        .card {
            background: rgba(255,255,255,0.05);
            padding: 30px;
            border-radius: 15px;
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.5);
            width: 350px;
            text-align: center;
        }

        h2 {
            margin-bottom: 10px;
        }

        .plan {
            margin-bottom: 20px;
            color: #ffd369;
            font-weight: bold;
        }

        input {
            width: 90%;
            padding: 10px;
            border: none;
            border-radius: 8px;
            margin: 10px 0;
            outline: none;
        }

        button {
            padding: 10px 20px;
            border: none;
            border-radius: 25px;
            background: linear-gradient(45deg,#ff7e5f,#ff3f6c);
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            transform: scale(1.05);
        }

        .success {
            margin-top: 15px;
            color: #00ffcc;
        }

        a {
            color: #00e6ff;
            word-break: break-all;
        }
    </style>
</head>

<body>

<div class="card">

    <h2>💳 Secure Payment</h2>
    <div class="plan">Plan: <?php echo $plan; ?> Day(s)</div>

    <form method="POST">
        <input type="text" name="mobile" placeholder="Enter Mobile Number" required>
        <br>
        <button name="pay">Pay Now</button>
    </form>

    <?php if($message != ""){ ?>
        <div class="success">
            <p><?php echo $message; ?></p>

            <?php if($link != ""){ ?>
                <p><b>Your Access Link:</b></p>
                <a href="<?php echo $link; ?>"><?php echo $link; ?></a>
            <?php } ?>

        </div>
    <?php } ?>

</div>

</body>
</html>