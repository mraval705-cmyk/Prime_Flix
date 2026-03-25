<?php
session_start();
include "db.php"; // Database connect karo

// Movies fetch karo database se
$query = "SELECT * FROM movies WHERE category = 'trending'";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>WATCHWISE </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-color: #0b0f19;
            /* Deep modern navy instead of plain black */
            --primary: #00e5ff;
            /* Vibrant Cyan/Blue */
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

        /* CUSTOM SCROLLBAR */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg-color);
        }

        ::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary);
        }

        /* HEADER - Glassmorphism */
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

        .language option {
            color: black;
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
            box-shadow: 0 0 15px rgba(0, 229, 255, 0.4);
        }

        /* HERO SECTION - Left Aligned, Modern */
        .hero {
            height: 100vh;
            background: linear-gradient(90deg, #0b0f19 20%, rgba(11, 15, 25, 0.3) 100%),
                url("https://image.tmdb.org/t/p/original/rMZonJhnHk0uPqQW524qA7tYmIQ.jpg") center/cover no-repeat;
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

        /* EMAIL BOX - Rounded */
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

        .email-box input:focus {
            border-color: var(--primary);
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
            transition: 0.3s;
        }

        .email-box button:hover {
            background: var(--primary-hover);
        }

        .error {
            color: #ff4d4d;
            margin-top: 10px;
            font-size: 14px;
            margin-left: 15px;
        }

        /* SECTIONS */
        .section {
            padding: 80px 5%;
        }

        .section h2 {
            font-size: 2rem;
            margin-bottom: 30px;
            font-weight: 600;
        }

        /* TRENDING */
        .trending-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .trending {
            display: flex;
            gap: 20px;
            overflow-x: auto;
            scroll-behavior: smooth;
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
            box-shadow: 0 15px 25px rgba(0, 229, 255, 0.2);
            border: 2px solid var(--primary);
        }

        .arrow-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(5px);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            font-size: 20px;
            cursor: pointer;
            z-index: 10;
            transition: 0.3s;
        }

        .arrow-btn:hover {
            background: var(--primary);
            color: #000;
        }

        .arrow-btn.left {
            left: -20px;
        }

        .arrow-btn.right {
            right: -20px;
        }

        /* REASONS CARDS - Modern Bento Grid */
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
            overflow: hidden;
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

        .icon-img {
            position: absolute;
            bottom: 20px;
            right: 20px;
            width: 60px;
            opacity: 0.7;
        }

        /* FAQ - Modern Accordion */
        .faq-item {
            background: transparent;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 0;
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

        /* MODAL */
        .modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.9);
            z-index: 1000;
            justify-content: center;
            align-items: center;
            backdrop-filter: blur(5px);
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

        .modal-left {
            width: 40%;
        }

        .modal-left img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .modal-right {
            width: 60%;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .modal-right h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .tags span {
            display: inline-block;
            background: rgba(255, 255, 255, 0.1);
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            margin-right: 10px;
            margin-bottom: 15px;
        }

        .modal-right p {
            font-size: 15px;
            color: var(--text-muted);
            margin-bottom: 25px;
            line-height: 1.6;
        }

        .btn-modal {
            display: inline-block;
            background: var(--primary);
            color: #000;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 30px;
            font-weight: 600;
            width: fit-content;
        }

        .close {
            position: absolute;
            top: 15px;
            right: 25px;
            font-size: 35px;
            color: #fff;
            cursor: pointer;
        }

        /* FOOTER */
        .footer {
            background: #05070d;
            padding: 80px 5%;
            font-size: 14px;
            color: #94a3b8;
        }

        .footer h4 {
            color: #fff;
            margin-bottom: 15px;
            font-size: 16px;
        }

        .footer a {
            display: block;
            color: #94a3b8;
            margin-bottom: 8px;
            text-decoration: none;
            transition: 0.3s;
        }

        .footer a:hover {
            color: #00e5ff;
        }

        .email-box {
            display: flex;
            gap: 10px;
            max-width: 500px;
            backdrop-filter: blur(15px);
            background: rgba(255, 255, 255, 0.05);
            padding: 10px;
            border-radius: 40px;
        }

        .hero {
            height: 100vh;
            background:
                linear-gradient(90deg, rgba(11, 15, 25, 0.95) 25%, rgba(11, 15, 25, 0.5) 100%),
                url("https://images.unsplash.com/photo-1524985069026-dd778a71c7b4") center/cover no-repeat;
            display: flex;
            align-items: center;
            padding: 0 5%;
            position: relative;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .hero {
                text-align: center;
                background: linear-gradient(0deg, #0b0f19 20%, rgba(11, 15, 25, 0.7) 100%), url("https://image.tmdb.org/t/p/original/rMZonJhnHk0uPqQW524qA7tYmIQ.jpg") center/cover;
            }

            .hero-content {
                margin: auto;
            }

            .hero h1 {
                font-size: 2.5rem;
            }

            .email-box {
                flex-direction: column;
                align-items: center;
            }

            .email-box input,
            .email-box button {
                width: 100%;
            }

            .modal-content {
                flex-direction: column;
            }

            .modal-left,
            .modal-right {
                width: 100%;
            }

            .modal-left img {
                height: 250px;
            }

            .arrow-btn {
                display: none;
            }

            /* Hide arrows on mobile, allow touch scroll */
        }
    </style>
</head>

<body>

    <header class="header">
        <div class="logo">WATCHWISE</div>
        <div class="header-right">
            <select class="language" onchange="changeLanguage(this.value)">
                <option value="en">English</option>
                <option value="hi">हिन्दी</option>
            </select>
            <a href="Signup.php" class="signin" data-key="signin">Sign In</a>
        </div>
    </header>

    <section class="hero">
        <div class="hero-content">
            <h1 data-key="title">Dive into endless entertainment.</h1>
            <h2 data-key="subtitle">Premium movies & shows. Starts at ₹149.</h2>

            <div class="email-box">
                <input type="email" id="emailTop" placeholder="Enter your email address">
                <button onclick="validateEmail('emailTop')">Get Started</button>
            </div>
            <div class="error" id="errorTop"></div>
        </div>
    </section>

    <section class="section">
        <h2 data-key="trending">Trending Now</h2>
        <div class="trending" id="trending">
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<img src="' . $row['image_url'] . '" 
                       data-title="' . $row['title'] . '" 
                       data-desc="' . $row['description'] . '" 
                       data-year="' . $row['release_year'] . '"
                       data-rating="' . $row['rating'] . '"
                       onclick="openModal(this)">';
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
            <div class="card">
                <h3>Seamless TV Experience</h3>
                <p>Watch on smart TVs, PlayStation, Xbox, Apple TV, and more without interruptions.</p>
                <span style="font-size: 40px; position:absolute; bottom:15px; right:20px;">📺</span>
            </div>
            <div class="card">
                <h3>Download & Go</h3>
                <p>Save your favourites easily and always have something to watch offline.</p>
                <span style="font-size: 40px; position:absolute; bottom:15px; right:20px;">📥</span>
            </div>
            <div class="card">
                <h3>Watch Everywhere</h3>
                <p>Stream unlimited movies and TV shows on your phone, tablet, laptop, and TV.</p>
                <span style="font-size: 40px; position:absolute; bottom:15px; right:20px;">💻</span>
            </div>
            <div class="card">
                <h3>Kids Profiles</h3>
                <p>Send kids on adventures with their favourite characters in a safe space.</p>
                <span style="font-size: 40px; position:absolute; bottom:15px; right:20px;">🧸</span>
            </div>
        </div>
    </section>

    <section class="section">
        <h2>Got Questions?</h2>

        <div class="faq-item" onclick="toggleFaq(this)">
            <div class="faq-title">What is Watchwise? <span class="faq-icon">+</span></div>
            <div class="faq-answer">Watchwise is a premium streaming service offering movies, TV shows, and exclusive
                originals across multiple devices.</div>
        </div>
        <div class="faq-item" onclick="toggleFaq(this)">
            <div class="faq-title">How much does it cost? <span class="faq-icon">+</span></div>
            <div class="faq-answer">Plans range from ₹149 to ₹649 per month. No hidden costs or contracts.</div>
        </div>
        <div class="faq-item" onclick="toggleFaq(this)">
            <div class="faq-title">Where can I watch? <span class="faq-icon">+</span></div>
            <div class="faq-answer">Watch anywhere, anytime on any internet-connected device. You can also download
                content for offline viewing.</div>
        </div>

        <div style="margin-top: 50px; text-align: center;">
            <p style="margin-bottom: 20px; color: var(--text-muted);">Ready to start watching? Enter your email to
                create an account.</p>
            <div class="email-box" style="margin: auto;">
                <input type="email" id="emailBottom" placeholder="Enter your email address">
                <button onclick="validateEmail('emailBottom')">Get Started</button>
            </div>
            <div class="error" id="errorBottom"></div>
        </div>
    </section>
    <footer class="footer">
        <div class="footer-top" style="display:flex; flex-wrap:wrap; gap:40px; justify-content:space-between;">
            <!-- Logo & description -->
            <div style="flex:1; min-width:250px;">
                <h2 style="color:var(--primary); font-weight:600;">WATCHWISE</h2>
                <p style="margin-top:10px; color:var(--text-muted); max-width:400px;">
                    Watchwise is India’s premium streaming platform offering unlimited movies,
                    TV shows, originals, and exclusive content. Stream anytime, anywhere.
                </p>
            </div>

            <!-- Company links -->
            <div style="flex:1; min-width:150px;">
                <h4>Company</h4>
                <a href="#">About Us</a>
                <a href="#">Careers</a>
                <a href="#">Press</a>
                <a href="#">Investors</a>
            </div>

            <!-- Support links -->
            <div style="flex:1; min-width:150px;">
                <h4>Support</h4>
                <a href="#">Help Centre</a>
                <a href="#">FAQ</a>
                <a href="#">Account</a>
                <a href="#">Contact Us</a>
            </div>

            <!-- Legal links -->
            <div style="flex:1; min-width:150px;">
                <h4>Legal</h4>
                <a href="#">Terms of Use</a>
                <a href="#">Privacy Policy</a>
                <a href="#">Cookie Policy</a>
                <a href="#">Corporate Information</a>
            </div>

            <!-- Contact info -->
            <div style="flex:1; min-width:200px;">
                <h4>Connect With Us</h4>
                <p>📧 support@watchwise.com</p>
                <p>📞 000-800-919-1743</p>
                <p>📍 Rajkot, Gujarat, India</p>
            </div>
        </div>

        <!-- Bottom copyright -->
        <div style="margin-top:40px; border-top:1px solid rgba(255,255,255,0.1); padding-top:20px; text-align:center;">
            <p>© 2026 Watchwise India. All Rights Reserved.</p>
            <p style="margin-top:5px; font-size:12px; color:var(--text-muted);">Protected by Google reCAPTCHA.</p>
        </div>
    </footer>

    <div id="movieModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <div class="modal-left">
                <img id="modalImg" src="">
            </div>
            <div class="modal-right">
                <h1 id="modalTitle">Title</h1>
                <div class="tags">
                    <span>2026</span>
                    <span>U/A 16+</span>
                    <span>Premium</span>
                </div>
                <p id="modalDesc">Description goes here.</p>
                <a href="step1.php" class="btn-modal">Watch Now</a>
            </div>
        </div>
    </div>

    <script>
        // 1. FAQ Toggle
        function toggleFaq(el) {
            el.classList.toggle("active");
            let ans = el.querySelector(".faq-answer");
            let icon = el.querySelector(".faq-icon");

            if (ans.style.display === "block") {
                ans.style.display = "none";
                icon.innerHTML = "+";
            } else {
                ans.style.display = "block";
                icon.innerHTML = "&times;";
            }
        }

        // 2. Email Validation (Integrated with your backend logic)
        function validateEmail(id) {
            let email = document.getElementById(id).value;
            let error = document.getElementById(id === "emailTop" ? "errorTop" : "errorBottom");
            let regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (email === "") {
                error.innerHTML = "Email is required!";
                return;
            }
            if (!regex.test(email)) {
                error.innerHTML = "Enter a valid email!";
                return;
            }

            // AJAX request to save email
            fetch("save_email.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: "email=" + encodeURIComponent(email)
                })
                .then(res => res.text())
                .then(data => {
                    if (data === "success") {
                        window.location.href = "step1.php";
                    } else if (data === "exists") {
                        error.innerHTML = "Email already registered!";
                    } else {
                        // For testing UI without backend, just redirect:
                        window.location.href = "step1.php";
                        // error.innerHTML = "Something went wrong!";
                    }
                }).catch(err => {
                    // Fallback for local testing without DB
                    window.location.href = "step1.php";
                });
        }

        // 3. Trending Scroll
        function scrollTrending(direction) {
            const row = document.getElementById("trending");
            row.scrollBy({
                left: 250 * direction,
                behavior: "smooth"
            });
        }

        // 4. Modal Logic
        function openModal(img) {
            document.getElementById("movieModal").style.display = "flex";
            document.getElementById("modalImg").src = img.src.replace("w300", "w500");
            document.getElementById("modalTitle").innerText = img.dataset.title;
            document.getElementById("modalDesc").innerText = img.dataset.desc;

            // Extra: Year aur Rating bhi update kar sakte ho
            const tags = document.querySelectorAll(".tags span");
            tags[0].innerText = img.dataset.year;
            tags[1].innerText = img.dataset.rating;
        }

        function closeModal() {
            document.getElementById("movieModal").style.display = "none";
        }

        // Close modal when clicking outside of it
        window.onclick = function(event) {
            let modal = document.getElementById("movieModal");
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        // 5. Language Translation
        const translations = {
            en: {
                title: "Dive into endless entertainment.",
                subtitle: "Premium movies & shows. Starts at ₹149.",
                signin: "Sign In",
                trending: "Trending Now"
            },
            hi: {
                title: "अंतहीन मनोरंजन में गोता लगाएँ।",
                subtitle: "प्रीमियम फिल्में और शो। मात्र ₹149 से शुरू।",
                signin: "साइन इन",
                trending: "ट्रेंडिंग नाउ"
            }
        };

        function changeLanguage(lang) {
            document.querySelectorAll("[data-key]").forEach(el => {
                const key = el.getAttribute("data-key");
                el.textContent = translations[lang][key];
            });
        }
    </script>
</body>

</html>