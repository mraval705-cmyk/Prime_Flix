<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

function sendOTP($to, $otp)
{
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'minalraval1222@gmail.com';
        $mail->Password   = 'mvoq uoap sqst apwu';
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom('minalraval1222@gmail.com', 'WatchWise');
        $mail->addAddress($to);

        $mail->isHTML(true);
        $mail->Subject = 'WatchWise OTP Verification';
        $mail->Body    = "
            <h2>WatchWise Password Reset OTP</h2>
            <p>Your OTP is: <b>$otp</b></p>
            <p>This OTP will expire in 10 minutes.</p>
        ";

        return $mail->send();
    } catch (Exception $e) {
        return false;
    }
}
?>