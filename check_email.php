<?php
include "db.php";
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email'] ?? '');

    if ($email == "") {
        echo json_encode([
            "status" => "error",
            "message" => "Email is required."
        ]);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            "status" => "error",
            "message" => "Invalid email format."
        ]);
        exit;
    }

    $safeEmail = mysqli_real_escape_string($conn, $email);
    $check = mysqli_query($conn, "SELECT id FROM users WHERE email='$safeEmail' LIMIT 1");

    if ($check && mysqli_num_rows($check) > 0) {
        echo json_encode([
            "status" => "exists",
            "message" => "This email is already registered."
        ]);
    } else {
        echo json_encode([
            "status" => "success",
            "message" => "Email looks good."
        ]);
    }
    exit;
}

echo json_encode([
    "status" => "error",
    "message" => "Invalid request."
]);
?>