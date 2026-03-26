
<?php
session_start();
if (isset($_POST['plan_name'])) {
    $_SESSION['reg_plan'] = $_POST['plan_name'];
    header("Location: step4.php");
    exit();
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
            background: transparent;
        }

        .logo {
            color: #0ea5e9;
            font-size: 32px;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
            text-shadow: 0 2px 10px rgba(14, 165, 233, 0.3);
        }

        .btn-signout {
            background: rgba(255, 255, 255, 0.05);
            color: #fff;
            padding: 10px 20px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            border: 1px solid rgba(255, 255, 255, 0.15);
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-signout:hover {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.3);
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
            text-align: center;
        }

        .step {
            color: #0ea5e9;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 12px;
        }

        h1 {
            font-size: 32px;
            font-weight: 600;
            margin-bottom: 40px;
            color: #ffffff;
        }

        .plans {
            display: flex;
            justify-content: center;
            gap: 25px;
            flex-wrap: wrap;
        }

        .plan {
            flex: 1;
            min-width: 260px;
            max-width: 300px;
            background: rgba(30, 41, 59, 0.65);
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            overflow: hidden;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            position: relative;
        }

        .plan:hover {
            transform: translateY(-5px);
            border-color: rgba(14, 165, 233, 0.5);
        }

        .plan.selected {
            border: 3px solid #0ea5e9;
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 15px 35px rgba(14, 165, 233, 0.25);
        }

        .plan-header {
            padding: 20px;
            color: #fff;
            font-size: 22px;
            font-weight: 700;
        }

        .plan-header small {
            font-size: 14px;
            font-weight: 400;
            opacity: 0.9;
            display: block;
            margin-top: 5px;
        }

        .mobile {
            background: linear-gradient(135deg, #0284c7, #0369a1);
        }

        .basic {
            background: linear-gradient(135deg, #4f46e5, #4338ca);
        }

        .standard {
            background: linear-gradient(135deg, #6366f1, #3730a3);
        }

        .premium {
            background: linear-gradient(135deg, #0ea5e9, #0284c7);
        }

        .popular {
            background: #0ea5e9;
            color: #fff;
            text-align: center;
            padding: 6px;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .plan-body {
            padding: 20px;
            text-align: left;
        }

        .row {
            padding: 12px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .row:last-child {
            border-bottom: none;
        }

        .label {
            color: #94a3b8;
            font-size: 14px;
            font-weight: 500;
        }

        .value {
            font-weight: 700;
            color: #fff;
            font-size: 16px;
        }

        .validation-msg {
            color: #f43f5e;
            font-size: 13px;
            margin-top: 10px;
            text-align: center;
            height: 15px;
        }

        .coupon-box {
            margin: 50px auto 20px;
            max-width: 450px;
            display: flex;
            gap: 12px;
        }

        .coupon-box input {
            flex: 1;
            padding: 16px 20px;
            font-size: 15px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            background: rgba(15, 23, 42, 0.5);
            color: #fff;
            transition: all 0.3s;
        }

        .coupon-box input:focus {
            outline: none;
            border-color: #0ea5e9;
            background: rgba(15, 23, 42, 0.8);
            box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.15);
        }

        .coupon-box button {
            padding: 0 25px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff;
            font-weight: 600;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .coupon-box button:hover {
            background: #0ea5e9;
            border-color: #0ea5e9;
        }

        .btn-wrap {
            text-align: center;
            margin: 40px 0;
        }

        .btn-primary {
            padding: 18px 60px;
            font-size: 20px;
            background: linear-gradient(to right, #0ea5e9, #0284c7);
            color: #fff;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 600;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 4px 15px rgba(14, 165, 233, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(14, 165, 233, 0.4);
        }

        @media (max-width: 768px) {
            .plans {
                flex-direction: column;
                align-items: center;
            }

            .plan {
                width: 100%;
                max-width: 400px;
            }

            .coupon-box {
                flex-direction: column;
                padding: 0 20px;
            }

            .coupon-box button {
                padding: 16px;
            }
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
        <h1>Choose the plan that's right for you</h1>

        <div class="plans">

            <div class="plan" data-price="149">
                <div class="plan-header mobile">Mobile<br><small>480p Resolution</small></div>
                <div class="plan-body">
                    <div class="row">
                        <div class="label">Monthly price</div>
                        <div class="value">₹149</div>
                    </div>
                    <div class="validation-msg"></div>
                </div>
            </div>

            <div class="plan" data-price="199">
                <div class="popular">Most Popular</div>
                <div class="plan-header basic">Basic<br><small>720p Resolution</small></div>
                <div class="plan-body">
                    <div class="row">
                        <div class="label">Monthly price</div>
                        <div class="value">₹199</div>
                    </div>
                    <div class="validation-msg"></div>
                </div>
            </div>

            <div class="plan" data-price="499">
                <div class="plan-header standard">Standard<br><small>1080p Resolution</small></div>
                <div class="plan-body">
                    <div class="row">
                        <div class="label">Monthly price</div>
                        <div class="value">₹499</div>
                    </div>
                    <div class="validation-msg"></div>
                </div>
            </div>

            <div class="plan" data-price="649">
                <div class="plan-header premium">Premium<br><small>4K + HDR</small></div>
                <div class="plan-body">
                    <div class="row">
                        <div class="label">Monthly price</div>
                        <div class="value">₹649</div>
                    </div>
                    <div class="validation-msg"></div>
                </div>
            </div>

        </div>

        <div class="coupon-box">
            <input type="text" id="couponCode" placeholder="Enter coupon code">
            <button onclick="applyCoupon()">Apply</button>
        </div>

        <div class="btn-wrap">
            <button class="btn-primary" onclick="goNext()">Next</button>
        </div>
    </div>

    <script>
        let selectedPlan = null;
        const plans = document.querySelectorAll(".plan");

        plans.forEach(plan => {
            plan.addEventListener("click", () => {
                plans.forEach(p => {
                    p.classList.remove("selected");
                    p.querySelector(".validation-msg").innerText = "";
                });
                plan.classList.add("selected");
                selectedPlan = plan;
            });
        });

        function goNext() {
            if (!selectedPlan) {
                alert("Please select a plan");
                return;
            }

            let planName = selectedPlan.querySelector(".plan-header").innerText.split('\n')[0];

            let form = document.createElement("form");
            form.method = "POST";
            form.innerHTML = `<input type="hidden" name="plan_name" value="${planName}">`;
            document.body.appendChild(form);
            form.submit();
        }

        function applyCoupon() {
            const couponInput = document.getElementById('couponCode').value;
            if (couponInput.trim() !== "") {
                alert("Checking coupon code: " + couponInput);
            } else {
                alert("Please enter a valid coupon code.");
            }
        }
    </script>
</body>

</html>