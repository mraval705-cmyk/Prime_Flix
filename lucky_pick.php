<?php
include "db.php";

$sql = "SELECT id, title, image_url, moods 
        FROM movies 
        WHERE is_active = 1
        ORDER BY RAND() 
        LIMIT 1";
$result = mysqli_query($conn, $sql);
$movie = mysqli_fetch_assoc($result);

if (!$movie) {
    $movie = [
        "title" => "No Movie Found",
        "image_url" => "https://via.placeholder.com/300x450?text=No+Movie",
        "moods" => "not available"
    ];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Watchwise Lucky Pick</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            min-height: 100vh;
            background: radial-gradient(circle at top, #0f172a, #020617 70%);
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px 20px;
        }

        .container {
            width: 100%;
            max-width: 900px;
            text-align: center;
        }

        .back {
            display: inline-block;
            margin-bottom: 18px;
            color: #38bdf8;
            text-decoration: none;
            font-weight: bold;
        }

        h1 {
            margin-bottom: 10px;
            font-size: 42px;
            color: #38bdf8;
        }

        .subtitle {
            color: #cbd5e1;
            margin-bottom: 30px;
            font-size: 17px;
        }

        .pick-card {
            margin: 0 auto 24px;
            max-width: 300px;
            background: linear-gradient(180deg, #1e293b, #0f172a);
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid rgba(56, 189, 248, 0.25);
            box-shadow: 0 18px 35px rgba(0, 0, 0, 0.4);
        }

        .pick-card img {
            width: 100%;
            height: 430px;
            object-fit: cover;
            display: block;
        }

        .pick-body {
            padding: 18px;
        }

        .pick-body h2 {
            margin: 0 0 12px;
            font-size: 26px;
        }

        .mood {
            color: #7dd3fc;
            font-size: 15px;
            margin-bottom: 16px;
        }

        .btn-row {
            display: flex;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        button,
        .go-back {
            border: none;
            border-radius: 12px;
            padding: 12px 18px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
        }

        button {
            background: linear-gradient(90deg, #06b6d4, #2563eb);
            color: white;
        }

        .go-back {
            background: rgba(255, 255, 255, 0.08);
            color: white;
            border: 1px solid rgba(148, 163, 184, 0.2);
        }
    </style>
</head>

<body>

    <div class="container">
        <a href="guest_movies.php" class="back">← Back to Guest Explore</a>
        <h1>Lucky Pick</h1>
        <div class="subtitle">Not sure what to watch? Let Watchwise choose a movie for you.</div>

        <div class="pick-card">
            <img src="<?php echo htmlspecialchars($movie['image_url']); ?>" alt="Movie Poster">
            <div class="pick-body">
                <h2><?php echo htmlspecialchars($movie['title']); ?></h2>
                <div class="mood">Mood: <?php echo htmlspecialchars($movie['moods']); ?></div>
                <div class="btn-row">
                    <a href="lucky_pick.php" style="background: linear-gradient(90deg, #06b6d4, #2563eb); color:white; border:none; border-radius:12px; padding:12px 18px; text-decoration:none; font-weight:bold;">Pick Another</a>
                    <a href="guest_movies.php" class="go-back">Explore More</a>
                </div>
            </div>
        </div>
    </div>


</body>

</html>