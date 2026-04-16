<?php
include "db.php";

$message = "";

/* ADD OFFER */
if (isset($_POST['add_offer'])) {
    $title = trim($_POST['title'] ?? '');
    $coupon_code = strtoupper(trim($_POST['coupon_code'] ?? ''));
    $discount_type = trim($_POST['discount_type'] ?? '');
    $discount_value = (float)($_POST['discount_value'] ?? 0);
    $min_amount = (float)($_POST['min_amount'] ?? 0);
    $valid_until = trim($_POST['valid_until'] ?? '');
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    if ($title !== "" && $coupon_code !== "" && $discount_type !== "" && $valid_until !== "") {
        $title_safe = mysqli_real_escape_string($conn, $title);
        $coupon_safe = mysqli_real_escape_string($conn, $coupon_code);
        $type_safe = mysqli_real_escape_string($conn, $discount_type);
        $valid_until_safe = mysqli_real_escape_string($conn, $valid_until);

        $check = mysqli_query($conn, "SELECT id FROM offers WHERE coupon_code='$coupon_safe' LIMIT 1");

        if ($check && mysqli_num_rows($check) > 0) {
            $message = "Coupon code already exists.";
        } else {
            $sql = "INSERT INTO offers (title, coupon_code, discount_type, discount_value, min_amount, valid_until, is_active)
                    VALUES ('$title_safe', '$coupon_safe', '$type_safe', '$discount_value', '$min_amount', '$valid_until_safe', '$is_active')";

            if (mysqli_query($conn, $sql)) {
                $message = "Offer added successfully.";
            } else {
                $message = "Error: " . mysqli_error($conn);
            }
        }
    } else {
        $message = "Please fill all required fields.";
    }
}

/* DELETE OFFER */
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM offers WHERE id = $id");
    header("Location: admin_offers.php");
    exit();
}

/* TOGGLE STATUS */
if (isset($_GET['toggle'])) {
    $id = (int)$_GET['toggle'];
    mysqli_query($conn, "UPDATE offers 
                         SET is_active = IF(is_active=1, 0, 1)
                         WHERE id = $id");
    header("Location: admin_offers.php");
    exit();
}

$offers = mysqli_query($conn, "SELECT * FROM offers ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Offers - Watchwise</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #0f172a, #020617);
            color: #fff;
            min-height: 100vh;
            padding: 24px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 24px;
        }

        .title {
            font-size: 28px;
            font-weight: 700;
            color: #38bdf8;
        }

        .back-btn {
            text-decoration: none;
            color: #fff;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.12);
            padding: 10px 16px;
            border-radius: 10px;
        }

        .grid {
            display: grid;
            grid-template-columns: 380px 1fr;
            gap: 22px;
        }

        .card {
            background: rgba(30, 41, 59, 0.72);
            border: 1px solid rgba(255, 255, 255, 0.10);
            border-radius: 18px;
            padding: 20px;
            backdrop-filter: blur(10px);
        }

        h2 {
            font-size: 20px;
            margin-bottom: 16px;
        }

        .message {
            margin-bottom: 14px;
            color: #22c55e;
            font-size: 14px;
        }

        .field {
            margin-bottom: 14px;
        }

        .field label {
            display: block;
            font-size: 13px;
            margin-bottom: 6px;
            color: #cbd5e1;
        }

        .field input,
        .field select {
            width: 100%;
            padding: 12px 14px;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.14);
            background: rgba(15, 23, 42, 0.7);
            color: #fff;
            outline: none;
        }

        .field select option {
            color: #000;
        }

        .checkbox-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 10px 0 16px;
            color: #e2e8f0;
            font-size: 14px;
        }

        .btn {
            width: 100%;
            padding: 13px;
            border: none;
            border-radius: 12px;
            background: linear-gradient(135deg, #0ea5e9, #2563eb);
            color: #fff;
            font-weight: 700;
            cursor: pointer;
        }

        .table-wrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 760px;
        }

        th,
        td {
            padding: 12px 10px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            font-size: 13px;
        }

        th {
            color: #93c5fd;
            font-weight: 600;
        }

        .badge {
            display: inline-block;
            padding: 5px 9px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
        }

        .active {
            background: rgba(34, 197, 94, 0.18);
            color: #22c55e;
        }

        .inactive {
            background: rgba(239, 68, 68, 0.18);
            color: #f87171;
        }

        .action-btn {
            display: inline-block;
            padding: 7px 10px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            margin-right: 6px;
        }

        .toggle-btn {
            background: rgba(14, 165, 233, 0.18);
            color: #38bdf8;
        }

        .delete-btn {
            background: rgba(239, 68, 68, 0.18);
            color: #f87171;
        }

        @media (max-width: 900px) {
            .grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="topbar">
            <div class="title">Watchwise Admin - Offers</div>
            <a href="admin.php" class="back-btn">Back to Admin</a>
        </div>

        <div class="grid">
            <div class="card">
                <h2>Add Offer</h2>

                <?php if ($message != ""): ?>
                    <div class="message"><?php echo htmlspecialchars($message); ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="field">
                        <label>Title</label>
                        <input type="text" name="title" required>
                    </div>

                    <div class="field">
                        <label>Coupon Code</label>
                        <input type="text" name="coupon_code" required>
                    </div>

                    <div class="field">
                        <label>Discount Type</label>
                        <select name="discount_type" required>
                            <option value="percent">percent</option>
                            <option value="fixed">fixed</option>
                        </select>
                    </div>

                    <div class="field">
                        <label>Discount Value</label>
                        <input type="number" step="0.01" name="discount_value" required>
                    </div>

                    <div class="field">
                        <label>Minimum Amount</label>
                        <input type="number" step="0.01" name="min_amount" required>
                    </div>

                    <div class="field">
                        <label>Valid Until</label>
                        <input type="date" name="valid_until" required>
                    </div>

                    <div class="checkbox-wrap">
                        <input type="checkbox" name="is_active" checked>
                        <span>Active Offer</span>
                    </div>

                    <button type="submit" name="add_offer" class="btn">Add Offer</button>
                </form>
            </div>

            <div class="card">
                <h2>All Offers</h2>

                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
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
                                <?php while ($offer = mysqli_fetch_assoc($offers)): ?>
                                    <tr>
                                        <td><?php echo (int)$offer['id']; ?></td>
                                        <td><?php echo htmlspecialchars($offer['title']); ?></td>
                                        <td><?php echo htmlspecialchars($offer['coupon_code']); ?></td>
                                        <td><?php echo htmlspecialchars($offer['discount_type']); ?></td>
                                        <td><?php echo htmlspecialchars($offer['discount_value']); ?></td>
                                        <td>₹<?php echo htmlspecialchars($offer['min_amount']); ?></td>
                                        <td><?php echo htmlspecialchars($offer['valid_until']); ?></td>
                                        <td>
                                            <?php if ((int)$offer['is_active'] === 1): ?>
                                                <span class="badge active">Active</span>
                                            <?php else: ?>
                                                <span class="badge inactive">Inactive</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a class="action-btn toggle-btn" href="admin_offers.php?toggle=<?php echo (int)$offer['id']; ?>">Toggle</a>
                                            <a class="action-btn delete-btn" href="admin_offers.php?delete=<?php echo (int)$offer['id']; ?>" onclick="return confirm('Delete this offer?')">Delete</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="9">No offers found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

</html>