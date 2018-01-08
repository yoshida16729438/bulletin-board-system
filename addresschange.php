//登録されているメールアドレス変更のページです
//main→change→addresschangeのように移動します

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

if(isset($_POST["exit"]))unset($_SESSION["address"]);

if($_SESSION["address"]==""){//URL直接入力による誤動作の防止
	header("location:change.php");
	exit();
}

$error=null;
if($_POST["change"]){
	if($_POST["address"]!=""){
		$address=$_POST["address"];
		$sql="select address from user;";
		$result=$pdo->query($sql);
		$exist=false;
		foreach($result as $row){
			if($row["address"]==$address)$exist=true;
		}
		if($exist==false){
			$sql="update user set address=:address where id=:id;";
			$stmt=$pdo->prepare($sql);
			$stmt->bindparam(":address",$address,pdo::PARAM_STR);
			$stmt->bindparam(":id",$id,pdo::PARAM_STR);
			$stmt->execute();
			unset($_SESSION["address"]);
			header("location:change.php");
			exit();
		}else $error="既に登録されているメールアドレスです<br/>";
	}else $error="変更後のメールアドレスを入力してください<br/>";
}

$pdo=null;

?>

<form action="addresschange.php" method="post">
<p><label>メールアドレスを変更してください:<br/>
<input type="text" name="address" value=<?php echo $_SESSION["address"]; ?> style="width:300"></label>
<input type="submit" name="change" value="変更"></p></form>
<?php echo $error;?>

<form action="addresschange.php" method="post">
<input type="submit" name="exit" value="戻る"></form>

