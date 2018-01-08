<head>
<meta http-equiv="content-language" content="ja">
<meta charset="UTF-8">
<title>(掲示板名)</title>
</head>
<body>
<h1>(掲示板名)　登録ページ</h1>
<?php

session_start();

if($_SESSION["password"]==""){
	header("location:register.php");
	exit();
}

$pdo=new PDO("mysql:host=(ホスト名);dbname=(データベース名);charset=utf8","(ユーザー名)","(パスワード)"); //接続
$sql="create table if not exists user(id text not null,name text not null,address text not null,password text not null,register int(1) ,avail int(1));";
$pdo->query($sql);

$replace_array=array("\r\n","\n\r","\r","\n");
$username=str_replace($replace_array,"",$_SESSION["username"]);
$address=str_replace($replace_array,"",$_SESSION["address"]);
$password=str_replace($replace_array,"",$_SESSION["password"]);

//echo "$username<br/>$address<br/>$password<br/>";

$sql="select address,avail from user;";
$result=$pdo->query($sql);

foreach($result as $row){
	if($address==$row["address"]&&$row["avail"]==1){
	?>

申し訳ございませんが、入力いただいたメールアドレスは既に登録済みであるため、別のメールアドレスをご利用ください。<br/>
<form action="register.php" method="post">
<input type="submit" value="別のアドレスで登録"></form>

	<?php
	exit();
	}
}

$sql="select id from user;";
$result=$pdo->query($sql);
$flag=false;
do{
	$id=uniqid(rand());
	$id=substr($id,0,6);
	foreach($result as $row){
		if($row[0]==$id)$flag=true;
	}
}while($flag==true);

$sql="insert into user(id,name,address,password,register,avail) values(:id,:name,:address,:password,0,1);";
$insert=$pdo->prepare($sql);
$insert->bindvalue(":id",$id,pdo::PARAM_STR);
$insert->bindvalue(":name",$username,pdo::PARAM_STR);
$insert->bindvalue(":address",$address,pdo::PARAM_STR);
$insert->bindvalue(":password",$password,pdo::PARAM_STR);
$insert->execute();

$url="(URL)/mailconfirm.php?id=$id";

$filename="mailtemplate.txt";
$content=file_get_contents($filename);
$mail=explode("<>",$content);
$message=$username.$mail[0].$url.$mail[1].$id.$mail[2];
$subject="掲示板登録に関して";
$header="From: (管理者メールアドレス) \r\n";
mb_language("Japanese");
mb_internal_encoding("UTF-8");
mb_send_mail($address,$subject,$message,$header);

//$filename="out.txt";
//file_put_contents($filename,$message);


echo "お客様情報の登録が完了となりました。ご入力いただいたメールアドレスに登録確認メールを送信いたしました。<br/>";
echo "そちらに添付されておりますURLに接続いただくことでご利用可能となります。<br/><br/>";

$pdo=null;

?>

続けて別のアカウントを登録される方はこちら<br/>
<form action="register.php" method="post">
<input type="submit" value="新規登録"></form>

</body>