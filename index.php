<?php
session_start();
include "db.php";

$query = "SELECT * FROM movies WHERE category = 'trending' AND is_active = 1";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>WATCHWISE</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

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

        .right-section {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .search-form {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .search-input {
            padding: 10px 14px;
            border-radius: 30px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            outline: none;
            background: rgba(255, 255, 255, 0.08);
            color: white;
            width: 190px;
            font-size: 14px;
        }

        .search-input::placeholder {
            color: #cbd5e1;
        }

        .search-btn {
            padding: 10px 16px;
            border: none;
            border-radius: 30px;
            background: #00d4ff;
            color: black;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }

        .search-btn:hover {
            opacity: 0.9;
        }

        .lang-select {
            padding: 10px 14px;
            border-radius: 30px;
            background: rgba(255, 255, 255, 0.08);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.15);
            outline: none;
            cursor: pointer;
        }

        .lang-select option {
            color: black;
        }

        .signin-btn {
            padding: 10px 22px;
            border-radius: 30px;
            background: #00d4ff;
            color: black;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
        }

        .signin-btn:hover {
            background: var(--primary-hover);
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

        .email-form {
            max-width: 540px;
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

        .email-box input {
            flex: 1;
            padding: 15px 25px;
            background: transparent;
            border: none;
            color: #fff;
            font-size: 16px;
            outline: none;
        }

        .email-box input::placeholder {
            color: #cbd5e1;
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

        .section {
            padding: 80px 5%;
        }

        .section h2 {
            font-size: 2rem;
            margin-bottom: 30px;
            font-weight: 600;
        }

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
            flex-shrink: 0;
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

        .faq-item {
            background: transparent;
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
            padding: 60px 5%;
            background: #070b13;
            color: var(--text-muted);
        }

        .footer-top {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 30px;
        }

        .footer h2,
        .footer h4 {
            color: #fff;
            margin-bottom: 18px;
        }

        .footer a {
            display: block;
            color: var(--text-muted);
            text-decoration: none;
            margin-bottom: 10px;
            transition: 0.3s;
        }

        .footer a:hover {
            color: var(--primary);
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            margin-top: 30px;
            padding-top: 20px;
            text-align: center;
            font-size: 14px;
        }

        .modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.9);
            z-index: 1000;
            justify-content: center;
            align-items: center;
            backdrop-filter: blur(5px);
            padding: 20px;
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
            margin-bottom: 15px;
        }

        .modal-right .tags {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .modal-right .tags span {
            background: rgba(255, 255, 255, 0.08);
            padding: 8px 14px;
            border-radius: 20px;
            font-size: 13px;
            color: var(--text-muted);
        }

        .modal-right p {
            color: var(--text-muted);
            line-height: 1.7;
            font-size: 15px;
        }

        .btn-modal {
            display: inline-block;
            padding: 12px 24px;
            background: var(--primary);
            color: #000;
            font-weight: 600;
            border-radius: 25px;
            text-decoration: none;
            transition: 0.3s;
            border: none;
            cursor: pointer;
        }

        .btn-modal:hover {
            background: var(--primary-hover);
        }

        button.btn-modal {
            border: none;
            cursor: pointer;
            font: inherit;
        }

        #trailerBtn {
            background: #111827;
            color: #fff;
            border: 1px solid #38bdf8;
        }

        #trailerBtn:hover {
            background: #0f172a;
        }

        .close {
            position: absolute;
            top: 15px;
            right: 18px;
            font-size: 30px;
            color: #fff;
            cursor: pointer;
            z-index: 1001;
        }

        @media (max-width: 900px) {
            .right-section {
                gap: 10px;
            }

            .search-input {
                width: 150px;
            }
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }

            .right-section {
                width: 100%;
                justify-content: flex-start;
                flex-wrap: wrap;
            }

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

            .footer-top {
                grid-template-columns: 1fr;
            }

            .search-form {
                width: 100%;
            }

            .search-input {
                width: 100%;
            }
        }
    </style>
</head>

