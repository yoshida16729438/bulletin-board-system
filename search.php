<head>
<meta http-equiv="content-language" content="ja">
<meta charset="UTF-8">
</head>

<title>(掲示板名)</title>
<h1>(掲示板名)　検索ページ<br/></h1>

<?php 
session_start();
$error=null;

if(isset($_POST["search"])){
	if($_POST["column"]!=""){
		if($_POST["word"]!=""){
			$_SESSION["column"]=$_POST["column"];
			$_SESSION["word"]=$_POST["word"];
		}else $error="検索ワードを入力してください<br/>";
	}else $error="検索する項目を選択してください</br>";
}

$column=$_SESSION["column"];
$word=$_SESSION["word"];

if($_SESSION["word"]==""){
	header("location:main.php");
	exit();
}

if(isset($_POST["back"])){
	unset($_SESSION["column"]);
	header("location:main.php");
	exit();
}

echo $error;

?>

<form action="search.php" method="post">
<p><label>検索ワード:<input type="text" name="word" value=<?php echo $word; ?>></label></p>
<p><label><input type="radio" name="column" value="name" <?php if($column=="name")echo'checked="checked"'; ?>>ユーザー名</label>
<label><input type="radio" name="column" value="comment" <?php if($column=="comment")echo'checked="checked"'; ?>>コメント</label>
<label><input type="radio" name="column" value="id" <?php if($column=="id")echo'checked="checked"'; ?>>ID</label></p>
<p><input type="submit" name="search" value="検索"></form>

<form action="search.php" method="post">
<input type="submit" name="back" value="戻る"></form>
<hr><br/>

<?php

$pdo=new PDO("mysql:host=(ホスト名);dbname=(データベース名);charset=utf8","(ユーザー名)","(パスワード)"); //接続

$sql="select * from comments where $column like '%$word%' order by number;";
$result=$pdo->query($sql);
$count1=count($result);
$count2=$count1;

foreach($result as $out){
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
	$count2++;
}

if($count2==$count1)echo "検索結果は存在しません<br/>";

$pdo=null;

?>

