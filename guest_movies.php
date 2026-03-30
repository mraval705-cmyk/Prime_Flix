<?php
include "db.php";

$moviesData = [];
$moodSet = [];

$sql = "SELECT id, title, image_url, trailer_url, moods, description 
        FROM movies 
        WHERE is_active = 1
        ORDER BY title ASC";
$result = mysqli_query($conn, $sql);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $moods = [];

        if (!empty($row['moods'])) {
            $moods = array_map('trim', explode(',', strtolower($row['moods'])));
            foreach ($moods as $m) {
                if ($m !== '') {
                    $moodSet[$m] = true;
                }
            }
        }

        $moviesData[] = [
            "id" => $row['id'],
            "title" => $row['title'],
            "img" => $row['image_url'],
            "moods" => $moods,
            "trailer" => !empty($row['trailer_url']) ? $row['trailer_url'] : "",
            "description" => $row['description']
        ];
    }
}

$allMoods = array_keys($moodSet);
sort($allMoods);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Watchwise Guest Explore</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(180deg, #020817, #06152d, #020817);
            color: white;
        }

        .hero {
            padding: 30px 20px 20px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero::before,
        .hero::after {
            content: "";
            position: absolute;
            width: 260px;
            height: 260px;
            border-radius: 50%;
            filter: blur(90px);
            opacity: 0.25;
            z-index: 0;
        }

        .hero::before {
            background: #0ea5e9;
            top: -60px;
            left: -60px;
        }

        .hero::after {
            background: #9333ea;
            top: 30px;
            right: -70px;
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .back-home {
            display: inline-block;
            margin-bottom: 18px;
            color: #38bdf8;
            text-decoration: none;
            font-weight: bold;
        }

        .title {
            font-size: 48px;
            font-weight: bold;
            margin: 10px 0;
            color: #38bdf8;
        }

        .subtitle {
            max-width: 720px;
            margin: 0 auto 24px;
            color: #cbd5e1;
            font-size: 18px;
            line-height: 1.5;
        }

        .search-wrap {
            display: flex;
            justify-content: center;
            margin-bottom: 22px;
        }

        .search-box {
            width: 100%;
            max-width: 560px;
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(56, 189, 248, 0.35);
            border-radius: 16px;
            padding: 12px 16px;
            backdrop-filter: blur(10px);
            box-shadow: 0 0 18px rgba(14, 165, 233, 0.15);
        }

        .search-box span {
            font-size: 18px;
            margin-right: 10px;
            color: #7dd3fc;
        }

        .search-box input {
            width: 100%;
            border: none;
            background: transparent;
            color: white;
            font-size: 16px;
            outline: none;
        }

        .search-box input::placeholder {
            color: #94a3b8;
        }

        .stats {
            display: flex;
            justify-content: center;
            gap: 14px;
            flex-wrap: wrap;
            margin-bottom: 22px;
        }

        .stat {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(148, 163, 184, 0.2);
            color: #e2e8f0;
            padding: 10px 16px;
            border-radius: 999px;
            font-size: 14px;
        }

        .section {
            padding: 10px 20px 30px;
        }

        .section-title {
            font-size: 28px;
            text-align: center;
            margin-bottom: 8px;
            color: #f8fafc;
        }

        .section-subtitle {
            text-align: center;
            color: #94a3b8;
            margin-bottom: 24px;
            font-size: 15px;
        }

        .mood-buttons {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 25px;
        }

        .mood-btn {
            padding: 12px 18px;
            border-radius: 999px;
            border: 1px solid #334155;
            background: rgba(30, 41, 59, 0.9);
            color: white;
            cursor: pointer;
            transition: 0.25s ease;
            font-size: 15px;
        }

        .mood-btn:hover {
            transform: translateY(-2px);
            border-color: #38bdf8;
            box-shadow: 0 0 14px rgba(56, 189, 248, 0.25);
        }

        .mood-btn.active {
            background: linear-gradient(90deg, #0ea5e9, #2563eb);
            border-color: #38bdf8;
            box-shadow: 0 0 20px rgba(14, 165, 233, 0.4);
            color: white;
        }

        .action-row {
            display: flex;
            justify-content: center;
            margin-bottom: 24px;
        }

        .lucky-btn {
            background: linear-gradient(90deg, #06b6d4, #3b82f6);
            color: white;
            text-decoration: none;
            padding: 13px 22px;
            border-radius: 14px;
            font-weight: bold;
            box-shadow: 0 0 18px rgba(14, 165, 233, 0.28);
            transition: 0.25s ease;
        }

        .lucky-btn:hover {
            transform: translateY(-2px) scale(1.02);
        }

        .current-filter {
            text-align: center;
            font-size: 18px;
            color: #7dd3fc;
            margin-bottom: 20px;
            font-weight: bold;
            min-height: 24px;
        }

        .movies {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 220px));
            justify-content: center;
            gap: 24px;
        }

        .card {
            background: linear-gradient(180deg, rgba(30, 41, 59, 0.98), rgba(15, 23, 42, 0.98));
            border: 1px solid rgba(148, 163, 184, 0.15);
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.35);
            transition: 0.3s ease;
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 16px 28px rgba(0, 0, 0, 0.45);
        }

        .card img {
            width: 100%;
            height: 320px;
            object-fit: cover;
            display: block;
            background: #0f172a;
        }

        .card-body {
            padding: 16px;
        }

        .card h3 {
            margin: 0 0 10px;
            font-size: 18px;
            min-height: 48px;
            line-height: 1.35;
        }

        .tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 14px;
            min-height: 52px;
            align-content: flex-start;
        }

        .tag {
            background: rgba(56, 189, 248, 0.12);
            color: #7dd3fc;
            border: 1px solid rgba(56, 189, 248, 0.25);
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 12px;
        }

        .card button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 12px;
            background: linear-gradient(90deg, #0ea5e9, #2563eb);
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: 0.25s ease;
        }

        .card button:hover {
            transform: scale(1.02);
        }

        .empty-state {
            text-align: center;
            color: #94a3b8;
            font-size: 18px;
            padding: 30px 0;
        }

        .modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(2, 6, 23, 0.92);
            justify-content: center;
            align-items: center;
            padding: 20px;
            z-index: 999;
        }

        .modal-box {
            width: 95%;
            max-width: 900px;
            background: #0f172a;
            border: 1px solid rgba(56, 189, 248, 0.2);
            border-radius: 18px;
            overflow: hidden;
            position: relative;
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.45);
        }

        .modal iframe {
            width: 100%;
            height: 500px;
            border: none;
            display: block;
        }

        .close {
            position: absolute;
            top: 10px;
            right: 16px;
            font-size: 30px;
            color: white;
            cursor: pointer;
            z-index: 10;
        }

        @media (max-width: 768px) {
            .title {
                font-size: 34px;
            }

            .subtitle {
                font-size: 15px;
            }

            .movies {
                grid-template-columns: repeat(auto-fit, minmax(180px, 180px));
                gap: 18px;
            }

            .card img {
                height: 260px;
            }

            .modal iframe {
                height: 260px;
            }
        }
    </style>
