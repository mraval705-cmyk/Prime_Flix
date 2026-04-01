<?php
function clean_input($data)
{
    return htmlspecialchars(trim($data));
}

function generateOTP()
{
    return rand(100000, 999999);
}

function isValidEmailFormat($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}
?>