<?php
session_start();
include "db.php";

// 1. Fetch Trending Movies
$movie_query = "SELECT * FROM movies WHERE category = 'trending' AND is_active = 1";
$movie_result = mysqli_query($conn, $movie_query);

// 2. Fetch Hero Content (Assuming 1 active slide)
$hero_query = "SELECT * FROM hero_slides LIMIT 1";
$hero_res = mysqli_query($conn, $hero_query);
$hero = mysqli_fetch_assoc($hero_res);

// 3. Fetch Features (Why Choose Us)
$features_result = mysqli_query($conn, "SELECT * FROM features ORDER BY id ASC");

// 4. Fetch FAQs
$faq_result = mysqli_query($conn, "SELECT * FROM faqs ORDER BY id ASC");

// 5. Fetch Footer Links using your actual column names
$footer_query = "SELECT * FROM footer_links WHERE is_active = 1 ORDER BY sort_order ASC";
$footer_res = mysqli_query($conn, $footer_query);
$footer_data = ['Company' => [], 'Support' => [], 'Legal' => []];

if ($footer_res) {
    while ($link = mysqli_fetch_assoc($footer_res)) {
        // Use 'section_name' to group the links
        $section = $link['section_name'];
        if (isset($footer_data[$section])) {
            $footer_data[$section][] = $link;
        }
    }
}
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

        /* Custom Scrollbar */
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
        }

        .search-input {
            padding: 10px 14px;
            border-radius: 30px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            background: rgba(255, 255, 255, 0.08);
            color: white;
            outline: none;
            width: 190px;
        }

        .search-btn,
        .signin-btn {
            padding: 10px 22px;
            border-radius: 30px;
            background: var(--primary);
            color: black;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
        }

        /* Hero */
        .hero {
            height: 100vh;
            background: linear-gradient(90deg, rgba(11, 15, 25, 0.95) 25%, rgba(11, 15, 25, 0.5) 100%),
                url("<?php echo $hero['image_path'] ?? 'https://images.unsplash.com/photo-1524985069026-dd778a71c7b4'; ?>") center/cover no-repeat;
            display: flex;
            align-items: center;
            padding: 0 5%;
        }

        .hero-content {
            max-width: 650px;
        }

        .hero h1 {
            font-size: 3.5rem;
            line-height: 1.2;
            margin-bottom: 20px;
        }

        .hero h2 {
            font-size: 1.5rem;
            color: var(--text-muted);
            margin-bottom: 30px;
        }

        .email-box {
            display: flex;
            gap: 10px;
            background: rgba(255, 255, 255, 0.05);
            padding: 10px;
            border-radius: 40px;
            backdrop-filter: blur(15px);
        }

        .email-box input {
            flex: 1;
            background: transparent;
            border: none;
            color: white;
            padding: 0 15px;
            outline: none;
        }

        .email-box button {
            background: var(--primary);
            border: none;
            padding: 15px 30px;
            border-radius: 30px;
            font-weight: 600;
            cursor: pointer;
        }

        .section {
            padding: 80px 5%;
        }

        .section h2 {
            font-size: 2rem;
            margin-bottom: 30px;
        }

        /* Slider */
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
            transition: 0.4s;
            flex-shrink: 0;
        }

        .trending img:hover {
            transform: translateY(-10px) scale(1.05);
            border: 2px solid var(--primary);
        }

        /* Cards */
        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
        }

        .card {
            background: rgba(255, 255, 255, 0.03);
            padding: 30px;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .email-box-wrapper {
            width: 100%;
            max-width: 520px;
        }

        .error-text {
            display: block;
            color: #ff4d4f;
            font-size: 14px;
            margin-top: 8px;
            padding-left: 15px;
        }

        .input-error {
            border: 1px solid #ff4d4f !important;
        }

        .card h3 {
            color: var(--primary);
            margin-bottom: 15px;
        }

        /* FAQ */
        .faq-item {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 25px 0;
            cursor: pointer;
        }

        .faq-title {
            display: flex;
            justify-content: space-between;
            font-size: 18px;
        }

        .faq-answer {
            display: none;
            margin-top: 15px;
            color: var(--text-muted);
            line-height: 1.6;
        }

        /* Modal */
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
        }

        .close {
            position: absolute;
            top: 15px;
            right: 18px;
            font-size: 30px;
            cursor: pointer;
            color: #fff;
        }

        .footer {
            padding: 60px 5%;
            background: #070b13;
            color: var(--text-muted);
            border-top: 1px solid #1e293b;
        }

        .footer-top {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 30px;
        }

        .footer a {
            display: block;
            color: var(--text-muted);
            text-decoration: none;
            margin-bottom: 10px;
        }

        .footer a:hover {
            color: var(--primary);
        }

        @media (max-width: 768px) {
            .footer-top {
                grid-template-columns: 1fr;
            }

            .modal-content {
                flex-direction: column;
            }

            .modal-left,
            .modal-right {
                width: 100%;
            }

            .hero h1 {
                font-size: 2.5rem;
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
            <h1><?php echo htmlspecialchars($hero['title'] ?? 'Dive into endless entertainment.'); ?></h1>
            <h2><?php echo htmlspecialchars($hero['subtitle'] ?? 'Premium movies & shows starts here.'); ?></h2>
            <form action="step2.php" method="POST" class="email-form" id="getStartedForm" novalidate>
                <div class="email-box-wrapper">
                    <div class="email-box">
                        <input type="email" name="email" id="heroEmail" placeholder="Enter your email"> <button type="submit">Get Started</button>
                    </div>
                    <span id="heroEmailError" class="error-text"></span>
                </div>
            </form>
        </div>
    </section>

    <section class="section">
        <h2>Trending Now</h2>
        <div class="trending" id="trendingSection">
            <?php if (mysqli_num_rows($movie_result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($movie_result)): ?>
                    <img src="<?php echo htmlspecialchars($row['image_url']); ?>"
                        data-title="<?php echo htmlspecialchars($row['title']); ?>"
                        data-desc="<?php echo htmlspecialchars($row['description']); ?>"
                        data-year="<?php echo htmlspecialchars($row['release_year']); ?>"
                        data-rating="<?php echo htmlspecialchars($row['rating']); ?>"
                        data-trailer="<?php echo htmlspecialchars($row['trailer_url'] ?? ''); ?>"
                        onclick="openModal(this)">
                <?php endwhile; ?>
            <?php else: ?>
                <p>No movies found.</p>
            <?php endif; ?>
        </div>
    </section>

    <section class="section">
        <h2>Why choose Watchwise?</h2>
        <div class="cards">
            <?php if (mysqli_num_rows($features_result) > 0): ?>
                <?php while ($feature = mysqli_fetch_assoc($features_result)): ?>
                    <div class="card">
                        <h3><?php echo htmlspecialchars($feature['title']); ?></h3>
                        <p><?php echo htmlspecialchars($feature['description']); ?></p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Features coming soon.</p>
            <?php endif; ?>
        </div>
    </section>

    <section class="section">
        <h2>Got Questions?</h2>
        <?php while ($faq = mysqli_fetch_assoc($faq_result)): ?>
            <div class="faq-item" onclick="toggleFaq(this)">
                <div class="faq-title">
                    <?php echo htmlspecialchars($faq['question']); ?>
                    <span class="faq-icon">+</span>
                </div>
                <div class="faq-answer">
                    <?php echo htmlspecialchars($faq['answer']); ?>
                </div>
            </div>
        <?php endwhile; ?>
    </section>

    <footer class="footer">
        <div class="footer-top">
            <div>
                <h2>WATCHWISE</h2>
                <p>Watchwise is your modern movie discovery platform with premium plans, trailers and trending entertainment in one place.</p>
            </div>

            <?php foreach (['Company', 'Support', 'Legal'] as $section): ?>
                <div>
                    <h4><?php echo $section; ?></h4>
                    <?php
                    if (!empty($footer_data[$section])):
                        foreach ($footer_data[$section] as $link):
                    ?>
                            <a href="<?php echo htmlspecialchars($link['url'] ?? '#'); ?>">
                                <?php echo htmlspecialchars($link['label'] ?? 'Link'); ?>
                            </a>
                    <?php
                        endforeach;
                    endif;
                    ?>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="footer-bottom">
            <p>© <?php echo date("Y"); ?> Watchwise India. All Rights Reserved.</p>
        </div>
    </footer>

    <div id="movieModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <div class="modal-left"><img id="modalImg" src=""></div>
            <div class="modal-right">
                <h1 id="modalTitle"></h1>
                <p id="modalDesc" style="margin: 20px 0; color: var(--text-muted);"></p>
                <button class="signin-btn" onclick="alert('Proceed to Subscription')">Watch Now</button>
            </div>
        </div>
    </div>

    <script>
        function toggleFaq(el) {
            const ans = el.querySelector(".faq-answer");
            const icon = el.querySelector(".faq-icon");
            const isOpen = ans.style.display === "block";
            ans.style.display = isOpen ? "none" : "block";
            icon.innerHTML = isOpen ? "+" : "&times;";
        }

        function openModal(el) {
            document.getElementById("modalImg").src = el.src;
            document.getElementById("modalTitle").innerText = el.dataset.title;
            document.getElementById("modalDesc").innerText = el.dataset.desc;
            document.getElementById("movieModal").style.display = "flex";
        }

        function closeModal() {
            document.getElementById("movieModal").style.display = "none";
        }

        window.onclick = (e) => {
            if (e.target.className === 'modal') closeModal();
        };

        // Custom Get Started Email Validation
        const getStartedForm = document.getElementById("getStartedForm");
        const heroEmail = document.getElementById("heroEmail");
        const heroEmailError = document.getElementById("heroEmailError");

        getStartedForm.addEventListener("submit", function(e) {
            const emailValue = heroEmail.value.trim();
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            heroEmailError.innerText = "";
            heroEmail.classList.remove("input-error");

            if (emailValue === "") {
                e.preventDefault();
                heroEmailError.innerText = "Please enter your email.";
                heroEmail.classList.add("input-error");
            } else if (!emailPattern.test(emailValue)) {
                e.preventDefault();
                heroEmailError.innerText = "Invalid email format.";
                heroEmail.classList.add("input-error");
            }
        });

        heroEmail.addEventListener("input", function() {
            heroEmailError.innerText = "";
            heroEmail.classList.remove("input-error");
        });
    </script>
</body>

</html>