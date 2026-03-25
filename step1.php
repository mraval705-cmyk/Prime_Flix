<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Watchwise – Setup Your Account</title>
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

    /* નવો ડાર્ક નેવી બ્લુ ઓવરલે */
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

    /* નવું મોડર્ન હેડર */
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
        background: rgba(255, 255, 255, 0.1);
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
        background: rgba(255, 255, 255, 0.2);
        border-color: rgba(255, 255, 255, 0.3);
    }

    /* સેન્ટર બોક્સ (Glassmorphism Effect) */
    .center-box {
        width: 100%;
        max-width: 460px;
        margin: 60px auto;
        text-align: center;
        background: rgba(30, 41, 59, 0.65);
        padding: 50px 40px;
        border-radius: 20px;
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
    }

    .devices {
        font-size: 42px;
        margin-bottom: 20px;
        display: flex;
        justify-content: center;
        gap: 15px;
    }

    .device {
        filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.4));
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
        font-size: 28px;
        font-weight: 600;
        margin-bottom: 15px;
        color: #ffffff;
    }

    .desc {
        font-size: 15px;
        color: #94a3b8;
        line-height: 1.6;
        margin-bottom: 35px;
    }

    /* નવું ગ્રેડિયન્ટ બટન */
    .btn-primary {
        display: block;
        width: 100%;
        padding: 16px;
        background: linear-gradient(to right, #0ea5e9, #0284c7);
        color: #fff;
        font-size: 16px;
        font-weight: 600;
        text-decoration: none;
        border-radius: 12px;
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
        /* ફૂટરને હંમેશા નીચે રાખવા માટે */
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
        <div class="devices">
            <span class="device">💻</span>
            <span class="device">🖥️</span>
            <span class="device">📱</span>
        </div>

        <p class="step">Step 1 of 3</p>

        <h1>Finish setting up your account</h1>

        <p class="desc">
            Watchwise is personalised for you. Create a password to watch on any device at any time, anywhere.
        </p>

        <a href="step2.php" class="btn-primary">Next</a>
    </div>

    <footer class="footer">
        <p>Questions? Call <a href="tel:0008009191743">000-800-919-1743</a> (Toll-Free)</p>
    </footer>

</body>

</html>