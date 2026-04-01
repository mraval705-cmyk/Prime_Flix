<?php
session_start();

if (!isset($_SESSION['otp_verified']) || $_SESSION['otp_verified'] !== true || !isset($_SESSION['forgot_email'])) {
    header("Location: forgot_password.php");
    exit();
}

$email = $_SESSION['forgot_email'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Reset Password - WatchWise</title>
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

        .email-badge {
            text-align: center;
            background: rgba(56, 189, 248, 0.12);
            color: #bae6fd;
            padding: 10px 12px;
            border-radius: 12px;
            font-size: 14px;
            margin-bottom: 18px;
            word-break: break-word;
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
            border: 1px solid transparent;
            outline: none;
            border-radius: 14px;
            font-size: 15px;
            background: #f1f5f9;
            color: #0f172a;
            transition: 0.2s ease;
        }

        input:focus {
            border-color: #38bdf8;
            box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.18);
        }

        .input-error {
            border-color: #f87171 !important;
            box-shadow: 0 0 0 3px rgba(248, 113, 113, 0.12);
        }

        .error-text {
            color: #fda4af;
            font-size: 13px;
            margin-top: 7px;
            padding-left: 3px;
            display: none;
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
            margin-top: 6px;
            transition: 0.25s;
        }

        .btn:hover {
            transform: translateY(-2px);
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
    </style>
</head>

<body>

    <div class="card">
        <div class="logo">WATCHWISE</div>
        <h2>Reset Password</h2>
        <p class="subtitle">Create your new password below.</p>

        <div class="email-badge"><?php echo htmlspecialchars($email); ?></div>

        <div class="field">
            <label>New Password</label>
            <input type="password" id="new_password" placeholder="Enter new password">
            <div id="newPassError" class="error-text"></div>
        </div>

        <div class="field">
            <label>Confirm Password</label>
            <input type="password" id="confirm_password" placeholder="Confirm new password">
            <div id="confirmPassError" class="error-text"></div>
        </div>

        <button class="btn" onclick="resetPassword()">Reset Password</button>

        <div id="message" class="msg"></div>
    </div>

    <script>
        function showMessage(text, type) {
            const msg = document.getElementById("message");
            msg.className = "msg " + type;
            msg.innerText = text;
        }

        function clearErrors() {
            document.getElementById("newPassError").style.display = "none";
            document.getElementById("confirmPassError").style.display = "none";

            document.getElementById("new_password").classList.remove("input-error");
            document.getElementById("confirm_password").classList.remove("input-error");

            document.getElementById("message").innerText = "";
            document.getElementById("message").className = "msg";
        }

        function setError(inputId, errorId, message) {
            document.getElementById(inputId).classList.add("input-error");
            const errorBox = document.getElementById(errorId);
            errorBox.innerText = message;
            errorBox.style.display = "block";
        }

        function resetPassword() {
            clearErrors();

            const new_password = document.getElementById("new_password").value.trim();
            const confirm_password = document.getElementById("confirm_password").value.trim();

            let isValid = true;

            if (new_password === "") {
                setError("new_password", "newPassError", "Please enter a new password.");
                isValid = false;
            } else if (new_password.length < 6) {
                setError("new_password", "newPassError", "Password must be at least 6 characters.");
                isValid = false;
            }

            if (confirm_password === "") {
                setError("confirm_password", "confirmPassError", "Please confirm your password.");
                isValid = false;
            } else if (new_password !== "" && new_password !== confirm_password) {
                setError("confirm_password", "confirmPassError", "Passwords do not match.");
                isValid = false;
            }

            if (!isValid) {
                return;
            }

            const formData = new FormData();
            formData.append("action", "reset_password");
            formData.append("email", "<?php echo htmlspecialchars($email); ?>");
            formData.append("otp", "verified_session");
            formData.append("new_password", new_password);
            formData.append("confirm_password", confirm_password);

            fetch("forgot_password_ajax.php", {
                    method: "POST",
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    showMessage(data.message, data.status === "success" ? "success" : "error");

                    if (data.status === "success") {
                        setTimeout(() => {
                            window.location.href = "Signup.php";
                        }, 1200);
                    }
                })
                .catch(() => {
                    showMessage("Something went wrong. Please try again.", "error");
                });
        }
    </script>

</body>


</html>