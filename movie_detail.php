<?php
include "db.php";

$id = intval($_GET['id'] ?? 0);

if ($id <= 0) {
    die("Invalid movie ID.");
}

$result = mysqli_query($conn, "SELECT * FROM movies WHERE id = $id LIMIT 1");
$movie = mysqli_fetch_assoc($result);

if (!$movie) {
    die("Movie not found.");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($movie['title']); ?> - WatchWise</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #031022;
            color: white;
        }

        .container {
            width: 90%;
            max-width: 1100px;
            margin: 40px auto;
        }

        .back-btn {
            display: inline-block;
            margin-bottom: 25px;
            text-decoration: none;
            background: #00d4ff;
            padding: 10px 18px;
            border-radius: 25px;
            color: black;
            font-weight: 600;
        }

        .movie-box {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            overflow: hidden;
            backdrop-filter: blur(10px);
            padding: 25px;
        }

        .movie-poster img {
            width: 300px;
            max-width: 100%;
            border-radius: 16px;
            object-fit: cover;
        }

        .movie-info {
            flex: 1;
            min-width: 280px;
        }

        .movie-info h1 {
            margin-top: 0;
            color: #00d4ff;
            font-size: 36px;
            margin-bottom: 15px;
        }

        .meta {
            color: #cbd5e1;
            margin-bottom: 20px;
            font-size: 16px;
        }

        .desc {
            color: #e2e8f0;
            line-height: 1.7;
            font-size: 16px;
        }

        .trailer-btn {
            display: inline-block;
            margin-top: 25px;
            text-decoration: none;
            background: #00d4ff;
            padding: 12px 22px;
            border-radius: 25px;
            color: black;
            font-weight: 600;
        }
    </style>
</head>

<body>

    <div class="container">
        <a href="javascript:history.back()" class="back-btn">Back</a>
        <div class="movie-box">
            <div class="movie-poster">
                <img src="<?php echo htmlspecialchars($movie['image_url']); ?>" alt="<?php echo htmlspecialchars($movie['title']); ?>">
            </div>

            <div class="movie-info">
                <h1><?php echo htmlspecialchars($movie['title']); ?></h1>

                <div class="meta">
                    Year: <?php echo htmlspecialchars($movie['release_year'] ?? 'N/A'); ?> |
                    Rating: <?php echo htmlspecialchars($movie['rating'] ?? 'N/A'); ?> |
                    Category: <?php echo htmlspecialchars($movie['category'] ?? 'N/A'); ?>
                </div>

                <div class="desc">
                    <?php echo htmlspecialchars($movie['description']); ?>
                </div>

                <?php if (!empty($movie['trailer_url'])) { ?>
                    <a href="<?php echo htmlspecialchars($movie['trailer_url']); ?>" target="_blank" class="trailer-btn">Watch Trailer</a>
                <?php } ?>
            </div>
        </div>
    </div>

</body>

</html>