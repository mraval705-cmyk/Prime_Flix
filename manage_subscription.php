<?php
include "db.php";
session_start();

$userId = $_SESSION['user_id'] ?? 1;

$userName = 'Watchwise User';
$userEmail = '';
$currentPlan = 'No Plan';
$paymentStatus = 'Pending';
$couponCode = 'Not Used';
$finalAmount = '0';

$userQuery = mysqli_query($conn, "SELECT * FROM users WHERE id = '" . mysqli_real_escape_string($conn, (string)$userId) . "' LIMIT 1");
if ($userQuery && mysqli_num_rows($userQuery) > 0) {
    $user = mysqli_fetch_assoc($userQuery);

    $userName = $user['name'] ?? 'Watchwise User';
    $userEmail = $user['email'] ?? '';
    $currentPlan = $user['plan'] ?? 'No Plan';
    $paymentStatus = $user['payment_status'] ?? 'Pending';

    if (!empty($userEmail)) {
        $safeEmail = mysqli_real_escape_string($conn, $userEmail);
        $paymentQuery = mysqli_query($conn, "SELECT * FROM payments WHERE email = '$safeEmail' ORDER BY id DESC LIMIT 1");

        if ($paymentQuery && mysqli_num_rows($paymentQuery) > 0) {
            $payment = mysqli_fetch_assoc($paymentQuery);
            $couponCode = !empty($payment['coupon_code']) ? $payment['coupon_code'] : 'Not Used';
            $finalAmount = $payment['final_amount'] ?? '0';
        }
    }
}

$plans = [];
$planResult = mysqli_query($conn, "SELECT * FROM plans WHERE is_active = 1 ORDER BY id ASC");
while ($planResult && $row = mysqli_fetch_assoc($planResult)) {
    $plans[] = $row;
}

