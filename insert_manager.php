<!--初期状態では管理者のデータが存在しないので挿入-->

<head>
<meta http-equiv="content-language" content="ja">
<meta charset="UTF-8">
<title>(掲示板名)</title>
</head>
<body>
<?php

$pdo=new PDO("mysql:host=(ホスト名);dbname=(データベース名);charset=utf8","(ユーザー名)","(パスワード)"); //接続

$sql="insert into user(id,name,address,password,register,avail) values('(管理者ID)','(管理者名)','(管理者メールアドレス)','(管理者パスワード)',1,1);";
$pdo->query($sql);

$pdo=null;

?>
</body>
