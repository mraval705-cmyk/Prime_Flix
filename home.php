<?php
session_start();
include "db.php";

$heroQuery = "SELECT * FROM movies ORDER BY id DESC LIMIT 1";
$heroResult = mysqli_query($conn, $heroQuery);
$heroMovie = mysqli_fetch_assoc($heroResult);

$moviesQuery = "SELECT * FROM movies WHERE category='trending' ORDER BY id DESC";
$moviesResult = mysqli_query($conn, $moviesQuery);

$dramasQuery = "SELECT * FROM movies WHERE category='drama' ORDER BY id DESC";
$dramasResult = mysqli_query($conn, $dramasQuery);

$kidsQuery = "SELECT * FROM movies WHERE category='kids' OR category='anime' ORDER BY id DESC";
$kidsResult = mysqli_query($conn, $kidsQuery);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WatchWise Home</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap');

        :root {
            --primary-blue: #0088ff;
            --bg-dark: #000000;
            --text-white: #f0f4f8;
            --card-width: 180px;
            --card-height: 270px;
            --surface-dark: #0a0e14;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            scrollbar-width: none;
        }

        *::-webkit-scrollbar {
            display: none;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-white);
            overflow-x: hidden;
        }

        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            padding: 20px 4%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0.9) 10%, rgba(0, 0, 0, 0));
        }

        .nav-left {
            display: flex;
            align-items: center;
            gap: 40px;
        }

        .logo {
            color: var(--primary-blue);
            font-size: 28px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 20px;
        }

        .nav-links a {
            text-decoration: none;
            color: #b0c4de;
            font-size: 14px;
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .search-box {
            display: flex;
            align-items: center;
            background: rgba(10, 14, 20, 0.75);
            border: 1px solid rgba(0, 136, 255, 0.3);
            padding: 5px 10px;
            border-radius: 4px;
        }

        .search-box input {
            background: transparent;
            border: none;
            color: #fff;
            padding: 5px;
            outline: none;
            width: 220px;
            font-size: 14px;
        }

        .profile-link {
            color: white;
            text-decoration: none;
            background: var(--primary-blue);
            padding: 8px 14px;
            border-radius: 6px;
        }

        .hero {
            height: 90vh;
            background-size: cover;
            background-position: center top;
            display: flex;
            align-items: center;
            padding: 0 4%;
            position: relative;
            background-image: linear-gradient(to right, rgba(0, 0, 0, 0.9) 0%, rgba(0, 0, 0, 0.4) 50%, rgba(0, 0, 0, 0) 100%),
                url('<?php echo !empty($heroMovie['image_url']) ? htmlspecialchars($heroMovie['image_url']) : "https://via.placeholder.com/1200x700"; ?>');
        }

        .hero-content {
            max-width: 650px;
            z-index: 10;
            margin-top: 50px;
        }

        .hero-title {
            font-size: 4rem;
            font-weight: 800;
            margin-bottom: 15px;
            line-height: 1.1;
        }

        .hero-desc {
            font-size: 1.2rem;
            line-height: 1.5;
            margin-bottom: 25px;
            color: #d0dbe6;
        }

        .hero-buttons {
            display: flex;
            gap: 15px;
        }

        .btn {
            padding: 0.6rem 1.8rem;
            border-radius: 4px;
            border: none;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .btn-play {
            background-color: var(--primary-blue);
            color: #fff;
        }

        .btn-more {
            background-color: rgba(30, 40, 56, 0.8);
            color: #fff;
        }

        .row-section {
            padding: 20px 4%;
            position: relative;
            z-index: 20;
            margin-bottom: 30px;
        }

        .row-title {
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 15px;
            color: #e5e5e5;
        }

        .movie-row {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            padding: 20px 0;
            scroll-behavior: smooth;
            width: 100%;
        }

        .movie-card {
            min-width: var(--card-width);
            height: var(--card-height);
            border-radius: 4px;
            overflow: hidden;
            cursor: pointer;
            position: relative;
        }

        .movie-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .movie-card:hover {
            transform: scale(1.05);
            transition: .3s;
            box-shadow: 0 0 20px rgba(0, 136, 255, 0.4);
            border: 2px solid var(--primary-blue);
        }

        .movie-info {
            display: none;
        }

        .handle {
            position: absolute;
            background: rgba(0, 0, 0, 0.6);
            color: white;
            border: none;
            height: var(--card-height);
            width: 40px;
            cursor: pointer;
            z-index: 50;
            font-size: 24px;
            opacity: 0;
        }

        .row-container {
            position: relative;
            display: flex;
            align-items: center;
        }

        .row-container:hover .handle {
            opacity: 1;
        }

        .left-handle {
            left: 0;
        }

        .right-handle {
            right: 0;
        }

        #movieModal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .9);
            justify-content: center;
            align-items: center;
            z-index: 2000;
            backdrop-filter: blur(10px);
        }

        .modal-box {
            background: var(--surface-dark);
            width: 90%;
            max-width: 600px;
            border-radius: 10px;
            overflow: hidden;
            position: relative;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .modal-img-container {
            width: 100%;
            height: 250px;
        }

        .modal-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .modal-content {
            padding: 20px 30px 30px;
        }

        .close {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(10, 14, 20, 0.8);
            color: white;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            :root {
                --card-width: 120px;
                --card-height: 180px;
            }

            .hero-title {
                font-size: 2.5rem;
            }

            .nav-links {
                display: none;
            }

            .search-box input {
                width: 120px;
            }

            .handle {
                display: none;
            }
        }
    </style>
</head>

<body>

    <div class="navbar">
        <div class="nav-left">
            <div class="logo">WatchWise</div>
            <ul class="nav-links">
                <li><a href="#hero">Home</a></li>
                <li><a href="#movies">Movies</a></li>
                <li><a href="#dramas">TV Shows</a></li>
                <li><a href="#kids">Kids & Anime</a></li>
            </ul>
        </div>
        <div class="nav-right">
            <div class="search-box">
                <span style="color: white; margin-right: 5px;">🔍</span>
                <input type="text" placeholder="Search title..." onkeyup="searchMovie(this.value)">
            </div>
            <a href="profile.php" class="profile-link">Profile</a>
        </div>
    </div>

    <section class="hero" id="hero">
        <div class="hero-content">
            <h1 class="hero-title"><?php echo !empty($heroMovie['title']) ? htmlspecialchars($heroMovie['title']) : 'Welcome'; ?></h1>
            <p class="hero-desc"><?php echo !empty($heroMovie['description']) ? htmlspecialchars($heroMovie['description']) : 'Watch the best content on WatchWise.'; ?></p>
            <div class="hero-buttons">
                <a href="#movies" class="btn btn-play">▶ Play</a>
                <button class="btn btn-more" onclick="openMovieByData('<?php echo htmlspecialchars($heroMovie['title'] ?? ''); ?>','<?php echo htmlspecialchars($heroMovie['description'] ?? ''); ?>','<?php echo htmlspecialchars($heroMovie['image_url'] ?? ''); ?>','<?php echo htmlspecialchars($heroMovie['release_year'] ?? ''); ?>','<?php echo htmlspecialchars($heroMovie['rating'] ?? ''); ?>')">ⓘ More Info</button>
            </div>
        </div>
    </section>

    <div class="row-section" id="movies">
        <h2 class="row-title">Trending Movies</h2>
        <div class="row-container">
            <button class="handle left-handle" onclick="scrollRow('moviesRow', -300)">❮</button>
            <div class="movie-row" id="moviesRow">
                <?php while ($row = mysqli_fetch_assoc($moviesResult)) { ?>
                    <div class="movie-card"
                        data-title="<?php echo htmlspecialchars($row['title']); ?>"
                        data-desc="<?php echo htmlspecialchars($row['description']); ?>"
                        data-img="<?php echo htmlspecialchars($row['image_url']); ?>"
                        data-year="<?php echo htmlspecialchars($row['release_year']); ?>"
                        data-rating="<?php echo htmlspecialchars($row['rating']); ?>"
                        onclick="openMovie(this)">
                        <img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>">
                    </div>
                <?php } ?>
            </div>
            <button class="handle right-handle" onclick="scrollRow('moviesRow', 300)">❯</button>
        </div>
    </div>

    <div class="row-section" id="dramas">
        <h2 class="row-title">Popular TV Dramas</h2>
        <div class="row-container">
            <button class="handle left-handle" onclick="scrollRow('dramasRow', -300)">❮</button>
            <div class="movie-row" id="dramasRow">
                <?php while ($row = mysqli_fetch_assoc($dramasResult)) { ?>
                    <div class="movie-card"
                        data-title="<?php echo htmlspecialchars($row['title']); ?>"
                        data-desc="<?php echo htmlspecialchars($row['description']); ?>"
                        data-img="<?php echo htmlspecialchars($row['image_url']); ?>"
                        data-year="<?php echo htmlspecialchars($row['release_year']); ?>"
                        data-rating="<?php echo htmlspecialchars($row['rating']); ?>"
                        onclick="openMovie(this)">
                        <img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>">
                    </div>
                <?php } ?>
            </div>
            <button class="handle right-handle" onclick="scrollRow('dramasRow', 300)">❯</button>
        </div>
    </div>

    <div class="row-section" id="kids">
        <h2 class="row-title">Kids & Anime</h2>
        <div class="row-container">
            <button class="handle left-handle" onclick="scrollRow('kidsRow', -300)">❮</button>
            <div class="movie-row" id="kidsRow">
                <?php while ($row = mysqli_fetch_assoc($kidsResult)) { ?>
                    <div class="movie-card"
                        data-title="<?php echo htmlspecialchars($row['title']); ?>"
                        data-desc="<?php echo htmlspecialchars($row['description']); ?>"
                        data-img="<?php echo htmlspecialchars($row['image_url']); ?>"
                        data-year="<?php echo htmlspecialchars($row['release_year']); ?>"
                        data-rating="<?php echo htmlspecialchars($row['rating']); ?>"
                        onclick="openMovie(this)">
                        <img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>">
                    </div>
                <?php } ?>
            </div>
            <button class="handle right-handle" onclick="scrollRow('kidsRow', 300)">❯</button>
        </div>
    </div>

    <div id="movieModal">
        <div class="modal-box">
            <span class="close" onclick="closeModal()">✕</span>
            <div class="modal-img-container">
                <img id="modalImg" src="" alt="Movie">
            </div>
            <div class="modal-content">
                <h2 id="modalTitle"></h2>
                <p id="modalMeta" style="margin:10px 0;color:#b0c4de;"></p>
                <p id="modalDesc" style="line-height:1.5;color:#d0dbe6;"></p>
            </div>
        </div>
    </div>

    <script>
        function scrollRow(rowId, amount) {
            document.getElementById(rowId).scrollBy({
                left: amount,
                behavior: 'smooth'
            });
        }

        function openMovie(card) {
            document.getElementById('modalImg').src = card.getAttribute('data-img');
            document.getElementById('modalTitle').innerText = card.getAttribute('data-title');
            document.getElementById('modalMeta').innerText = card.getAttribute('data-year') + ' | Rating: ' + card.getAttribute('data-rating');
            document.getElementById('modalDesc').innerText = card.getAttribute('data-desc');
            document.getElementById('movieModal').style.display = 'flex';
        }

        function openMovieByData(title, desc, img, year, rating) {
            document.getElementById('modalImg').src = img;
            document.getElementById('modalTitle').innerText = title;
            document.getElementById('modalMeta').innerText = year + ' | Rating: ' + rating;
            document.getElementById('modalDesc').innerText = desc;
            document.getElementById('movieModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('movieModal').style.display = 'none';
        }

        window.onclick = function(e) {
            const modal = document.getElementById('movieModal');
            if (e.target === modal) {
                closeModal();
            }
        };

        function searchMovie(value) {
            const cards = document.querySelectorAll('.movie-card');
            const search = value.toLowerCase().trim();

            cards.forEach(card => {
                const title = card.getAttribute('data-title').toLowerCase();
                card.style.display = title.includes(search) ? 'block' : 'none';
            });
        }
    </script>

</body>

</html>