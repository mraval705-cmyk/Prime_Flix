<?php
include "db.php";

$users = [];

$query = "
    SELECT 
        u.id,
        u.name,
        u.email,
        u.plan,
        u.payment_status,
        p.final_amount,
        p.amount,
        p.payment_method,
        p.coupon_code
    FROM users u
    LEFT JOIN (
        SELECT p1.*
        FROM payments p1
        INNER JOIN (
            SELECT email, MAX(id) AS max_id
            FROM payments
            GROUP BY email
        ) p2 ON p1.email = p2.email AND p1.id = p2.max_id
    ) p ON u.email = p.email
    ORDER BY u.id DESC
";

$result = mysqli_query($conn, $query);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $safeEmail = mysqli_real_escape_string($conn, $row['email']);
        $countResult = mysqli_query($conn, "SELECT COUNT(*) AS total FROM payments WHERE email = '$safeEmail'");
        $historyCount = 0;

        if ($countResult && mysqli_num_rows($countResult) > 0) {
            $countRow = mysqli_fetch_assoc($countResult);
            $historyCount = (int)($countRow['total'] ?? 0);
        }

        $row['history_count'] = $historyCount;
        $users[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Subscriptions - Watchwise</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #0f172a, #020617);
            color: #fff;
            min-height: 100vh;
            padding: 24px;
        }

        .container {
            max-width: 1300px;
            margin: 0 auto;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 24px;
        }

        .title {
            font-size: 30px;
            font-weight: 800;
            color: #38bdf8;
        }

        .back-btn {
            text-decoration: none;
            color: #fff;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.12);
            padding: 10px 16px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
        }

        .hero-box {
            background: rgba(30, 41, 59, 0.72);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            padding: 22px;
            margin-bottom: 22px;
        }

        .hero-box h2 {
            font-size: 24px;
            margin-bottom: 8px;
        }

        .hero-box p {
            color: #94a3b8;
            font-size: 14px;
            line-height: 1.6;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 22px;
        }

        .stat-card {
            background: rgba(30, 41, 59, 0.72);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 18px;
            padding: 18px;
        }

        .stat-label {
            color: #94a3b8;
            font-size: 12px;
            margin-bottom: 8px;
        }

        .stat-value {
            font-size: 26px;
            font-weight: 800;
            color: #fff;
        }

        .table-card {
            background: rgba(30, 41, 59, 0.72);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            padding: 18px;
        }

        .table-wrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1050px;
        }

        th,
        td {
            padding: 13px 12px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            font-size: 13px;
            vertical-align: top;
        }

        th {
            color: #93c5fd;
            font-weight: 700;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.6px;
        }

        td {
            color: #e2e8f0;
        }

        .email {
            color: #94a3b8;
            font-size: 12px;
            margin-top: 4px;
            word-break: break-word;
        }

        .badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
        }

        .plan-badge {
            background: rgba(14, 165, 233, 0.16);
            color: #7dd3fc;
        }

        .success-badge {
            background: rgba(34, 197, 94, 0.16);
            color: #4ade80;
        }

        .muted {
            color: #94a3b8;
        }

        .amount {
            font-weight: 700;
            color: #fff;
        }

        .empty {
            text-align: center;
            color: #94a3b8;
            padding: 28px 0;
        }

        @media (max-width: 950px) {
            .stats {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 560px) {
            .stats {
                grid-template-columns: 1fr;
            }

            .title {
                font-size: 24px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="topbar">
            <div class="title">Watchwise Admin - Subscriptions</div>
            <a href="admin.php" class="back-btn">Back to Admin</a>
        </div>

        <div class="hero-box">
            <h2>Subscription Overview</h2>
            <p>
                This page shows the current subscription data of all users along with their latest payment details.
                If any user changes their plan, the updated plan and latest payment entry will reflect here automatically.
            </p>
        </div>

        <?php
        $totalUsers = count($users);
        $activePlans = 0;
        $successfulPayments = 0;
        $totalRevenue = 0;

        foreach ($users as $u) {
            if (!empty($u['plan']) && strtolower($u['plan']) !== 'no plan') {
                $activePlans++;
            }

            if (!empty($u['payment_status']) && strtolower($u['payment_status']) === 'success') {
                $successfulPayments++;
            }

            $latestAmount = $u['final_amount'] !== null && $u['final_amount'] !== ''
                ? (float)$u['final_amount']
                : (float)($u['amount'] ?? 0);

            $totalRevenue += $latestAmount;
        }
        ?>

        <div class="stats">
            <div class="stat-card">
                <div class="stat-label">Total Users</div>
                <div class="stat-value"><?php echo $totalUsers; ?></div>
            </div>

            <div class="stat-card">
                <div class="stat-label">Users With Active Plan</div>
                <div class="stat-value"><?php echo $activePlans; ?></div>
            </div>

            <div class="stat-card">
                <div class="stat-label">Successful Subscription Status</div>
                <div class="stat-value"><?php echo $successfulPayments; ?></div>
            </div>

            <div class="stat-card">
                <div class="stat-label">Latest Revenue Snapshot</div>
                <div class="stat-value">₹<?php echo (int)$totalRevenue; ?></div>
            </div>
        </div>

        <div class="table-card">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Current Plan</th>
                            <th>Payment Status</th>
                            <th>Latest Paid Amount</th>
                            <th>Last Payment Method</th>
                            <th>Coupon Used</th>
                            <th>History Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($users)): ?>
                            <?php foreach ($users as $user): ?>
                                <?php
                                $latestAmount = $user['final_amount'] !== null && $user['final_amount'] !== ''
                                    ? $user['final_amount']
                                    : ($user['amount'] ?? '0');

                                $method = $user['payment_method'] ?? '';
                                $coupon = $user['coupon_code'] ?? '';
                                ?>
                                <tr>
                                    <td>
                                        <div><?php echo htmlspecialchars($user['name'] ?? 'User'); ?></div>
                                        <div class="email"><?php echo htmlspecialchars($user['email'] ?? ''); ?></div>
                                    </td>

                                    <td>
                                        <span class="badge plan-badge">
                                            <?php echo htmlspecialchars($user['plan'] ?? 'No Plan'); ?>
                                        </span>
                                    </td>

                                    <td>
                                        <span class="badge success-badge">
                                            <?php echo htmlspecialchars($user['payment_status'] ?? 'Pending'); ?>
                                        </span>
                                    </td>

                                    <td class="amount">₹<?php echo htmlspecialchars((string)$latestAmount); ?></td>

                                    <td>
                                        <?php if (!empty($method)): ?>
                                            <?php echo htmlspecialchars($method); ?>
                                        <?php else: ?>
                                            <span class="muted">No method</span>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <?php if (!empty($coupon)): ?>
                                            <?php echo htmlspecialchars($coupon); ?>
                                        <?php else: ?>
                                            <span class="muted">Not used</span>
                                        <?php endif; ?>
                                    </td>

                                    <td><?php echo (int)$user['history_count']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="empty">No subscription data found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>