<body>

    <header class="header">
        <div class="logo">WATCHWISE</div>

        <div class="right-section">
            <form action="search.php" method="GET" class="search-form">
                <input type="text" name="query" placeholder="Search movies..." class="search-input" required>
                <button type="submit" class="search-btn">Search</button>
            </form>


            <a href="Signup.php" class="signin-btn">Sign In</a>
        </div>
    </header>

    <section class="hero">
        <div class="hero-content">
            <h1 data-key="title">Dive into endless entertainment.</h1>
            <h2 data-key="subtitle">Premium movies & shows. Starts at ₹149.</h2>

            <form action="step2.php" method="POST" class="email-form" onsubmit="return validateHeroEmail()">
                <div class="email-box">
                    <input type="email" id="heroEmail" name="email" placeholder="Enter your email address">
                    <button type="submit">Get Started</button>
                </div>
                <div class="error" id="errorTop"></div>
            </form>
        </div>
    </section>

    <section class="section">
        <h2>Trending Now</h2>
        <div class="trending-wrapper">
            <button class="arrow-btn left" onclick="scrollTrending(-300)">&#10094;</button>

            <div class="trending" id="trendingSection">
                <?php
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<img src="' . htmlspecialchars($row['image_url']) . '" 
                           data-title="' . htmlspecialchars($row['title']) . '" 
                           data-desc="' . htmlspecialchars($row['description']) . '" 
                           data-year="' . htmlspecialchars($row['release_year']) . '" 
                           data-rating="' . htmlspecialchars($row['rating']) . '" 
                           data-trailer="' . htmlspecialchars($row['trailer_url'] ?? '') . '" 
                           onclick="openModal(this)">';
                    }
                } else {
                    echo "<p>No movies found.</p>";
                }
                ?>
            </div>

            <button class="arrow-btn right" onclick="scrollTrending(300)">&#10095;</button>
        </div>
    </section>

    <section class="section">
        <h2>Why choose Watchwise?</h2>
        <div class="cards">
            <div class="card">
                <h3>Seamless TV Experience</h3>
                <p>Watch on smart TVs, PlayStation, Xbox, Apple TV, Chromecast and more.</p>
            </div>
            <div class="card">
                <h3>Download & Go</h3>
                <p>Save your favourite movies and shows to watch later anytime, even offline.</p>
            </div>
            <div class="card">
                <h3>Watch Everywhere</h3>
                <p>Enjoy on phone, tablet, laptop and TV with one premium account.</p>
            </div>
            <div class="card">
                <h3>Kids Safe Profiles</h3>
                <p>Create a safe and fun space for kids with family-friendly entertainment.</p>
            </div>
        </div>
    </section>

    <section class="section">
        <h2>Got Questions?</h2>

        <div class="faq-item" onclick="toggleFaq(this)">
            <div class="faq-title">
                What is Watchwise?
                <span class="faq-icon">+</span>
            </div>
            <div class="faq-answer">
                Watchwise is a premium streaming platform where users can discover and enjoy movies, shows and trailers.
            </div>
        </div>

        <div class="faq-item" onclick="toggleFaq(this)">
            <div class="faq-title">
                How much does Watchwise cost?
                <span class="faq-icon">+</span>
            </div>
            <div class="faq-answer">
                Plans start at ₹149 and go up depending on quality and screens.
            </div>
        </div>

        <div class="faq-item" onclick="toggleFaq(this)">
            <div class="faq-title">
                Can I watch trailers before subscribing?
                <span class="faq-icon">+</span>
            </div>
            <div class="faq-answer">
                Yes. In the trending section you can open a movie card and watch its trailer inside the website.
            </div>
        </div>

        <div style="margin-top: 50px; text-align: center;">
            <p style="margin-bottom: 20px; color: var(--text-muted);">Ready to start watching? Enter your email to create an account.</p>

            <form action="step2.php" method="POST" class="email-form" onsubmit="return validateBottomEmail()">
                <div class="email-box" style="margin: auto;">
                    <input type="email" id="emailBottom" name="email" placeholder="Enter your email address">
                    <button type="submit">Get Started</button>
                </div>
                <div class="error" id="errorBottom"></div>
            </form>
        </div>
    </section>

    <footer class="footer">
        <div class="footer-top">
            <div>
                <h2>WATCHWISE</h2>
                <p>Watchwise is your modern movie discovery platform with premium plans, trailers and trending entertainment in one place.</p>
            </div>

            <div>
                <h4>Company</h4>
                <a href="#">About Us</a>
                <a href="#">Careers</a>
                <a href="#">Press</a>
                <a href="#">Investors</a>
            </div>

            <div>
                <h4>Support</h4>
                <a href="#">Help Centre</a>
                <a href="#">FAQ</a>
                <a href="#">Account</a>
                <a href="#">Contact Us</a>
            </div>

            <div>
                <h4>Legal</h4>
                <a href="#">Terms of Use</a>
                <a href="#">Privacy Policy</a>
                <a href="#">Cookie Policy</a>
                <a href="#">Corporate Information</a>
            </div>
        </div>

        <div class="footer-bottom">
            <p>© 2026 Watchwise India. All Rights Reserved.</p>
        </div>
    </footer>

    <div id="movieModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <div class="modal-left">
                <img id="modalImg" src="" alt="Movie Poster">
            </div>
            <div class="modal-right">
                <h1 id="modalTitle">Title</h1>
                <div class="tags">
                    <span id="modalYear">2026</span>
                    <span>U/A 16+</span>
                    <span id="modalRating">Premium</span>
                </div>
                <p id="modalDesc">Description goes here.</p>

                <div style="display:flex; gap:12px; flex-wrap:wrap; margin-top:20px;">
                    <a href="step2.php" class="btn-modal">Watch Movie</a>
                    <button type="button" class="btn-modal" id="trailerBtn" onclick="openTrailerModal()">Watch Trailer</button>
                </div>
            </div>
        </div>
    </div>

    <div id="trailerModal" class="modal">
        <div class="modal-content" style="max-width: 900px; width: 95%; padding: 0; background: #000; overflow: hidden;">
            <span class="close" onclick="closeTrailerModal()" style="position:absolute; top:10px; right:16px; z-index:10;">&times;</span>
            <iframe id="trailerFrame" width="100%" height="500" src="" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
        </div>
    </div>

    <script>
        let currentTrailer = "";

        function scrollTrending(amount) {
            document.getElementById("trendingSection").scrollBy({
                left: amount,
                behavior: "smooth"
            });
        }

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

        function validateHeroEmail() {
            let email = document.getElementById("heroEmail").value.trim();
            let error = document.getElementById("errorTop");
            let regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (email === "") {
                error.innerHTML = "Email is required!";
                return false;
            }
            if (!regex.test(email)) {
                error.innerHTML = "Enter a valid email!";
                return false;
            }

            error.innerHTML = "";
            return true;
        }

        function validateBottomEmail() {
            let email = document.getElementById("emailBottom").value.trim();
            let error = document.getElementById("errorBottom");
            let regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (email === "") {
                error.innerHTML = "Email is required!";
                return false;
            }
            if (!regex.test(email)) {
                error.innerHTML = "Enter a valid email!";
                return false;
            }

            error.innerHTML = "";
            return true;
        }

        function openModal(el) {
            document.getElementById("modalImg").src = el.getAttribute("src");
            document.getElementById("modalTitle").innerText = el.getAttribute("data-title");
            document.getElementById("modalDesc").innerText = el.getAttribute("data-desc");
            document.getElementById("modalYear").innerText = el.getAttribute("data-year") || "2026";
            document.getElementById("modalRating").innerText = el.getAttribute("data-rating") || "Premium";

            currentTrailer = el.getAttribute("data-trailer") || "";

            const trailerBtn = document.getElementById("trailerBtn");
            if (!currentTrailer) {
                trailerBtn.disabled = true;
                trailerBtn.style.opacity = "0.6";
                trailerBtn.innerText = "Trailer Not Available";
            } else {
                trailerBtn.disabled = false;
                trailerBtn.style.opacity = "1";
                trailerBtn.innerText = "Watch Trailer";
            }

            document.getElementById("movieModal").style.display = "flex";
        }

        function closeModal() {
            document.getElementById("movieModal").style.display = "none";
        }

        function openTrailerModal() {
            if (!currentTrailer) return;

            let finalUrl = currentTrailer.includes("?") ?
                currentTrailer + "&autoplay=1" :
                currentTrailer + "?autoplay=1";

            document.getElementById("trailerFrame").src = finalUrl;
            document.getElementById("trailerModal").style.display = "flex";
        }

        function closeTrailerModal() {
            document.getElementById("trailerFrame").src = "";
            document.getElementById("trailerModal").style.display = "none";
        }

        window.onclick = function(event) {
            const movieModal = document.getElementById("movieModal");
            const trailerModal = document.getElementById("trailerModal");

            if (event.target === movieModal) {
                closeModal();
            }
            if (event.target === trailerModal) {
                closeTrailerModal();
            }
        };
    </script>
</body>

</html>
