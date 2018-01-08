<!--管理者専用ページ-->

<head>
<meta http-equiv="content-language" content="ja">
<meta charset="UTF-8">
<title>(掲示板名)</title>
</head>
<body>
<?php session_start(); ?>
<h1>(掲示板名)　管理ページ<br/></h1>
<?php

if($_SESSION["manage"]==""){//URL直接入力による誤動作防止
	header("location:toppage.php");
	exit();
}

if(isset($_POST["exit"])){//ログアウト処理
	unset($_SESSION["manage"]);
	header("location:toppage.php");
	exit();
}

$pdo=new PDO("mysql:host=(ホスト名);dbname=(データベース名);charset=utf8","(ユーザー名)","(パスワード)"); //接続
$sql="create table if not exists comments(id text not null,number int not null auto_increment primary key,name text,comment text default null,time text,data mediumblob default null,width int default null,height int default null,type text default null,filename text default null);"; //テーブル作成
$pdo->query($sql);

$message1=null;
if(isset($_POST["delete"])){//削除機能
	if($_POST["dnum"]!=""){
		$delete=$_POST["dnum"];
		$sql="select number from comments;";
		$result=$pdo->query($sql);
		foreach($result as $row){
			if($row["number"]==$delete){
				$exist=true;
				if($_POST["password"]!=""){
					if($_SESSION["manage"]==$_POST["password"]){
						$sql="delete from comments where number=$delete;";
						$pdo->query($sql);
						$message1="投稿".$delete."を削除しました<br/>";
					}else $message1="パスワードが間違っています<br/>";
				}else $message1="管理者パスワードを入力してください<br/>";
			}
		}
		if($exist!=true) $message1="投稿番号".$delete."の投稿は存在しません<br/>";
	}else $message1="削除したい投稿の番号を入力してください<br/>";
}

$message2=null;
if(isset($_POST["freeze"])){//アカウント凍結
	if($_POST["fid"]!=""){
		$fid=$_POST["fid"];
		$sql="select id from user;";
		$result=$pdo->query($sql);
		foreach($result as $row){
			if($row["id"]==$fid){
				$exist=true;
				if($_POST["password"]!=""){
					if($_SESSION["manage"]==$_POST["password"]){
						$sql="update user set avail=0 where id=$fid;";
						$pdo->query($sql);
						$message2="アカウント".$fid."を凍結しました<br/>";
					}else $message2="パスワードが間違っています<br/>";
				}else $message2="管理者パスワードを入力してください<br/>";
			}
		}
		if($exist!=true) $message2="アカウント".$fid."は存在しません<br/>";
	}else $message2="凍結するアカウントのIDを入力してください<br/>";
}

$message3=null;
if(isset($_POST["unfreeze"])){//アカウント凍結
	if($_POST["unfid"]!=""){
		$unfid=$_POST["unfid"];
		$sql="select id from user;";
		$result=$pdo->query($sql);
		foreach($result as $row){
			if($row["id"]==$unfid){
				$exist=true;
				if($_POST["password"]!=""){
					if($_SESSION["manage"]==$_POST["password"]){
						$sql="update user set avail=1 where id=$unfid;";
						$pdo->query($sql);
						$message3="アカウント".$unfid."を凍結解除しました<br/>";
					}else $message3="パスワードが間違っています<br/>";
				}else $message3="管理者パスワードを入力してください<br/>";
			}
		}
		if($exist!=true) $message3="アカウント".$unfid."は存在しません<br/>";
	}else $message3="凍結解除するアカウントのIDを入力してください<br/>";
}

$message4=null;
if(isset($_POST["reset"])){//掲示板リセット
	if($_POST["password"]!=""){
		if($_POST["password"]==$_SESSION["manage"]){
			$sql="drop table comments;";
			$pdo->query($sql);
			$sql="create table comments(id text not null,number int not null auto_increment primary key,name text,comment text default null,time text,data mediumblob default null,width int default null,height int default null,type text default null,filename text default null);"; //テーブル作成
			$pdo->query($sql);
			$message4="掲示板をリセットしました<br/>";
		}else $message4="パスワードが間違っています<br/>";
	}else $message4="管理者パスワードを入力してください<br/>";

}

?>

<form action="management.php" method="post">
<p><input type="submit" name="exit" value="退出"></p></form>

<p>投稿を削除<br>
<form action="management.php" method="post" />
<label>削除したい番号を入力:<input type="text" name="dnum" size=5 /></label><!--削除-->
<br/><label>管理者パスワードを入力:<input type="password" name="password" /></label>
<br/><input type="submit" name="delete" value="削除"></p></form>
<?php echo $message1;?>

<p>アカウントを凍結<br/>
<form action="management.php" method="post" />
<label>凍結したいIDを入力:<input type="text" name="fid" size=5 /></label><!--削除-->
<br/><label>管理者パスワードを入力:<input type="password" name="password" /></label>
<br/><input type="submit" name="freeze" value="凍結"></p></form>
<?php echo $message2;?>

<p>凍結を解除<br/>
<form action="management.php" method="post" />
<label>凍結解除したいIDを入力:<input type="text" name="unfid" size=5 /></label><!--削除-->
<br/><label>管理者パスワードを入力:<input type="password" name="password" /></label>
<br/><input type="submit" name="unfreeze" value="解除"></p></form>
<?php echo $message3;?>

<p>掲示板をリセット<br/>
<form action="management.php" method="post" />
<label>管理者パスワードを入力:<input type="password" name="password" /></label>
<br/><input type="submit" name="reset" value="リセット"></p></form>
<?php echo $message4;?>
<hr>

<?php
echo "アカウント一覧<br/>";
$sql="select * from user;";
$account=$pdo->query($sql);
foreach($account as $re){
	echo"name=".$re["name"]."<br/>";
	echo"id=".$re["id"]."<br/>";
	echo"address=".$re["address"]."<br/>";
	echo"register=".$re["register"]."<br/>";
	echo"avail=".$re["avail"]."<br/>";
	echo "<br/>";
}
echo "<hr>";

echo "投稿一覧<br/>";
$sql="select * from comments order by number;";//全データ取得
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

$pdo=null;

?>
