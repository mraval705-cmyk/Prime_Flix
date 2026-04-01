<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Forgot Password - WatchWise</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background:
                linear-gradient(rgba(3, 10, 30, 0.82), rgba(3, 10, 30, 0.88)),
                url('https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?auto=format&fit=crop&w=1400&q=80');
            background-size: cover;
            background-position: center;
            padding: 20px;
        }

        .card {
            width: 100%;
            max-width: 420px;
            background: rgba(12, 20, 40, 0.88);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 24px;
            padding: 35px 28px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.45);
            backdrop-filter: blur(14px);
        }

        .logo {
            text-align: center;
            color: #38bdf8;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 2px;
            margin-bottom: 14px;
        }

        h2 {
            text-align: center;
            color: #fff;
            font-size: 32px;
            margin-bottom: 10px;
        }

        .subtitle {
            text-align: center;
            color: #cbd5e1;
            font-size: 14px;
            line-height: 1.5;
            margin-bottom: 24px;
        }

        .field {
            margin-bottom: 16px;
        }

        .field label {
            display: block;
            color: #e2e8f0;
            font-size: 14px;
            margin-bottom: 8px;
            font-weight: 600;
        }

        input {
            width: 100%;
            padding: 15px 16px;
            border: none;
            outline: none;
            border-radius: 14px;
            font-size: 15px;
            background: #f1f5f9;
            color: #0f172a;
        }

        .btn {
            width: 100%;
            border: none;
            border-radius: 14px;
            padding: 15px;
            font-size: 17px;
            font-weight: 700;
            color: #fff;
            cursor: pointer;
            background: linear-gradient(90deg, #0ea5e9, #2563eb);
            margin-top: 4px;
            transition: 0.25s;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .otp-row {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .otp-row input {
            flex: 1;
        }

        .otp-row button {
            width: 150px;
            margin-top: 0;
        }

        .msg {
            text-align: center;
            margin-top: 18px;
            font-size: 15px;
            min-height: 22px;
            font-weight: 600;
        }

        .success {
            color: #86efac;
        }

        .error {
            color: #fda4af;
        }

        .back {
            display: block;
            text-align: center;
            margin-top: 18px;
            color: #38bdf8;
            text-decoration: none;
            font-size: 15px;
        }

        .hidden {
            display: none;
        }
    </style>
</head>

<body>

    <div class="card">
        <div class="logo">WATCHWISE</div>
        <h2>Forgot Password</h2>
        <p class="subtitle">Enter your email, get OTP, and verify it to continue.</p>

        <div class="field">
            <label>Email Address</label>
            <input type="email" id="email" placeholder="Enter your registered email">
        </div>

        <button class="btn" onclick="sendOTP()">Send OTP</button>

        <div id="otpBox" class="hidden">
            <div class="field" style="margin-top:18px;">
                <label>Enter OTP</label>
                <div class="otp-row">
                    <input type="text" id="otp" placeholder="6-digit OTP">
                    <button class="btn" onclick="verifyOTP()">Verify</button>
                </div>
            </div>
        </div>

        <div id="message" class="msg"></div>

        <a href="Signup.php" class="back">Back to Sign In</a>
    </div>

    <script>
        function showMessage(text, type) {
            const msg = document.getElementById("message");
            msg.className = "msg " + type;
            msg.innerText = text;
        }

        function sendOTP() {
            const email = document.getElementById("email").value.trim();

            const formData = new FormData();
            formData.append("action", "send_otp");
            formData.append("email", email);

            fetch("forgot_password_ajax.php", {
                    method: "POST",
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    showMessage(data.message, data.status === "success" ? "success" : "error");
                    if (data.status === "success") {
                        document.getElementById("otpBox").classList.remove("hidden");
                    }
                });
        }

        function verifyOTP() {
            const email = document.getElementById("email").value.trim();
            const otp = document.getElementById("otp").value.trim();

            const formData = new FormData();
            formData.append("action", "verify_otp");
            formData.append("email", email);
            formData.append("otp", otp);

            fetch("forgot_password_ajax.php", {
                    method: "POST",
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    showMessage(data.message, data.status === "success" ? "success" : "error");

                    if (data.status === "success") {
                        setTimeout(() => {
                            window.location.href = "reset_password.php";
                        }, 700);
                    }
                });
        }
    </script>

</body>

</html>