<head>
<meta http-equiv="content-language" content="ja">
<meta charset="UTF-8">
<?php session_start();?>
<title>(掲示板名)</title>
</head>
<body>
<h1>(掲示板名)　登録ページ<br/></h1>

<?php

if($_SESSION["password"]==""){
	header("location:register.php");
	exit();
}

$username=$_SESSION["username"];
$address=$_SESSION["address"];
$password=$_SESSION["password"];
$aster=mb_strlen($password);
$viewpass="";
for($i=0;$i<$aster;$i++)$viewpass=$viewpass."*";
echo "以下の内容で登録してもよろしいですか？<br/><br/>";
echo "メールアドレス: $address<br/><br/>";
echo "ユーザー名:　　 $username<br/><br/>";
echo "パスワード:　　 $viewpass<br/><br/>";
?>

よろしければ「登録」ボタンを、登録内容を変更したい場合は「変更」ボタンを押してください。<br/>

<form action="sendmail.php" method="post">
<input type="submit" value="登録"></form>

<form action="register.php" method="post">
<input type="submit" value="変更"></form>

</body>
