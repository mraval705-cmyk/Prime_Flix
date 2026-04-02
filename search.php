<?php
session_start();
include "db.php";

$query = trim($_GET['query'] ?? '');
$safe_query = mysqli_real_escape_string($conn, $query);

$results = [];

// user email optional
$user_email = $_SESSION['user_email'] ?? null;
$safe_user_email = mysqli_real_escape_string($conn, $user_email ?? '');

if ($query != "") {

    // search history save karo
    if ($user_email) {
        mysqli_query($conn, "
            INSERT INTO search_history (user_email, search_term, searched_at)
            VALUES ('$safe_user_email', '$safe_query', NOW())
        ");
    } else {
        mysqli_query($conn, "
            INSERT INTO search_history (search_term, searched_at)
            VALUES ('$safe_query', NOW())
        ");
    }

    // movies search
    $sql = "SELECT * FROM movies 
            WHERE title LIKE '%$safe_query%' 
            OR description LIKE '%$safe_query%'";

    $run = mysqli_query($conn, $sql);

    if ($run) {
        while ($row = mysqli_fetch_assoc($run)) {
            $results[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - WatchWise</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #031022;
            color: white;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 40px auto;
        }

        h1 {
            margin-bottom: 25px;
            color: #00d4ff;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .back-btn {
            text-decoration: none;
            background: #00d4ff;
            padding: 10px 18px;
            border-radius: 25px;
            color: black;
            font-weight: 600;
        }

        .search-info {
            color: #cbd5e1;
            margin-bottom: 20px;
            font-size: 18px;
        }

        .grid {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            align-items: flex-start;
        }

        .movie-card {
            background: rgba(255, 255, 255, 0.06);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.25);
            width: 260px;
            backdrop-filter: blur(10px);
        }

        .movie-card img {
            width: 100%;
            height: 340px;
            object-fit: cover;
            display: block;
        }

        .movie-content {
            padding: 15px;
        }

        .movie-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .movie-desc {
            font-size: 14px;
            color: #d1d5db;
            line-height: 1.5;
        }

        .movie-content {
            padding: 15px;
        }

        .movie-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .movie-desc {
            font-size: 14px;
            color: #d1d5db;
            line-height: 1.5;
        }

        .no-result {
            margin-top: 30px;
            font-size: 18px;
            color: #fda4af;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="top-bar">
            <h1>Search Results</h1>
            <a href="index.php" class="back-btn">Back</a>
        </div>

        <div class="search-info">
            You searched for: <strong><?php echo htmlspecialchars($query); ?></strong>
        </div>

        <?php if (!empty($results)) { ?>
            <div class="grid">
                <?php foreach ($results as $movie) { ?>
                    <div class="movie-card">
                        <img src="<?php echo htmlspecialchars($movie['image_url']); ?>" alt="Movie Image">
                        <div class="movie-content">
                            <div class="movie-title"><?php echo htmlspecialchars($movie['title']); ?></div>
                            <div class="movie-desc"><?php echo htmlspecialchars($movie['description']); ?></div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } else { ?>
            <div class="no-result">No movies found.</div>
        <?php } ?>
    </div>

</body>

</html>
