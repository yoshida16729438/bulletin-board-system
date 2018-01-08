//各登録情報変更への分岐を行うページ

<?php
session_start();
?>
<head>
<meta http-equiv="content-language" content="ja">
<meta charset="UTF-8">
</head>

<title>(掲示板名)</title>
<h1>(掲示板名)　登録変更<br/></h1>

<?php

$pdo=new PDO("mysql:host=(ホスト名);dbname=(データベース名);charset=utf8","(ユーザー名)","(パスワード)"); //接続
$password=$_SESSION["password"];
$name=$_SESSION["name"];
$id=$_SESSION["id"];

$sql="select name,address from user where id=:id;";
$stmt=$pdo->prepare($sql);
$stmt->bindparam(":id",$id,pdo::PARAM_STR);
$stmt->execute();
foreach($stmt as $row)$address=$row["address"];

if($_SESSION["id"]==""||$password==""){//URL直接入力による誤動作防止
	header("location:toppage.php");
	exit();
}

if(isset($_POST["address"])){
	$_SESSION["address"]=$address;
	header("location:addresschange.php");
	exit();
}

$pass=null;
for($i=1;$i<=mb_strlen($password);$i++){
	$pass=$pass."*";
}

$pdo=null;

?>

メールアドレス:&nbsp&nbsp<?php echo $address;?><br/>
<form action="change.php" method="post">
<input type="submit" value="変更" name="address"></form>
<br/><br/>

ユーザー名:&nbsp&nbsp<?php echo $name;?><br/>
<form action="namechange.php" method="post">
<input type="submit" value="変更" name="name"></form>
<br/><br/>

パスワード:&nbsp&nbsp<?php echo $pass;?><br/>
<form action="passchangecheck.php" method="post">
<input type="submit" value="変更" name="password"></form>
<br/><br/>

<br/>
<form action="main.php" method="post">
<input type="submit" value="戻る"></form>
