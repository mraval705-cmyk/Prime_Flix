<?php
session_start();
include "db.php";

/* ---------------- FETCH HERO ---------------- */
$hero = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM hero_slides LIMIT 1"));

/* ---------------- FETCH SITE SETTINGS ---------------- */
$settings = [];
$settings_query = mysqli_query($conn, "SELECT setting_key, setting_value FROM site_settings");
if ($settings_query) {
    while ($row = mysqli_fetch_assoc($settings_query)) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
}

/* ---------------- FETCH MOVIES ---------------- */
$trending_result = mysqli_query($conn, "SELECT * FROM movies WHERE category = 'trending' AND is_active = 1 ORDER BY id DESC");

/* ---------------- FETCH FEATURES ---------------- */
$features_result = mysqli_query($conn, "SELECT * FROM features ORDER BY id DESC");

/* ---------------- FETCH FAQS ---------------- */
$faqs_result = mysqli_query($conn, "SELECT * FROM faqs ORDER BY id DESC");

/* ---------------- FETCH FOOTER LINKS ---------------- */
$footer_links_result = mysqli_query($conn, "SELECT * FROM footer_links WHERE is_active = 1 ORDER BY section_name, sort_order ASC");

$footer_sections = [
    "Company" => [],
    "Support" => [],
    "Legal" => []
];

if ($footer_links_result) {
    while ($row = mysqli_fetch_assoc($footer_links_result)) {
        $section = $row['section_name'];
        if (!isset($footer_sections[$section])) {
            $footer_sections[$section] = [];
        }
        $footer_sections[$section][] = $row;
    }
}

/* ---------------- FETCH OFFERS ---------------- */
$offers_result = mysqli_query($conn, "SELECT coupon_code, discount_type, discount_value, min_amount FROM offers WHERE is_active = 1 AND valid_until >= CURDATE() ORDER BY id DESC LIMIT 6");


/* ---------------- FALLBACKS ---------------- */
$hero_title = $hero['title'] ?? 'Dive into endless entertainment.';
$hero_subtitle = $hero['subtitle'] ?? 'Premium movies & shows. Starts at ₹149.';
$hero_image = !empty($hero['image_url'])
    ? $hero['image_url']
    : 'https://images.unsplash.com/photo-1524985069026-dd778a71c7b4';

