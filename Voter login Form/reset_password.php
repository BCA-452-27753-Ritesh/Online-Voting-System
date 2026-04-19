<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #1d2671, #c33764);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .box {
            width: 450px;
            background: white;
            padding: 30px;
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
    <h3 class="text-center mb-4">🔑 Reset Password</h3>

    <form action="update_password.php" method="POST" onsubmit="return validatePassword()">

        <!-- ❌ Mobile removed (secure) -->

        <div class="mb-3">
            <label>New Password</label>
            <input type="password" id="new_pass" name="new_pass" class="form-control" placeholder="Enter new password" required>
        </div>

        <div class="mb-3">
            <label>Confirm Password</label>
            <input type="password" id="confirm_pass" name="confirm_pass" class="form-control" placeholder="Confirm password" required>
        </div>

        <button type="submit" class="btn btn-success w-100">Update Password</button>

        <div class="text-center mt-3">
            <a href="login.html">Back to Login</a>
        </div>
    </form>
</div>

<script>
function validatePassword() {
    let pass = document.getElementById("new_pass").value;
    let confirm = document.getElementById("confirm_pass").value;

    if(pass !== confirm){
        alert("Passwords do not match ❌");
        return false;
    }

    if(pass.length < 6){
        alert("Password must be at least 6 characters");
        return false;
    }

    return true;
}
</script>

</body>
</html>