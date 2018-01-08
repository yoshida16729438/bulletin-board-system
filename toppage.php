<head>
<meta http-equiv="content-language" content="ja">
<meta charset="UTF-8">
<title>(掲示板名)</title>
</head>
<body>
<?php

session_start();
$pdo=new PDO("mysql:host=(ホスト名);dbname=(データベース名);charset=utf8","(ユーザー名)","(パスワード)"); //接続
$error1=null;
$error2=null;

if($_POST["login"]){
	if($_POST["id"]!=""){
		if($_POST["loginpassword"]!=""){
			$sql="select id, password, avail,register,name from user;";
			$result=$pdo->query($sql);
			foreach($result as $row){
				if($row["id"]==$_POST["id"]){
					if($row["avail"]==1){
						if($row["register"]==1){
							if($row["password"]==$_POST["loginpassword"]){
								$_SESSION["name"]=$row["name"];
								$_SESSION["id"]=$_POST["id"];
								$_SESSION["password"]=$_POST["loginpassword"];
								setcookie("id",$_POST["id"],time()+7*24*60*60);
								header("location:main.php");
								exit();
							}else $error1="パスワードが間違っています<br/>";
						}else $error1="ご利用のIDは有効化されていません。登録メールをご確認ください<br/>";
					}else $error1="ご利用のIDは利用停止となっております<br/>";
				}
			}
			if($error1==null) $error1="入力されたIDは存在しません<br/>";
		}else $error1="パスワードを入力してください<br/>";
	}else $error1="IDを入力してください<br/>";
}else if($_POST["manage"]){
	if($_POST["managepassword"]!=""){
		$sql="select password from user where id='manager';";
		$result=$pdo->query($sql);
		foreach($result as $row){
			if($row[0]==$_POST["managepassword"]){
				$_SESSION["manage"]=$row[0];
				header("location:management.php");
				exit();
			}else $error2="パスワードが間違っています<br/>";
		}
	}else $error2="パスワードを入力してください<br/>";
}

$pdo=null;

?>
<h1>(掲示板名)<br/></h1>
<h3>(掲示板概要)<br/></h3>

※ご利用には会員登録の上、ログインしていただく必要があります。<br/>

<p>ログインはこちら</p>
<form action="toppage.php" method="post">
<p><label>ID:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<input type="text" name="id" value=<?php if(isset($_COOKIE["id"]))echo $_COOKIE["id"];?>></label></p>
<p><label>パスワード:&nbsp<input type="password" name="loginpassword" ></label></p>
<p><input type="submit" name="login" value="ログイン"></p></form>
<?php echo $error1; ?>
<br/>

使い方は<a href="help.php">こちら</a><br/><br/>

<p>ID・パスワードを忘れた方はこちら</p>
<form action="forgetid.php" method="post">
<input type="submit" value="IDの問い合わせ"></form>

<form action="forgetpassword.php" method="post">
<input type="submit" value="パスワードの問い合わせ"></form>

<p>会員登録はこちら</p>
<form action="register.php" method="post">
<p><input type="submit" value="登録"></p></form>

<br/><br/>
<p>管理者専用入口</p>
<form action="toppage.php" method="post">
<p><label>管理者パスワード:&nbsp<input type="password" name="managepassword"></label>
<input type="submit" name="manage" value="管理者ページへ"></p></form>

<?php echo $error2; ?>
</body>
