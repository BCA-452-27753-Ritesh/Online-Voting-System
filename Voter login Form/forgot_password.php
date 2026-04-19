<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #1d2671, #c33764);
            height: 100vh;
            display: flex;              /* 🔥 center trick */
            justify-content: center;    /* horizontal center */
            align-items: center;        /* vertical center */
        }

        .box {
            width: 900px;               /* 🔥 bigger size */
            background: white;
            padding: 100px;
            border-radius: 15px;
            box-shadow: 0px 12px 30px rgba(0,0,0,0.25);
        }

        .form-control {
            height: 45px;
            font-size: 16px;
        }

        .btn {
            padding: 10px;
            font-size: 16px;
        }
    </style>
</head>
<body>

<div class="box">
    <h3 class="text-center mb-4">🔐 Forgot Password</h3>

    <form action="send_otp.php" method="POST">
        
        <div class="mb-3">
            <label>Mobile Number</label>
            <input type="text" name="mobile" class="form-control" placeholder="Enter mobile number" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Send OTP</button>

        <div class="text-center mt-3">
            <a href="login.html">Back to Login</a>
        </div>

    </form>
</div>

</body>
</html>