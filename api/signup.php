<?php

require_once 'tool.php';
header("content-type:text/html;charset=utf-8");
$dsn = 'mysql:host=127.0.0.1;dbname=test';
$u = 'root';
$p = 'root';
$pdo = new PDO($dsn, $u, $p);

$user = '';
$pass = '';
$reg = [
  'error' => true,
  'text' => '用户名已存在',
];

if (!isset($_REQUEST['user'], $_REQUEST['pass'])) {
  exit('{"user":"","pass":""}');
}

$user = $_REQUEST['user'];
$pass = $_REQUEST['pass'];

$sql = "select * from `users` where `user`='$user'";
$rows = $pdo->query($sql);
$res = $rows->fetch(PDO::FETCH_ASSOC);

if (!$res) {
  $sql = "insert into `users` (`user`,`pass`) values ('$user','$pass')";
  $pdo->exec($sql);
  $res = $pdo->query('select * from `users`');
  $data = $res->fetchAll();
  $reg['error'] = false;
  $reg['text'] = '注册成功！';
}



echo json_encode($reg);
