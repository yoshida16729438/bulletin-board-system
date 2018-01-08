<?php
$pdo=new PDO("mysql:host=(ホスト名);dbname=(データベース名);charset=utf8","(ユーザー名)","(パスワード)"); //接続
$number=$_GET["number"];
$sql="select data,type from comments where number=$number;";
$result=$pdo->query($sql);
foreach($result as $row){
	$data=$row["data"];
	$type=$row["type"];
}

header("content-type:$type");
echo $data;

$pdo=null;

?>