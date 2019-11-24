<?php

session_start();
header("content-type:text/html;charset=utf-8");
require_once 'tool.php';

if (!isset($_SESSION['USER'])) {
	exit('{}');
}

$dsn = 'mysql:host=127.0.0.1;dbname=test';
$u = 'root';
$p = 'root';
$pdo = new PDO($dsn, $u, $p);

function ext($type)
{
	$t = explode('/', $type);
	$s = array_pop($t);
	return ($s == 'jpeg' || $s == 'pjpeg') ? '.jpg' : ".{$s}";
}

function formatBytes($size)
{
	$units = array(' B', ' KB', ' MB', ' GB', ' TB');
	for ($i = 0; $size >= 1024 && $i < 4; $i++) {
		$size /= 1024;
	}
	// return round($size, 2) . $units[$i];
	return number_format($size, 2) . $units[$i];
}

$errs = [
	['UPLOAD_ERR_OK', '没有错误发生，文件上传成功'],
	['UPLOAD_ERR_INI_SIZE', '上传的文件超过了php.ini中upload_max_filesize选项限制的值'],
	['UPLOAD_ERR_FORM_SIZE', '上传文件的大小超过了HTML表单中MAX_FILE_SIZE选项指定的值'],
	['UPLOAD_ERR_PARTIAL', '文件只有部分被上传'],
	['UPLOAD_ERR_NO_FILE', '没有文件被上传'],
	['UPLOAD_ERR_NO_TMP_DIR', '找不到临时文件夹'],
	['UPLOAD_ERR_CANT_WRITE', '文件写入失败']
];

$path = '../uploads/';
$name = $_FILES["file"]["name"];
$type = $_FILES["file"]["type"];
$size = formatBytes($_FILES["file"]["size"]);
$tmpName = $_FILES["file"]["tmp_name"];
$saveName = date('Ymdhis') . '_' .  rnd(3) . ext($type);
$file = $path . $saveName;

$data = [];

if ($_FILES["file"]["error"] > 0) {
	exit('错误：' . $errs[$_FILES["file"]["error"]][1]);
} else {
	// out('文件：' . $name);
	// out('类型：' . $type);
	// out('大小：' . $size);
	// out('保存：' . $file);

}
/*
通过使用 PHP 的全局数组 $_FILES，你可以从客户计算机向远程服务器上传文件。
第一个参数是表单的 input name，
第二个下标可以是 "name", "type", "size", "tmp_name" 或 "error"。
就像这样：
	$_FILES["file"]["name"]     - 被上传文件的名称
	$_FILES["file"]["type"]     - 被上传文件的类型
	$_FILES["file"]["size"]     - 被上传文件的大小，以字节计
	$_FILES["file"]["tmp_name"] - 存储在服务器的文件的临时副本的名称
	$_FILES["file"]["error"]    - 由文件上传导致的错误代码
这是一种非常简单文件上传方式。
基于安全方面的考虑，您应当增加有关什么用户有权上传文件的限制。
*/

$max_size = 1024 * 1024 * 4; // = 4M
if ((($type === "image/png") || ($type === "image/jpeg") || ($type === "image/gif") || ($type === "image/gif") || ($type === "image/pjpeg")) && ($_FILES["file"]["size"] < $max_size)
) {
	// out('<b>文件上传成功！</b>');
	move_uploaded_file($tmpName, $file);
	$data = [
		'name' => $name,
		'type' => $type,
		'size' => $size,
		'file' => $file,
		'saveName' => $saveName,
		'user' => $_SESSION['USER'],
		'msg' => '<b>文件上传成功！</b>'
	];
} else {
	// out('上传文件类型错误！');
	$data['msg'] = '上传文件类型错误！';
}

$user = $_SESSION['USER'];
$_SESSION['FILE'] = $saveName;
$sql = "insert into `photos` (`owner`,`fname`) values ('$user','$saveName')";
$pdo->exec($sql);



// echo json_encode($data);

/*
保存被上传的文件
上面的例子在服务器的 PHP 临时文件夹创建了一个被上传文件的临时副本。
这个临时的复制文件会在脚本结束时消失。
要保存被上传的文件，需要把它拷贝到指定的文件夹位置，推荐改名。
*/

/*
方法1：
function getExt1($filename)
{
   $arr = explode('.',$filename);
   return array_pop($arr);;
}

方法2：
function getExt2($filename)
{
   $ext = strrchr($filename,'.');
   return $ext;
}

方法3：
function getExt3($filename)
{
   $pos = strrpos($filename, '.');
   $ext = substr($filename, $pos);
   return $ext;
}

方法4：
function getExt4($filename)
{
   $arr = pathinfo($filename);
   $ext = $arr['extension'];
   return $ext;
}

方法5：
function getExt5($filename)
{
   $str = strrev($filename);
   return strrev(strchr($str,'.',true));
}
*/
