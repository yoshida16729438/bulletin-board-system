<!--アカウント削除の確定-->

<?php
session_start();
?>
<head>
<meta http-equiv="content-language" content="ja">
<meta charset="UTF-8">
</head>

<title>(掲示板名)</title>
<h1>(掲示板名)　アカウントの削除<br/></h1>

<?php

$error=null;

$pdo=new PDO("mysql:host=(ホスト名);dbname=(データベース名);charset=utf8","(ユーザー名)","(パスワード)"); //接続

if($_SESSION["delete"]!=true){//URL直接入力による誤動作防止
	header("location:main.php");
	exit();
}

$id=$_SESSION["id"];
$sql="delete from user where id=$id;";
$pdo->query($sql);
unset($_SESSION["id"]);
unset($_SESSION["password"]);
unset($_SESSION["name"]);
unset($_SESSION["delete"]);

echo "アカウントを削除しました。またのご利用をお待ちしております。<br/>";

$pdo=null;

?>

<form action="toppage.php" method="post">
<input type="submit" value="トップページへ"></form>
