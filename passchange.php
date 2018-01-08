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
$error=null;

if($id==""){
	header("location:change.php");
	exit();
}

if(isset($_POST["exit"]))unset($_SESSION["check"]);

if($_SESSION["check"]!=true){
	header("location:change.php");
	exit();
}

if(isset($_POST["change"])){
	if($_POST["password1"]!=""){
		if($_POST["password2"]!=""){
			if($_POST["password1"]==$_POST["password2"]){
				$password=$_POST["password1"];
				$sql="update user set password=:password where id=:id;";
				$stmt=$pdo->prepare($sql);
				$stmt->bindparam(":password",$password,pdo::PARAM_STR);
				$stmt->bindparam(":id",$id,pdo::PARAM_STR);
				$stmt->execute();
				unset($_SESSION["check"]);
				$_SESSION["password"]=$password;
				header("location:passchange.php");
				exit();
			}else $error="パスワードが一致していません<br/>";
		}else $error="確認用パスワードを入力してください<br/>";
	}else $error="パスワードを入力してください<br/>";
}

$pdo=null;

?>

設定したいパスワードを入力してください<br/>
<form action="passchange.php" method="post">
<p><label>パスワード:&nbsp&nbsp<input type="password" name="password1"></label></p>
確認のためもう一度同じパスワードを入力してください<br/>
<p><label>パスワード:&nbsp&nbsp<input type="password" name="password2"></label></p>

<input type="submit" value="変更" name="change"></p></form>

<?php echo $error;?>
<br/>
<form action="passchange.php" method="post">
<input type="submit" value="戻る" name="exit"></form>