<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Watchwise - Welcome</title>

    <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap');

    /* ===== Reset ===== */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    html,
    body {
        height: 100%;
        font-family: 'Poppins', sans-serif;
        /* Watchwise ડાર્ક નેવી બ્લુ ગ્રેડિયન્ટ બેકગ્રાઉન્ડ */
        background: linear-gradient(135deg, #0f172a 0%, #020617 100%);
    }

    /* ===== Splash Screen ===== */
    .splash {
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        overflow: hidden;
        position: relative;
    }

    /* લોગોની પાછળ આવતો આછો વાદળી ગ્લો (Glow) */
    .splash::before {
        content: "";
        position: absolute;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(14, 165, 233, 0.2) 0%, transparent 70%);
        z-index: 0;
        animation: pulseGlow 2.5s infinite alternate;
    }

    .logo {
        color: #0ea5e9;
        /* Watchwise Sky Blue */
        font-size: 4.5rem;
        font-weight: 800;
        letter-spacing: 8px;
        text-transform: uppercase;
        text-shadow: 0 4px 20px rgba(14, 165, 233, 0.4);
        z-index: 1;
        opacity: 0;
        animation: fadeInText 2.5s ease forwards;
    }

    /* ===== Animations ===== */
    @keyframes fadeInText {
        0% {
            opacity: 0;
            transform: scale(0.8);
            letter-spacing: -5px;
        }

        50% {
            opacity: 0.8;
            transform: scale(1.05);
            letter-spacing: 15px;
            /* વચ્ચે અક્ષરો થોડા વધુ છૂટા પડશે */
        }

        100% {
            opacity: 1;
            transform: scale(1);
            letter-spacing: 8px;
            /* અંતે એકદમ સેટ થઈ જશે */
        }
    }

    @keyframes pulseGlow {
        0% {
            transform: scale(0.8);
            opacity: 0.5;
        }

        100% {
            transform: scale(1.3);
            opacity: 1;
        }
    }

    /* મોબાઈલ સ્ક્રીન માટે */
    @media(max-width: 600px) {
        .logo {
            font-size: 2.5rem;
        }

        @keyframes fadeInText {
            0% {
                opacity: 0;
                transform: scale(0.8);
                letter-spacing: -2px;
            }

            50% {
                opacity: 0.8;
                transform: scale(1.05);
                letter-spacing: 8px;
            }

            100% {
                opacity: 1;
                transform: scale(1);
                letter-spacing: 4px;
            }
        }
    }
    </style>
</head>

<body>
    <div class="splash">
        <h1 class="logo">WATCHWISE</h1>
    </div>

    <script>
    // 3 સેકન્ડ પછી cook.php પર રિડાયરેક્ટ કરશે
    setTimeout(() => {
        window.location.href = "cook.php";
    }, 3000);
    </script>
</body>

</html>