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

$password=$_SESSION["password"];
$id=$_SESSION["id"];
$error=null;

if($id==""){
	header("location:change.php");
	exit();
}

if(isset($_POST["check"])){
	if($_POST["password"]!=""){
		if($_POST["password"]==$password){
			$_SESSION["check"]=true;
			header("location:passchange.php");
			exit();
		}else $error="パスワードが間違っています<br/>";
	}else $error="パスワードを入力してください<br/>";
}

?>

パスワード変更には現在のパスワードの確認が必要です<br/>
<form action="passchangecheck.php" method="post">
<p><label>現在のパスワード:&nbsp&nbsp<input type="password" name="password"></label>
<input type="submit" value="確認" name="check"></p></form>
<?php echo $error; ?>

<br/>

<form action="change.php" method="post">
<input type="submit" value="戻る"></form>