<?php
session_start();
include "db.php";

$message = "";

/** ---------------- SAFE DELETE LOGIC ---------------- **/
$allowed_tables = ['movies', 'footer_links', 'features', 'faqs'];

if (isset($_GET['delete_table']) && isset($_GET['id'])) {
    $table = $_GET['delete_table'];
    $id = (int) $_GET['id'];

    if (in_array($table, $allowed_tables)) {
        mysqli_query($conn, "DELETE FROM $table WHERE id = $id");
        header("Location: admin.php?msg=Deleted successfully");
        exit();
    }
}

/** ---------------- HERO UPDATE ---------------- **/
if (isset($_POST['update_hero'])) {
    $title = mysqli_real_escape_string($conn, $_POST['hero_title'] ?? '');
    $subtitle = mysqli_real_escape_string($conn, $_POST['hero_subtitle'] ?? '');
    $img = mysqli_real_escape_string($conn, $_POST['hero_image_url'] ?? '');

    $checkHero = mysqli_query($conn, "SELECT id FROM hero_slides LIMIT 1");

    if (mysqli_num_rows($checkHero) > 0) {
        $heroRow = mysqli_fetch_assoc($checkHero);
        $heroId = (int)$heroRow['id'];

        $sql = "UPDATE hero_slides 
                SET title='$title', subtitle='$subtitle', image_url='$img' 
                WHERE id=$heroId";
    } else {
        $sql = "INSERT INTO hero_slides (title, subtitle, image_url) 
                VALUES ('$title', '$subtitle', '$img')";
    }

    if (mysqli_query($conn, $sql)) {
        $message = "Hero section updated successfully!";
    } else {
        $message = "Database Error: " . mysqli_error($conn);
    }
}

/** ---------------- ADD MOVIE ---------------- **/
if (isset($_POST['add_movie'])) {
    $title = mysqli_real_escape_string($conn, $_POST['movie_title'] ?? '');
    $img = mysqli_real_escape_string($conn, $_POST['movie_image_url'] ?? '');
    $desc = mysqli_real_escape_string($conn, $_POST['movie_description'] ?? '');
    $cat = mysqli_real_escape_string($conn, $_POST['movie_category'] ?? 'trending');

    if ($title !== "" && $img !== "") {
        $sql = "INSERT INTO movies (title, image_url, description, category, is_active) 
                VALUES ('$title', '$img', '$desc', '$cat', 1)";

        if (mysqli_query($conn, $sql)) {
            $message = "Movie added successfully!";
        } else {
            $message = "Movie insert error: " . mysqli_error($conn);
        }
    } else {
        $message = "Movie title and image URL are required.";
    }
}

/** ---------------- ADD FEATURE ---------------- **/
if (isset($_POST['add_feature'])) {
    $title = mysqli_real_escape_string($conn, $_POST['feature_title'] ?? '');
    $desc = mysqli_real_escape_string($conn, $_POST['feature_description'] ?? '');

    if ($title !== "" && $desc !== "") {
        $sql = "INSERT INTO features (title, description) VALUES ('$title', '$desc')";

        if (mysqli_query($conn, $sql)) {
            $message = "Feature added successfully!";
        } else {
            $message = "Feature insert error: " . mysqli_error($conn);
        }
    } else {
        $message = "Feature title and description are required.";
    }
}

/** ---------------- ADD FAQ ---------------- **/
if (isset($_POST['add_faq'])) {
    $question = mysqli_real_escape_string($conn, $_POST['faq_question'] ?? '');
    $answer = mysqli_real_escape_string($conn, $_POST['faq_answer'] ?? '');

    if ($question !== "" && $answer !== "") {
        $sql = "INSERT INTO faqs (question, answer) VALUES ('$question', '$answer')";

        if (mysqli_query($conn, $sql)) {
            $message = "FAQ added successfully!";
        } else {
            $message = "FAQ insert error: " . mysqli_error($conn);
        }
    } else {
        $message = "FAQ question and answer are required.";
    }
}

/** ---------------- ADD FOOTER LINK ---------------- **/
if (isset($_POST['add_footer'])) {
    $section_name = mysqli_real_escape_string($conn, $_POST['section_name'] ?? '');
    $label = mysqli_real_escape_string($conn, $_POST['label'] ?? '');
    $url = mysqli_real_escape_string($conn, $_POST['url'] ?? '');
    $sort_order = (int)($_POST['sort_order'] ?? 0);

    if ($section_name !== "" && $label !== "" && $url !== "") {
        $sql = "INSERT INTO footer_links (section_name, label, url, sort_order, is_active) 
                VALUES ('$section_name', '$label', '$url', $sort_order, 1)";

        if (mysqli_query($conn, $sql)) {
            $message = "Footer link added successfully!";
        } else {
            $message = "Footer insert error: " . mysqli_error($conn);
        }
    } else {
        $message = "Section, label and URL are required.";
    }
}

