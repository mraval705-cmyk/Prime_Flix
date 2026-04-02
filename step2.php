<?php
session_start();
include "db.php";

$emailValue = "";
$emailServerError = "";
$passwordServerError = "";
$isSubmitted = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $emailValue = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // agar sirf home page se email aaya hai, to bas fill karo, validation mat chalao
    if (isset($_POST['email']) && !isset($_POST['password'])) {
        $_SESSION['reg_email'] = $emailValue;
    } else {
        $isSubmitted = true;
        $valid = true;

        if ($emailValue == "") {
            $emailServerError = "Please enter email.";
            $valid = false;
        } elseif (!filter_var($emailValue, FILTER_VALIDATE_EMAIL)) {
            $emailServerError = "Invalid email format.";
            $valid = false;
        } else {
            $safeEmail = mysqli_real_escape_string($conn, $emailValue);
            $checkEmail = mysqli_query($conn, "SELECT id FROM users WHERE email='$safeEmail' LIMIT 1");

            if ($checkEmail && mysqli_num_rows($checkEmail) > 0) {
                $emailServerError = "This email is already registered.";
                $valid = false;
            }
        }

        if ($password == "") {
            $passwordServerError = "Password is required.";
            $valid = false;
        } elseif (strlen($password) < 8) {
            $passwordServerError = "Password must be at least 8 characters.";
            $valid = false;
        }

        if ($valid) {
            $_SESSION['reg_email'] = $emailValue;
            $_SESSION['reg_password'] = password_hash($password, PASSWORD_DEFAULT);
            header("Location: step3.php");
            exit();
        }
    }
} else {
    $emailValue = $_SESSION['reg_email'] ?? '';
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
            font-size: 12px;
            margin-top: 8px;
            display: none;
            padding-left: 5px;
        }

        .success-msg {
            color: #22c55e;
            font-size: 12px;
            margin-top: 8px;
            display: none;
            padding-left: 5px;
        }

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
            Just a few more steps and you’re done!
        </p>

        <form method="POST" onsubmit="return validateForm()">
            <div class="form-group">
                <input type="email" id="emailTop" name="email" placeholder="Email address"
                    value="<?php echo htmlspecialchars($emailValue); ?>" onblur="checkEmailAjax()">
                <div class="error" id="emailError"><?php echo htmlspecialchars($emailServerError); ?></div>
                <div class="success-msg" id="emailSuccess"></div>
            </div>

            <div class="form-group">
                <input type="password" id="password" name="password" placeholder="Add a password">
                <div class="error" id="passwordError"><?php echo htmlspecialchars($passwordServerError); ?></div>
            </div>

            <button type="submit" class="btn-primary">Next</button>
        </form>
    </div>

    <footer class="footer">
        <p>Questions? Call <a href="tel:0008009191743">000-800-919-1743</a> (Toll-Free)</p>
    </footer>

    <script>
        let ajaxEmailStatus = "";
        const formWasSubmitted = <?php echo $isSubmitted ? 'true' : 'false'; ?>;

        window.onload = function() {
            if (formWasSubmitted) {
                const emailServerError = `<?php echo addslashes($emailServerError); ?>`;
                const passwordServerError = `<?php echo addslashes($passwordServerError); ?>`;

                if (emailServerError !== "") {
                    document.getElementById("emailError").style.display = "block";
                    document.getElementById("emailTop").style.borderColor = "#f43f5e";
                }

                if (passwordServerError !== "") {
                    document.getElementById("passwordError").style.display = "block";
                    document.getElementById("password").style.borderColor = "#f43f5e";
                }
            }
        };

        function resetEmailMessages() {
            const emailError = document.getElementById("emailError");
            const emailSuccess = document.getElementById("emailSuccess");
            const emailInput = document.getElementById("emailTop");

            emailError.style.display = "none";
            emailSuccess.style.display = "none";
            emailInput.style.borderColor = "rgba(255, 255, 255, 0.15)";
        }

        function checkEmailAjax() {
            const emailInput = document.getElementById("emailTop");
            const email = emailInput.value.trim();
            const emailError = document.getElementById("emailError");
            const emailSuccess = document.getElementById("emailSuccess");

            resetEmailMessages();
            ajaxEmailStatus = "";

            if (email === "") return;

            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) return;

            const xhr = new XMLHttpRequest();
            xhr.open("POST", "check_email.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);

                        if (response.status === "exists") {
                            emailError.innerText = response.message;
                            emailError.style.display = "block";
                            emailInput.style.borderColor = "#f43f5e";
                            ajaxEmailStatus = "exists";
                        } else if (response.status === "success") {
                            emailSuccess.innerText = response.message;
                            emailSuccess.style.display = "block";
                            emailInput.style.borderColor = "#22c55e";
                            ajaxEmailStatus = "ok";
                        }
                    } catch (e) {}
                }
            };

            xhr.send("email=" + encodeURIComponent(email));
        }

        function validateForm() {
            let isValid = true;

            const emailInput = document.getElementById("emailTop");
            const passwordInput = document.getElementById("password");
            const email = emailInput.value.trim();
            const password = passwordInput.value.trim();

            const emailError = document.getElementById("emailError");
            const passwordError = document.getElementById("passwordError");
            const emailSuccess = document.getElementById("emailSuccess");

            emailError.style.display = "none";
            passwordError.style.display = "none";
            emailSuccess.style.display = "none";
            emailInput.style.borderColor = "rgba(255, 255, 255, 0.15)";
            passwordInput.style.borderColor = "rgba(255, 255, 255, 0.15)";

            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

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
            } else if (ajaxEmailStatus === "exists") {
                emailError.innerText = "This email is already registered.";
                emailError.style.display = "block";
                emailInput.style.borderColor = "#f43f5e";
                isValid = false;
            }

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