$why_choose_title = $settings['why_choose_title'] ?? 'Why choose Watchwise?';
$faq_title = $settings['faq_title'] ?? 'Got Questions?';
$cta_text = $settings['cta_text'] ?? 'Ready to start watching? Enter your email to create an account.';
$footer_about = $settings['footer_about'] ?? 'Watchwise is your modern movie discovery platform with premium plans, trailers and trending entertainment in one place.';
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
            background:
                linear-gradient(90deg, rgba(11, 15, 25, 0.95) 25%, rgba(11, 15, 25, 0.5) 100%),
                url("<?php echo htmlspecialchars($hero_image); ?>") center/cover no-repeat;
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

        .offers-strip-section {
            padding: 22px 5% 10px;
            background: linear-gradient(180deg, rgba(2, 6, 23, 0.96), rgba(11, 15, 25, 0.92));
        }

        .offers-strip {
            max-width: 1280px;
            margin: 0 auto;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .offer-pill {
            min-height: 42px;
            padding: 8px 12px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.08);
            color: #e2e8f0;
            font-size: 12px;
            line-height: 1.2;
            backdrop-filter: blur(8px);
            transition: 0.25s ease;
        }

        .offer-pill:hover {
            transform: translateY(-2px);
            border-color: rgba(0, 229, 255, 0.45);
            box-shadow: 0 10px 20px rgba(0, 229, 255, 0.08);
        }

        .offer-pill-icon {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #22c55e, #16a34a);
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .offer-pill strong {
            color: #ffffff;
            font-size: 12px;
        }

        .offer-pill a {
            color: inherit;
            text-decoration: none;
        }

        .offer-pill a:hover {
            color: #fff;
        }

        @media (max-width: 768px) {
            .offers-strip-section {
                padding: 16px 4% 6px;
            }

            .offers-strip {
                gap: 8px;
            }

            .offer-pill {
                width: 100%;
                justify-content: flex-start;
            }
        }

        @media (max-width: 900px) {
            .right-section {
                gap: 10px;
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
                    background:
                        linear-gradient(0deg, #0b0f19 20%, rgba(11, 15, 25, 0.7) 100%),
                        url("<?php echo htmlspecialchars($hero_image); ?>") center/cover;
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

                .hero h1 {
                    font-size: 2.5rem;
                }
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
            <h1><?php echo htmlspecialchars($hero_title); ?></h1>
            <h2><?php echo htmlspecialchars($hero_subtitle); ?></h2>

            <form action="step2.php" method="POST" class="email-form" onsubmit="return validateHeroEmail()">
                <div class="email-box">
                    <input type="email" id="heroEmail" name="email" placeholder="Enter your email address">
                    <button type="submit">Get Started</button>
                </div>
            </form>
        </div>
    </section>



    <section class="section">
        <h2>Trending Now</h2>
        <div class="trending-wrapper">
            <button class="arrow-btn left" onclick="scrollTrending(-300)">&#10094;</button>

            <div class="trending" id="trendingSection">
                <?php
                if ($trending_result && mysqli_num_rows($trending_result) > 0) {
                    while ($row = mysqli_fetch_assoc($trending_result)) {
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
        <h2><?php echo htmlspecialchars($why_choose_title); ?></h2>
        <div class="cards">
            <?php if ($features_result && mysqli_num_rows($features_result) > 0): ?>
                <?php while ($feature = mysqli_fetch_assoc($features_result)): ?>
                    <div class="card">
                        <h3><?php echo htmlspecialchars($feature['title']); ?></h3>
                        <p><?php echo htmlspecialchars($feature['description']); ?></p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
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
            <?php endif; ?>
        </div>
    </section>

    <section class="section">
        <h2><?php echo htmlspecialchars($faq_title); ?></h2>

        <?php if ($faqs_result && mysqli_num_rows($faqs_result) > 0): ?>
            <?php while ($faq = mysqli_fetch_assoc($faqs_result)): ?>
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
        <?php else: ?>
            <div class="faq-item" onclick="toggleFaq(this)">
                <div class="faq-title">
                    What is Watchwise?
                    <span class="faq-icon">+</span>
                </div>
                <div class="faq-answer">
                    Watchwise is a premium streaming platform where users can discover and enjoy movies, shows and trailers.
                </div>
            </div>
        <?php endif; ?>

    </section>

    <footer class="footer">
        <div class="footer-top">
            <div>
                <h2>WATCHWISE</h2>
                <p><?php echo htmlspecialchars($footer_about); ?></p>
            </div>

            <?php foreach ($footer_sections as $section_name => $links): ?>
                <div>
                    <h4><?php echo htmlspecialchars($section_name); ?></h4>
                    <?php if (!empty($links)): ?>
                        <?php foreach ($links as $link): ?>
                            <a href="<?php echo htmlspecialchars($link['url']); ?>">
                                <?php echo htmlspecialchars($link['label']); ?>
                            </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <a href="#">No links</a>
                    <?php endif; ?>
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
                <h1 id="modalTitle">Title</h1>
                <div class="tags">
                    <span id="modalYear">2026</span>
                    <span>U/A 16+</span>
                    <span id="modalRating">Premium</span>
                </div>
                <p id="modalDesc">Description goes here.</p>

                <div style="display:flex; gap:12px; flex-wrap:wrap; margin-top:20px;">
                    <a href="Signup.php" class="btn-modal">Watch Movie</a>
                    <button type="button" class="btn-modal" id="trailerBtn" onclick="openTrailerModal()">Watch Trailer</button>
                </div>
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