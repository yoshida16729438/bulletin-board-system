<?php
session_start();
?>
<head>
<meta http-equiv="content-language" content="ja">
<meta charset="UTF-8">
</head>

<title>(掲示板名)</title>
<h1>(掲示板名)　投稿削除ページ<br/></h1>

<?php

$pdo=new PDO("mysql:host=(ホスト名);dbname=(データベース名);charset=utf8","(ユーザー名)","(パスワード)"); //接続

$dnum=$_SESSION["dnum"];

if($dnum==""){
	header("location:main.php");
	exit();
}

if(isset($_POST["cancel"])){
	unset($_SESSION["dnum"]);
	header("location:main.php");
	exit();
}

if(isset($_POST["delete"])){
	$sql="delete from comments where number=$dnum";
	$pdo->query($sql);
	unset($_SESSION["dnum"]);
	header("location:main.php");
	exit();
}

$sql="select * from comments where number=$dnum;";//全データ取得
$all=$pdo->query($sql);
foreach($all as $out){
	$number=$out["number"];
	$type=$out["type"];
	echo "投稿".$out["number"]."　ID:".$out["id"]."　ユーザー名:".$out["name"]."　投稿時刻:".$out["time"]."<br/>".$out["comment"]."<br/>";
	?>

	<a href=<?php echo "viewimgvideo.php?number=$number";?> target="_blank">

	<?php
	if($type=="image/jpg"||$type=="image/jpeg"||$type=="image/png"||$type=="image/gif"){
		?>
		<img src=<?php echo "viewimgvideo.php?number=$number";?> width=<?php echo $out["width"];?> height=<?php echo $out["height"];?> alt=<?php echo $out["filename"];?>>
		<?php
	}
	else if($type=="video/mp4"){
		?>
		<video controls type="video/mp4" width=600 height=400>
		<source src=<?php echo "viewimgvideo.php?number=$number";?>>
		<p>動画を再生するにはvideoタグをサポートしたブラウザを使用する必要があります。</p>
		</video>
		<?php
	}
	?>
	</a>
	<?php
	echo "<br/><br/>";
}

echo "本当にこの投稿を削除しますか？<br/>";

$pdo=null;

?>

<form action="delete.php" method="post" />
<button type="submit" name="delete" style="WIDTH:100px;HEIGHT:25px">削除</button>
<button type="submit" name="cancel" style="WIDTH:100px;HEIGHT:25px">キャンセル</button></form>

<?php
$pdo=null;
?>