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

if($_SESSION["address"]==""){
	header("location:toppage.php");
	exit();
}

$filename="forgetpasswordmail.txt";
$content=file_get_contents($filename);
$mail=explode("<>",$content);
$message=$_SESSION["name"].$mail[0].$_SESSION["password"].$mail[1];
$subject="パスワードのお問い合わせに関して";
$header="From: 管理者メールアドレス \r\n";
mb_language("Japanese");
mb_internal_encoding("UTF-8");
mb_send_mail($_SESSION["address"],$subject,$message,$header);

echo "入力いただいたメールアドレスへ、パスワード確認のメールを送信いたしました<br/>";
unset($_SESSION["address"]);
unset($_SESSION["password"]);
unset($_SESSION["name"]);

$pdo=null;

?>

<form action="toppage.php" method="post">
<input type="submit" value="トップページへ"></form>

