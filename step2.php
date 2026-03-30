<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['reg_email'] = $_POST['email'];
    $_SESSION['reg_password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
    header("Location: step3.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Watchwise - Create a password</title>
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
            /* તમારો જૂનો બેકગ્રાઉન્ડ ઇમેજ પાથ */
            background: url("img/Screenshot 2026-01-01 184050.png") center/cover no-repeat fixed;
            min-height: 100vh;
            color: #fff;
            position: relative;
            display: flex;
            flex-direction: column;
        }

        /* ડાર્ક નેવી બ્લુ ઓવરલે */
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

        /* હેડર ડિઝાઇન */
        header {
            padding: 25px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: transparent;
        }

        .logo {
            color: #0ea5e9;
            /* Watchwise Sky Blue */
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

        /* સેન્ટર બોક્સ (Glassmorphism Effect) */
        .center-box {
            width: 100%;
            max-width: 450px;
            margin: 60px auto;
            background: rgba(30, 41, 59, 0.65);
            padding: 45px 40px;
            border-radius: 20px;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
        }

        .step {
            color: #0ea5e9;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 12px;
        }

        h1 {
            font-size: 26px;
            font-weight: 600;
            margin-bottom: 15px;
            color: #ffffff;
            line-height: 1.3;
        }

        .desc {
            font-size: 15px;
            margin-bottom: 30px;
            color: #94a3b8;
            line-height: 1.6;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        input {
            width: 100%;
            padding: 16px 20px;
            font-size: 15px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            background: rgba(15, 23, 42, 0.5);
            color: #fff;
            transition: all 0.3s ease;
        }

        input::placeholder {
            color: #64748b;
        }

        input:focus {
            outline: none;
            border-color: #0ea5e9;
            background: rgba(15, 23, 42, 0.8);
            box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.15);
        }

        .error {
            color: #f43f5e;
            /* Red for validation errors */
            font-size: 12px;
            margin-top: 8px;
            display: none;
            padding-left: 5px;
        }

        /* પ્રીમિયમ ગ્રેડિયન્ટ બટન */
        .btn-primary {
            width: 100%;
            margin-top: 20px;
            padding: 16px;
            background: linear-gradient(to right, #0ea5e9, #0284c7);
            color: #fff;
            font-size: 16px;
            font-weight: 600;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 4px 15px rgba(14, 165, 233, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(14, 165, 233, 0.4);
        }

        /* ફૂટર ડિઝાઇન */
        .footer {
            margin-top: auto;
            padding: 30px;
            color: #64748b;
            font-size: 14px;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }

        .footer a {
            color: #94a3b8;
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer a:hover {
            color: #0ea5e9;
        }

        @media (max-width: 500px) {
            .center-box {
                margin: 40px 20px;
                padding: 40px 25px;
            }

            header {
                padding: 20px;
            }
        }
    </style>
</head>

<body>

    <header>
        <div class="logo">Watchwise</div>
        <div onclick="window.location.href='index.php'" class="btn-signout">Sign Out</div>
    </header>

    <div class="center-box">
        <p class="step">Step 1 of 3</p>

        <h1>Create a password</h1>

        <p class="desc">
            Just a few more steps and you’re done!<br>
        </p>

        <form method="POST" onsubmit="return validateForm()">
            <div class="form-group">
                <input type="email" id="emailTop" name="email" placeholder="Email address">
                <div class="error" id="emailError"></div>
            </div>

            <div class="form-group">
                <input type="password" id="password" name="password" placeholder="Add a password">
                <div class="error" id="passwordError"></div>
            </div>

            <button type="submit" class="btn-primary">Next</button>
        </form>
    </div>

    <footer class="footer">
        <p>Questions? Call <a href="tel:0008009191743">000-800-919-1743</a> (Toll-Free)</p>
    </footer>

    <script>
        function validateForm() {
            let isValid = true;

            const emailInput = document.getElementById("emailTop");
            const passwordInput = document.getElementById("password");
            const email = emailInput.value.trim();
            const password = passwordInput.value.trim();

            const emailError = document.getElementById("emailError");
            const passwordError = document.getElementById("passwordError");

            // Reset errors
            emailError.style.display = "none";
            passwordError.style.display = "none";
            emailInput.style.borderColor = "rgba(255, 255, 255, 0.15)";
            passwordInput.style.borderColor = "rgba(255, 255, 255, 0.15)";

            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            // Email Validation
            if (email === "") {
                emailError.innerText = "Email is required.";
                emailError.style.display = "block";
                emailInput.style.borderColor = "#f43f5e";
                isValid = false;
            } else if (!emailPattern.test(email)) {
                emailError.innerText = "Please enter a valid email address.";
                emailError.style.display = "block";
                emailInput.style.borderColor = "#f43f5e";
                isValid = false;
            }

            // Password Validation
            if (password === "") {
                passwordError.innerText = "Password is required.";
                passwordError.style.display = "block";
                passwordInput.style.borderColor = "#f43f5e";
                isValid = false;
            } else if (password.length < 8) {
                passwordError.innerText = "Password must be at least 8 characters.";
                passwordError.style.display = "block";
                passwordInput.style.borderColor = "#f43f5e";
                isValid = false;
            }

            return isValid;
        }
    </script>

</body>

</html>