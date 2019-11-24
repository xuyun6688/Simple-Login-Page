<?php

require_once 'tool.php';
header("content-type:text/html;charset=utf-8");

session_start();
$data = ['error' => true, 'user' => ''];

$dsn = 'mysql:host=127.0.0.1;dbname=test';
$u = 'root';
$p = 'root';
$pdo = new PDO($dsn, $u, $p);

if (!isset($_REQUEST['user'], $_REQUEST['pass'])) {
  exit('{"user":"","pass":""}');
}

$user = $_REQUEST['user'];
$pass = $_REQUEST['pass'];
$sql = "select * from `users` where `user`='$user' and `pass`='$pass'";
$rows = $pdo->query($sql);
$res = $rows->fetch(PDO::FETCH_ASSOC);


if ($res) {
  $data['error'] = false;
  $data['user'] = $user;
  $_SESSION['USER'] = $user;
};

echo json_encode($data);
