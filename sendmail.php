<?php
include("_check_session.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
$mail = new PHPMailer(true);

$response = ['success' => false, 'message' => ''];

$visitor_id = $_POST['visitor_id'];

$conDB = new db_conn();

$sql = "SELECT * FROM `visitors` WHERE md5(`id`) = '$visitor_id'";
$result = $conDB->sqlQuery($sql);
$email = "";
while ($objResult = mysqli_fetch_assoc($result)) {
    $email = $objResult['email'];
}

if ($email) {
    if (sendMail($mail, $email, $conDB)) {
        $response['success'] = true;
        $response['message'] = 'ส่งใบประกาศไปยังอีเมลแล้ว';
    }
} else {
    $response['message'] = 'ไม่พบอีเมลของผู้ใช้';
}

echo json_encode($response);
function sendMail($mail, $sendto, $conDB)
{
    try {
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'EEFforum2024@eef.or.th';
        $mail->Password = 'EEF#1234';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';


        $mail->setFrom('EEFforum2024@eef.or.th', 'All for Education ปลุกพลังปวงชนเพื่อเด็กไทยทุกคน');
        $mail->addAddress(trim($sendto));

        $mail->isHTML(true);
        $mail->Subject = 'ยืนยันการลงทะเบียนเข้าร่วมงาน All for Education ปลุกพลังปวงชนเพื่อเด็กไทยทุกคน';
        $mail->Body    = '<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ยืนยันการลงทะเบียนเข้าร่วมงาน All for Education</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333333;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            background-color: #054573;
            color: #ffffff;
            padding: 20px;
            border-radius: 8px 8px 0 0;
        }
        .content {
            margin: 20px 0;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #054573;
            color: #ffffff;
            text-decoration: none;
            border-radius: 4px;
        }
        .footer {
            text-align: center;
            color: #999999;
            font-size: 12px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>ส่งเมลถึง ' . htmlspecialchars($sendto) . '</h1>
        </div>
        <div class="content">
            <p>ส่งเมลถึง ' . htmlspecialchars($sendto) . '</p>
        </div>
        <div class="footer">
            <p>&copy; 2024 All for Education. สงวนลิขสิทธิ์.</p>
        </div>
    </div>
</body>
</html>
';
        $mail->AltBody = '';

        if ($mail->send()) {
            $sql_update = "UPDATE `visitors` SET `count_email` = `count_email` + 1 WHERE `email` = '" . htmlspecialchars($sendto) . "'";
            $conDB->sqlQuery($sql_update);
            return true;
        } else {
            return false;
        }
    } catch (Exception $e) {
        return false;
    }
}
