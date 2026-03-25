<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WatchWise - Blue & Black Theme</title>
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

    /* NAVBAR */
    .navbar {
        position: fixed;
        top: 0;
        width: 100%;
        padding: 20px 4%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        z-index: 1000;
        transition: background-color 0.5s ease;
        background: linear-gradient(to bottom, rgba(0, 0, 0, 0.9) 10%, rgba(0, 0, 0, 0));
    }

    .navbar.black-nav {
        background-color: #000000;
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
        cursor: pointer;
        text-shadow: 0px 0px 10px rgba(0, 136, 255, 0.4);
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
        transition: color 0.3s;
    }

    .nav-links a:hover {
        color: #fff;
    }

    .nav-right {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    /* SEARCH BAR */
    .search-box {
        display: flex;
        align-items: center;
        background: rgba(10, 14, 20, 0.75);
        border: 1px solid rgba(0, 136, 255, 0.3);
        padding: 5px 10px;
        border-radius: 4px;
        backdrop-filter: blur(2px);
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

    .profile-icon {
        width: 35px;
        height: 35px;
        border-radius: 4px;
        cursor: pointer;
        object-fit: cover;
    }

    /* HERO SECTION */
    .hero {
        height: 90vh;
        background-size: cover;
        background-position: center top;
        display: flex;
        align-items: center;
        padding: 0 4%;
        position: relative;
        transition: background-image 0.5s ease-in-out;
    }

    .hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(to right, rgba(0, 0, 0, 0.9) 0%, rgba(0, 0, 0, 0.4) 50%, rgba(0, 0, 0, 0) 100%);
    }

    .hero::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 10rem;
        background-image: linear-gradient(180deg, transparent, #000000);
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
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);
        line-height: 1.1;
    }

    .hero-desc {
        font-size: 1.2rem;
        font-weight: 400;
        line-height: 1.5;
        margin-bottom: 25px;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.9);
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
        transition: 0.2s;
    }

    .btn-play {
        background-color: var(--primary-blue);
        color: #fff;
        box-shadow: 0 4px 15px rgba(0, 136, 255, 0.3);
    }

    .btn-play:hover {
        background-color: #006bce;
    }

    .btn-more {
        background-color: rgba(30, 40, 56, 0.8);
        color: #fff;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .btn-more:hover {
        background-color: rgba(30, 40, 56, 0.5);
    }

    /* ROWS & CARDS */
    .row-section {
        padding: 10px 4%;
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

    .row-container {
        position: relative;
        display: flex;
        align-items: center;
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
        transition: transform 0.3s ease, opacity 0.3s ease;
        flex-shrink: 0;
        position: relative;
    }

    .movie-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .movie-card:hover {
        transform: scale(1.1);
        z-index: 100;
        box-shadow: 0 0 20px rgba(0, 136, 255, 0.4);
        border: 2px solid var(--primary-blue);
    }

    .movie-row:hover .movie-card:not(:hover) {
        opacity: 0.3;
    }

    /* SCROLL BUTTONS */
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
        transition: background 0.3s, color 0.3s;
        opacity: 0;
    }

    .row-container:hover .handle {
        opacity: 1;
    }

    .handle:hover {
        background: rgba(0, 0, 0, 0.9);
        color: var(--primary-blue);
    }

    .left-handle {
        left: 0;
        border-top-right-radius: 4px;
        border-bottom-right-radius: 4px;
    }

    .right-handle {
        right: 0;
        border-top-left-radius: 4px;
        border-bottom-left-radius: 4px;
    }

    /* MODAL */
    #movieModal {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, .9);
        justify-content: center;
        align-items: center;
        z-index: 2000;
        opacity: 0;
        transition: opacity 0.3s ease;
        backdrop-filter: blur(10px);
        overflow-y: auto;
    }

    #movieModal.show {
        opacity: 1;
    }

    .modal-box {
        background: var(--surface-dark);
        width: 90%;
        max-width: 600px;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0, 136, 255, 0.15);
        position: relative;
        margin: 40px auto;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .modal-img-container {
        width: 100%;
        height: 250px;
        position: relative;
    }

    .modal-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        mask-image: linear-gradient(to bottom, rgba(0, 0, 0, 1) 60%, rgba(0, 0, 0, 0));
        -webkit-mask-image: linear-gradient(to bottom, rgba(0, 0, 0, 1) 60%, rgba(0, 0, 0, 0));
    }

    .modal-content {
        padding: 15px 30px 30px;
        margin-top: -40px;
        position: relative;
        z-index: 10;
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
        z-index: 100;
        font-size: 16px;
        transition: 0.3s;
    }

    .close:hover {
        background: var(--primary-blue);
    }

    .reviews-badge {
        background-color: #121a24;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 11px;
        color: #b0c4de;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    /* REVIEW SECTION STYLES */
    .review-section {
        margin-top: 20px;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        padding-top: 15px;
    }

    .review-section h3 {
        margin-bottom: 10px;
        font-size: 1.2rem;
    }

    .star-rating {
        display: flex;
        gap: 5px;
        font-size: 20px;
        cursor: pointer;
        margin-bottom: 10px;
        color: #2c3e50;
    }

    .star-rating span.active {
        color: var(--primary-blue);
    }

    .review-input {
        width: 100%;
        background: #0f141e;
        border: 1px solid #1f2937;
        color: #fff;
        padding: 10px;
        border-radius: 4px;
        resize: vertical;
        min-height: 60px;
        margin-bottom: 10px;
        font-family: inherit;
        font-size: 0.9rem;
    }

    .review-input:focus {
        outline: none;
        border-color: var(--primary-blue);
        box-shadow: 0 0 5px rgba(0, 136, 255, 0.3);
    }

    .user-reviews-list {
        margin-top: 15px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .user-review-card {
        background: #0f141e;
        padding: 12px;
        border-radius: 6px;
        border: 1px solid #1f2937;
    }

    .user-review-card .stars {
        color: var(--primary-blue);
        margin-bottom: 5px;
        font-size: 12px;
    }

    .user-review-card .text {
        font-size: 0.9rem;
        line-height: 1.4;
        color: #b0c4de;
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

        .modal-content {
            padding: 15px 20px 20px;
        }
    }
    </style>
</head>

<body>

    <div class="navbar" id="navbar">
        <div class="nav-left">
            <div class="logo">WatchWise</div>
            <ul class="nav-links">
                <li><a href="#hero">Home</a></li>
                <li><a href="#myListSection">My List</a></li>
                <li><a href="#movies">Movies</a></li>
                <li><a href="#dramas">TV Shows</a></li>
                <li><a href="#cartoons">Kids & Anime</a></li>
            </ul>
        </div>
        <div class="nav-right">
            <div class="search-box">
                <span style="color: white; margin-right: 5px;">🔍</span>
                <input type="text" placeholder="Search title or mood..." onkeyup="searchMovie(this.value)">
            </div>
            <a href="profile.php">
                <img src="<?php
                            echo $profile['image'] ?? 'img/image.png';
                            ?>" class="profile-icon">
            </a>
        </div>
    </div>

    <section class="hero" id="hero">
        <div class="hero-content">
            <h1 class="hero-title" id="heroTitle">Loading...</h1>
            <p class="hero-desc" id="heroDesc">Loading...</p>
            <div class="hero-buttons">
                <button class="btn btn-play">▶ Play</button>
                <button class="btn btn-more">ⓘ More Info</button>
            </div>
        </div>
    </section>

    <div class="row-section" id="myListSection" style="margin-top: -60px; display: none;">
        <h2 class="row-title">My List</h2>
        <div class="row-container">
            <button class="handle left-handle" onclick="scrollRow('myList', -300)">❮</button>
            <div class="movie-row" id="myList"></div>
            <button class="handle right-handle" onclick="scrollRow('myList', 300)">❯</button>
        </div>
    </div>

    <div class="row-section" style="margin-top: -60px;">
        <h2 class="row-title">Trending Movies</h2>
        <div class="row-container">
            <button class="handle left-handle" onclick="scrollRow('movies', -300)">❮</button>
            <div class="movie-row" id="movies"></div>
            <button class="handle right-handle" onclick="scrollRow('movies', 300)">❯</button>
        </div>
    </div>

    <div class="row-section">
        <h2 class="row-title">Popular TV Dramas</h2>
        <div class="row-container">
            <button class="handle left-handle" onclick="scrollRow('dramas', -300)">❮</button>
            <div class="movie-row" id="dramas"></div>
            <button class="handle right-handle" onclick="scrollRow('dramas', 300)">❯</button>
        </div>
    </div>

    <div class="row-section">
        <h2 class="row-title">Kids & Anime</h2>
        <div class="row-container">
            <button class="handle left-handle" onclick="scrollRow('cartoons', -300)">❮</button>
            <div class="movie-row" id="cartoons"></div>
            <button class="handle right-handle" onclick="scrollRow('cartoons', 300)">❯</button>
        </div>
    </div>

    <div id="movieModal">
        <div class="modal-box">
            <span class="close" onclick="closeModal()">✕</span>
            <div class="modal-img-container">
                <img id="modalImg" src="">
            </div>
            <div class="modal-content">
                <h2 id="modalTitle" style="font-size: 1.8rem; margin-bottom: 8px; font-weight: 800;">Title</h2>
                <div style="display: flex; gap: 12px; margin-bottom: 12px; align-items: center; font-size: 12px;">
                    <span id="modalRating" style="color: var(--primary-blue); font-weight: bold;">98% Match</span>
                    <span style="color: #a3a3a3;">2024</span>
                    <span
                        style="border: 1px solid #a3a3a3; padding: 1px 6px; font-size: 11px; border-radius: 2px;">HD</span>
                    <span id="modalReviews" class="reviews-badge">⭐ 8.5 (1.2k Reviews)</span>
                </div>
                <p id="modalDesc" style="line-height: 1.4; font-size: 0.95rem; color: #fff; margin-bottom: 18px;">
                    Watch this amazing show on WatchWise. Experience the drama, action, and suspense.
                </p>
                <div style="display: flex; gap: 10px;">
                    <a id="watchLink" class="btn btn-play" style="text-decoration: none;" target="_blank">▶ Play</a>
                    <button id="addToListBtn" class="btn btn-more" onclick="toggleMyList()">+ My List</button>
                </div>

                <div class="review-section">
                    <h3>Rate & Review</h3>
                    <div class="star-rating" id="starContainer">
                        <span data-val="1">★</span>
                        <span data-val="2">★</span>
                        <span data-val="3">★</span>
                        <span data-val="4">★</span>
                        <span data-val="5">★</span>
                    </div>
                    <textarea id="userReviewText" class="review-input"
                        placeholder="What did you think about this?"></textarea>
                    <button class="btn btn-play" onclick="submitReview()"
                        style="padding: 8px 16px; font-size: 0.9rem; border-radius: 4px;">Post Review</button>

                    <div class="user-reviews-list" id="displayReviews">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    // ================= DATA ARRAYS WITH MOODS, RATINGS & HD IMAGES =================
    const heroImgs = [{
            img: "https://image.tmdb.org/t/p/original/or06FN3Dka5tukK1e9sl16pB3iy.jpg",
            title: "Avengers: Endgame",
            desc: "After the devastating events of Infinity War, the universe is in ruins."
        },
        {
            img: "https://image.tmdb.org/t/p/original/rAiYTfKGqDCRIIqo664sY9XZIvQ.jpg",
            title: "Interstellar",
            desc: "Explorers travel through a wormhole in space."
        },
        {
            img: "https://image.tmdb.org/t/p/original/mDfJG3LC3Dqb67AZ52x3Z0jU0uB.jpg",
            title: "Avatar: The Way of Water",
            desc: "Jake Sully lives with his newfound family formed on the extrasolar moon Pandora."
        }
    ];

    const moviesList = [{
            title: "Inception",
            img: "https://image.tmdb.org/t/p/w500/9gk7adHYeDvHkCSEqAvQNLV5Uge.jpg",
            moods: ["mind-bending", "sci-fi", "intense"]
        },
        {
            title: "Joker",
            img: "https://image.tmdb.org/t/p/w500/udDclJoHjfjb8Ekgsd4FDteOkCU.jpg",
            moods: ["dark", "sad", "psychological"]
        },
        {
            title: "The Dark Knight",
            img: "https://image.tmdb.org/t/p/w500/qJ2tW6WMUDux911r6m7haRef0WH.jpg",
            moods: ["action", "dark", "hero"]
        },
        {
            title: "Avengers: Endgame",
            img: "https://image.tmdb.org/t/p/w500/or06FN3Dka5tukK1e9sl16pB3iy.jpg",
            moods: ["action", "epic", "sad"]
        },
        {
            title: "Spider-Man: No Way Home",
            img: "https://image.tmdb.org/t/p/w500/1g0dhYtq4irTY1GPXvft6k4YLjm.jpg",
            moods: ["happy", "action", "hero"]
        },
        {
            title: "Dune",
            img: "https://image.tmdb.org/t/p/w500/d5NXSklXo0qyIYkgV94XAgMIckC.jpg",
            moods: ["sci-fi", "epic", "intense"]
        },
        {
            title: "John Wick: Chapter 4",
            img: "https://image.tmdb.org/t/p/w500/vZloFAK7NmvMGKE7VkF5UHaz0I.jpg",
            moods: ["action", "intense", "thriller"]
        },
        {
            title: "Top Gun: Maverick",
            img: "https://image.tmdb.org/t/p/w500/62HCnUTziyWcpDaBO2i1DX17ljH.jpg",
            moods: ["action", "epic", "hero"]
        }
    ];
    const dramasList = [{
            title: "Breaking Bad",
            img: "https://image.tmdb.org/t/p/w500/ggFHVNu6YYI5L9pCfOacjizRGt.jpg",
            moods: ["dark", "intense", "crime"]
        },
        {
            title: "Money Heist",
            img: "https://image.tmdb.org/t/p/w500/reEMJA1uzscCbkpeRJeTT2bjqUp.jpg",
            moods: ["action", "suspense", "clever"]
        },
        {
            title: "Stranger Things",
            img: "https://image.tmdb.org/t/p/w500/49WJfeN0moxb9IPfGn8AIqMGskD.jpg",
            moods: ["scary", "sci-fi", "nostalgic"]
        },
        {
            title: "The Last of Us",
            img: "https://image.tmdb.org/t/p/w500/u3bZgnGQ9T01sWNhyveQz0wH0Hl.jpg",
            moods: ["survival", "drama", "intense"]
        },
        {
            title: "Peaky Blinders",
            img: "https://image.tmdb.org/t/p/w500/vUUqzWa2LnHIVqkaKVlVGkVcZIW.jpg",
            moods: ["crime", "dark", "drama"]
        },
        {
            title: "Dark",
            img: "https://image.tmdb.org/t/p/w500/apbrbWs8M9lyOpJYU5WXrpFbk1Z.jpg",
            moods: ["mind-bending", "sci-fi", "mystery"]
        },
        {
            title: "The Witcher",
            img: "https://image.tmdb.org/t/p/w500/zrPpUlehQaBf8YX2NrVrKK8IEpf.jpg",
            moods: ["fantasy", "action", "dark"]
        }
    ];

    const cartoonsList = [{
            title: "Coco",
            img: "https://image.tmdb.org/t/p/w500/eKi8dIrr8voobbaGzDpe8w0PVbC.jpg",
            moods: ["happy", "sad", "music", "family"]
        },
        {
            title: "Toy Story 4",
            img: "https://image.tmdb.org/t/p/w500/w9kR8qbmQ01HwnvK4alvnQ2ca0L.jpg",
            moods: ["happy", "funny", "family"]
        },
        {
            title: "Spider-Man: Into the Spider-Verse",
            img: "https://image.tmdb.org/t/p/w500/iiZZdoQBEYBv6id8su7ImL0oCbD.jpg",
            moods: ["action", "hero", "colorful"]
        },
        {
            title: "Kung Fu Panda 4",
            img: "https://image.tmdb.org/t/p/w500/kDp1vUBnMpe8ak4rjgl3cLELqjU.jpg",
            moods: ["funny", "action", "happy"]
        },
        {
            title: "Frozen II",
            img: "https://image.tmdb.org/t/p/w500/pjeMs3yqRmFL3giJy4PMXWZTTPa.jpg",
            moods: ["music", "adventure", "family"]
        },
        {
            title: "The Incredibles 2",
            img: "https://image.tmdb.org/t/p/w500/x1txcDXkcM65gl7w20PwYSxAYah.jpg",
            moods: ["action", "family", "funny"]
        }
    ];

    // ================= GLOBAL STATE =================
    let currentModalMovie = null;
    let selectedRating = 0;
    let mySavedList = JSON.parse(localStorage.getItem('watchWiseList')) || [];
    let userReviews = JSON.parse(localStorage.getItem('watchWiseReviews')) || {};

    // ================= RENDER FUNCTIONS =================
    function createItems(containerId, list) {
        const container = document.getElementById(containerId);
        container.innerHTML = "";
        list.forEach(item => {
            const div = document.createElement("div");
            div.className = "movie-card";
            div.setAttribute("data-title", item.title.toLowerCase());

            const allMoods = item.moods ? item.moods.join(" ").toLowerCase() : "";
            div.setAttribute("data-tags", allMoods);

            const img = document.createElement("img");
            img.src = item.img;
            img.loading = "lazy";
            img.onerror = function() {
                this.src =
                    `https://via.placeholder.com/180x270/222/fff?text=${encodeURIComponent(item.title)}`;
            };

            div.appendChild(img);
            div.onclick = () => openModal(item);
            container.appendChild(div);
        });
    }

    function renderMyList() {
        const section = document.getElementById('myListSection');
        if (mySavedList.length > 0) {
            section.style.display = 'block';
            createItems('myList', mySavedList);
        } else {
            section.style.display = 'none';
        }
    }

    // Initialize Rows
    createItems("movies", moviesList);
    createItems("dramas", dramasList);
    createItems("cartoons", cartoonsList);
    renderMyList();

    // ================= HERO LOGIC =================
    let heroIndex = 0;

    function updateHero() {
        const hero = document.getElementById("hero");
        const current = heroImgs[heroIndex];
        hero.style.backgroundImage = `url('${current.img}')`;
        document.getElementById("heroTitle").innerText = current.title;
        document.getElementById("heroDesc").innerText = current.desc;
        heroIndex = (heroIndex + 1) % heroImgs.length;
    }
    updateHero();
    setInterval(updateHero, 6000);

    // ================= NAVBAR SCROLL =================
    window.addEventListener("scroll", () => {
        const nav = document.getElementById("navbar");
        if (window.scrollY > 50) nav.classList.add("black-nav");
        else nav.classList.remove("black-nav");
    });

    // ================= ROW SCROLL BUTTONS =================
    function scrollRow(rowId, amount) {
        const row = document.getElementById(rowId);
        row.scrollBy({
            left: amount,
            behavior: 'smooth'
        });
    }

    // ================= MODAL LOGIC =================
    function openModal(movie) {
        currentModalMovie = movie;
        const modal = document.getElementById("movieModal");
        const btn = document.getElementById("addToListBtn");

        const isInList = mySavedList.some(m => m.title === movie.title);
        btn.innerText = isInList ? "✓ Remove from List" : "+ My List";

        modal.style.display = "flex";
        setTimeout(() => modal.classList.add("show"), 10);

        document.getElementById("modalImg").src = movie.img;
        document.getElementById("modalTitle").innerText = movie.title;
        document.getElementById("watchLink").href =
            `https://www.youtube.com/results?search_query=${encodeURIComponent(movie.title)}+trailer`;

        // Reset Rating inputs
        selectedRating = 0;
        updateStarUI();
        document.getElementById("userReviewText").value = "";

        // Load existing reviews
        loadReviewsForMovie(movie.title);
    }

    function closeModal() {
        const modal = document.getElementById("movieModal");
        modal.classList.remove("show");
        setTimeout(() => modal.style.display = "none", 300);
    }

    window.onclick = function(e) {
        if (e.target == document.getElementById("movieModal")) closeModal();
    };

    // ================= MY LIST LOGIC =================
    function toggleMyList() {
        if (!currentModalMovie) return;

        const index = mySavedList.findIndex(m => m.title === currentModalMovie.title);
        const btn = document.getElementById("addToListBtn");

        // UI & LocalStorage Update
        if (index > -1) {
            mySavedList.splice(index, 1);
            btn.innerText = "+ My List";
        } else {
            mySavedList.push(currentModalMovie);
            btn.innerText = "✓ Remove from List";
        }

        localStorage.setItem('watchWiseList', JSON.stringify(mySavedList));
        renderMyList();

        // Database Update (PHP Call)
        fetch("save_mylist.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "title=" + encodeURIComponent(currentModalMovie.title) + "&image=" + encodeURIComponent(
                    currentModalMovie.img)
            })
            .then(res => res.text())
            .then(data => {
                console.log("MyList Response:", data);
            })
            .catch(err => console.error("Error saving to MyList:", err));
    }

    // ================= SMART SEARCH =================
    function searchMovie(val) {
        val = val.toLowerCase();
        document.querySelectorAll(".movie-card").forEach(card => {
            const title = card.getAttribute("data-title");
            const tags = card.getAttribute("data-tags");

            if (title.includes(val) || tags.includes(val)) {
                card.style.display = "block";
            } else {
                card.style.display = "none";
            }
        });

        // Search Keyword Database Call
        if (val.trim() !== "") {
            fetch("save_search.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "search=" + encodeURIComponent(val)
            }).catch(err => console.error("Error saving search:", err));
        }
    }

    // ================= RATING & REVIEW SYSTEM =================
    const stars = document.querySelectorAll("#starContainer span");

    stars.forEach(star => {
        star.addEventListener("click", function() {
            selectedRating = parseInt(this.getAttribute("data-val"));
            updateStarUI();
        });
    });

    function updateStarUI() {
        stars.forEach(s => {
            if (parseInt(s.getAttribute("data-val")) <= selectedRating) {
                s.classList.add("active");
            } else {
                s.classList.remove("active");
            }
        });
    }

    async function saveReviewToDatabase(reviewData) {
        const formData = new URLSearchParams();
        formData.append("title", reviewData.movie);
        formData.append("rating", reviewData.rating);
        formData.append("text", reviewData.text);
        formData.append("date", reviewData.date);

        try {
            const response = await fetch("save_review.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: formData
            });
            const result = await response.text();
            console.log("Review Saved:", result);
        } catch (error) {
            console.error("Error saving review:", error);
        }
    }

    function submitReview() {
        if (selectedRating === 0) {
            alert("Please select a star rating!");
            return;
        }

        const reviewText = document.getElementById("userReviewText").value.trim();
        const movieTitle = currentModalMovie.title;

        const newReview = {
            movie: movieTitle,
            rating: selectedRating,
            text: reviewText || "No written review provided.",
            date: new Date().toLocaleDateString()
        };

        // Save to DB
        saveReviewToDatabase(newReview);

        // Save to LocalStorage
        if (!userReviews[movieTitle]) {
            userReviews[movieTitle] = [];
        }
        userReviews[movieTitle].unshift(newReview);
        localStorage.setItem('watchWiseReviews', JSON.stringify(userReviews));

        // Update UI
        selectedRating = 0;
        updateStarUI();
        document.getElementById("userReviewText").value = "";
        loadReviewsForMovie(movieTitle);
    }

    function loadReviewsForMovie(title) {
        const reviewsContainer = document.getElementById("displayReviews");
        reviewsContainer.innerHTML = "";

        const movieReviews = userReviews[title] || [];

        if (movieReviews.length === 0) {
            reviewsContainer.innerHTML =
                "<p style='color: #888; font-size: 0.9rem;'>No reviews yet. Be the first to review!</p>";
            return;
        }

        movieReviews.forEach(review => {
            const starString = "★".repeat(review.rating) + "☆".repeat(5 - review.rating);
            const reviewEl = document.createElement("div");
            reviewEl.className = "user-review-card";
            reviewEl.innerHTML = `
                <div class="stars">${starString} <span style="color:#888; font-size:12px; margin-left:10px;">${review.date}</span></div>
                <div class="text">${review.text}</div>
            `;
            reviewsContainer.appendChild(reviewEl);
        });
    }
    </script>

</body>

</html>