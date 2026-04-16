<?php
include "db.php";
session_start();

$userId = $_SESSION['user_id'] ?? 1;
$selectedPlan = trim($_GET['plan'] ?? '');

if ($selectedPlan === '') {
    header("Location: manage_subscription.php");
    exit();
}

/* ================= USER FETCH ================= */
$safeUserId = mysqli_real_escape_string($conn, (string)$userId);
$userQuery = mysqli_query($conn, "SELECT * FROM users WHERE id = '$safeUserId' LIMIT 1");

if (!$userQuery || mysqli_num_rows($userQuery) === 0) {
    die("User not found");
}

$user = mysqli_fetch_assoc($userQuery);
$userEmail = $user['email'] ?? '';

if ($userEmail === '') {
    die("User email not found");
}

/* ================= PLAN FETCH ================= */
$safePlan = mysqli_real_escape_string($conn, $selectedPlan);
$planQuery = mysqli_query($conn, "SELECT * FROM plans WHERE plan_name = '$safePlan' AND is_active = 1 LIMIT 1");

if (!$planQuery || mysqli_num_rows($planQuery) === 0) {
    die("Plan not found");
}

$planRow = mysqli_fetch_assoc($planQuery);
$price = $planRow['price'] ?? 0;

/* ================= UPDATE USER ================= */
$updateUser = mysqli_query($conn, "
    UPDATE users
    SET plan = '$safePlan', payment_status = 'Success'
    WHERE id = '$safeUserId'
");

if (!$updateUser) {
    die("User update failed: " . mysqli_error($conn));
}

/* ================= INSERT PAYMENT ================= */
/*
   Tumhare payments table me pehle se ye columns definitely hain:
   email, plan, final_amount, payment_method, payment_status
   Isliye safe insert kar rahe hain.
*/

$paymentMethod = 'Plan Update';
$insertPayment = mysqli_query($conn, "
    INSERT INTO payments (
        email,
        plan,
        amount,
        payment_method,
        upi_app,
        upi_id,
        card_name,
        card_number,
        expiry_date,
        coupon_code,
        discount_amount,
        final_amount
    ) VALUES (
        '" . mysqli_real_escape_string($conn, $userEmail) . "',
        '$safePlan',
        '" . mysqli_real_escape_string($conn, (string)$price) . "',
        'Plan Update',
        '',
        '',
        '',
        '',
        '',
        '',
        '0',
        '" . mysqli_real_escape_string($conn, (string)$price) . "'
    )
");

if (!$insertPayment) {
    die("Payment insert failed: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Updated - Watchwise</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background:
                radial-gradient(circle at top right, rgba(14, 165, 233, 0.10), transparent 25%),
                radial-gradient(circle at top left, rgba(59, 130, 246, 0.08), transparent 22%),
                linear-gradient(180deg, #020617 0%, #06152d 45%, #020617 100%);
            color: #fff;
            padding: 20px;
        }

        .box {
            width: 100%;
            max-width: 520px;
            background: rgba(15, 23, 42, 0.82);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 24px;
            padding: 34px 28px;
            text-align: center;
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.35);
        }

        .success {
            font-size: 34px;
            margin-bottom: 10px;
        }

        h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }

        p {
            color: #94a3b8;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 22px;
        }

        .detail {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 16px;
            padding: 14px 16px;
            margin-bottom: 12px;
            text-align: left;
        }

        .label {
            color: #94a3b8;
            font-size: 12px;
            margin-bottom: 5px;
        }

        .value {
            font-size: 18px;
            font-weight: 700;
            color: #fff;
        }

        .btn {
            display: inline-block;
            margin-top: 18px;
            text-decoration: none;
            padding: 12px 22px;
            border-radius: 12px;
            background: linear-gradient(135deg, #0ea5e9, #2563eb);
            color: #fff;
            font-size: 14px;
            font-weight: 700;
        }
    </style>
</head>

<body>
    <div class="box">
        <div class="success">✅</div>
        <h1>Subscription Updated</h1>
        <p>Your Watchwise membership has been updated successfully.</p>

        <div class="detail">
            <div class="label">New Plan</div>
            <div class="value"><?php echo htmlspecialchars($selectedPlan); ?></div>
        </div>

        <div class="detail">
            <div class="label">Amount</div>
            <div class="value">₹<?php echo htmlspecialchars((string)$price); ?></div>
        </div>

        <div class="detail">
            <div class="label">Payment Status</div>
            <div class="value">Success</div>
        </div>

        <a href="user_movie.php" class="btn">Go to Home</a>
    </div>
</body>

</html>