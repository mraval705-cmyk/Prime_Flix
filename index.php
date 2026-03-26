<?php
session_start();
include "db.php";

// HERO
$heroQuery = "SELECT * FROM hero_slides WHERE is_active = 1 ORDER BY id DESC LIMIT 1";
$heroResult = mysqli_query($conn, $heroQuery);
$hero = mysqli_fetch_assoc($heroResult);

// TRENDING MOVIES
$movieQuery = "SELECT * FROM movies WHERE category = 'trending' ORDER BY id DESC";
$movieResult = mysqli_query($conn, $movieQuery);

// FEATURES
$featureQuery = "SELECT * FROM features WHERE is_active = 1 ORDER BY id ASC";
$featureResult = mysqli_query($conn, $featureQuery);

// FAQ
$faqQuery = "SELECT * FROM faqs WHERE is_active = 1 ORDER BY id ASC";
$faqResult = mysqli_query($conn, $faqQuery);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>WATCHWISE</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap">
    <style>
        :root {
            --bg-color: #0b0f19;
            --primary: #00e5ff;
            --primary-hover: #00b4d8;
            --text-light: #f1f5f9;
            --text-muted: #94a3b8;
            --card-bg: #1e293b;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-light);
            overflow-x: hidden;
        }

        .header {
            position: fixed;
            top: 0;
            width: 100%;
            padding: 20px 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 100;
            background: rgba(11, 15, 25, 0.8);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .logo {
            color: var(--primary);
            font-size: 32px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .header-right {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .language {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 14px;
            outline: none;
            cursor: pointer;
        }

        .signin {
            padding: 8px 25px;
            background: var(--primary);
            color: #000;
            font-size: 14px;
            font-weight: 600;
            border-radius: 20px;
            text-decoration: none;
            transition: 0.3s;
        }

        .signin:hover {
            background: var(--primary-hover);
        }

        .hero {
            height: 100vh;
            background:
                linear-gradient(90deg, #0b0f19 20%, rgba(11, 15, 25, 0.3) 100%),
                url('<?php echo !empty($hero['image_url']) ? htmlspecialchars($hero['image_url']) : "https://image.tmdb.org/t/p/original/rMZonJhnHk0uPqQW524qA7tYmIQ.jpg"; ?>') center/cover no-repeat;
            display: flex;
            align-items: center;
            padding: 0 5%;
        }

        .hero-content {
            max-width: 650px;
            margin-top: 50px;
        }

        .hero h1 {
            font-size: 3.5rem;
            line-height: 1.2;
            margin-bottom: 20px;
            font-weight: 700;
        }

        .hero h2 {
            font-size: 1.5rem;
            font-weight: 400;
            color: var(--text-muted);
            margin-bottom: 30px;
        }

        .email-box {
            display: flex;
            gap: 10px;
            max-width: 500px;
        }

        .email-box input {
            flex: 1;
            padding: 15px 25px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 30px;
            color: #fff;
            font-size: 16px;
            outline: none;
        }

        .email-box button {
            background: var(--primary);
            color: #000;
            border: none;
            padding: 15px 30px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 30px;
            cursor: pointer;
        }

        .error {
            color: #ff4d4d;
            margin-top: 10px;
            font-size: 14px;
            margin-left: 15px;
        }

        .section {
            padding: 80px 5%;
        }

        .section h2 {
            font-size: 2rem;
            margin-bottom: 30px;
            font-weight: 600;
        }

        .trending {
            display: flex;
            gap: 20px;
            overflow-x: auto;
            padding: 20px 0;
        }

        .trending img {
            width: 200px;
            height: 300px;
            object-fit: cover;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.4s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
        }

        .trending img:hover {
            transform: translateY(-10px) scale(1.05);
            border: 2px solid var(--primary);
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
        }

        .card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
            padding: 30px;
            border-radius: 20px;
            position: relative;
            transition: 0.3s;
        }

        .card:hover {
            background: rgba(255, 255, 255, 0.08);
            transform: translateY(-5px);
        }

        .card h3 {
            font-size: 20px;
            margin-bottom: 15px;
            color: var(--primary);
        }

        .card p {
            font-size: 15px;
            color: var(--text-muted);
            line-height: 1.6;
        }

        .faq-item {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 25px 0;
            cursor: pointer;
        }

        .faq-title {
            display: flex;
            justify-content: space-between;
            font-size: 18px;
            font-weight: 400;
        }

        .faq-icon {
            font-size: 24px;
            color: var(--primary);
            transition: 0.3s;
        }

        .faq-item.active .faq-icon {
            transform: rotate(45deg);
        }

        .faq-answer {
            display: none;
            margin-top: 15px;
            font-size: 15px;
            color: var(--text-muted);
            line-height: 1.6;
        }

        .footer {
            background: #05070d;
            padding: 60px 5%;
            color: #94a3b8;
            text-align: center;
        }

        .modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.9);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: var(--card-bg);
            width: 90%;
            max-width: 800px;
            border-radius: 20px;
            display: flex;
            overflow: hidden;
            position: relative;
        }

        .modal-left,
        .modal-right {
            width: 50%;
        }

        .modal-left img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .modal-right {
            padding: 40px;
        }

        .modal-right h1 {
            font-size: 28px;
            margin-bottom: 15px;
        }

        .modal-right p {
            color: var(--text-muted);
            margin-bottom: 20px;
        }

        .close {
            position: absolute;
            top: 15px;
            right: 25px;
            font-size: 35px;
            color: #fff;
            cursor: pointer;
        }

        @media(max-width:768px){
            .hero {
                height: auto;
                min-height: 100vh;
                padding-top: 120px;
                padding-bottom: 50px;
            }
            .hero h1 {
                font-size: 2.5rem;
            }
            .email-box {
                flex-direction: column;
            }
            .modal-content {
                flex-direction: column;
            }
            .modal-left, .modal-right {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<header class="header">
    <div class="logo">WATCHWISE</div>
    <div class="header-right">
        <select class="language">
            <option>English</option>
            <option>हिन्दी</option>
        </select>
        <a href="Signup.php" class="signin">Sign In</a>
    </div>
</header>

<section class="hero">
    <div class="hero-content">
        <h1><?php echo !empty($hero['title']) ? htmlspecialchars($hero['title']) : 'Dive into endless entertainment.'; ?></h1>
        <h2><?php echo !empty($hero['subtitle']) ? htmlspecialchars($hero['subtitle']) : 'Premium movies & shows. Starts at ₹149.'; ?></h2>

        <div class="email-box">
            <input type="email" id="emailTop" placeholder="Enter your email address">
            <button onclick="validateEmail('emailTop')"><?php echo !empty($hero['button_text']) ? htmlspecialchars($hero['button_text']) : 'Get Started'; ?></button>
        </div>
        <div class="error" id="errorTop"></div>
    </div>
</section>

<section class="section">
    <h2>Trending Now</h2>
    <div class="trending" id="trending">
        <?php
        if ($movieResult && mysqli_num_rows($movieResult) > 0) {
            while ($row = mysqli_fetch_assoc($movieResult)) {
                ?>
                <img src="<?php echo htmlspecialchars($row['image_url']); ?>"
                     data-title="<?php echo htmlspecialchars($row['title']); ?>"
                     data-desc="<?php echo htmlspecialchars($row['description']); ?>"
                     data-year="<?php echo htmlspecialchars($row['release_year']); ?>"
                     data-rating="<?php echo htmlspecialchars($row['rating']); ?>"
                     onclick="openModal(this)">
                <?php
            }
        } else {
            echo "<p>No movies found.</p>";
        }
        ?>
    </div>
</section>

<section class="section">
    <h2>Why choose Watchwise?</h2>
    <div class="cards">
        <?php
        if ($featureResult && mysqli_num_rows($featureResult) > 0) {
            while ($feature = mysqli_fetch_assoc($featureResult)) {
                ?>
                <div class="card">
                    <h3><?php echo htmlspecialchars($feature['title']); ?></h3>
                    <p><?php echo htmlspecialchars($feature['description']); ?></p>
                    <span style="font-size: 40px; position:absolute; bottom:15px; right:20px;">
                        <?php echo htmlspecialchars($feature['icon']); ?>
                    </span>
                </div>
                <?php
            }
        } else {
            echo "<p>No features found.</p>";
        }
        ?>
    </div>
</section>

<section class="section">
    <h2>Got Questions?</h2>

    <?php
    if ($faqResult && mysqli_num_rows($faqResult) > 0) {
        while ($faq = mysqli_fetch_assoc($faqResult)) {
            ?>
            <div class="faq-item" onclick="toggleFaq(this)">
                <div class="faq-title">
                    <?php echo htmlspecialchars($faq['question']); ?>
                    <span class="faq-icon">+</span>
                </div>
                <div class="faq-answer"><?php echo htmlspecialchars($faq['answer']); ?></div>
            </div>
            <?php
        }
    } else {
        echo "<p>No FAQs found.</p>";
    }
    ?>

    <div style="margin-top: 50px; text-align: center;">
        <p style="margin-bottom: 20px; color: var(--text-muted);">
            Ready to start watching? Enter your email to create an account.
        </p>
        <div class="email-box" style="margin: auto;">
            <input type="email" id="emailBottom" placeholder="Enter your email address">
            <button onclick="validateEmail('emailBottom')">Get Started</button>
        </div>
        <div class="error" id="errorBottom"></div>
    </div>
</section>

<footer class="footer">
    <h2 style="color:var(--primary); margin-bottom:10px;">WATCHWISE</h2>
    <p>Dynamic streaming platform powered by PHP, MySQL and Laragon.</p>
</footer>

<div class="modal" id="movieModal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <div class="modal-left">
            <img id="modalImg" src="" alt="Movie">
        </div>
        <div class="modal-right">
            <h1 id="modalTitle"></h1>
            <p><strong>Year:</strong> <span id="modalYear"></span></p>
            <p><strong>Rating:</strong> <span id="modalRating"></span></p>
            <p id="modalDesc"></p>
            <a href="step1.php" class="signin">Watch Now</a>
        </div>
    </div>
</div>

<script>
function validateEmail(inputId) {
    const input = document.getElementById(inputId);
    const errorBox = inputId === 'emailTop' ? document.getElementById('errorTop') : document.getElementById('errorBottom');
    const email = input.value.trim();

    errorBox.innerText = "";

    if (email === "") {
        errorBox.innerText = "Please enter your email.";
        return;
    }

    const emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
    if (!emailPattern.test(email)) {
        errorBox.innerText = "Please enter a valid email.";
        return;
    }

    window.location.href = "step1.php";
}

function toggleFaq(item) {
    item.classList.toggle("active");
    const answer = item.querySelector(".faq-answer");
    answer.style.display = answer.style.display === "block" ? "none" : "block";
}

function openModal(el) {
    document.getElementById("modalImg").src = el.src;
    document.getElementById("modalTitle").innerText = el.getAttribute("data-title");
    document.getElementById("modalDesc").innerText = el.getAttribute("data-desc");
    document.getElementById("modalYear").innerText = el.getAttribute("data-year");
    document.getElementById("modalRating").innerText = el.getAttribute("data-rating");
    document.getElementById("movieModal").style.display = "flex";
}

function closeModal() {
    document.getElementById("movieModal").style.display = "none";
}
</script>

</body>
</html>