<?php

session_start();
require_once 'tool.php';
header("content-type:text/html;charset=utf-8");

$dsn = 'mysql:host=127.0.0.1;dbname=test';
$u = 'root';
$p = 'root';
$pdo = new PDO($dsn, $u, $p);

$data = [
  'error' => true,
  'text' => '提交参数错误',
  'user' => ''
];
$user = '';
$pass = '';
$code = '';

if (isset($_REQUEST['user'], $_REQUEST['pass'], $_REQUEST['code'])) {

  $user = $_REQUEST['user'];
  $pass = $_REQUEST['pass'];
  $code = $_REQUEST['code'];
  $sql = "select * from `users` where `user`='$user' and `pass`='$pass'";
  $rows = $pdo->query($sql);
  $res = $rows->fetch(PDO::FETCH_ASSOC);

  if ($res && $code === $_SESSION['CODE']) {
    $_SESSION['USER'] = $_REQUEST['user'];
    $data['error'] = false;
    $data['text'] = '登陆成功！';
    $data['user'] = $user;
    $_SESSION['CODE'] = '';
    unset($_SESSION['CODE']);
  } elseif (!$res) {
    $data['text'] = '用户名或密码错误！';
  } elseif ($code !== $_SESSION['CODE']) {
    $data['text'] = '验证码错误！';
  }
} else {
  exit('{"user":"","pass":""}');
}

echo json_encode($data);
