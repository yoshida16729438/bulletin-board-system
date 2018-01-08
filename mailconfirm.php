<head>
<meta http-equiv="content-language" content="ja">
<meta charset="UTF-8">
<title>(掲示板名)　登録完了</title>
</head>
<body>
<h1>(掲示板名)　登録ページ</h1>

<?php

session_start();

$pdo=new PDO("mysql:host=(ホスト名);dbname=(データベース名);charset=utf8","(ユーザー名)","(パスワード)"); //接続

$id=$_GET["id"];
$_SESSION["id"]=$id;

$sql="update user set register=1 where id=$id;";
$pdo->query($sql);

echo "アカウントが有効化されました。トップページからログインしてご利用ください。<br/>";

$pdo=null;

?>

<form action="toppage.php" method="post">
<input type="submit" value="トップページへ"></form>

</body>
