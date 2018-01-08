//アカウント削除

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

if($_SESSION["id"]==""){//URL直接入力による誤動作防止
	header("location:toppage.php");
	exit();
}

if(isset($_POST["yes"])){
	if($_POST["password"]!=""){
		if($_POST["password"]==$_SESSION["password"]){
			$_SESSION["delete"]=true;
			header("location:deleteaccount2.php");
			exit();
		}else $error="パスワードが間違っています<br/>";
	}else $error="パスワードを入力してください<br/>";
}
else if(isset($_POST["no"])){
	header("location:main.php");
	exit();
}

echo "本当にアカウントを消してもよろしいですか？<br/>";
echo "消す場合はパスワードを入力したうえで「はい」を押してください<br/>";
?>

<form action="deleteaccount.php" method="post">
<input type="password" name="password">
<button type="submit" name="yes" style="WIDTH:60px;HEIGHT:25px">はい</button>
<button type="submit" name="no" style="WIDTH:60px;HEIGHT:25px">いいえ</button>
</form>
<?php echo "$error";
