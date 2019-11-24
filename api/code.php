<?php

require_once 'tool.php';
session_start();

$n = 4;
$w = $n * 42;
$h = 46;
$img = imagecreate($w, $h);
// $img = imagecreatefrompng('seasky.png');

// 创建画笔（颜色）
$black = imagecolorallocate($img, 0, 0, 0);
$gray = imagecolorallocate($img, 80, 80, 80);
$white = imagecolorallocate($img, 255, 255, 255);
$red = imagecolorallocate($img, 255, 114, 86);
$darkgreen = imagecolorallocate($img, 0, 100, 0);
$cornflowerblue = imagecolorallocate($img, 99, 147, 236);
$gold = imagecolorallocate($img, 218, 165, 32);
$lightgray = imagecolorallocate($img, 220, 220, 220);
// 填背景色
imagefill($img, 0, 0, $lightgray);

// 产生杂色
$colors = [$black, $gray, $red, $darkgreen, $cornflowerblue, $gold];
for ($i = 0; $i < 5; $i++) {
  $r = mt_rand(0, sizeof($colors) - 1);
  imagearc($img, mt_rand(0, $w), mt_rand(0, $h), mt_rand($h / 2, $w / 2), mt_rand($h / 2, $w / 2), 0, 360, $colors[$r]);
  $r = mt_rand(0, sizeof($colors) - 1);
  imageline($img, 0, mt_rand(0, $h), $w, mt_rand(0, $h), $colors[$r]);
}

// 文字输出
$fontfile = "simhei.ttf";

// 随机 n 位验证码

$text = rnd($n);
$_SESSION['CODE'] = $text;

// 验证码输出
$y = $h * 0.9;
$x = 10;
$color = $gray;
for ($i = 0; $i < strlen($text); $i++) {
  $size = mt_rand(20, 42);
  $angle = mt_rand(-20, 20);
  $r = mt_rand(0, sizeof($colors) - 1);
  imagettftext($img, $size, $angle, $x, $y, $colors[$r], $fontfile, $text[$i]);
  $x += 42;
}

// 声明图片数据格式（之前不能有任何输出）
header("Content-type: image/png");

// 输出图片
imagepng($img);

// 删除（释放）内存
imagedestroy($img);
