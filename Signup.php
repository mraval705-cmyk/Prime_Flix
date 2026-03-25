<?php
session_start();
include "db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid Password!";
        }
    } else {
        $error = "User not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Watchwise – Welcome</title>
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
            width: 100%;
        }

        .logo {
            color: #0ea5e9;
            font-size: 34px;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
            text-shadow: 0 2px 10px rgba(14, 165, 233, 0.3);
        }

        .container {
            width: 100%;
            max-width: 420px;
            margin: auto;
            padding: 40px;
            background: rgba(30, 41, 59, 0.65);
            border-radius: 20px;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
        }

        h1 {
            font-size: 26px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #ffffff;
        }

        p {
            color: #94a3b8;
            font-size: 14px;
            margin-bottom: 30px;
        }

        .input-group {
            margin-bottom: 20px;
            position: relative;
        }

        .input-box {
            width: 100%;
            padding: 15px 20px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            font-size: 15px;
            background: rgba(15, 23, 42, 0.5);
            color: #fff;
            transition: all 0.3s ease;
        }

        .input-box::placeholder {
            color: #64748b;
        }

        .input-box:focus {
            border-color: #0ea5e9;
            background: rgba(15, 23, 42, 0.8);
            outline: none;
            box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.15);
        }

        .error {
            color: #f43f5e;
            font-size: 12px;
            margin-top: 6px;
            display: none;
            padding-left: 5px;
        }

        .server-error {
            color: #f43f5e;
            font-size: 13px;
            margin-bottom: 15px;
            padding-left: 5px;
        }

        .btn-primary {
            width: 100%;
            padding: 15px;
            background: linear-gradient(to right, #0ea5e9, #0284c7);
            border: none;
            color: #fff;
            font-size: 16px;
            font-weight: 600;
            border-radius: 12px;
            cursor: pointer;
            margin-top: 10px;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 4px 15px rgba(14, 165, 233, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(14, 165, 233, 0.4);
        }

        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 25px 0;
            color: #64748b;
            font-size: 13px;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .divider:not(:empty)::before {
            margin-right: 15px;
        }

        .divider:not(:empty)::after {
            margin-left: 15px;
        }

        .btn-secondary {
            width: 100%;
            padding: 15px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #e2e8f0;
            font-size: 15px;
            font-weight: 500;
            border-radius: 12px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            font-size: 14px;
            color: #94a3b8;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .remember input {
            width: 16px;
            height: 16px;
            accent-color: #0ea5e9;
            cursor: pointer;
        }

        .forgot-link {
            color: #0ea5e9;
            text-decoration: none;
            transition: color 0.3s;
        }

        .forgot-link:hover {
            color: #38bdf8;
            text-decoration: underline;
        }

        .signup {
            margin-top: 30px;
            text-align: center;
            font-size: 14px;
            color: #94a3b8;
        }

        .signup a {
            color: #fff;
            font-weight: 600;
            text-decoration: none;
            margin-left: 5px;
            transition: color 0.3s;
        }

        .signup a:hover {
            color: #0ea5e9;
        }
    </style>
</head>

<body>

    <header>
        <div class="logo">Watchwise</div>
    </header>

    <div class="container">
        <h1>Welcome back</h1>
        <p>Sign in to continue to Watchwise</p>

        <?php if (!empty($error)) : ?>
            <div class="server-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" onsubmit="return validateForm()">
            <div class="input-group">
                <input id="emailInput" name="email" class="input-box" type="text" placeholder="Email or mobile number">
                <div id="emailError" class="error"></div>
            </div>

            <div class="input-group">
                <input id="passwordInput" name="password" class="input-box" type="password" placeholder="Password">
                <div id="passwordError" class="error"></div>
            </div>

            <button type="submit" class="btn-primary">Sign In</button>
        </form>

        <div class="divider">OR</div>

        <button class="btn-secondary">Use a sign-in code</button>

        <div class="options">
            <label class="remember">
                <input type="checkbox" checked>
                Remember me
            </label>
            <a href="forgot_password.php" class="forgot-link">Forgot password?</a>
        </div>

        <div class="signup">
            Don't have an account? <a href="step1.php">Sign up now</a>
        </div>
    </div>

    <script>
        function isValidEmailOrPhone(value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const phoneRegex = /^[0-9]{10}$/;
            return emailRegex.test(value) || phoneRegex.test(value);
        }

        function validateForm() {
            const email = document.getElementById("emailInput");
            const password = document.getElementById("passwordInput");
            const emailError = document.getElementById("emailError");
            const passwordError = document.getElementById("passwordError");

            const emailValue = email.value.trim();
            const passwordValue = password.value.trim();

            emailError.style.display = "none";
            passwordError.style.display = "none";
            email.style.borderColor = "rgba(255, 255, 255, 0.15)";
            password.style.borderColor = "rgba(255, 255, 255, 0.15)";

            let valid = true;

            if (emailValue === "") {
                emailError.innerText = "Please enter your email or phone number.";
                emailError.style.display = "block";
                email.style.borderColor = "#f43f5e";
                valid = false;
            } else if (!isValidEmailOrPhone(emailValue)) {
                emailError.innerText = "Please enter a valid email or 10-digit number.";
                emailError.style.display = "block";
                email.style.borderColor = "#f43f5e";
                valid = false;
            }

            if (passwordValue === "") {
                passwordError.innerText = "Password is required.";
                passwordError.style.display = "block";
                password.style.borderColor = "#f43f5e";
                valid = false;
            } else if (passwordValue.length < 4) {
                passwordError.innerText = "Password must be at least 4 characters.";
                passwordError.style.display = "block";
                password.style.borderColor = "#f43f5e";
                valid = false;
            }

            return valid;
        }
    </script>

</body>

</html>