</head>

<body>

    <div class="hero">
        <div class="hero-content">
            <a href="index.php" class="back-home">← Back to Home</a>
            <div class="title">Watchwise Guest Explore</div>

            <div class="search-wrap">
                <div class="search-box">
                    <span>🔍</span>
                    <input type="text" id="searchBox" placeholder="Search by movie title or mood..." onkeyup="searchMovies(this.value)">
                </div>
            </div>

        </div>
    </div>

    <div class="section">
        <div class="section-title">Choose Your Mood</div>
        <div class="section-subtitle">Tap a mood and the matching movies will be highlighted for your vibe.</div>

        <div class="mood-buttons">
            <button class="mood-btn active" onclick="filterMood('all', this)">All</button>
            <?php foreach ($allMoods as $mood): ?>
                <button class="mood-btn" onclick="filterMood('<?php echo htmlspecialchars($mood); ?>', this)">
                    <?php echo ucfirst(htmlspecialchars($mood)); ?>
                </button>
            <?php endforeach; ?>
        </div>



        <div class="current-filter" id="currentFilter">Showing: All Movies</div>

        <div class="movies" id="movieGrid"></div>
    </div>

    <div class="modal" id="trailerModal">
        <div class="modal-box">
            <span class="close" onclick="closeTrailer()">&times;</span>
            <iframe id="trailerFrame" allowfullscreen></iframe>
        </div>
    </div>

    <script>
        const movies = <?php echo json_encode($moviesData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>;

        function renderMovies(list) {
            const grid = document.getElementById("movieGrid");
            grid.innerHTML = "";

            if (list.length === 0) {
                grid.innerHTML = "<div class='empty-state'>No matching movies found.</div>";
                return;
            }

            list.forEach(movie => {
                const tagsHTML = movie.moods.map(mood => `<span class="tag">${mood}</span>`).join("");

                grid.innerHTML += `
            <div class="card">
                <img src="${movie.img}" alt="${movie.title}" onerror="this.src='https://via.placeholder.com/300x450?text=Movie+Poster'">
                <div class="card-body">
                    <h3>${movie.title}</h3>
                    <div class="tags">${tagsHTML}</div>
                    <button onclick="openTrailer('${movie.trailer}')">Watch Trailer</button>
                </div>
            </div>
        `;
            });
        }

        function setActiveButton(clickedBtn) {
            const buttons = document.querySelectorAll(".mood-btn");
            buttons.forEach(btn => btn.classList.remove("active"));
            if (clickedBtn) clickedBtn.classList.add("active");
        }

        function filterMood(mood, clickedBtn = null) {
            setActiveButton(clickedBtn);
            const filterText = document.getElementById("currentFilter");

            if (mood === "all") {
                filterText.innerText = "Showing: All Movies";
                renderMovies(movies);
            } else {
                const filtered = movies.filter(movie => movie.moods.includes(mood));
                filterText.innerText = "Showing: " + mood.charAt(0).toUpperCase() + mood.slice(1) + " Mood Movies";
                renderMovies(filtered);
            }
        }

        function searchMovies(value) {
            value = value.toLowerCase().trim();
            const filterText = document.getElementById("currentFilter");

            const filtered = movies.filter(movie =>
                movie.title.toLowerCase().includes(value) ||
                movie.moods.join(" ").toLowerCase().includes(value)
            );

            if (value === "") {
                filterText.innerText = "Showing: All Movies";
                const allButton = document.querySelectorAll(".mood-btn")[0];
                setActiveButton(allButton);
                renderMovies(movies);
            } else {
                filterText.innerText = "Search Results for: " + value;
                document.querySelectorAll(".mood-btn").forEach(btn => btn.classList.remove("active"));
                renderMovies(filtered);
            }
        }

        function openTrailer(link) {
            if (!link) {
                alert("Trailer not available for this movie.");
                return;
            }
            document.getElementById("trailerFrame").src = link + (link.includes("?") ? "&autoplay=1" : "?autoplay=1");
            document.getElementById("trailerModal").style.display = "flex";
        }

        function closeTrailer() {
            document.getElementById("trailerFrame").src = "";
            document.getElementById("trailerModal").style.display = "none";
        }

        window.onclick = function(event) {
            const modal = document.getElementById("trailerModal");
            if (event.target === modal) {
                closeTrailer();
            }
        }

        renderMovies(movies);
    </script>

</body>

</html>