function safePlanValue($plan, $keys, $default = '')
{
    foreach ($keys as $key) {
        if (isset($plan[$key]) && $plan[$key] !== '' && $plan[$key] !== null) {
            return $plan[$key];
        }
    }
    return $default;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Subscription - Watchwise</title>
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
            background:
                radial-gradient(circle at top right, rgba(14, 165, 233, 0.10), transparent 25%),
                radial-gradient(circle at top left, rgba(59, 130, 246, 0.08), transparent 22%),
                linear-gradient(180deg, #020617 0%, #06152d 45%, #020617 100%);
            color: #fff;
            padding: 28px 16px;
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 26px;
        }

        .logo {
            color: #0ea5e9;
            font-size: 32px;
            font-weight: 800;
            text-transform: uppercase;
            text-decoration: none;
        }

        .back-btn {
            text-decoration: none;
            color: #fff;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.08);
            padding: 11px 18px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
        }

        .hero-box {
            background: rgba(15, 23, 42, 0.78);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 24px;
            padding: 28px;
            margin-bottom: 24px;
        }

        .hero-box h1 {
            font-size: 34px;
            margin-bottom: 8px;
        }

        .hero-box p {
            color: #94a3b8;
            font-size: 15px;
            line-height: 1.6;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
            margin-bottom: 24px;
        }

        .summary-card {
            background: rgba(15, 23, 42, 0.78);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            padding: 20px;
        }

        .summary-label {
            color: #94a3b8;
            font-size: 13px;
            margin-bottom: 10px;
        }

        .summary-value {
            font-size: 22px;
            font-weight: 700;
            color: #fff;
            word-break: break-word;
        }

        .plans-box {
            background: rgba(15, 23, 42, 0.78);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 24px;
            padding: 24px;
        }

        .plans-box h2 {
            font-size: 24px;
            margin-bottom: 6px;
        }

        .plans-box p {
            color: #94a3b8;
            font-size: 14px;
            margin-bottom: 22px;
        }

        .plans {
            display: grid;
            grid-template-columns: repeat(4, minmax(180px, 1fr));
            gap: 14px;
        }

        .plan-card {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 18px;
            padding: 16px;
            transition: 0.25s ease;
            min-height: 250px;
        }

        .plan-card:hover {
            transform: translateY(-4px);
            border-color: rgba(14, 165, 233, 0.35);
        }

        .plan-name {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .plan-resolution {
            color: #94a3b8;
            font-size: 12px;
            margin-bottom: 10px;
        }

        .plan-price {
            font-size: 20px;
            font-weight: 800;
            color: #0ea5e9;
            margin-bottom: 12px;
        }

        .plan-feature {
            color: #cbd5e1;
            font-size: 12px;
            margin-bottom: 6px;
            line-height: 1.4;
        }

        .current-badge {
            display: inline-block;
            margin-top: 14px;
            padding: 7px 12px;
            border-radius: 999px;
            background: rgba(34, 197, 94, 0.16);
            color: #4ade80;
            font-size: 12px;
            font-weight: 700;
        }

        .plan-btn {
            display: inline-block;
            width: 100%;
            text-align: center;
            margin-top: 12px;
            padding: 10px 12px;
            border-radius: 10px;
            text-decoration: none;
            background: linear-gradient(135deg, #0ea5e9, #2563eb);
            color: #fff;
            font-size: 13px;
            font-weight: 700;
            border: none;
            cursor: pointer;
        }

        .secondary-actions {
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
            margin-top: 24px;
        }

        .secondary-actions a {
            text-decoration: none;
            padding: 12px 18px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
        }

        .renew-btn {
            background: linear-gradient(135deg, #0ea5e9, #2563eb);
            color: #fff;
        }

        .home-btn {
            background: rgba(255, 255, 255, 0.06);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        @media (max-width: 960px) {
            .summary-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .plans {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 620px) {
            .summary-grid {
                grid-template-columns: 1fr;
            }

            .hero-box h1 {
                font-size: 28px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="topbar">
            <a href="user_movie.php" class="logo">WATCHWISE</a>
            <a href="user_movie.php" class="back-btn">← Back to Home</a>
        </div>

        <div class="hero-box">
            <h1>Manage Subscription</h1>
            <p>
                View your current membership, compare available plans, and continue your Watchwise subscription experience.
            </p>
        </div>

        <div class="summary-grid">
            <div class="summary-card">
                <div class="summary-label">Profile Name</div>
                <div class="summary-value"><?php echo htmlspecialchars($userName); ?></div>
            </div>

            <div class="summary-card">
                <div class="summary-label">Current Plan</div>
                <div class="summary-value"><?php echo htmlspecialchars($currentPlan); ?></div>
            </div>

            <div class="summary-card">
                <div class="summary-label">Payment Status</div>
                <div class="summary-value"><?php echo htmlspecialchars($paymentStatus); ?></div>
            </div>

            <div class="summary-card">
                <div class="summary-label">Final Paid Amount</div>
                <div class="summary-value">₹<?php echo htmlspecialchars((string)$finalAmount); ?></div>
            </div>
        </div>

        <div class="plans-box">
            <h2>Available Plans</h2>
            <p>Select a plan if you want to upgrade, renew, or change your current membership.</p>

            <div class="plans">
                <?php foreach ($plans as $plan): ?>
                    <?php
                    $planName = safePlanValue($plan, ['plan_name'], 'Plan');
                    $resolution = safePlanValue($plan, ['resolution'], '');
                    $price = safePlanValue($plan, ['price'], '0');
                    $videoQuality = safePlanValue($plan, ['video_quality', 'video_sound_quality'], '');
                    $supportedDevices = safePlanValue($plan, ['supported_devices'], '');
                    $downloadDevices = safePlanValue($plan, ['download_devices'], '');
                    ?>
                    <div class="plan-card">
                        <div class="plan-name"><?php echo htmlspecialchars($planName); ?></div>
                        <div class="plan-resolution"><?php echo htmlspecialchars($resolution); ?></div>
                        <div class="plan-price">₹<?php echo htmlspecialchars((string)$price); ?></div>

                        <?php if ($videoQuality !== ''): ?>
                            <div class="plan-feature"><strong>Quality:</strong> <?php echo htmlspecialchars($videoQuality); ?></div>
                        <?php endif; ?>

                        <?php if ($supportedDevices !== ''): ?>
                            <div class="plan-feature"><strong>Devices:</strong> <?php echo htmlspecialchars($supportedDevices); ?></div>
                        <?php endif; ?>

                        <?php if ($downloadDevices !== ''): ?>
                            <div class="plan-feature"><strong>Downloads:</strong> <?php echo htmlspecialchars((string)$downloadDevices); ?></div>
                        <?php endif; ?>

                        <?php if (strtolower($planName) === strtolower($currentPlan)): ?>
                            <div class="current-badge">Current Plan</div>
                        <?php else: ?>
                            <a href="change_plan.php?plan=<?php echo urlencode($planName); ?>" class="plan-btn">Change to this Plan</a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="secondary-actions">
                <a href="change_plan.php" class="renew-btn">Renew / Change Membership</a>
                <a href="user_movie.php" class="home-btn">Go to Home</a>
            </div>
        </div>
    </div>
</body>

</html>