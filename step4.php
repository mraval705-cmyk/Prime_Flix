<?php
session_start();
include "db.php";

$showSuccess = false;

if (!isset($_SESSION['reg_email']) || !isset($_SESSION['reg_password'])) {
    header("Location: step2.php");
    exit();
}

if (isset($_GET['payment_done']) && $_GET['payment_done'] == '1') {

    if (isset($_SESSION['reg_email']) && isset($_SESSION['reg_password'])) {

        $email = $_SESSION['reg_email'];
        $pass = $_SESSION['reg_password'];
        $plan = isset($_SESSION['reg_plan']) ? $_SESSION['reg_plan'] : 'Standard';
        $payment_method = isset($_GET['method']) ? $_GET['method'] : 'Unknown';
        $upi_app = isset($_GET['upi_app']) ? $_GET['upi_app'] : '';
        $upi_id = isset($_GET['upi_id']) ? $_GET['upi_id'] : '';

        // Plan ke hisab se amount set karo
        $amount = 0;
        if ($plan == 'Mobile') {
            $amount = 149;
        } elseif ($plan == 'Basic') {
            $amount = 199;
        } elseif ($plan == 'Standard') {
            $amount = 499;
        } elseif ($plan == 'Premium') {
            $amount = 649;
        }

        $email_safe = mysqli_real_escape_string($conn, $email);
        $pass_safe = mysqli_real_escape_string($conn, $pass);
        $plan_safe = mysqli_real_escape_string($conn, $plan);
        $method_safe = mysqli_real_escape_string($conn, $payment_method);
        $upi_app_safe = mysqli_real_escape_string($conn, $upi_app);
        $upi_id_safe = mysqli_real_escape_string($conn, $upi_id);

        $check = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email_safe'");

        if (mysqli_num_rows($check) == 0) {
            $sql = "INSERT INTO users (email, password, plan, payment_status) 
                    VALUES ('$email_safe', '$pass_safe', '$plan_safe', 'Success')";
            mysqli_query($conn, $sql);
        }

        // payment record save karo
        mysqli_query($conn, "INSERT INTO payments (email, plan, amount, payment_method, upi_app, upi_id) 
                             VALUES ('$email_safe', '$plan_safe', '$amount', '$method_safe', '$upi_app_safe', '$upi_id_safe')");

        $showSuccess = true;
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
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.9) 0%, rgba(2, 6, 23, 0.8) 100%);
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
            max-width: 480px;
            margin: 40px auto;
            background: rgba(30, 41, 59, 0.65);
            padding: 40px;
            border-radius: 20px;
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
        }

        .back {
            color: #0ea5e9;
            cursor: pointer;
            font-size: 14px;
            margin-bottom: 25px;
            display: inline-block;
        }

        .step {
            color: #0ea5e9;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 12px;
        }

        h1 {
            font-size: 26px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #ffffff;
        }

        p {
            color: #94a3b8;
            font-size: 15px;
            margin-bottom: 15px;
        }

        input,
        select {
            width: 100%;
            padding: 16px 20px;
            font-size: 15px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            background: rgba(15, 23, 42, 0.5);
            color: #fff;
            margin-bottom: 6px;
        }

        .row {
            display: flex;
            gap: 15px;
        }

        .plan {
            background: linear-gradient(135deg, rgba(14, 165, 233, 0.1), rgba(2, 132, 199, 0.1));
            border: 1px solid rgba(14, 165, 233, 0.3);
            padding: 18px;
            border-radius: 12px;
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
        }

        .pay-box {
            border: 1px solid rgba(255, 255, 255, 0.15);
            background: rgba(15, 23, 42, 0.5);
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 15px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        button {
            width: 100%;
            background: linear-gradient(to right, #0ea5e9, #0284c7);
            color: #fff;
            border: none;
            padding: 16px;
            font-size: 18px;
            font-weight: 600;
            border-radius: 12px;
            margin-top: 25px;
            cursor: pointer;
        }

        .error {
            color: #f43f5e;
            font-size: 13px;
            margin-bottom: 10px;
            text-align: left;
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
            <?php if ($showSuccess): ?>
                <div style="text-align:center;">
                    <div style="font-size:65px; margin-bottom: 20px;">🎉</div>
                    <h1 style="color:#0ea5e9;">Welcome to Watchwise</h1>
                    <p style="color: #e2e8f0;">Your premium membership is active now.</p>
                    <button onclick="location.href='Signup.php'">Start Watching</button>
                </div>
                <?php
                unset($_SESSION['reg_email']);
                unset($_SESSION['reg_password']);
                unset($_SESSION['reg_plan']);
                ?>
            <?php else: ?>
                <div id="payment-content"></div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        const content = document.getElementById("payment-content");

        function choosePayment() {
            if (!content) return;
            content.innerHTML = `
                <div style="text-align:center">
                    <div style="font-size:45px; margin-bottom: 10px;">🛡️</div>
                    <div class="step">Step 3 of 3</div>
                    <h1>Choose how to pay</h1>
                    <p>Your payment is encrypted and you can change how you pay anytime.</p>
                    <div class="pay-box" onclick="cardPage()">
                        <span>Credit or Debit Card</span><span style="color: #0ea5e9;">❯</span>
                    </div>
                    <div class="pay-box" onclick="upiPage()">
                        <span>UPI AutoPay</span><span style="color: #0ea5e9;">❯</span>
                    </div>
                </div>`;
        }

        function cardPage() {
            content.innerHTML = `
                <div class="back" onclick="choosePayment()">‹ Change payment method</div>
                <div class="step">Step 3 of 3</div>
                <h1>Set up your card</h1>
                <input id="cardNumber" placeholder="Card number" maxlength="19">
                <div id="cardErr" class="error"></div>
                <div class="row">
                    <div style="flex:1"><input id="expiry" placeholder="MM/YY" maxlength="5"><div id="expiryErr" class="error"></div></div>
                    <div style="flex:1"><input id="cvv" placeholder="CVV" maxlength="3" type="password"><div id="cvvErr" class="error"></div></div>
                </div>
                <input id="cardName" placeholder="Name on card">
                <div id="nameErr" class="error"></div>
                <button onclick="validateCard()">Start Membership</button>
            `;
            formatCardInput();
            formatExpiryInput();
        }

        function upiPage() {
            content.innerHTML = `
                <div class="back" onclick="choosePayment()">‹ Change payment method</div>
                <div class="step">Step 3 of 3</div>
                <h1>Set up UPI AutoPay</h1>
                <select id="upiApp">
                    <option value="">Select UPI app</option>
                    <option>Google Pay</option>
                    <option>PhonePe</option>
                    <option>Paytm</option>
                </select>
                <div id="upiAppErr" class="error"></div>
                <input id="upiId" placeholder="example@upi">
                <div id="upiErr" class="error"></div>
                <button onclick="validateUPI()">Next</button>
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

            if (card.length < 16) {
                document.getElementById("cardErr").innerText = "Invalid Card";
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
                successPage('Card');
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

        function successPage(method, upiApp = '', upiId = '') {
            let url = "step4.php?payment_done=1&method=" + encodeURIComponent(method);

            if (upiApp !== '') {
                url += "&upi_app=" + encodeURIComponent(upiApp);
            }

            if (upiId !== '') {
                url += "&upi_id=" + encodeURIComponent(upiId);
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

        <?php if (!$showSuccess): ?>
            choosePayment();
        <?php endif; ?>
    </script>
</body>

</html>