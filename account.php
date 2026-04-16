<?php
include "db.php";
session_start();

$userId = $_SESSION['user_id'] ?? 1;

$userQuery = mysqli_query($conn, "SELECT * FROM users WHERE id='$userId' LIMIT 1");
$user = mysqli_fetch_assoc($userQuery);

if (!$user) {
    die("User not found.");
}

$email = mysqli_real_escape_string($conn, $user['email']);
$paymentQuery = mysqli_query($conn, "SELECT * FROM payments WHERE email='$email' ORDER BY id DESC LIMIT 1");
$payment = mysqli_fetch_assoc($paymentQuery);

$currentPlan = $user['plan'] ?? 'No Plan';
$paymentStatus = $user['payment_status'] ?? 'Pending';
$couponCode = $payment['coupon_code'] ?? 'Not Used';
$finalAmount = $payment['final_amount'] ?? '0';
$userName = $user['name'] ?? 'Watchwise User';
$userEmail = $user['email'] ?? '';

$profileImage = "https://cdn-icons-png.flaticon.com/512/847/847969.png";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Watchwise Profile</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(180deg, #020817, #031525, #020817);
            color: #fff;
            min-height: 100vh;
        }

        .navbar {
            position: sticky;
            top: 0;
            z-index: 1000;
            width: 100%;
            padding: 18px 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(2, 8, 23, 0.92);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }

        .nav-left {
            display: flex;
            align-items: center;
            gap: 34px;
        }

        .logo {
            color: #00c2ff;
            font-size: 28px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-decoration: none;
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 22px;
        }

        .nav-links a {
            text-decoration: none;
            color: #94a3b8;
            font-size: 14px;
            transition: 0.25s;
        }

        .nav-links a:hover {
            color: #fff;
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .search-box {
            display: flex;
            align-items: center;
            background: rgba(15, 23, 42, 0.8);
            border: 1px solid rgba(0, 194, 255, 0.25);
            padding: 10px 14px;
            border-radius: 10px;
            min-width: 260px;
            color: #94a3b8;
        }

        .search-box input {
            background: transparent;
            border: none;
            outline: none;
            color: #fff;
            margin-left: 8px;
            width: 100%;
            font-size: 14px;
        }

        .profile-wrap {
            position: relative;
        }

        .profile-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            background: rgba(15, 23, 42, 0.88);
            border: 1px solid rgba(0, 194, 255, 0.28);
            border-radius: 14px;
            padding: 8px 14px 8px 10px;
            cursor: pointer;
            min-width: 190px;
        }

        .profile-btn img {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(0, 194, 255, 0.35);
            background: #fff;
        }

        .profile-btn span {
            color: #fff;
            font-weight: 600;
            font-size: 14px;
        }

        .profile-menu {
            position: absolute;
            right: 0;
            top: 58px;
            width: 340px;
            background: rgba(10, 18, 34, 0.98);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 18px;
            box-shadow: 0 18px 45px rgba(0, 0, 0, 0.4);
            padding: 18px;
            display: none;
        }

        .profile-menu.show {
            display: block;
        }

        .menu-top {
            display: flex;
            align-items: center;
            gap: 14px;
            padding-bottom: 14px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .menu-top img {
            width: 58px;
            height: 58px;
            border-radius: 50%;
            object-fit: cover;
            background: #fff;
        }

        .menu-name {
            font-size: 17px;
            font-weight: 700;
            color: #fff;
        }

        .menu-email {
            font-size: 12px;
            color: #94a3b8;
            margin-top: 3px;
            word-break: break-word;
        }

        .menu-section {
            padding-top: 14px;
        }

        .menu-title {
            font-size: 12px;
            color: #38bdf8;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            gap: 14px;
            padding: 10px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            color: #94a3b8;
            font-size: 13px;
        }

        .info-value {
            color: #fff;
            font-size: 13px;
            font-weight: 600;
            text-align: right;
        }

        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
        }

        .badge-success {
            background: rgba(34, 197, 94, 0.16);
            color: #22c55e;
        }

        .badge-plan {
            background: rgba(0, 194, 255, 0.14);
            color: #38bdf8;
        }

        .menu-actions {
            display: flex;
            flex-direction: column;
            gap: 10px;
            padding-top: 16px;
        }

        .menu-actions a {
            text-decoration: none;
            text-align: center;
            padding: 12px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
        }

        .btn-primary {
            background: linear-gradient(135deg, #00c2ff, #2563eb);
            color: #fff;
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.06);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .hero {
            padding: 70px 5% 30px;
        }

        .hero-card {
            background: linear-gradient(135deg, rgba(0, 194, 255, 0.14), rgba(59, 130, 246, 0.08));
            border: 1px solid rgba(0, 194, 255, 0.16);
            border-radius: 26px;
            padding: 34px;
            display: flex;
            justify-content: space-between;
            gap: 20px;
            align-items: center;
            flex-wrap: wrap;
        }

        .hero-text h1 {
            font-size: 36px;
            margin-bottom: 10px;
        }

        .hero-text p {
            color: #94a3b8;
            font-size: 15px;
            line-height: 1.6;
            max-width: 640px;
        }

        .hero-plan {
            text-align: right;
        }

        .hero-plan .small {
            color: #94a3b8;
            font-size: 13px;
            margin-bottom: 6px;
        }

        .hero-plan .big {
            font-size: 28px;
            font-weight: 800;
            color: #38bdf8;
        }

        .section {
            padding: 0 5% 40px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
        }

        .card {
            background: rgba(15, 23, 42, 0.75);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 20px;
            padding: 20px;
        }

        .card-title {
            color: #94a3b8;
            font-size: 13px;
            margin-bottom: 10px;
        }

        .card-value {
            font-size: 22px;
            font-weight: 700;
            color: #fff;
            word-break: break-word;
        }

        @media (max-width: 1100px) {
            .grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .nav-links {
                display: none;
            }
        }

        @media (max-width: 760px) {
            .navbar {
                flex-direction: column;
                gap: 14px;
                align-items: stretch;
            }

            .nav-left,
            .nav-right {
                justify-content: space-between;
            }

            .search-box {
                min-width: 100%;
            }

            .profile-btn {
                min-width: 100%;
                justify-content: center;
            }

            .profile-menu {
                width: 100%;
                right: 0;
            }

            .grid {
                grid-template-columns: 1fr;
            }

            .hero-card {
                padding: 24px;
            }

            .hero-text h1 {
                font-size: 28px;
            }

            .hero-plan {
                text-align: left;
            }
        }
    </style>
</head>

<body>

    <nav class="navbar">
        <div class="nav-left">
            <a href="movie.php" class="logo">WATCHWISE</a>

            <ul class="nav-links">
                <li><a href="movie.php">Home</a></li>
                <li><a href="movie.php#mylist">My List</a></li>
                <li><a href="movie.php#movies">Movies</a></li>
                <li><a href="movie.php#tvshows">TV Shows</a></li>
                <li><a href="movie.php#kids">Kids & Anime</a></li>
                <li><a href="index.php">Logout</a></li>
            </ul>
        </div>

        <div class="nav-right">
            <div class="search-box">
                🔎
                <input type="text" placeholder="Search title or mood...">
            </div>

            <div class="profile-wrap">
                <div class="profile-btn" onclick="toggleProfileMenu()">
                    <img src="<?php echo $profileImage; ?>" alt="Profile">
                    <span>Profile</span>
                </div>

                <div class="profile-menu" id="profileMenu">
                    <div class="menu-top">
                        <img src="<?php echo $profileImage; ?>" alt="Profile">
                        <div>
                            <div class="menu-name"><?php echo htmlspecialchars($userName); ?></div>
                            <div class="menu-email"><?php echo htmlspecialchars($userEmail); ?></div>
                        </div>
                    </div>

                    <div class="menu-section">
                        <div class="menu-title">Subscription Details</div>

                        <div class="info-row">
                            <div class="info-label">Current Plan</div>
                            <div class="info-value">
                                <span class="badge badge-plan"><?php echo htmlspecialchars($currentPlan); ?></span>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-label">Payment Status</div>
                            <div class="info-value">
                                <span class="badge badge-success"><?php echo htmlspecialchars($paymentStatus); ?></span>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-label">Coupon Used</div>
                            <div class="info-value"><?php echo htmlspecialchars($couponCode); ?></div>
                        </div>

                        <div class="info-row">
                            <div class="info-label">Final Paid Amount</div>
                            <div class="info-value">₹<?php echo htmlspecialchars($finalAmount); ?></div>
                        </div>
                    </div>

                    <div class="menu-actions">
                        <a href="step3.php" class="btn-primary">Manage Subscription</a>
                        <a href="index.php" class="btn-secondary">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <section class="hero">
        <div class="hero-card">
            <div class="hero-text">
                <h1>Welcome back, <?php echo htmlspecialchars($userName); ?></h1>
                <p>
                    This is your Watchwise profile area. Here you can view your subscription details,
                    payment status, latest coupon usage, and manage your plan like OTT platforms.
                </p>
            </div>

            <div class="hero-plan">
                <div class="small">Active Membership</div>
                <div class="big"><?php echo htmlspecialchars($currentPlan); ?></div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="grid">
            <div class="card">
                <div class="card-title">Profile Name</div>
                <div class="card-value"><?php echo htmlspecialchars($userName); ?></div>
            </div>

            <div class="card">
                <div class="card-title">Email Address</div>
                <div class="card-value"><?php echo htmlspecialchars($userEmail); ?></div>
            </div>

            <div class="card">
                <div class="card-title">Subscription Plan</div>
                <div class="card-value"><?php echo htmlspecialchars($currentPlan); ?></div>
            </div>

            <div class="card">
                <div class="card-title">Latest Paid Amount</div>
                <div class="card-value">₹<?php echo htmlspecialchars($finalAmount); ?></div>
            </div>
        </div>
    </section>

    <script>
        function toggleProfileMenu() {
            document.getElementById("profileMenu").classList.toggle("show");
        }

        document.addEventListener("click", function(e) {
            const wrap = document.querySelector(".profile-wrap");
            const menu = document.getElementById("profileMenu");

            if (!wrap.contains(e.target)) {
                menu.classList.remove("show");
            }
        });
    </script>
</body>

</html>