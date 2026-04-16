<?php
include "db.php";
session_start();

$userId = $_SESSION['user_id'] ?? 1;
$selectedPlan = trim($_GET['plan'] ?? '');

$userName = 'Watchwise User';
$userEmail = '';
$currentPlan = 'No Plan';
$paymentStatus = 'Pending';
$currentAmount = '0';

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
            $currentAmount = $payment['final_amount'] ?? '0';
        }
    }
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

$newPlanData = null;
if ($selectedPlan !== '') {
    $safePlan = mysqli_real_escape_string($conn, $selectedPlan);
    $planQuery = mysqli_query($conn, "SELECT * FROM plans WHERE plan_name = '$safePlan' AND is_active = 1 LIMIT 1");
    if ($planQuery && mysqli_num_rows($planQuery) > 0) {
        $newPlanData = mysqli_fetch_assoc($planQuery);
    }
}

$allPlans = [];
$plansResult = mysqli_query($conn, "SELECT * FROM plans WHERE is_active = 1 ORDER BY id ASC");
while ($plansResult && $row = mysqli_fetch_assoc($plansResult)) {
    $allPlans[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Plan - Watchwise</title>
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
            max-width: 1000px;
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

        .compare-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 18px;
            margin-bottom: 24px;
        }

        .compare-card {
            background: rgba(15, 23, 42, 0.78);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 22px;
            padding: 22px;
        }

        .compare-label {
            color: #94a3b8;
            font-size: 13px;
            margin-bottom: 8px;
        }

        .compare-title {
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .compare-price {
            color: #0ea5e9;
            font-size: 26px;
            font-weight: 800;
            margin-bottom: 12px;
        }

        .compare-feature {
            color: #cbd5e1;
            font-size: 13px;
            margin-bottom: 8px;
            line-height: 1.5;
        }

        .plans-box {
            background: rgba(15, 23, 42, 0.78);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 24px;
            padding: 24px;
        }

        .plans-box h2 {
            font-size: 24px;
            margin-bottom: 14px;
        }

        .plans-list {
            display: grid;
            grid-template-columns: repeat(4, minmax(180px, 1fr));
            gap: 14px;
        }

        .plan-mini {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 18px;
            padding: 16px;
        }

        .plan-mini-name {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .plan-mini-res {
            color: #94a3b8;
            font-size: 12px;
            margin-bottom: 10px;
        }

        .plan-mini-price {
            color: #0ea5e9;
            font-size: 20px;
            font-weight: 800;
            margin-bottom: 12px;
        }

        .plan-select-btn {
            display: inline-block;
            width: 100%;
            text-align: center;
            text-decoration: none;
            padding: 10px 12px;
            border-radius: 10px;
            background: linear-gradient(135deg, #0ea5e9, #2563eb);
            color: #fff;
            font-size: 13px;
            font-weight: 700;
        }

        .current-badge {
            display: inline-block;
            padding: 7px 12px;
            border-radius: 999px;
            background: rgba(34, 197, 94, 0.16);
            color: #4ade80;
            font-size: 12px;
            font-weight: 700;
        }

        .actions {
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
            margin-top: 24px;
        }

        .actions a {
            text-decoration: none;
            padding: 12px 18px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
        }

        .primary-btn {
            background: linear-gradient(135deg, #0ea5e9, #2563eb);
            color: #fff;
        }

        .secondary-btn {
            background: rgba(255, 255, 255, 0.06);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .empty-box {
            background: rgba(15, 23, 42, 0.78);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 22px;
            padding: 24px;
            text-align: center;
            color: #cbd5e1;
        }

        @media (max-width: 900px) {
            .compare-grid {
                grid-template-columns: 1fr;
            }

            .plans-list {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="topbar">
            <a href="user_movie.php" class="logo">WATCHWISE</a>
            <a href="manage_subscription.php" class="back-btn">← Back</a>
        </div>

        <div class="hero-box">
            <h1>Change Plan</h1>
            <p>
                Compare your current membership with a new plan and continue only if you want to update your subscription.
            </p>
        </div>

        <?php if ($newPlanData): ?>
            <?php
            $newPlanName = safePlanValue($newPlanData, ['plan_name'], 'Plan');
            $newResolution = safePlanValue($newPlanData, ['resolution'], '');
            $newPrice = safePlanValue($newPlanData, ['price'], '0');
            $newQuality = safePlanValue($newPlanData, ['video_quality', 'video_sound_quality'], '');
            $newDevices = safePlanValue($newPlanData, ['supported_devices'], '');
            $newDownloads = safePlanValue($newPlanData, ['download_devices'], '');
            ?>
            <div class="compare-grid">
                <div class="compare-card">
                    <div class="compare-label">Current Membership</div>
                    <div class="compare-title"><?php echo htmlspecialchars($currentPlan); ?></div>
                    <div class="compare-price">₹<?php echo htmlspecialchars((string)$currentAmount); ?></div>
                    <div class="compare-feature"><strong>Status:</strong> <?php echo htmlspecialchars($paymentStatus); ?></div>
                    <div class="compare-feature"><strong>User:</strong> <?php echo htmlspecialchars($userName); ?></div>
                </div>

                <div class="compare-card">
                    <div class="compare-label">Selected New Plan</div>
                    <div class="compare-title"><?php echo htmlspecialchars($newPlanName); ?></div>
                    <div class="compare-price">₹<?php echo htmlspecialchars((string)$newPrice); ?></div>

                    <?php if ($newResolution !== ''): ?>
                        <div class="compare-feature"><strong>Resolution:</strong> <?php echo htmlspecialchars($newResolution); ?></div>
                    <?php endif; ?>

                    <?php if ($newQuality !== ''): ?>
                        <div class="compare-feature"><strong>Quality:</strong> <?php echo htmlspecialchars($newQuality); ?></div>
                    <?php endif; ?>

                    <?php if ($newDevices !== ''): ?>
                        <div class="compare-feature"><strong>Devices:</strong> <?php echo htmlspecialchars($newDevices); ?></div>
                    <?php endif; ?>

                    <?php if ($newDownloads !== ''): ?>
                        <div class="compare-feature"><strong>Downloads:</strong> <?php echo htmlspecialchars((string)$newDownloads); ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="actions">
                <a href="step4.php?selected_plan=<?php echo urlencode($newPlanName); ?>" class="primary-btn">Proceed to Update</a>
                <a href="manage_subscription.php" class="secondary-btn">Choose Another Plan</a>
            </div>
        <?php else: ?>
            <div class="plans-box">
                <h2>Select a Plan</h2>
                <div class="plans-list">
                    <?php foreach ($allPlans as $plan): ?>
                        <?php
                        $planName = safePlanValue($plan, ['plan_name'], 'Plan');
                        $resolution = safePlanValue($plan, ['resolution'], '');
                        $price = safePlanValue($plan, ['price'], '0');
                        ?>
                        <div class="plan-mini">
                            <div class="plan-mini-name"><?php echo htmlspecialchars($planName); ?></div>
                            <div class="plan-mini-res"><?php echo htmlspecialchars($resolution); ?></div>
                            <div class="plan-mini-price">₹<?php echo htmlspecialchars((string)$price); ?></div>

                            <?php if (strtolower($planName) === strtolower($currentPlan)): ?>
                                <div class="current-badge">Current Plan</div>
                            <?php else: ?>
                                <a href="change_plan.php?plan=<?php echo urlencode($planName); ?>" class="plan-select-btn">Select Plan</a>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>