<?php
session_start();

$length = 6;
$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
$captcha = '';

for ($i = 0; $i < $length; $i++) {
    $captcha .= $characters[rand(0, strlen($characters) - 1)];
}

$width = 120;
$height = 40;
http://mismatch/capcha.ph
$image = imagecreatetruecolor($width, $height);
$bg_color = imagecolorallocate($image, 255, 255, 255);
$text_color = imagecolorallocate($image, 0, 0, 0);

imagefilledrectangle($image, 0, 0, $width, $height, $bg_color);
imagestring($image, 5, 35, 10, $captcha, $text_color);

header('Content-type: image/png');
imagepng($image);

imagedestroy($image);

$_SESSION['captcha'] = $captcha;
?>