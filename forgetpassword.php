<?php session_start(); ?>

<head>
<meta http-equiv="content-language" content="ja">
<meta charset="UTF-8">
</head>
<title>(掲示板名)</title>
<h1>(掲示板名)　問い合わせ<br/></h1>
<?php

$error=null;

$pdo=new PDO("mysql:host=(ホスト名);dbname=(データベース名);charset=utf8","(ユーザー名)","(パスワード)"); //接続
$sql="create table if not exists user(id text not null,name text not null,address text not null,password text not null,register int(1) ,avail int(1));";
$pdo->query($sql);

if(isset($_POST["confirm"])){
	if($_POST["address"]!=""){
		if($_POST["id"]!=""){
			$address=$_POST["address"];
			$id=$_POST["id"];
			$sql="select * from user where address=:address;";
			$result=$pdo->prepare($sql);
			$result->bindparam(":address",$address,pdo::PARAM_STR);
			$result->execute();
			foreach($result as $info){
				if($info["id"]==$id){
					$_SESSION["name"]=$info["name"];
					$_SESSION["address"]=$_POST["address"];
					$_SESSION["password"]=$info["password"];
				}
			}
			if(!isset($_SESSION["address"])){
				$error="入力されたメールアドレスは登録されていないか、IDが間違っています<br/>";
			}else {
				header("location:mailpassword.php");
				exit();
			}
		}else $error="ご利用のIDを入力してください<br/>";
	}else $error="登録したメールアドレスを入力してください<br/>";
}

$pdo=null;

?>

登録されているメールアドレスとIDを入力し、「確認」ボタンを押してください。<br/>

<form action="forgetpassword.php" method="post">
<p><label><?php echo "メールアドレス: ";?><input type="text" name="address" style="width:300"></p>
<p><label><?php echo "ユーザーID:　　 ";?><input type="text" name="id"></p>
<input type="submit" name="confirm" value="確認"></form>
<?php echo $error; ?>
<br/>

<form action="forgetid.php" method="post">
<input type="submit" value="ID問い合わせへ" ></form>

<form action="toppage.php" method="post">
<input type="submit" value="トップページに戻る" name="exit"></form>
