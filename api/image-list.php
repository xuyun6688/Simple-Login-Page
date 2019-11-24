<?php

session_start();
require_once 'tool.php';

header("content-type:text/html;charset=utf-8");

$data = [
  'my' => [],
  'others' => []
];
if (isset($_SESSION['USER'])) {
  $dsn = 'mysql:host=127.0.0.1;dbname=test';
  $u = 'root';
  $p = 'root';
  $pdo = new PDO($dsn, $u, $p);
  $user = $_SESSION['USER'];
  $sql = "select owner,fname from `photos` where `owner`='{$user}'";
  $table = $pdo->query($sql);
  $res = $table->fetchAll(PDO::FETCH_ASSOC);
  $data['my'] = $res;
  $sql = "select owner,fname from `photos` where `owner`!='{$user}'";
  $table = $pdo->query($sql);
  $res = $table->fetchAll(PDO::FETCH_ASSOC);
  $data['others'] = $res;
}

echo json_encode($data);