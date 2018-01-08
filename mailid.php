<!--ID問い合わせ成功時にメールにIDを添付して送信するページ
メール本文テンプレ―トはforgetidmail.txt-->

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

if($_SESSION["address"]==""){//URL直接入力による誤動作防止
	header("location:toppage.php");
	exit();
}

$filename="forgetidmail.txt";
$content=file_get_contents($filename);
$mail=explode("<>",$content);
$message=$_SESSION["name"].$mail[0].$_SESSION["id"].$mail[1];
$subject="ユーザーIDのお問い合わせに関して";
$header="From: (管理者メールアドレス) \r\n";
mb_language("Japanese");
mb_internal_encoding("UTF-8");
mb_send_mail($_SESSION["address"],$subject,$message,$header);

echo "入力いただいたメールアドレスへ、ID確認のメールを送信いたしました<br/>";
unset($_SESSION["address"]);
unset($_SESSION["id"]);
unset($_SESSION["name"]);

$pdo=null;

?>

続けてパスワードの問い合わせをされる方はこちら<br/>
<form action="forgetpassword.php" method="post">
<input type="submit" value="パスワード問い合わせ" ></form>

<form action="toppage.php" method="post">
<input type="submit" value="トップページへ"></form>

