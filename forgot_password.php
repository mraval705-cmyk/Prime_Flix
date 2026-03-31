<?php
include "db.php";

$emailErr = "";
$newPassErr = "";
$confirmErr = "";
$successMsg = "";
$generalErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    $valid = true;

    // Email validation
    if ($email == "") {
        $emailErr = "Please enter your email.";
        $valid = false;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email format.";
        $valid = false;
    }

    // New password validation
    if ($new_password == "") {
        $newPassErr = "Please enter new password.";
        $valid = false;
    } elseif (strlen($new_password) < 6) {
        $newPassErr = "Password must be at least 6 characters.";
        $valid = false;
    }

    // Confirm password validation
    if ($confirm_password == "") {
        $confirmErr = "Please confirm your password.";
        $valid = false;
    } elseif ($new_password != "" && $confirm_password != $new_password) {
        $confirmErr = "Passwords do not match.";
        $valid = false;
    }

    if ($valid) {
        $email_safe = mysqli_real_escape_string($conn, $email);

        $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email_safe'");

        if ($check && mysqli_num_rows($check) > 0) {
            $hashed = password_hash($new_password, PASSWORD_DEFAULT);
            $hashed_safe = mysqli_real_escape_string($conn, $hashed);

            // Generate OTP for password_resets table
            $otp = rand(100000, 999999);

            // Update password in users table
            $update = mysqli_query($conn, "UPDATE users SET password='$hashed_safe' WHERE email='$email_safe'");

            if ($update) {
                // Insert reset record
                $insertReset = mysqli_query($conn, "INSERT INTO password_resets (email, otp) VALUES ('$email_safe', '$otp')");

                if ($insertReset) {
                    $successMsg = "Password updated successfully.";
                    $email = "";
                } else {
                    $generalErr = "Password updated, but reset history could not be saved.";
                }
            } else {
                $generalErr = "Failed to update password.";
            }
        } else {
            $emailErr = "Email not found.";
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Forgot Password</title>
    <style>
        body {
            font-family: Arial;
            background: #0f172a;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .box {
            width: 350px;
            background: #1e293b;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
        }

        .form-group {
            margin-bottom: 14px;
        }

        input {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            box-sizing: border-box;
            outline: none;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #0ea5e9;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 6px;
            font-size: 15px;
        }

        button:hover {
            background: #0284c7;
        }

        .error {
            color: #ff6b6b;
            font-size: 13px;
            margin-top: 5px;
            padding-left: 2px;
        }

        .success {
            color: lightgreen;
            font-size: 14px;
            margin-bottom: 12px;
        }

        .general-error {
            color: #ff6b6b;
            font-size: 14px;
            margin-bottom: 12px;
        }

        a {
            color: #38bdf8;
            text-decoration: none;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="box">
        <h2>Forgot Password</h2>

        <?php if ($successMsg != "") { ?>
            <div class="success"><?php echo $successMsg; ?></div>
        <?php } ?>

        <?php if ($generalErr != "") { ?>
            <div class="general-error"><?php echo $generalErr; ?></div>
        <?php } ?>

        <form method="POST">
            <div class="form-group">
                <input type="email" name="email" placeholder="Enter your email"
                    value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
                <?php if ($emailErr != "") { ?>
                    <div class="error"><?php echo $emailErr; ?></div>
                <?php } ?>
            </div>

            <div class="form-group">
                <input type="password" name="new_password" placeholder="New password">
                <?php if ($newPassErr != "") { ?>
                    <div class="error"><?php echo $newPassErr; ?></div>
                <?php } ?>
            </div>

            <div class="form-group">
                <input type="password" name="confirm_password" placeholder="Confirm password">
                <?php if ($confirmErr != "") { ?>
                    <div class="error"><?php echo $confirmErr; ?></div>
                <?php } ?>
            </div>

            <button type="submit">Update Password</button>
        </form>

        <br>
        <a href="Signup.php">Back to Sign In</a>
    </div>
</body>

</html>