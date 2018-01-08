<head>
<meta http-equiv="content-language" content="ja">
<meta charset="UTF-8">
<title>(掲示板名)</title>
</head>
<body>

<h1>(掲示板名)　登録ページ<br/></h1>

<?php
session_start();
$username=null;
$address=null;
$error=null;

if($_POST["submit"]){
	if($_POST["username"]!=""){
		if($_POST["address"]!=""){
			if($_POST["password1"]!=""){
				if($_POST["password1"]==$_POST["password2"]){
					$_SESSION["username"]=$_POST["username"];
					$_SESSION["address"]=$_POST["address"];
					$_SESSION["password"]=$_POST["password1"];
					header("location:registerconfirm.php");
					exit();
				}else $error="パスワードが一致していません<br/>";
			}else $error="パスワードが入力されていません<br/>";
		}else $error="メールアドレスが入力されていません<br/>";
	}else $error="ユーザー名が入力されていません<br/>";
}

?>

この度は、当掲示板へのご登録ありがとうございます。<br/>
登録には、ユーザー名とパスワード、認証のためにメールアドレスが必須となります。<br/>
また、登録の際には、お客様固有のIDを発行いたします。<br/>
ログインに必要となるため、必ずお控えください。<br/>
ユーザー名とパスワードは後から変更可能です。<br/><br/>

登録をおやめになる場合はこちらからお戻りください。
<form action="toppage.php" method="post">
<p><input type="submit" value="トップページに戻る" name="back"></p></form>

<br/>
ご登録の方はこちらに必要事項の記入をお願いいたします。<br/>
<form action="register.php" method="post">
<p><label><?php echo "メールアドレス: ";?><input type="text" name="address" value="<?php echo $address ?>" style="width:300"></label></p>
<p><label><?php echo "ユーザー名:　　 ";?><input type="text" name="username" value="<?php echo $username ?>"></label></p>
<p><label><?php echo "パスワード:　　 ";?><input type="password" name="password1"></label></p>
確認のためもう一度パスワードの入力をお願いいたします。<br/>
<p><label><?php echo "パスワード:　　 ";?><input type="password" name="password2"></label></p>
※パスワードは英数字のみ使用可能です。また、ユーザー名とパスワードは後から変更できます。<br/>
※また、入力が不足していたり、パスワードが一致していない場合は次に進めませんのでよくご確認ください。<br/>
全て入力できましたらこちらから進んでください。<br/>
<?php echo $error; ?>
<p><input type="submit" name="submit" value="確認" ></form></p>

</body>