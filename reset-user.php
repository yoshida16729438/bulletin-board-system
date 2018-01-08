<!--ユーザー情報テーブルのリセット-->

<head>
<meta http-equiv="content-language" content="ja">
<meta charset="UTF-8">
</head>

<title>(掲示板名)</title>
<h1>(掲示板名)　ユーザーリセット</h1>

<?php
try{
	$pdo=new PDO("mysql:host=(ホスト名);dbname=(データベース名);charset=utf8","(ユーザー名)","(パスワード)"); //接続
	$sql="drop table if exists user";
	$pdo->query($sql);
	$sql="create table if not exists user(id text not null,name text not null,address text not null,password text not null,register int(1) ,avail int(1));";
	$pdo->query($sql);
	echo "Table user reseted successfully.</br>";

}catch(PDOException $e){
	echo "error\n</br>";
}
$pdo=null;

?>
