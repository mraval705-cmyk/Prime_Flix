<?php
session_start();
include "db.php";

/*
    NEW USER FLOW:
    reg_email, reg_password, reg_plan etc. session se aayega

    EXISTING USER UPGRADE FLOW:
    user_id session se aayega
    selected_plan GET se aayega
*/

$isUpgrade = isset($_SESSION['user_id']) && isset($_GET['selected_plan']);
if ($isUpgrade) {
    $userId = (int)($_SESSION['user_id'] ?? 0);

    if ($userId <= 0) {
        header("Location: user_movie.php");
        exit();
    }

    $userQuery = mysqli_query($conn, "SELECT * FROM users WHERE id = $userId LIMIT 1");
    if (!$userQuery || mysqli_num_rows($userQuery) === 0) {
        die("User not found.");
    }

    $userRow = mysqli_fetch_assoc($userQuery);

    $name = $userRow['name'] ?? 'Watchwise User';
    $email = $userRow['email'] ?? '';
    $pass = $userRow['password'] ?? '';
    $plan = trim($_GET['selected_plan'] ?? '');
    if ($plan === '') {
        header("Location: manage_subscription.php");
        exit();
    }

    $coupon = strtoupper(trim($_GET['coupon'] ?? ''));
} else {
    if (!isset($_SESSION['reg_email']) || !isset($_SESSION['reg_password'])) {
        header("Location: step2.php");
        exit();
    }

    $name = $_SESSION['reg_name'] ?? '';
    $email = $_SESSION['reg_email'] ?? '';
    $pass = $_SESSION['reg_password'] ?? '';
    $plan = $_SESSION['reg_plan'] ?? '';
    $coupon = strtoupper(trim($_SESSION['reg_coupon'] ?? ''));
}

$payment_method = $_GET['method'] ?? '';
$upi_app = $_GET['upi_app'] ?? '';
$upi_id = $_GET['upi_id'] ?? '';

$card_name = $_GET['card_name'] ?? '';
$card_number = $_GET['card_number'] ?? '';
$expiry_date = $_GET['expiry_date'] ?? '';

$amount = 0;
$plan_resolution = '';
$plan_safe_fetch = mysqli_real_escape_string($conn, $plan);

$plan_query = mysqli_query($conn, "SELECT * FROM plans WHERE plan_name='$plan_safe_fetch' AND is_active=1 LIMIT 1");
if ($plan_query && mysqli_num_rows($plan_query) > 0) {
    $plan_row = mysqli_fetch_assoc($plan_query);
    $amount = (float)($plan_row['price'] ?? 0);
    $plan_resolution = $plan_row['resolution'] ?? '';
}

$discount = 0;
$final_amount = $amount;
$coupon_message = "";
$coupon_safe = "";
$offer_badge = "No offer";
$offer_status = "normal";

