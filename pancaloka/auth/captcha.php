<?php
session_start();

$karakter = 'ABCDEFGHJKLMNOPQRSTUVWXYZabcdefghjkmnopqrstuvwxyz';
$captcha = substr(str_shuffle($karakter), 0, 4);

$_SESSION['captcha'] = $captcha;

header('Content-type: image/png');
$image = imagecreate(100, 40);

$bg = imagecolorallocate($image, 240, 240, 240);
$textcolor = imagecolorallocate($image, 0, 0, 0);

imagestring($image, 5, 25, 10, $captcha, $textcolor);
imagepng($image);
imagedestroy($image);
?>