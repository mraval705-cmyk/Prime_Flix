<?php
session_start();
include "db.php";

$offers = [];
$offer_result = mysqli_query($conn, "
    SELECT * 
    FROM offers 
    WHERE is_active = 1 
      AND valid_until >= CURDATE() 
    ORDER BY valid_until ASC, id DESC
    LIMIT 10
");

while ($offer_result && $offer = mysqli_fetch_assoc($offer_result)) {
    $offers[] = $offer;
}

function getOfferScore($offer)
{
    $type = $offer['discount_type'] ?? 'flat';
    $value = (float)($offer['discount_value'] ?? 0);
    $minAmount = (float)($offer['min_amount'] ?? 0);

    if ($type === 'percent') {
        return $value * 10 - ($minAmount / 100);
    }

    return $value - ($minAmount / 100);
}

usort($offers, function ($a, $b) {
    return getOfferScore($b) <=> getOfferScore($a);
});
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Watchwise - All Offers</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background:
                linear-gradient(135deg, rgba(15, 23, 42, 0.95), rgba(2, 6, 23, 0.93)),
                url("img/Screenshot 2026-01-01 184050.png") center/cover no-repeat fixed;
            color: #fff;
            min-height: 100vh;
            padding: 22px 12px;
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 16px;
        }

        .logo {
            color: #0ea5e9;
            font-size: 22px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .back-btn {
            text-decoration: none;
            color: #fff;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.14);
            padding: 8px 14px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
        }

        .back-btn:hover {
            background: rgba(255, 255, 255, 0.14);
        }

        .hero-box {
            background: rgba(15, 23, 42, 0.74);
            border: 1px solid rgba(255, 255, 255, 0.10);
            border-radius: 16px;
            padding: 16px 14px;
            text-align: center;
            backdrop-filter: blur(12px);
            margin-bottom: 18px;
        }

        .step {
            color: #38bdf8;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        h1 {
            font-size: 21px;
            margin-bottom: 5px;
            line-height: 1.2;
        }

        .subtitle {
            color: #94a3b8;
            font-size: 11px;
            max-width: 620px;
            margin: 0 auto;
            line-height: 1.4;
        }

        .offers-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 14px;
        }

        .offer-card {
            background: rgba(15, 23, 42, 0.85);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 18px;
            padding: 14px;
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.20);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 10px;
            transition: 0.3s;
            min-height: 180px;
        }

        .offer-card:hover {
            transform: translateY(-4px) scale(1.02);
            border-color: #0ea5e9;
        }

        .offer-left {
            flex: 1;
            min-width: 0;
        }

        .offer-top {
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin-bottom: 6px;
        }

        .coupon-code {
            color: #ffffff;
            font-size: 16px;
            font-weight: 800;
            line-height: 1.2;
        }

        .offer-badge {
            display: inline-block;
            width: fit-content;
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: #fff;
            padding: 4px 8px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 700;
        }

        .recommended-badge {
            display: inline-block;
            width: fit-content;
            background: linear-gradient(135deg, #16a34a, #15803d);
            color: #fff;
            padding: 4px 8px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 700;
        }

        .offer-line {
            color: #cbd5e1;
            font-size: 11px;
            line-height: 1.4;
            margin-bottom: 3px;
        }

        .offer-line strong {
            color: #fff;
            font-weight: 600;
        }

        .validity {
            display: inline-block;
            margin-top: 6px;
            padding: 4px 8px;
            border-radius: 999px;
            background: rgba(14, 165, 233, 0.12);
            border: 1px solid rgba(14, 165, 233, 0.22);
            color: #bae6fd;
            font-size: 9px;
            font-weight: 600;
        }

        .select-btn {
            text-decoration: none;
            width: 100%;
            text-align: center;
            padding: 10px;
            border-radius: 10px;
            background: linear-gradient(135deg, #0ea5e9, #2563eb);
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            display: inline-block;
        }

        .select-btn:hover {
            transform: translateY(-1px);
        }

        .empty-box {
            background: rgba(15, 23, 42, 0.72);
            border: 1px solid rgba(255, 255, 255, 0.10);
            border-radius: 16px;
            padding: 18px 14px;
            text-align: center;
            color: #cbd5e1;
            font-size: 12px;
        }

        @media (max-width: 700px) {
            h1 {
                font-size: 19px;
            }

            .offers-list {
                grid-template-columns: 1fr;
            }

            .offer-card {
                min-height: auto;
            }

            .coupon-code {
                font-size: 14px;
            }

            .offer-line {
                font-size: 10px;
            }

            .select-btn {
                font-size: 11px;
                padding: 9px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="topbar">
            <div class="logo">Watchwise</div>
            <a href="step3.php" class="back-btn">← Back to Plans</a>
        </div>

        <div class="hero-box">
            <div class="step">Offers & Discounts</div>
            <h1>Select the best coupon for your plan</h1>
            <p class="subtitle">
                Choose any valid offer below. The selected coupon will automatically go to your plan page.
            </p>
        </div>

        <?php if (!empty($offers)): ?>
            <div class="offers-list">
                <?php foreach ($offers as $index => $offer): ?>
                    <?php
                    $coupon = strtoupper($offer['coupon_code']);
                    $discountType = $offer['discount_type'];
                    $discountValue = (float)$offer['discount_value'];
                    $minAmount = (float)$offer['min_amount'];
                    $validUntil = $offer['valid_until'];

                    if ($discountType === 'percent') {
                        $badgeText = (int)$discountValue . "% OFF";
                    } else {
                        $badgeText = "Flat ₹" . (int)$discountValue . " OFF";
                    }

                    $isRecommended = $index < 3;
                    ?>
                    <div class="offer-card">
                        <div class="offer-left">
                            <div class="offer-top">
                                <div class="coupon-code"><?php echo htmlspecialchars($coupon); ?></div>
                                <div class="offer-badge"><?php echo htmlspecialchars($badgeText); ?></div>
                                <?php if ($isRecommended): ?>
                                    <div class="recommended-badge">Recommended</div>
                                <?php endif; ?>
                            </div>

                            <div class="offer-line"><strong>Discount:</strong> <?php echo htmlspecialchars($badgeText); ?></div>
                            <div class="offer-line"><strong>Minimum Amount:</strong> ₹<?php echo (int)$minAmount; ?></div>
                            <div class="offer-line"><strong>Coupon Code:</strong> <?php echo htmlspecialchars($coupon); ?></div>
                            <div class="validity">Valid till <?php echo htmlspecialchars($validUntil); ?></div>
                        </div>

                        <a href="step3.php?coupon=<?php echo urlencode($coupon); ?>" class="select-btn">
                            Select Offer
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-box">
                No active offers available right now.
            </div>
        <?php endif; ?>
    </div>
</body>

</html>