if (!empty($coupon)) {
    $coupon_safe = mysqli_real_escape_string($conn, $coupon);

    $offer_q = mysqli_query($conn, "SELECT * FROM offers
        WHERE coupon_code = '$coupon_safe'
        AND is_active = 1
        AND valid_until >= CURDATE()
        LIMIT 1");

    if ($offer_q && mysqli_num_rows($offer_q) > 0) {
        $offer = mysqli_fetch_assoc($offer_q);

        if ($amount >= (float)$offer['min_amount']) {
            if ($offer['discount_type'] == 'percent') {
                $discount = ($amount * (float)$offer['discount_value']) / 100;
                $offer_badge = (int)$offer['discount_value'] . "% OFF";
            } else {
                $discount = (float)$offer['discount_value'];
                $offer_badge = "₹" . (int)$offer['discount_value'] . " OFF";
            }

            $final_amount = $amount - $discount;

            if ($final_amount < 0) {
                $final_amount = 0;
            }

            $coupon_message = "Saved ₹" . (int)$discount . " with " . $coupon;
            $offer_status = "success";
        } else {
            $coupon_message = "Coupon is valid but minimum amount is not matched.";
            $offer_badge = "Min ₹" . (int)$offer['min_amount'];
            $offer_status = "warning";
        }
    } else {
        $coupon_message = "Invalid or expired coupon code.";
        $offer_badge = "Invalid coupon";
        $offer_status = "error";
    }
}

$paymentMethods = [];
$payment_methods_q = mysqli_query($conn, "SELECT method_key, display_name FROM payment_methods WHERE is_active=1 ORDER BY sort_order ASC, id ASC");
if ($payment_methods_q) {
    while ($pm = mysqli_fetch_assoc($payment_methods_q)) {
        $paymentMethods[] = $pm;
    }
}

$upiApps = [];
$upi_apps_q = mysqli_query($conn, "SELECT app_name FROM upi_apps WHERE is_active=1 ORDER BY sort_order ASC, id ASC");
if ($upi_apps_q) {
    while ($ua = mysqli_fetch_assoc($upi_apps_q)) {
        $upiApps[] = $ua['app_name'];
    }
}

if (isset($_GET['payment_done']) && $_GET['payment_done'] == '1') {

    if (empty($name) && !empty($card_name)) {
        $name = $card_name;
    }

    if (empty($name)) {
        $name = 'Watchwise User';
    }

    $name_safe = mysqli_real_escape_string($conn, $name);
    $email_safe = mysqli_real_escape_string($conn, $email);
    $pass_safe = mysqli_real_escape_string($conn, $pass);
    $plan_safe = mysqli_real_escape_string($conn, $plan);
    $method_safe = mysqli_real_escape_string($conn, $payment_method);

    $upi_app_safe = mysqli_real_escape_string($conn, $upi_app);
    $upi_id_safe = mysqli_real_escape_string($conn, $upi_id);

    $card_name_safe = mysqli_real_escape_string($conn, $card_name);
    $card_number_safe = mysqli_real_escape_string($conn, $card_number);
    $expiry_date_safe = mysqli_real_escape_string($conn, $expiry_date);

    if ($email_safe != "") {

        if ($isUpgrade) {
            $update_user = mysqli_query($conn, "UPDATE users
                SET name='$name_safe', plan='$plan_safe', payment_status='Success'
                WHERE email='$email_safe'");

            if (!$update_user) {
                die("User update error: " . mysqli_error($conn));
            }
        } else {
            if ($pass_safe == "") {
                die("Password session missing.");
            }

            $check = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email_safe' LIMIT 1");

            if ($check && mysqli_num_rows($check) == 0) {
                $sql = "INSERT INTO users (name, email, password, plan, payment_status)
                        VALUES ('$name_safe', '$email_safe', '$pass_safe', '$plan_safe', 'Success')";
                $insert_user = mysqli_query($conn, $sql);

                if (!$insert_user) {
                    die("User insert error: " . mysqli_error($conn));
                }
            } else {
                $update_user = mysqli_query($conn, "UPDATE users
                    SET name='$name_safe', password='$pass_safe', plan='$plan_safe', payment_status='Success'
                    WHERE email='$email_safe'");

                if (!$update_user) {
                    die("User update error: " . mysqli_error($conn));
                }
            }
        }

        $payment_sql = "INSERT INTO payments
            (email, plan, amount, payment_method, upi_app, upi_id, card_name, card_number, expiry_date, coupon_code, discount_amount, final_amount)
            VALUES
            ('$email_safe', '$plan_safe', '$amount', '$method_safe', '$upi_app_safe', '$upi_id_safe', '$card_name_safe', '$card_number_safe', '$expiry_date_safe', '$coupon_safe', '$discount', '$final_amount')";

        if (mysqli_query($conn, $payment_sql)) {

            if ($isUpgrade) {
                header("Location: update_success.php?plan=" . urlencode($plan) . "&amount=" . urlencode((string)$final_amount));
                exit();
            } else {
                $_SESSION['payment_success'] = "yes";
                header("Location: Signup.php");
                exit();
            }
        } else {
            die("Payment insert error: " . mysqli_error($conn));
        }
    } else {
        die("Email session missing.");
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Watchwise - Payment</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: url("img/Screenshot 2026-01-01 184050.png") center center / cover no-repeat fixed;
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
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.92) 0%, rgba(2, 6, 23, 0.86) 100%);
            z-index: -1;
        }

        header {
            padding: 25px 40px;
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

        .signout {
            background: rgba(255, 255, 255, 0.05);
            color: #fff;
            padding: 10px 20px;
            border-radius: 10px;
            cursor: pointer;
            border: 1px solid rgba(255, 255, 255, 0.15);
            text-decoration: none;
        }

        .container {
            width: 100%;
            max-width: 470px;
            margin: 34px auto;
            background: rgba(30, 41, 59, 0.68);
            padding: 28px;
            border-radius: 22px;
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
        }

        .back {
            color: #0ea5e9;
            cursor: pointer;
            font-size: 13px;
            margin-bottom: 18px;
            display: inline-block;
        }

        .step {
            color: #0ea5e9;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 10px;
            letter-spacing: 1px;
        }

        h1 {
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 16px;
            color: #ffffff;
        }

        p {
            color: #94a3b8;
            font-size: 14px;
            margin-bottom: 12px;
            line-height: 1.5;
        }

        input,
        select {
            width: 100%;
            padding: 14px 16px;
            font-size: 14px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            background: rgba(15, 23, 42, 0.5);
            color: #fff;
            margin-bottom: 6px;
            outline: none;
        }

        input::placeholder {
            color: #cbd5e1;
        }

        select option {
            color: #000;
        }

        .row {
            display: flex;
            gap: 12px;
        }

        .pay-box {
            border: 1px solid rgba(255, 255, 255, 0.15);
            background: rgba(15, 23, 42, 0.5);
            padding: 16px 18px;
            border-radius: 12px;
            margin-bottom: 12px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 15px;
            transition: 0.25s ease;
        }

        .pay-box:hover {
            border-color: rgba(14, 165, 233, 0.45);
            transform: translateY(-2px);
        }

        button {
            width: 100%;
            background: linear-gradient(to right, #0ea5e9, #0284c7);
            color: #fff;
            border: none;
            padding: 15px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 12px;
            margin-top: 18px;
            cursor: pointer;
        }

        .error {
            color: #f43f5e;
            font-size: 12px;
            margin-bottom: 8px;
            text-align: left;
        }

        .summary-box {
            background: rgba(15, 23, 42, 0.58);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 16px;
            padding: 16px;
            margin-bottom: 18px;
            text-align: left;
        }

        .summary-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 10px;
        }

        .summary-title {
            font-size: 16px;
            font-weight: 700;
            color: #ffffff;
        }

        .plan-chip {
            display: inline-block;
            font-size: 11px;
            font-weight: 700;
            color: #dbeafe;
            background: rgba(14, 165, 233, 0.12);
            border: 1px solid rgba(14, 165, 233, 0.28);
            padding: 6px 10px;
            border-radius: 999px;
            margin-top: 8px;
        }

        .offer-badge {
            display: inline-block;
            font-size: 11px;
            font-weight: 700;
            color: #fff;
            padding: 7px 10px;
            border-radius: 999px;
            white-space: nowrap;
        }

        .offer-normal {
            background: linear-gradient(135deg, #f97316, #ea580c);
        }

        .offer-success {
            background: linear-gradient(135deg, #16a34a, #15803d);
        }

        .offer-warning {
            background: linear-gradient(135deg, #eab308, #ca8a04);
        }

        .offer-error {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
        }

        .bill-grid {
            margin-top: 8px;
        }

        .bill-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            font-size: 13px;
            color: #e2e8f0;
        }

        .bill-row .left {
            color: #cbd5e1;
        }

        .bill-row .right {
            color: #ffffff;
            font-weight: 600;
        }

        .strike {
            text-decoration: line-through;
            color: #94a3b8 !important;
        }

        .discount-amount {
            color: #22c55e !important;
        }

        .bill-divider {
            height: 1px;
            background: rgba(255, 255, 255, 0.08);
            margin: 8px 0;
        }

        .final-row {
            padding-top: 10px;
        }

        .final-row .left {
            font-size: 14px;
            font-weight: 700;
            color: #ffffff;
        }

        .final-row .right {
            font-size: 20px;
            font-weight: 800;
            color: #0ea5e9;
        }

        .coupon-chip {
            display: inline-block;
            font-size: 11px;
            font-weight: 600;
            color: #e2e8f0;
            background: rgba(14, 165, 233, 0.12);
            border: 1px solid rgba(14, 165, 233, 0.25);
            padding: 6px 10px;
            border-radius: 999px;
            margin-top: 10px;
            margin-right: 8px;
        }

        .coupon-msg {
            font-size: 12px;
            margin-top: 10px;
            line-height: 1.4;
        }

        .coupon-msg.success {
            color: #22c55e;
        }

        .coupon-msg.warning {
            color: #facc15;
        }

        .coupon-msg.error-text {
            color: #f87171;
        }

        @media (max-width: 560px) {
            header {
                padding: 20px;
            }

            .container {
                width: calc(100% - 24px);
                margin: 24px auto;
                padding: 22px;
            }

            .row {
                flex-direction: column;
                gap: 0;
            }

            .summary-top {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>

<body>

    <header>
        <div class="logo">Watchwise</div>
        <div onclick="logout()" class="signout">Sign Out</div>
    </header>

    <div class="container">
        <div id="page">
            <div id="payment-content"></div>
        </div>
    </div>

    <script>
        const content = document.getElementById("payment-content");
        const paymentMethods = <?= json_encode($paymentMethods) ?>;
        const upiApps = <?= json_encode($upiApps) ?>;
        const isUpgrade = <?= $isUpgrade ? 'true' : 'false' ?>;
        const stepHtml = isUpgrade ? '' : '<div class="step">Step 3 of 3</div>';

        const paymentSummaryHtml = `
        <div class="summary-box">
            <div class="summary-top">
                <div>
                    <div class="summary-title">Bill Details</div>
                    <div class="plan-chip"><?= htmlspecialchars($plan) ?><?= !empty($plan_resolution) ? ' • ' . htmlspecialchars($plan_resolution) : '' ?></div>
                </div>
                <div class="offer-badge offer-<?= htmlspecialchars($offer_status) ?>">
                    <?= htmlspecialchars($offer_badge) ?>
                </div>
            </div>

            <div class="bill-grid">
                <div class="bill-row">
                    <span class="left">Selected Plan</span>
                    <span class="right"><?= htmlspecialchars($plan) ?></span>
                </div>

                <div class="bill-row">
                    <span class="left">Original Price</span>
                    <span class="right strike">₹<?= (int)$amount ?></span>
                </div>

                <div class="bill-row">
                    <span class="left">Discount</span>
                    <span class="right discount-amount">- ₹<?= (int)$discount ?></span>
                </div>

                <div class="bill-divider"></div>

                <div class="bill-row final-row">
                    <span class="left">Final Price</span>
                    <span class="right">₹<?= (int)$final_amount ?></span>
                </div>
            </div>

            <?php if (!empty($coupon)) { ?>
                <div class="coupon-chip">Coupon: <?= htmlspecialchars($coupon) ?></div>
            <?php } ?>

            <?php if (!empty($coupon_message)) { ?>
                <div class="coupon-msg <?= $offer_status === 'success' ? 'success' : ($offer_status === 'warning' ? 'warning' : ($offer_status === 'error' ? 'error-text' : '')) ?>">
                    <?= htmlspecialchars($coupon_message) ?>
                </div>
            <?php } ?>
        </div>
    `;

        function choosePayment() {
            if (!content) return;

            let boxes = '';

            paymentMethods.forEach(method => {
                if (method.method_key === 'Card') {
                    boxes += `<div class="pay-box" onclick="cardPage()">
                    <span>${method.display_name}</span><span style="color: #0ea5e9;">❯</span>
                </div>`;
                }

                if (method.method_key === 'UPI') {
                    boxes += `<div class="pay-box" onclick="upiPage()">
                    <span>${method.display_name}</span><span style="color: #0ea5e9;">❯</span>
                </div>`;
                }
            });

            content.innerHTML = `
        <div style="text-align:center">
            <div style="font-size:42px; margin-bottom: 8px;">🛡️</div>
            ${stepHtml}
            <h1>Choose how to pay</h1>
            ${paymentSummaryHtml}
            <p>Your payment is encrypted and you can change how you pay anytime.</p>
            ${boxes}
        </div>`;
        }

        function cardPage() {
            content.innerHTML = `
        <div class="back" onclick="choosePayment()">‹ Change payment method</div>
        ${stepHtml}
        <h1>Set up your card</h1>
        ${paymentSummaryHtml}

        <input id="cardNumber" placeholder="Card number" maxlength="19">
        <div id="cardErr" class="error"></div>

        <div class="row">
            <div style="flex:1">
                <input id="expiry" placeholder="MM/YY" maxlength="5">
                <div id="expiryErr" class="error"></div>
            </div>
            <div style="flex:1">
                <input id="cvv" placeholder="CVV" maxlength="3" type="password">
                <div id="cvvErr" class="error"></div>
            </div>
        </div>

        <input id="cardName" placeholder="Name on card">
        <div id="nameErr" class="error"></div>

        <button onclick="validateCard()">${isUpgrade ? 'Update Subscription' : 'Start Membership'}</button>
    `;
            formatCardInput();
            formatExpiryInput();
        }

        function upiPage() {
            let upiOptions = `<option value="">Select UPI app</option>`;
            upiApps.forEach(app => {
                upiOptions += `<option value="${app}">${app}</option>`;
            });

            content.innerHTML = `
        <div class="back" onclick="choosePayment()">‹ Change payment method</div>
        ${stepHtml}
        <h1>Set up UPI AutoPay</h1>
        ${paymentSummaryHtml}

        <select id="upiApp">
            ${upiOptions}
        </select>
        <div id="upiAppErr" class="error"></div>

        <input id="upiId" placeholder="example@upi">
        <div id="upiErr" class="error"></div>

        <button onclick="validateUPI()">${isUpgrade ? 'Update Subscription' : 'Next'}</button>
    `;
        }

        function validateCard() {
            const card = document.getElementById("cardNumber").value.trim().replace(/\s/g, "");
            const expiry = document.getElementById("expiry").value.trim();
            const cvv = document.getElementById("cvv").value.trim();
            const name = document.getElementById("cardName").value.trim();

            document.getElementById("cardErr").innerText = "";
            document.getElementById("expiryErr").innerText = "";
            document.getElementById("cvvErr").innerText = "";
            document.getElementById("nameErr").innerText = "";

            let valid = true;

            if (!/^\d{16}$/.test(card)) {
                document.getElementById("cardErr").innerText = "Invalid card number";
                valid = false;
            }

            if (!/^\d{2}\/\d{2}$/.test(expiry)) {
                document.getElementById("expiryErr").innerText = "Invalid expiry";
                valid = false;
            }

            if (!/^\d{3}$/.test(cvv)) {
                document.getElementById("cvvErr").innerText = "Invalid CVV";
                valid = false;
            }

            if (name === "") {
                document.getElementById("nameErr").innerText = "Enter card holder name";
                valid = false;
            }

            if (valid) {
                const maskedCard = "XXXX XXXX XXXX " + card.slice(-4);
                successPage('Card', '', '', name, maskedCard, expiry);
            }
        }

        function validateUPI() {
            const upiApp = document.getElementById("upiApp").value;
            const upi = document.getElementById("upiId").value.trim();

            document.getElementById("upiAppErr").innerText = "";
            document.getElementById("upiErr").innerText = "";

            let valid = true;

            if (upiApp === "") {
                document.getElementById("upiAppErr").innerText = "Please select UPI app";
                valid = false;
            }

            if (!upi.includes("@")) {
                document.getElementById("upiErr").innerText = "Invalid UPI";
                valid = false;
            }

            if (valid) {
                successPage('UPI', upiApp, upi);
            }
        }

        function successPage(method, upiApp = '', upiId = '', cardName = '', cardNumber = '', expiryDate = '') {
            let url = "step4.php?payment_done=1&method=" + encodeURIComponent(method);

            if (isUpgrade) {
                url += "&selected_plan=" + encodeURIComponent("<?= htmlspecialchars($plan) ?>");
            }

            if (method === 'UPI') {
                url += "&upi_app=" + encodeURIComponent(upiApp);
                url += "&upi_id=" + encodeURIComponent(upiId);
            }

            if (method === 'Card') {
                url += "&card_name=" + encodeURIComponent(cardName);
                url += "&card_number=" + encodeURIComponent(cardNumber);
                url += "&expiry_date=" + encodeURIComponent(expiryDate);
            }

            window.location.href = url;
        }

        function formatCardInput() {
            const cardInput = document.getElementById("cardNumber");
            if (cardInput) {
                cardInput.addEventListener("input", function() {
                    this.value = this.value.replace(/\D/g, '').replace(/(\d{4})(?=\d)/g, '$1 ');
                });
            }
        }

        function formatExpiryInput() {
            const expiryInput = document.getElementById("expiry");
            if (expiryInput) {
                expiryInput.addEventListener("input", function() {
                    let value = this.value.replace(/\D/g, '');
                    if (value.length > 2) {
                        value = value.substring(0, 2) + '/' + value.substring(2, 4);
                    }
                    this.value = value;
                });
            }
        }

        function logout() {
            window.location.href = "index.php";
        }

        choosePayment();
    </script>
</body>

</html>