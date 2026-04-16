<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
<title>Update Success</title>
<style>
body {
    background: #020617;
    color: white;
    font-family: Poppins;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

.box {
    background: #0f172a;
    padding: 40px;
    border-radius: 20px;
    text-align:center;
}

.btn {
    margin-top:20px;
    display:inline-block;
    padding:12px 20px;
    background:#0ea5e9;
    color:white;
    border-radius:10px;
    text-decoration:none;
}
</style>
</head>

<body>

<div class="box">
    <h1>✅ Subscription Updated</h1>
    <p>Your plan has been changed successfully.</p>

    <a href="user_movie.php" class="btn">Go to Home</a>
</div>

</body>
</html>