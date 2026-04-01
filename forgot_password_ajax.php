<?php
session_start();
include "db.php";
include "mailer.php";

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    echo json_encode(["status" => "error", "message" => "Invalid request."]);
    exit;
}

$action = $_POST['action'] ?? '';

if ($action == "send_otp") {
    $email = trim($_POST['email'] ?? '');

    if ($email == "") {
        echo json_encode(["status" => "error", "message" => "Please enter email."]);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["status" => "error", "message" => "Invalid email format."]);
        exit;
    }

    $email_safe = mysqli_real_escape_string($conn, $email);

    $checkUser = mysqli_query($conn, "SELECT id FROM users WHERE email='$email_safe' LIMIT 1");
    if (!$checkUser || mysqli_num_rows($checkUser) == 0) {
        echo json_encode(["status" => "error", "message" => "Email not found."]);
        exit;
    }

    $otp = rand(100000, 999999);
    $expires_at = date("Y-m-d H:i:s", strtotime("+10 minutes"));

    // old otp invalidate
    mysqli_query($conn, "UPDATE otp_requests SET is_used=1 WHERE email='$email_safe'");

    $insertOtp = mysqli_query($conn, "
        INSERT INTO otp_requests (email, otp, purpose, expires_at, is_used, created_at)
        VALUES ('$email_safe', '$otp', 'forgot_password', '$expires_at', 0, NOW())
    ");

    if (!$insertOtp) {
        echo json_encode([
            "status" => "error",
            "message" => "OTP save failed: " . mysqli_error($conn)
        ]);
        exit;
    }

    // session backup
    $_SESSION['forgot_email'] = $email;
    $_SESSION['forgot_otp'] = $otp;
    $_SESSION['forgot_otp_expiry'] = time() + 600;

    if (sendOTP($email, $otp)) {
        echo json_encode(["status" => "success", "message" => "OTP sent successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Email send failed."]);
    }
    exit;
}

if ($action == "verify_otp") {
    $email = trim($_POST['email'] ?? '');
    $otp = trim($_POST['otp'] ?? '');

    if ($email == "" || $otp == "") {
        echo json_encode(["status" => "error", "message" => "Email and OTP required."]);
        exit;
    }

    $email_safe = mysqli_real_escape_string($conn, $email);
    $otp_safe = mysqli_real_escape_string($conn, $otp);

    // latest OTP row only
    $query = mysqli_query($conn, "
        SELECT * FROM otp_requests
        WHERE email='$email_safe'
        ORDER BY id DESC
        LIMIT 1
    ");

    if ($query && mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_assoc($query);

        if ($row['is_used'] == 1) {
            echo json_encode(["status" => "error", "message" => "OTP already used."]);
            exit;
        }

        if ($row['otp'] != $otp) {
            echo json_encode(["status" => "error", "message" => "Invalid OTP."]);
            exit;
        }

        if (strtotime($row['expires_at']) < time()) {
            echo json_encode(["status" => "error", "message" => "OTP expired."]);
            exit;
        }

        $_SESSION['otp_verified'] = true;
        $_SESSION['forgot_email'] = $email;

        echo json_encode(["status" => "success", "message" => "OTP verified successfully."]);
        exit;
    }

    // session fallback
    if (
        isset($_SESSION['forgot_email'], $_SESSION['forgot_otp'], $_SESSION['forgot_otp_expiry']) &&
        $_SESSION['forgot_email'] === $email &&
        $_SESSION['forgot_otp'] == $otp &&
        $_SESSION['forgot_otp_expiry'] >= time()
    ) {
        $_SESSION['otp_verified'] = true;
        echo json_encode(["status" => "success", "message" => "OTP verified successfully."]);
        exit;
    }

    echo json_encode(["status" => "error", "message" => "Invalid or expired OTP."]);
    exit;
}
if ($action == "reset_password") {
    $email = trim($_POST['email'] ?? '');
    $new_password = trim($_POST['new_password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');

    if ($email == "" || $new_password == "" || $confirm_password == "") {
        echo json_encode(["status" => "error", "message" => "All fields are required."]);
        exit;
    }

    if (strlen($new_password) < 6) {
        echo json_encode(["status" => "error", "message" => "Password must be at least 6 characters."]);
        exit;
    }

    if ($new_password !== $confirm_password) {
        echo json_encode(["status" => "error", "message" => "Passwords do not match."]);
        exit;
    }

    if (!isset($_SESSION['otp_verified']) || $_SESSION['otp_verified'] !== true || !isset($_SESSION['forgot_email']) || $_SESSION['forgot_email'] !== $email) {
        echo json_encode(["status" => "error", "message" => "Please verify OTP first."]);
        exit;
    }

    $email_safe = mysqli_real_escape_string($conn, $email);
    $hashed = password_hash($new_password, PASSWORD_DEFAULT);
    $hashed_safe = mysqli_real_escape_string($conn, $hashed);

    $update = mysqli_query($conn, "UPDATE users SET password='$hashed_safe' WHERE email='$email_safe'");

    if ($update) {
        mysqli_query($conn, "UPDATE otp_requests SET is_used=1 WHERE email='$email_safe'");

        unset($_SESSION['otp_verified']);
        unset($_SESSION['forgot_email']);
        unset($_SESSION['forgot_otp']);
        unset($_SESSION['forgot_otp_expiry']);

        echo json_encode(["status" => "success", "message" => "Password reset successful. Redirecting to login..."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Password update failed: " . mysqli_error($conn)]);
    }
    exit;
}

echo json_encode(["status" => "error", "message" => "Unknown action."]);
