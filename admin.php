<?php
session_start();
include "db.php";

$message = "";

/* ---------------- HELPER FUNCTIONS ---------------- */
function esc($conn, $value)
{
    return mysqli_real_escape_string($conn, trim($value ?? ''));
}

function upsertSetting($conn, $key, $value)
{
    $key = mysqli_real_escape_string($conn, $key);
    $value = mysqli_real_escape_string($conn, $value);

    $check = mysqli_query($conn, "SELECT id FROM site_settings WHERE setting_key='$key' LIMIT 1");
    if ($check && mysqli_num_rows($check) > 0) {
        mysqli_query($conn, "UPDATE site_settings SET setting_value='$value' WHERE setting_key='$key'");
    } else {
        mysqli_query($conn, "INSERT INTO site_settings (setting_key, setting_value) VALUES ('$key', '$value')");
    }
}

/* ---------------- SAFE DELETE LOGIC ---------------- */
$allowed_tables = ['movies', 'features', 'faqs', 'footer_links', 'plans', 'offers'];

if (isset($_GET['delete_table']) && isset($_GET['id'])) {
    $table = $_GET['delete_table'];
    $id = (int) $_GET['id'];

    if (in_array($table, $allowed_tables)) {
        mysqli_query($conn, "DELETE FROM $table WHERE id = $id");
        header("Location: admin.php?msg=Deleted successfully");
        exit();
    }
}

/* ---------------- HERO UPDATE ---------------- */
if (isset($_POST['update_hero'])) {
    $title = esc($conn, $_POST['hero_title']);
    $subtitle = esc($conn, $_POST['hero_subtitle']);
    $img = esc($conn, $_POST['hero_image_url']);

    $checkHero = mysqli_query($conn, "SELECT id FROM hero_slides LIMIT 1");

    if ($checkHero && mysqli_num_rows($checkHero) > 0) {
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
        $message = "Hero update error: " . mysqli_error($conn);
    }
}

/* ---------------- SITE SETTINGS UPDATE ---------------- */
if (isset($_POST['update_site_settings'])) {
    upsertSetting($conn, 'why_choose_title', $_POST['why_choose_title'] ?? '');
    upsertSetting($conn, 'faq_title', $_POST['faq_title'] ?? '');
    upsertSetting($conn, 'cta_text', $_POST['cta_text'] ?? '');
    upsertSetting($conn, 'footer_about', $_POST['footer_about'] ?? '');

    $message = "Homepage text settings updated successfully!";
}

/* ---------------- ADD / UPDATE PLAN ---------------- */
if (isset($_POST['save_plan'])) {
    $plan_id = (int)($_POST['plan_id'] ?? 0);
    $plan_name = esc($conn, $_POST['plan_name']);
    $price = (float)($_POST['price'] ?? 0);
    $resolution = esc($conn, $_POST['resolution']);
    $is_active = isset($_POST['plan_is_active']) ? 1 : 0;

    if ($plan_name !== "" && $resolution !== "" && $price > 0) {
        if ($plan_id > 0) {
            $sql = "UPDATE plans SET 
                        plan_name='$plan_name',
                        price='$price',
                        resolution='$resolution',
                        is_active=$is_active
                    WHERE id=$plan_id";
            $message = mysqli_query($conn, $sql) ? "Plan updated successfully!" : "Plan update error: " . mysqli_error($conn);
        } else {
            $sql = "INSERT INTO plans (plan_name, price, resolution, is_active)
                    VALUES ('$plan_name', '$price', '$resolution', $is_active)";
            $message = mysqli_query($conn, $sql) ? "Plan added successfully!" : "Plan insert error: " . mysqli_error($conn);
        }
    } else {
        $message = "Plan name, price and resolution are required.";
    }
}