/** ---------------- DATA FETCHING ---------------- **/
$hero = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM hero_slides LIMIT 1"));
$movies = mysqli_query($conn, "SELECT * FROM movies ORDER BY id DESC");
$features = mysqli_query($conn, "SELECT * FROM features ORDER BY id DESC");
$faqs = mysqli_query($conn, "SELECT * FROM faqs ORDER BY id DESC");
$footer_links = mysqli_query($conn, "SELECT * FROM footer_links ORDER BY section_name, sort_order ASC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Watchwise | Admin Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0b0f19;
            --card: #1e293b;
            --accent: #00e5ff;
            --text: #f1f5f9;
            --muted: #94a3b8;
            --border: #334155;
            --danger: #ff4d4d;
            --success-bg: #064e3b;
            --success-text: #34d399;
        }

        * {
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            margin: 0;
            background: var(--bg);
            color: var(--text);
            display: flex;
        }

        .sidebar {
            width: 260px;
            background: #020617;
            min-height: 100vh;
            padding: 30px 20px;
            position: fixed;
            left: 0;
            top: 0;
            border-right: 1px solid #1e293b;
        }

        .sidebar h2 {
            color: var(--accent);
            letter-spacing: 2px;
            margin-bottom: 35px;
        }

        .sidebar a {
            display: block;
            color: var(--muted);
            text-decoration: none;
            padding: 12px 14px;
            border-radius: 8px;
            transition: 0.3s;
            margin-bottom: 10px;
        }

        .sidebar a:hover {
            background: var(--card);
            color: #fff;
        }

        .main-content {
            margin-left: 260px;
            width: calc(100% - 260px);
            padding: 35px;
        }

        .card {
            background: var(--card);
            padding: 28px;
            border-radius: 16px;
            margin-bottom: 28px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        h3 {
            margin-top: 0;
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-top: 12px;
            margin-bottom: 6px;
            color: var(--muted);
            font-size: 14px;
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 12px;
            margin-bottom: 12px;
            background: #0b0f19;
            border: 1px solid var(--border);
            color: #fff;
            border-radius: 8px;
            outline: none;
        }

        textarea {
            min-height: 100px;
            resize: vertical;
        }

        button {
            background: var(--accent);
            color: #000;
            border: none;
            padding: 12px 24px;
            border-radius: 30px;
            font-weight: 700;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            opacity: 0.85;
        }

        .row {
            display: flex;
            gap: 15px;
        }

        .row .col {
            flex: 1;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 18px;
        }

        th,
        td {
            padding: 14px;
            text-align: left;
            border-bottom: 1px solid var(--border);
            vertical-align: top;
        }

        th {
            color: var(--accent);
        }

        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            background: #064e3b;
            color: #34d399;
        }

        .btn-delete {
            color: var(--danger);
            text-decoration: none;
            font-weight: 600;
        }

        .alert {
            background: var(--success-bg);
            color: #fff;
            padding: 14px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        code {
            color: #cbd5e1;
            word-break: break-all;
        }

        img.preview {
            width: 45px;
            height: 60px;
            object-fit: cover;
            border-radius: 6px;
        }

        @media (max-width: 900px) {
            body {
                display: block;
            }

            .sidebar {
                position: relative;
                width: 100%;
                min-height: auto;
            }

            .main-content {
                margin-left: 0;
                width: 100%;
                padding: 20px;
            }

            .row {
                flex-direction: column;
            }

            table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
</head>

<body>

    <div class="sidebar">
        <h2>WATCHWISE</h2>
        <a href="#hero">Hero Section</a>
        <a href="#movies">Movies</a>
        <a href="#features">Features</a>
        <a href="#faqs">FAQs</a>
        <a href="#footer">Footer Links</a>
        <hr style="border: 0; border-top: 1px solid #1e293b; margin: 20px 0;">
        <a href="index.php" target="_blank">View Site ↗</a>
    </div>

    <div class="main-content">
        <?php if ($message || isset($_GET['msg'])): ?>
            <div class="alert"><?php echo htmlspecialchars($message ?: $_GET['msg']); ?></div>
        <?php endif; ?>

        <!-- HERO -->
        <div class="card" id="hero">
            <h3>Homepage Hero Content</h3>
            <form method="POST">
                <label>Title</label>
                <input type="text" name="hero_title" value="<?php echo htmlspecialchars($hero['title'] ?? ''); ?>">

                <label>Subtitle</label>
                <input type="text" name="hero_subtitle" value="<?php echo htmlspecialchars($hero['subtitle'] ?? ''); ?>">

                <label>Background Image URL</label>
                <input type="text" name="hero_image_url" value="<?php echo htmlspecialchars($hero['image_url'] ?? ($hero['image_path'] ?? '')); ?>">

                <button type="submit" name="update_hero">Update Hero Section</button>
            </form>
        </div>

        <!-- MOVIES -->
        <div class="card" id="movies">
            <h3>Add Movie</h3>
            <form method="POST">
                <div class="row">
                    <div class="col">
                        <label>Movie Title</label>
                        <input type="text" name="movie_title" placeholder="Movie name">
                    </div>
                    <div class="col">
                        <label>Poster URL</label>
                        <input type="text" name="movie_image_url" placeholder="Poster image URL">
                    </div>
                </div>

                <label>Description</label>
                <textarea name="movie_description" placeholder="Short description"></textarea>

                <label>Category</label>
                <select name="movie_category">
                    <option value="trending">Trending</option>
                    <option value="latest">Latest Release</option>
                </select>

                <button type="submit" name="add_movie">Add Movie</button>
            </form>

            <h3 style="margin-top: 35px;">Current Movie Catalog</h3>
            <table>
                <thead>
                    <tr>
                        <th>Poster</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($movies && mysqli_num_rows($movies) > 0): ?>
                        <?php while ($m = mysqli_fetch_assoc($movies)): ?>
                            <tr>
                                <td>
                                    <img src="<?php echo htmlspecialchars($m['image_url']); ?>" class="preview" alt="movie">
                                </td>
                                <td><?php echo htmlspecialchars($m['title']); ?></td>
                                <td><span class="status-badge"><?php echo htmlspecialchars($m['category']); ?></span></td>
                                <td>
                                    <a href="admin.php?delete_table=movies&id=<?php echo $m['id']; ?>"
                                        class="btn-delete"
                                        onclick="return confirm('Remove this movie?')">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">No movies found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- FEATURES -->
        <div class="card" id="features">
            <h3>Manage Features</h3>
            <form method="POST">
                <label>Feature Title</label>
                <input type="text" name="feature_title" placeholder="Example: Unlimited Streaming">

                <label>Feature Description</label>
                <textarea name="feature_description" placeholder="Write feature description"></textarea>

                <button type="submit" name="add_feature">Add Feature</button>
            </form>

            <h3 style="margin-top: 35px;">Current Features</h3>
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($features && mysqli_num_rows($features) > 0): ?>
                        <?php while ($feature = mysqli_fetch_assoc($features)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($feature['title']); ?></td>
                                <td><?php echo htmlspecialchars($feature['description']); ?></td>
                                <td>
                                    <a href="admin.php?delete_table=features&id=<?php echo $feature['id']; ?>"
                                        class="btn-delete"
                                        onclick="return confirm('Delete this feature?')">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3">No features found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- FAQS -->
        <div class="card" id="faqs">
            <h3>Manage FAQs</h3>
            <form method="POST">
                <label>Question</label>
                <input type="text" name="faq_question" placeholder="Enter question">

                <label>Answer</label>
                <textarea name="faq_answer" placeholder="Enter answer"></textarea>

                <button type="submit" name="add_faq">Add FAQ</button>
            </form>

            <h3 style="margin-top: 35px;">Current FAQs</h3>
            <table>
                <thead>
                    <tr>
                        <th>Question</th>
                        <th>Answer</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($faqs && mysqli_num_rows($faqs) > 0): ?>
                        <?php while ($faq = mysqli_fetch_assoc($faqs)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($faq['question']); ?></td>
                                <td><?php echo htmlspecialchars($faq['answer']); ?></td>
                                <td>
                                    <a href="admin.php?delete_table=faqs&id=<?php echo $faq['id']; ?>"
                                        class="btn-delete"
                                        onclick="return confirm('Delete this FAQ?')">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3">No FAQs found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- FOOTER -->
        <div class="card" id="footer">
            <h3>Manage Footer Links</h3>

            <form method="POST">
                <div class="row">
                    <div class="col">
                        <label>Section</label>
                        <select name="section_name">
                            <option value="Company">Company</option>
                            <option value="Support">Support</option>
                            <option value="Legal">Legal</option>
                        </select>
                    </div>
                    <div class="col">
                        <label>Label</label>
                        <input type="text" name="label" placeholder="Example: About Us">
                    </div>
                </div>

                <label>URL</label>
                <input type="text" name="url" placeholder="Example: about.php or https://example.com">

                <label>Sort Order</label>
                <input type="number" name="sort_order" value="1">

                <button type="submit" name="add_footer">Add Footer Link</button>
            </form>

            <h3 style="margin-top: 35px;">Current Footer Links</h3>
            <table>
                <thead>
                    <tr>
                        <th>Section</th>
                        <th>Label</th>
                        <th>URL</th>
                        <th>Sort Order</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($footer_links && mysqli_num_rows($footer_links) > 0): ?>
                        <?php while ($f = mysqli_fetch_assoc($footer_links)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($f['section_name']); ?></td>
                                <td><?php echo htmlspecialchars($f['label']); ?></td>
                                <td><code><?php echo htmlspecialchars($f['url']); ?></code></td>
                                <td><?php echo (int)($f['sort_order'] ?? 0); ?></td>
                                <td>
                                    <a href="admin.php?delete_table=footer_links&id=<?php echo $f['id']; ?>"
                                        class="btn-delete"
                                        onclick="return confirm('Delete this footer link?')">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">No footer links found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>