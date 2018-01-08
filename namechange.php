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
$id=$_SESSION["id"];

if($_SESSION["id"]==""){
	header("location:change.php");
	exit();
}

$error=null;
if($_POST["change"]){
	if($_POST["name"]!=""){
		$name=$_POST["name"];
		$sql="update user set name=:name where id=:id;";
		$stmt=$pdo->prepare($sql);
		$stmt->bindparam(":name",$name,pdo::PARAM_STR);
		$stmt->bindparam(":id",$id,pdo::PARAM_STR);
		$stmt->execute();

		$sql="update comments set name=:name where id=:id;";
		$stmt=$pdo->prepare($sql);
		$stmt->bindparam(":name",$name,pdo::PARAM_STR);
		$stmt->bindparam(":id",$id,pdo::PARAM_STR);
		$stmt->execute();

		$_SESSION["name"]=$name;
		header("location:change.php");
		exit();
	}else $error="変更後の名前を入力してください<br/>";
}

$pdo=null;

?>

<form action="namechange.php" method="post">
<p><label>ユーザー名を変更してください:<br/>
<input type="text" name="name" value=<?php echo $_SESSION["name"]; ?>></label>
<input type="submit" name="change" value="変更"></p></form>
<?php echo $error;?>

<form action="change.php" method="post">
<input type="submit" value="戻る"></form>