/* ---------------- ADD / UPDATE OFFER ---------------- */
if (isset($_POST['save_offer'])) {
    $offer_id = (int)($_POST['offer_id'] ?? 0);
    $title = esc($conn, $_POST['offer_title']);
    $coupon_code = strtoupper(esc($conn, $_POST['coupon_code']));
    $discount_type = esc($conn, $_POST['discount_type']);
    $discount_value = (float)($_POST['discount_value'] ?? 0);
    $min_amount = (float)($_POST['min_amount'] ?? 0);
    $valid_until = esc($conn, $_POST['valid_until']);
    $is_active = isset($_POST['offer_is_active']) ? 1 : 0;

    if ($coupon_code !== "" && $discount_type !== "" && $discount_value > 0 && $valid_until !== "") {
        if ($offer_id > 0) {
            $sql = "UPDATE offers SET 
                        title='$title',
                        coupon_code='$coupon_code',
                        discount_type='$discount_type',
                        discount_value='$discount_value',
                        min_amount='$min_amount',
                        valid_until='$valid_until',
                        is_active=$is_active
                    WHERE id=$offer_id";
            $message = mysqli_query($conn, $sql) ? "Offer updated successfully!" : "Offer update error: " . mysqli_error($conn);
        } else {
            $sql = "INSERT INTO offers (title, coupon_code, discount_type, discount_value, min_amount, valid_until, is_active)
                    VALUES ('$title', '$coupon_code', '$discount_type', '$discount_value', '$min_amount', '$valid_until', $is_active)";
            $message = mysqli_query($conn, $sql) ? "Offer added successfully!" : "Offer insert error: " . mysqli_error($conn);
        }
    } else {
        $message = "Coupon code, discount type, discount value and valid until date are required.";
    }
}

