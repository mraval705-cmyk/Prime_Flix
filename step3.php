<?php
session_start();
include "db.php";

$prefilledCoupon = strtoupper(trim($_GET['coupon'] ?? ''));

if (isset($_POST['plan_name'])) {
    $_SESSION['reg_plan'] = $_POST['plan_name'];
    $_SESSION['reg_coupon'] = strtoupper(trim($_POST['coupon_code'] ?? ''));
    header("Location: step4.php");
    exit();
}

$plans = [];
$plan_result = mysqli_query($conn, "SELECT * FROM plans WHERE is_active = 1 ORDER BY id ASC");
while ($plan_result && $row = mysqli_fetch_assoc($plan_result)) {
    $plans[] = $row;
}

$offers = [];
$validCoupons = [];

$offer_result = mysqli_query($conn, "SELECT * FROM offers WHERE is_active = 1 AND valid_until >= CURDATE() ORDER BY id DESC");
while ($offer_result && $offer = mysqli_fetch_assoc($offer_result)) {
    $offers[] = $offer;
    $validCoupons[] = strtoupper($offer['coupon_code']);
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

function getPlanFallbackDetails($planName)
{
    $name = strtolower(trim($planName));

    if ($name === 'mobile') {
        return [
            'video_quality' => 'Fair',
            'supported_devices' => 'Mobile phone, tablet',
            'watch_devices' => '1',
            'download_devices' => '1',
            'spatial_audio' => ''
        ];
    }

    if ($name === 'basic') {
        return [
            'video_quality' => 'Good',
            'supported_devices' => 'TV, computer, mobile phone, tablet',
            'watch_devices' => '1',
            'download_devices' => '1',
            'spatial_audio' => ''
        ];
    }

    if ($name === 'standard') {
        return [
            'video_quality' => 'Great',
            'supported_devices' => 'TV, computer, mobile phone, tablet',
            'watch_devices' => '2',
            'download_devices' => '2',
            'spatial_audio' => ''
        ];
    }

    if ($name === 'premium') {
        return [
            'video_quality' => 'Best',
            'supported_devices' => 'TV, computer, mobile phone, tablet',
            'watch_devices' => '4',
            'download_devices' => '6',
            'spatial_audio' => 'Included'
        ];
    }

    return [
        'video_quality' => '',
        'supported_devices' => '',
        'watch_devices' => '',
        'download_devices' => '',
        'spatial_audio' => ''
    ];
}

$plansForJs = [];
foreach ($plans as $plan) {
    $planName = safePlanValue($plan, ['plan_name'], 'Plan');
    $price = (float) safePlanValue($plan, ['price', 'monthly_price'], 0);

    $plansForJs[] = [
        'plan_name' => $planName,
        'price' => $price
    ];
}

$offersForJs = [];
foreach ($offers as $offer) {
    $offersForJs[] = [
        'coupon_code' => strtoupper($offer['coupon_code'] ?? ''),
        'discount_type' => $offer['discount_type'] ?? 'flat',
        'discount_value' => (float)($offer['discount_value'] ?? 0),
        'min_amount' => (float)($offer['min_amount'] ?? 0),
        'valid_until' => $offer['valid_until'] ?? ''
    ];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Watchwise - Choose your plan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: url("img/Screenshot 2026-01-01 184050.png") center/cover no-repeat fixed;
            min-height: 100vh;
            color: #fff;
            position: relative;
            display: flex;
            flex-direction: column;
        }

        body::before {
            content: "";
            position: fixed;
            inset: 0;
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.92) 0%, rgba(2, 6, 23, 0.88) 100%);
            z-index: -1;
        }

        header {
            padding: 24px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            color: #0ea5e9;
            font-size: 32px;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .btn-signout {
            background: rgba(255, 255, 255, 0.06);
            color: #fff;
            padding: 10px 18px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            border: 1px solid rgba(255, 255, 255, 0.15);
            text-decoration: none;
        }

        .btn-signout:hover {
            background: rgba(255, 255, 255, 0.12);
        }

        .container {
            width: 100%;
            max-width: 1500px;
            margin: 10px auto 40px;
            padding: 0 18px;
            text-align: center;
        }

        .step {
            color: #0ea5e9;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.6px;
            margin-bottom: 10px;
        }

        h1 {
            font-size: 32px;
            font-weight: 600;
            margin-bottom: 10px;
            color: #ffffff;
        }

        .subtext {
            color: #94a3b8;
            font-size: 15px;
            margin-bottom: 22px;
        }

        .plans {
            display: flex;
            justify-content: center;
            gap: 16px;
            flex-wrap: wrap;
            align-items: stretch;
        }

        .plan {
            flex: 1;
            min-width: 250px;
            max-width: 335px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.14);
            border-radius: 24px;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.25s ease;
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.26);
            position: relative;
            backdrop-filter: blur(10px);
            text-align: left;
        }

        .plan:hover {
            transform: translateY(-4px);
            border-color: rgba(14, 165, 233, 0.45);
        }

        .plan.selected {
            border: 2px solid #ffffff;
            box-shadow: 0 16px 35px rgba(14, 165, 233, 0.24);
            transform: translateY(-4px);
        }

        .popular {
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #3f3f46;
            color: #fff;
            font-size: 13px;
            font-weight: 600;
        }

        .plan-header {
            padding: 18px 18px 16px;
            color: #fff;
            font-size: 21px;
            font-weight: 700;
            text-align: left;
            position: relative;
        }

        .plan-header small {
            font-size: 13px;
            font-weight: 500;
            opacity: 0.95;
            display: block;
            margin-top: 4px;
        }

        .mobile {
            background: linear-gradient(135deg, #1d4ed8, #2563eb);
        }

        .basic {
            background: linear-gradient(135deg, #3730a3, #6d28d9);
        }

        .standard {
            background: linear-gradient(135deg, #4338ca, #c026d3);
        }

        .premium {
            background: linear-gradient(135deg, #4338ca, #e11d48);
        }

        .selected-dot {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            width: 22px;
            height: 22px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.95);
            color: #7c3aed;
            display: none;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
        }

        .plan.selected .selected-dot {
            display: inline-flex;
        }

        .plan-body {
            padding: 18px 22px 22px;
        }

        .row {
            padding: 12px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.10);
        }

        .row:last-child {
            border-bottom: none;
        }

        .label {
            color: #94a3b8;
            font-size: 13px;
            margin-bottom: 4px;
            font-weight: 500;
            line-height: 1.35;
        }

        .value {
            font-weight: 700;
            color: #fff;
            font-size: 15px;
            line-height: 1.4;
        }

        .price-box {
            margin-top: 22px;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
            background: rgba(15, 23, 42, 0.75);
            border: 1px solid rgba(255, 255, 255, 0.10);
            border-radius: 18px;
            padding: 18px;
            display: none;
            text-align: left;
        }

        .price-title {
            font-size: 16px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 12px;
        }

        .price-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
        }

        .price-item {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            padding: 12px;
        }

        .price-item span {
            display: block;
        }

        .price-item .small {
            color: #94a3b8;
            font-size: 12px;
            margin-bottom: 6px;
        }

        .old-price {
            text-decoration: line-through;
            color: #94a3b8;
            font-size: 14px;
        }

        .new-price {
            color: #22c55e;
            font-size: 20px;
            font-weight: 700;
        }

        .offer-badge {
            display: inline-block;
            margin-top: 7px;
            font-size: 11px;
            font-weight: 700;
            color: #fff;
            background: linear-gradient(135deg, #f97316, #ea580c);
            border-radius: 999px;
            padding: 5px 9px;
        }

        .coupon-box {
            margin: 18px auto 0;
            max-width: 500px;
            display: flex;
            gap: 10px;
        }

        .coupon-box input {
            flex: 1;
            padding: 14px 16px;
            font-size: 15px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            background: rgba(15, 23, 42, 0.7);
            color: #fff;
        }

        .coupon-box input:focus {
            outline: none;
            border-color: #0ea5e9;
            box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.15);
        }

        .coupon-box button {
            padding: 0 24px;
            background: linear-gradient(to right, #0ea5e9, #0284c7);
            border: none;
            color: #fff;
            font-weight: 600;
            border-radius: 12px;
            cursor: pointer;
        }

        .coupon-msg {
            margin-top: 10px;
            text-align: center;
            font-size: 14px;
            min-height: 20px;
            color: #22c55e;
        }

        .plan-error {
            color: #f43f5e;
            text-align: center;
            margin-top: 12px;
            font-size: 14px;
            min-height: 20px;
        }

        .btn-wrap {
            text-align: center;
            margin: 28px 0 40px;
        }

        .btn-primary {
            padding: 16px 60px;
            font-size: 19px;
            background: linear-gradient(to right, #0ea5e9, #0284c7);
            color: #fff;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 600;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
        }

        @media (max-width: 900px) {
            .price-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            header {
                padding: 20px;
            }

            .plans {
                flex-direction: column;
                align-items: center;
            }

            .plan {
                width: 100%;
                max-width: 420px;
            }

            .coupon-box {
                flex-direction: column;
                padding: 0 12px;
            }

            .coupon-box button {
                padding: 14px;
            }

            .price-grid {
                grid-template-columns: 1fr;
            }
        }

        .offers-link-wrap {
            text-align: center;
            margin: 12px auto 22px;
        }

        .offers-link-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
            padding: 12px 22px;
            border-radius: 999px;
            background: linear-gradient(135deg, #0ea5e9, #2563eb);
            color: #ffffff;
            font-size: 14px;
            font-weight: 700;
            box-shadow: 0 12px 28px rgba(14, 165, 233, 0.18);
            transition: 0.25s ease;
        }

        .offers-link-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 16px 32px rgba(14, 165, 233, 0.24);
        }
    </style>
</head>

<body>
    <header>
        <div class="logo">Watchwise</div>
        <div onclick="window.location.href='index.php'" class="btn-signout">Sign Out</div>
    </header>

    <div class="container">
        <p class="step">Step 2 of 3</p>
        <h1>Choose the plan that’s right for you</h1>
        <p class="subtext">Select a plan, apply a coupon, and see the final price before payment.</p>

        <div class="offers-link-wrap">
            <a href="offers.php" class="offers-link-btn">View All Offers</a>
        </div>

        <div class="plans">
            <?php foreach ($plans as $plan): ?>
                <?php
                $planClass = strtolower(safePlanValue($plan, ['plan_name'], 'basic'));
                $planName = safePlanValue($plan, ['plan_name'], 'Plan');
                $resolution = safePlanValue($plan, ['resolution'], '');
                $price = (int) safePlanValue($plan, ['price', 'monthly_price'], 0);

                $fallback = getPlanFallbackDetails($planName);

                $videoQuality = safePlanValue($plan, ['video_quality', 'video_sound_quality'], $fallback['video_quality']);
                $supportedDevices = safePlanValue($plan, ['supported_devices'], $fallback['supported_devices']);
                $watchDevices = safePlanValue($plan, ['watch_devices', 'devices_at_same_time', 'devices_household_can_watch'], $fallback['watch_devices']);
                $downloadDevices = safePlanValue($plan, ['download_devices'], $fallback['download_devices']);
                $spatialAudio = safePlanValue($plan, ['spatial_audio'], $fallback['spatial_audio']);

                $isPopular = isset($plan['is_popular']) ? ((int)$plan['is_popular'] === 1) : (strtolower($planName) === 'basic');
                ?>
                <div class="plan" data-plan="<?php echo htmlspecialchars($planName); ?>" data-price="<?php echo $price; ?>">
                    <?php if ($isPopular): ?>
                        <div class="popular">Most Popular</div>
                    <?php endif; ?>

                    <div class="plan-header <?php echo htmlspecialchars($planClass); ?>">
                        <?php echo htmlspecialchars($planName); ?><br>
                        <small><?php echo htmlspecialchars($resolution); ?></small>
                        <span class="selected-dot">✓</span>
                    </div>

                    <div class="plan-body">
                        <div class="row">
                            <div class="label">Monthly price</div>
                            <div class="value">₹<?php echo $price; ?></div>
                        </div>

                        <?php if ($videoQuality !== ''): ?>
                            <div class="row">
                                <div class="label">Video and sound quality</div>
                                <div class="value"><?php echo htmlspecialchars($videoQuality); ?></div>
                            </div>
                        <?php endif; ?>

                        <?php if ($resolution !== ''): ?>
                            <div class="row">
                                <div class="label">Resolution</div>
                                <div class="value"><?php echo htmlspecialchars($resolution); ?></div>
                            </div>
                        <?php endif; ?>

                        <?php if ($spatialAudio !== ''): ?>
                            <div class="row">
                                <div class="label">Spatial audio</div>
                                <div class="value"><?php echo htmlspecialchars($spatialAudio); ?></div>
                            </div>
                        <?php endif; ?>

                        <?php if ($supportedDevices !== ''): ?>
                            <div class="row">
                                <div class="label">Supported devices</div>
                                <div class="value"><?php echo htmlspecialchars($supportedDevices); ?></div>
                            </div>
                        <?php endif; ?>

                        <?php if ($watchDevices !== ''): ?>
                            <div class="row">
                                <div class="label">Devices your household can watch at the same time</div>
                                <div class="value"><?php echo htmlspecialchars($watchDevices); ?></div>
                            </div>
                        <?php endif; ?>

                        <?php if ($downloadDevices !== ''): ?>
                            <div class="row">
                                <div class="label">Download devices</div>
                                <div class="value"><?php echo htmlspecialchars($downloadDevices); ?></div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div id="planError" class="plan-error"></div>

        <div id="pricePreview" class="price-box">
            <div class="price-title">Live price preview</div>
            <div class="price-grid">
                <div class="price-item">
                    <span class="small">Selected Plan</span>
                    <span class="new-price" id="previewPlan">-</span>
                </div>
                <div class="price-item">
                    <span class="small">Original Price</span>
                    <span class="old-price" id="previewOriginal">₹0</span>
                </div>
                <div class="price-item">
                    <span class="small">Final Price</span>
                    <span class="new-price" id="previewFinal">₹0</span>
                </div>
                <div class="price-item">
                    <span class="small">Applied Offer</span>
                    <span class="offer-badge" id="previewBadge">No offer</span>
                </div>
            </div>
        </div>

        <div class="coupon-box">
            <input type="text" id="couponCode" placeholder="Enter coupon code" value="<?php echo htmlspecialchars($prefilledCoupon); ?>">
            <button type="button" onclick="applyCoupon()">Apply</button>
        </div>

        <div id="couponMsg" class="coupon-msg"></div>

        <div class="btn-wrap">
            <button class="btn-primary" onclick="goNext()">Next</button>
        </div>
    </div>

    <script>
        let selectedPlan = null;
        let selectedPlanPrice = 0;
        let couponApplied = false;

        const validCoupons = <?php echo json_encode($validCoupons); ?>;
        const plansData = <?php echo json_encode($plansForJs, JSON_UNESCAPED_UNICODE); ?>;
        const offersData = <?php echo json_encode($offersForJs, JSON_UNESCAPED_UNICODE); ?>;

        const plans = document.querySelectorAll(".plan");
        const couponInput = document.getElementById("couponCode");
        const couponMsg = document.getElementById("couponMsg");
        const planError = document.getElementById("planError");

        const pricePreview = document.getElementById("pricePreview");
        const previewPlan = document.getElementById("previewPlan");
        const previewOriginal = document.getElementById("previewOriginal");
        const previewFinal = document.getElementById("previewFinal");
        const previewBadge = document.getElementById("previewBadge");

        plans.forEach(plan => {
            plan.addEventListener("click", () => {
                plans.forEach(p => p.classList.remove("selected"));
                plan.classList.add("selected");
                selectedPlan = plan.getAttribute("data-plan");
                selectedPlanPrice = parseFloat(plan.getAttribute("data-price")) || 0;
                planError.innerText = "";
                updatePreview();
            });
        });

        function findOfferByCode(code) {
            return offersData.find(item => item.coupon_code === code) || null;
        }

        function calculateFinal(price, offer) {
            let discount = 0;

            if (!offer) {
                return {
                    final: price,
                    discount: 0,
                    valid: false,
                    reason: "No offer"
                };
            }

            const minAmount = parseFloat(offer.min_amount || 0);
            if (price < minAmount) {
                return {
                    final: price,
                    discount: 0,
                    valid: false,
                    reason: "Minimum ₹" + minAmount + " required"
                };
            }

            if (offer.discount_type === "percent") {
                discount = (price * parseFloat(offer.discount_value || 0)) / 100;
            } else {
                discount = parseFloat(offer.discount_value || 0);
            }

            let finalAmount = price - discount;
            if (finalAmount < 0) {
                finalAmount = 0;
            }

            return {
                final: Math.round(finalAmount),
                discount: Math.round(discount),
                valid: true,
                reason: offer.coupon_code
            };
        }

        function updatePreview() {
            if (!selectedPlan) {
                pricePreview.style.display = "none";
                return;
            }

            const couponCode = couponInput.value.trim().toUpperCase();
            const offer = couponCode ? findOfferByCode(couponCode) : null;
            const result = calculateFinal(selectedPlanPrice, offer);

            pricePreview.style.display = "block";
            previewPlan.innerText = selectedPlan;
            previewOriginal.innerText = "₹" + selectedPlanPrice;

            if (offer && result.valid) {
                previewFinal.innerText = "₹" + result.final;
                previewBadge.innerText = offer.coupon_code;
                previewBadge.style.background = "linear-gradient(135deg, #16a34a, #15803d)";
            } else if (offer && !result.valid) {
                previewFinal.innerText = "₹" + selectedPlanPrice;
                previewBadge.innerText = result.reason;
                previewBadge.style.background = "linear-gradient(135deg, #dc2626, #b91c1c)";
            } else {
                previewFinal.innerText = "₹" + selectedPlanPrice;
                previewBadge.innerText = "No offer";
                previewBadge.style.background = "linear-gradient(135deg, #f97316, #ea580c)";
            }
        }

        function applyCoupon() {
            const couponCode = couponInput.value.trim().toUpperCase();

            if (couponCode === "") {
                couponMsg.style.color = "#f43f5e";
                couponMsg.innerText = "Please enter coupon code";
                couponApplied = false;
                updatePreview();
                return;
            }

            if (validCoupons.includes(couponCode)) {
                couponMsg.style.color = "#22c55e";
                couponMsg.innerText = "Coupon applied";
                couponApplied = true;
                couponInput.value = couponCode;
            } else {
                couponMsg.style.color = "#f43f5e";
                couponMsg.innerText = "Invalid coupon code";
                couponApplied = false;
            }

            updatePreview();
        }

        function goNext() {
            if (!selectedPlan) {
                planError.innerText = "Please select a plan";
                return;
            }

            const couponCode = couponInput.value.trim().toUpperCase();

            if (couponCode !== "" && !couponApplied) {
                couponMsg.style.color = "#f43f5e";
                couponMsg.innerText = "Please click Apply first";
                return;
            }

            const form = document.createElement("form");
            form.method = "POST";
            form.action = "step3.php";
            form.innerHTML = `
                <input type="hidden" name="plan_name" value="${selectedPlan}">
                <input type="hidden" name="coupon_code" value="${couponCode}">
            `;
            document.body.appendChild(form);
            form.submit();
        }

        window.addEventListener("DOMContentLoaded", function() {
            const prefilledCoupon = couponInput.value.trim().toUpperCase();

            if (prefilledCoupon !== "" && validCoupons.includes(prefilledCoupon)) {
                couponApplied = true;
                couponMsg.style.color = "#22c55e";
                couponMsg.innerText = "Offer selected from offers page";
                updatePreview();
            }
        });
    </script>
</body>

</html>