/* ---------------- ADD / UPDATE MOVIE ---------------- */
if (isset($_POST['save_movie'])) {
    $movie_id = (int)($_POST['movie_id'] ?? 0);
    $title = esc($conn, $_POST['movie_title']);
    $img = esc($conn, $_POST['movie_image_url']);
    $desc = esc($conn, $_POST['movie_description']);
    $cat = esc($conn, $_POST['movie_category']);
    $release_year = esc($conn, $_POST['release_year']);
    $rating = esc($conn, $_POST['rating']);
    $trailer_url = esc($conn, $_POST['trailer_url']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    if ($title !== "" && $img !== "") {
        if ($movie_id > 0) {
            $sql = "UPDATE movies SET 
                        title='$title',
                        image_url='$img',
                        description='$desc',
                        category='$cat',
                        release_year='$release_year',
                        rating='$rating',
                        trailer_url='$trailer_url',
                        is_active=$is_active
                    WHERE id=$movie_id";
            $message = mysqli_query($conn, $sql) ? "Movie updated successfully!" : "Movie update error: " . mysqli_error($conn);
        } else {
            $sql = "INSERT INTO movies (title, image_url, description, category, release_year, rating, trailer_url, is_active)
                    VALUES ('$title', '$img', '$desc', '$cat', '$release_year', '$rating', '$trailer_url', $is_active)";
            $message = mysqli_query($conn, $sql) ? "Movie added successfully!" : "Movie insert error: " . mysqli_error($conn);
        }
    } else {
        $message = "Movie title and poster URL are required.";
    }
}

/* ---------------- ADD / UPDATE FEATURE ---------------- */
if (isset($_POST['save_feature'])) {
    $feature_id = (int)($_POST['feature_id'] ?? 0);
    $title = esc($conn, $_POST['feature_title']);
    $desc = esc($conn, $_POST['feature_description']);

    if ($title !== "" && $desc !== "") {
        if ($feature_id > 0) {
            $sql = "UPDATE features SET title='$title', description='$desc' WHERE id=$feature_id";
            $message = mysqli_query($conn, $sql) ? "Feature updated successfully!" : "Feature update error: " . mysqli_error($conn);
        } else {
            $sql = "INSERT INTO features (title, description) VALUES ('$title', '$desc')";
            $message = mysqli_query($conn, $sql) ? "Feature added successfully!" : "Feature insert error: " . mysqli_error($conn);
        }
    } else {
        $message = "Feature title and description are required.";
    }
}

/* ---------------- ADD / UPDATE FAQ ---------------- */
if (isset($_POST['save_faq'])) {
    $faq_id = (int)($_POST['faq_id'] ?? 0);
    $question = esc($conn, $_POST['faq_question']);
    $answer = esc($conn, $_POST['faq_answer']);

    if ($question !== "" && $answer !== "") {
        if ($faq_id > 0) {
            $sql = "UPDATE faqs SET question='$question', answer='$answer' WHERE id=$faq_id";
            $message = mysqli_query($conn, $sql) ? "FAQ updated successfully!" : "FAQ update error: " . mysqli_error($conn);
        } else {
            $sql = "INSERT INTO faqs (question, answer) VALUES ('$question', '$answer')";
            $message = mysqli_query($conn, $sql) ? "FAQ added successfully!" : "FAQ insert error: " . mysqli_error($conn);
        }
    } else {
        $message = "FAQ question and answer are required.";
    }
}

/* ---------------- ADD / UPDATE FOOTER LINK ---------------- */
if (isset($_POST['save_footer'])) {
    $footer_id = (int)($_POST['footer_id'] ?? 0);
    $section_name = esc($conn, $_POST['section_name']);
    $label = esc($conn, $_POST['label']);
    $url = esc($conn, $_POST['url']);
    $sort_order = (int)($_POST['sort_order'] ?? 0);
    $is_active = isset($_POST['footer_is_active']) ? 1 : 0;

    if ($section_name !== "" && $label !== "" && $url !== "") {
        if ($footer_id > 0) {
            $sql = "UPDATE footer_links SET 
                        section_name='$section_name',
                        label='$label',
                        url='$url',
                        sort_order=$sort_order,
                        is_active=$is_active
                    WHERE id=$footer_id";
            $message = mysqli_query($conn, $sql) ? "Footer link updated successfully!" : "Footer update error: " . mysqli_error($conn);
        } else {
            $sql = "INSERT INTO footer_links (section_name, label, url, sort_order, is_active)
                    VALUES ('$section_name', '$label', '$url', $sort_order, $is_active)";
            $message = mysqli_query($conn, $sql) ? "Footer link added successfully!" : "Footer insert error: " . mysqli_error($conn);
        }
    } else {
        $message = "Section, label and URL are required.";
    }
}

/* ---------------- EDIT FETCH ---------------- */
$edit_movie = null;
$edit_feature = null;
$edit_faq = null;
$edit_footer = null;
$edit_plan = null;
$edit_offer = null;

if (isset($_GET['edit_type']) && isset($_GET['id'])) {
    $edit_type = $_GET['edit_type'];
    $id = (int)$_GET['id'];

    if ($edit_type === 'movie') {
        $res = mysqli_query($conn, "SELECT * FROM movies WHERE id=$id LIMIT 1");
        $edit_movie = $res ? mysqli_fetch_assoc($res) : null;
    } elseif ($edit_type === 'feature') {
        $res = mysqli_query($conn, "SELECT * FROM features WHERE id=$id LIMIT 1");
        $edit_feature = $res ? mysqli_fetch_assoc($res) : null;
    } elseif ($edit_type === 'faq') {
        $res = mysqli_query($conn, "SELECT * FROM faqs WHERE id=$id LIMIT 1");
        $edit_faq = $res ? mysqli_fetch_assoc($res) : null;
    } elseif ($edit_type === 'footer') {
        $res = mysqli_query($conn, "SELECT * FROM footer_links WHERE id=$id LIMIT 1");
        $edit_footer = $res ? mysqli_fetch_assoc($res) : null;
    } elseif ($edit_type === 'plan') {
        $res = mysqli_query($conn, "SELECT * FROM plans WHERE id=$id LIMIT 1");
        $edit_plan = $res ? mysqli_fetch_assoc($res) : null;
    } elseif ($edit_type === 'offer') {
        $res = mysqli_query($conn, "SELECT * FROM offers WHERE id=$id LIMIT 1");
        $edit_offer = $res ? mysqli_fetch_assoc($res) : null;
    }
}

/* ---------------- DATA FETCHING ---------------- */
$hero = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM hero_slides LIMIT 1"));

$settings = [];
$settings_query = mysqli_query($conn, "SELECT setting_key, setting_value FROM site_settings");
if ($settings_query) {
    while ($row = mysqli_fetch_assoc($settings_query)) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
}

$plans = mysqli_query($conn, "SELECT * FROM plans ORDER BY id ASC");
$offers = mysqli_query($conn, "SELECT * FROM offers ORDER BY id DESC");
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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap">

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

        .topbar {
            background: linear-gradient(135deg, #0f172a, #111827);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 18px;
            padding: 22px 24px;
            margin-bottom: 28px;
        }

        .topbar h1 {
            margin: 0;
            font-size: 28px;
            color: var(--accent);
        }

        .topbar p {
            margin: 6px 0 0;
            color: var(--muted);
            font-size: 14px;
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
        }

        .row {
            display: flex;
            gap: 15px;
        }

        .row .col {
            flex: 1;
        }

        .alert {
            background: var(--success-bg);
            color: #fff;
            padding: 14px;
            border-radius: 8px;
            margin-bottom: 20px;
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

        .btn-link {
            color: #7dd3fc;
            text-decoration: none;
            font-weight: 600;
            margin-right: 12px;
        }

        .btn-delete {
            color: var(--danger);
            text-decoration: none;
            font-weight: 600;
        }

        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            background: #064e3b;
            color: #34d399;
        }

        .muted {
            color: var(--muted);
            font-size: 13px;
        }

        .check-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 8px 0 14px;
        }

        .check-row input {
            width: auto;
            margin: 0;
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
        <a href="#settings">Site Settings</a>
        <a href="#plans">Plans</a>
        <a href="#offers">Offers</a>
        <a href="#movies">Movies</a>

        <!-- 👇 YE ADD KIYA -->
        <a href="admin_subscriptions.php">Subscriptions</a>

        <a href="#features">Features</a>
        <a href="#faqs">FAQs</a>
        <a href="#footer">Footer Links</a>
        <hr style="border: 0; border-top: 1px solid #1e293b; margin: 20px 0;">
        <a href="index.php" target="_blank">View Site ↗</a>
    </div>

    <div class="main-content">

        <div class="topbar">
            <h1>Admin Panel</h1>
            <p>Control homepage content directly from here.</p>
        </div>

        <?php if ($message || isset($_GET['msg'])): ?>
            <div class="alert"><?php echo htmlspecialchars($message ?: $_GET['msg']); ?></div>
        <?php endif; ?>

        <!-- HERO -->
        <div class="card" id="hero">
            <h3>Homepage Hero Content</h3>
            <form method="POST">
                <label>Hero Title</label>
                <input type="text" name="hero_title" value="<?php echo htmlspecialchars($hero['title'] ?? ''); ?>">

                <label>Hero Subtitle</label>
                <input type="text" name="hero_subtitle" value="<?php echo htmlspecialchars($hero['subtitle'] ?? ''); ?>">

                <label>Hero Background Image URL</label>
                <input type="text" name="hero_image_url" value="<?php echo htmlspecialchars($hero['image_url'] ?? ''); ?>">

                <button type="submit" name="update_hero">Update Hero Section</button>
            </form>
        </div>

        <!-- SITE SETTINGS -->
        <div class="card" id="settings">
            <h3>Homepage Text Settings</h3>
            <form method="POST">
                <label>Why Choose Section Title</label>
                <input type="text" name="why_choose_title" value="<?php echo htmlspecialchars($settings['why_choose_title'] ?? 'Why choose Watchwise?'); ?>">

                <label>FAQ Section Title</label>
                <input type="text" name="faq_title" value="<?php echo htmlspecialchars($settings['faq_title'] ?? 'Got Questions?'); ?>">

                <label>CTA Text</label>
                <textarea name="cta_text"><?php echo htmlspecialchars($settings['cta_text'] ?? 'Ready to start watching? Enter your email to create an account.'); ?></textarea>

                <label>Footer About Text</label>
                <textarea name="footer_about"><?php echo htmlspecialchars($settings['footer_about'] ?? 'Watchwise is your modern movie discovery platform with premium plans, trailers and trending entertainment in one place.'); ?></textarea>

                <button type="submit" name="update_site_settings">Update Site Settings</button>
            </form>
        </div>

        <!-- PLANS -->
        <div class="card" id="plans">
            <h3><?php echo $edit_plan ? 'Edit Plan' : 'Manage Plans'; ?></h3>
            <form method="POST">
                <input type="hidden" name="plan_id" value="<?php echo (int)($edit_plan['id'] ?? 0); ?>">

                <div class="row">
                    <div class="col">
                        <label>Plan Name</label>
                        <input type="text" name="plan_name" value="<?php echo htmlspecialchars($edit_plan['plan_name'] ?? ''); ?>" placeholder="Example: Mobile">
                    </div>
                    <div class="col">
                        <label>Price</label>
                        <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($edit_plan['price'] ?? ''); ?>" placeholder="Example: 149">
                    </div>
                </div>

                <label>Resolution</label>
                <input type="text" name="resolution" value="<?php echo htmlspecialchars($edit_plan['resolution'] ?? ''); ?>" placeholder="Example: 480p Resolution">

                <div class="check-row">
                    <input type="checkbox" name="plan_is_active" id="plan_is_active" <?php echo (!isset($edit_plan['is_active']) || $edit_plan['is_active'] == 1) ? 'checked' : ''; ?>>
                    <label for="plan_is_active" style="margin:0;">Show this plan on website</label>
                </div>

                <button type="submit" name="save_plan"><?php echo $edit_plan ? 'Update Plan' : 'Add Plan'; ?></button>
                <?php if ($edit_plan): ?>
                    <a href="admin.php#plans" class="btn-link" style="margin-left:15px;">Cancel Edit</a>
                <?php endif; ?>
            </form>

            <h3 style="margin-top: 35px;">Current Plans</h3>
            <table>
                <thead>
                    <tr>
                        <th>Plan Name</th>
                        <th>Price</th>
                        <th>Resolution</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($plans && mysqli_num_rows($plans) > 0): ?>
                        <?php while ($p = mysqli_fetch_assoc($plans)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($p['plan_name']); ?></td>
                                <td>₹<?php echo (int)$p['price']; ?></td>
                                <td><?php echo htmlspecialchars($p['resolution']); ?></td>
                                <td><?php echo ((int)($p['is_active'] ?? 0) === 1) ? 'Active' : 'Hidden'; ?></td>
                                <td>
                                    <a href="admin.php?edit_type=plan&id=<?php echo $p['id']; ?>#plans" class="btn-link">Edit</a>
                                    <a href="admin.php?delete_table=plans&id=<?php echo $p['id']; ?>" class="btn-delete" onclick="return confirm('Delete this plan?')">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">No plans found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- OFFERS -->
        <div class="card" id="offers">
            <h3><?php echo $edit_offer ? 'Edit Offer' : 'Manage Offers'; ?></h3>
            <form method="POST">
                <input type="hidden" name="offer_id" value="<?php echo (int)($edit_offer['id'] ?? 0); ?>">

                <div class="row">
                    <div class="col">
                        <label>Offer Title</label>
                        <input type="text" name="offer_title" value="<?php echo htmlspecialchars($edit_offer['title'] ?? ''); ?>" placeholder="Example: Flat ₹50 Off">
                    </div>
                    <div class="col">
                        <label>Coupon Code</label>
                        <input type="text" name="coupon_code" value="<?php echo htmlspecialchars($edit_offer['coupon_code'] ?? ''); ?>" placeholder="Example: WELCOME50">
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <label>Discount Type</label>
                        <select name="discount_type">
                            <option value="fixed" <?php echo (($edit_offer['discount_type'] ?? '') === 'fixed') ? 'selected' : ''; ?>>Fixed</option>
                            <option value="percent" <?php echo (($edit_offer['discount_type'] ?? '') === 'percent') ? 'selected' : ''; ?>>Percent</option>
                        </select>
                    </div>
                    <div class="col">
                        <label>Discount Value</label>
                        <input type="number" step="0.01" name="discount_value" value="<?php echo htmlspecialchars($edit_offer['discount_value'] ?? ''); ?>" placeholder="Example: 50 or 10">
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <label>Minimum Amount</label>
                        <input type="number" step="0.01" name="min_amount" value="<?php echo htmlspecialchars($edit_offer['min_amount'] ?? '0'); ?>" placeholder="Example: 149">
                    </div>
                    <div class="col">
                        <label>Valid Until</label>
                        <input type="date" name="valid_until" value="<?php echo htmlspecialchars($edit_offer['valid_until'] ?? ''); ?>">
                    </div>
                </div>

                <div class="check-row">
                    <input type="checkbox" name="offer_is_active" id="offer_is_active" <?php echo (!isset($edit_offer['is_active']) || $edit_offer['is_active'] == 1) ? 'checked' : ''; ?>>
                    <label for="offer_is_active" style="margin:0;">Show this offer on website</label>
                </div>

                <button type="submit" name="save_offer"><?php echo $edit_offer ? 'Update Offer' : 'Add Offer'; ?></button>
                <?php if ($edit_offer): ?>
                    <a href="admin.php#offers" class="btn-link" style="margin-left:15px;">Cancel Edit</a>
                <?php endif; ?>
            </form>

            <h3 style="margin-top: 35px;">Current Offers</h3>
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Coupon</th>
                        <th>Type</th>
                        <th>Value</th>
                        <th>Min Amount</th>
                        <th>Valid Until</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($offers && mysqli_num_rows($offers) > 0): ?>
                        <?php while ($o = mysqli_fetch_assoc($offers)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($o['title']); ?></td>
                                <td><span class="badge"><?php echo htmlspecialchars($o['coupon_code']); ?></span></td>
                                <td><?php echo htmlspecialchars($o['discount_type']); ?></td>
                                <td><?php echo htmlspecialchars($o['discount_value']); ?></td>
                                <td>₹<?php echo (int)$o['min_amount']; ?></td>
                                <td><?php echo htmlspecialchars($o['valid_until']); ?></td>
                                <td><?php echo ((int)($o['is_active'] ?? 0) === 1) ? 'Active' : 'Hidden'; ?></td>
                                <td>
                                    <a href="admin.php?edit_type=offer&id=<?php echo $o['id']; ?>#offers" class="btn-link">Edit</a>
                                    <a href="admin.php?delete_table=offers&id=<?php echo $o['id']; ?>" class="btn-delete" onclick="return confirm('Delete this offer?')">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8">No offers found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- MOVIES -->
        <div class="card" id="movies">
            <h3><?php echo $edit_movie ? 'Edit Movie' : 'Add Movie'; ?></h3>
            <form method="POST">
                <input type="hidden" name="movie_id" value="<?php echo (int)($edit_movie['id'] ?? 0); ?>">

                <div class="row">
                    <div class="col">
                        <label>Movie Title</label>
                        <input type="text" name="movie_title" value="<?php echo htmlspecialchars($edit_movie['title'] ?? ''); ?>" placeholder="Movie name">
                    </div>
                    <div class="col">
                        <label>Poster URL</label>
                        <input type="text" name="movie_image_url" value="<?php echo htmlspecialchars($edit_movie['image_url'] ?? ''); ?>" placeholder="Poster image URL">
                    </div>
                </div>

                <label>Description</label>
                <textarea name="movie_description" placeholder="Short description"><?php echo htmlspecialchars($edit_movie['description'] ?? ''); ?></textarea>

                <div class="row">
                    <div class="col">
                        <label>Category</label>
                        <select name="movie_category">
                            <option value="trending" <?php echo (($edit_movie['category'] ?? '') === 'trending') ? 'selected' : ''; ?>>Trending</option>
                            <option value="latest" <?php echo (($edit_movie['category'] ?? '') === 'latest') ? 'selected' : ''; ?>>Latest Release</option>
                        </select>
                    </div>
                    <div class="col">
                        <label>Release Year</label>
                        <input type="text" name="release_year" value="<?php echo htmlspecialchars($edit_movie['release_year'] ?? '2026'); ?>">
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <label>Rating</label>
                        <input type="text" name="rating" value="<?php echo htmlspecialchars($edit_movie['rating'] ?? 'Premium'); ?>" placeholder="Premium / 8.5 / U/A etc.">
                    </div>
                    <div class="col">
                        <label>Trailer URL</label>
                        <input type="text" name="trailer_url" value="<?php echo htmlspecialchars($edit_movie['trailer_url'] ?? ''); ?>" placeholder="YouTube embed URL">
                    </div>
                </div>

                <div class="check-row">
                    <input type="checkbox" name="is_active" id="is_active" <?php echo (!isset($edit_movie['is_active']) || $edit_movie['is_active'] == 1) ? 'checked' : ''; ?>>
                    <label for="is_active" style="margin:0;">Show this movie on website</label>
                </div>

                <button type="submit" name="save_movie"><?php echo $edit_movie ? 'Update Movie' : 'Add Movie'; ?></button>
                <?php if ($edit_movie): ?>
                    <a href="admin.php#movies" class="btn-link" style="margin-left:15px;">Cancel Edit</a>
                <?php endif; ?>
            </form>

            <h3 style="margin-top: 35px;">Current Movie Catalog</h3>
            <table>
                <thead>
                    <tr>
                        <th>Poster</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Year</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($movies && mysqli_num_rows($movies) > 0): ?>
                        <?php while ($m = mysqli_fetch_assoc($movies)): ?>
                            <tr>
                                <td><img src="<?php echo htmlspecialchars($m['image_url']); ?>" class="preview" alt="movie"></td>
                                <td><?php echo htmlspecialchars($m['title']); ?></td>
                                <td><span class="badge"><?php echo htmlspecialchars($m['category']); ?></span></td>
                                <td><?php echo htmlspecialchars($m['release_year'] ?? '-'); ?></td>
                                <td><?php echo ((int)($m['is_active'] ?? 0) === 1) ? 'Active' : 'Hidden'; ?></td>
                                <td>
                                    <a href="admin.php?edit_type=movie&id=<?php echo $m['id']; ?>#movies" class="btn-link">Edit</a>
                                    <a href="admin.php?delete_table=movies&id=<?php echo $m['id']; ?>" class="btn-delete" onclick="return confirm('Remove this movie?')">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">No movies found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- FEATURES -->
        <div class="card" id="features">
            <h3><?php echo $edit_feature ? 'Edit Feature' : 'Manage Features'; ?></h3>
            <form method="POST">
                <input type="hidden" name="feature_id" value="<?php echo (int)($edit_feature['id'] ?? 0); ?>">

                <label>Feature Title</label>
                <input type="text" name="feature_title" value="<?php echo htmlspecialchars($edit_feature['title'] ?? ''); ?>" placeholder="Example: Unlimited Streaming">

                <label>Feature Description</label>
                <textarea name="feature_description" placeholder="Write feature description"><?php echo htmlspecialchars($edit_feature['description'] ?? ''); ?></textarea>

                <button type="submit" name="save_feature"><?php echo $edit_feature ? 'Update Feature' : 'Add Feature'; ?></button>
                <?php if ($edit_feature): ?>
                    <a href="admin.php#features" class="btn-link" style="margin-left:15px;">Cancel Edit</a>
                <?php endif; ?>
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
                                    <a href="admin.php?edit_type=feature&id=<?php echo $feature['id']; ?>#features" class="btn-link">Edit</a>
                                    <a href="admin.php?delete_table=features&id=<?php echo $feature['id']; ?>" class="btn-delete" onclick="return confirm('Delete this feature?')">Delete</a>
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

        <!-- FAQ -->
        <div class="card" id="faqs">
            <h3><?php echo $edit_faq ? 'Edit FAQ' : 'Manage FAQs'; ?></h3>
            <form method="POST">
                <input type="hidden" name="faq_id" value="<?php echo (int)($edit_faq['id'] ?? 0); ?>">

                <label>Question</label>
                <input type="text" name="faq_question" value="<?php echo htmlspecialchars($edit_faq['question'] ?? ''); ?>" placeholder="Enter question">

                <label>Answer</label>
                <textarea name="faq_answer" placeholder="Enter answer"><?php echo htmlspecialchars($edit_faq['answer'] ?? ''); ?></textarea>

                <button type="submit" name="save_faq"><?php echo $edit_faq ? 'Update FAQ' : 'Add FAQ'; ?></button>
                <?php if ($edit_faq): ?>
                    <a href="admin.php#faqs" class="btn-link" style="margin-left:15px;">Cancel Edit</a>
                <?php endif; ?>
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
                                    <a href="admin.php?edit_type=faq&id=<?php echo $faq['id']; ?>#faqs" class="btn-link">Edit</a>
                                    <a href="admin.php?delete_table=faqs&id=<?php echo $faq['id']; ?>" class="btn-delete" onclick="return confirm('Delete this FAQ?')">Delete</a>
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
            <h3><?php echo $edit_footer ? 'Edit Footer Link' : 'Manage Footer Links'; ?></h3>

            <form method="POST">
                <input type="hidden" name="footer_id" value="<?php echo (int)($edit_footer['id'] ?? 0); ?>">

                <div class="row">
                    <div class="col">
                        <label>Section</label>
                        <select name="section_name">
                            <option value="Company" <?php echo (($edit_footer['section_name'] ?? '') === 'Company') ? 'selected' : ''; ?>>Company</option>
                            <option value="Support" <?php echo (($edit_footer['section_name'] ?? '') === 'Support') ? 'selected' : ''; ?>>Support</option>
                            <option value="Legal" <?php echo (($edit_footer['section_name'] ?? '') === 'Legal') ? 'selected' : ''; ?>>Legal</option>
                        </select>
                    </div>
                    <div class="col">
                        <label>Label</label>
                        <input type="text" name="label" value="<?php echo htmlspecialchars($edit_footer['label'] ?? ''); ?>" placeholder="Example: About Us">
                    </div>
                </div>

                <label>URL</label>
                <input type="text" name="url" value="<?php echo htmlspecialchars($edit_footer['url'] ?? ''); ?>" placeholder="Example: about.php or https://example.com">

                <label>Sort Order</label>
                <input type="number" name="sort_order" value="<?php echo htmlspecialchars($edit_footer['sort_order'] ?? 1); ?>">

                <div class="check-row">
                    <input type="checkbox" name="footer_is_active" id="footer_is_active" <?php echo (!isset($edit_footer['is_active']) || $edit_footer['is_active'] == 1) ? 'checked' : ''; ?>>
                    <label for="footer_is_active" style="margin:0;">Show this footer link</label>
                </div>

                <button type="submit" name="save_footer"><?php echo $edit_footer ? 'Update Footer Link' : 'Add Footer Link'; ?></button>
                <?php if ($edit_footer): ?>
                    <a href="admin.php#footer" class="btn-link" style="margin-left:15px;">Cancel Edit</a>
                <?php endif; ?>
            </form>

            <h3 style="margin-top: 35px;">Current Footer Links</h3>
            <table>
                <thead>
                    <tr>
                        <th>Section</th>
                        <th>Label</th>
                        <th>URL</th>
                        <th>Sort Order</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($footer_links && mysqli_num_rows($footer_links) > 0): ?>
                        <?php while ($f = mysqli_fetch_assoc($footer_links)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($f['section_name']); ?></td>
                                <td><?php echo htmlspecialchars($f['label']); ?></td>
                                <td><?php echo htmlspecialchars($f['url']); ?></td>
                                <td><?php echo (int)($f['sort_order'] ?? 0); ?></td>
                                <td><?php echo ((int)($f['is_active'] ?? 0) === 1) ? 'Active' : 'Hidden'; ?></td>
                                <td>
                                    <a href="admin.php?edit_type=footer&id=<?php echo $f['id']; ?>#footer" class="btn-link">Edit</a>
                                    <a href="admin.php?delete_table=footer_links&id=<?php echo $f['id']; ?>" class="btn-delete" onclick="return confirm('Delete this footer link?')">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">No footer links found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</body>

</html>