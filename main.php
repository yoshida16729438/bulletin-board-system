<?php session_start(); ?>

<head>
<meta http-equiv="content-language" content="ja">
<meta charset="UTF-8">
</head>
<title>(掲示板名)</title>
<h1>(掲示板名)　メインページ<br/></h1>
<?php

$adderror=null;
$deleteerror=null;
$editerror=null;
$searcherror=null;

$pdo=new PDO("mysql:host=(ホスト名);dbname=(データベース名);charset=utf8","(ユーザー名)","(パスワード)"); //接続
$sql="create table if not exists comments(id text not null,number int not null auto_increment primary key,name text,comment text default null,time text,data mediumblob default null,width int default null,height int default null,type text default null,filename text default null);"; //テーブル作成
$pdo->query($sql);

if($_SESSION["password"]==""||$_SESSION["id"]==""){
	header("location:toppage.php");
	exit();
}

if(isset($_POST["exit"])){
	unset($_SESSION["password"]);
	unset($_SESSION["id"]);
	unset($_SESSION["name"]);
	header("location:toppage.php");
	exit();
}

if(isset($_POST["search"])){
	if($_POST["column"]!=""){
		if($_POST["word"]!=""){
			$_SESSION["column"]=$_POST["column"];
			$_SESSION["word"]=$_POST["word"];
			header("location:search.php");
			exit();
		}else $searcherror="検索するワードを入力してください<br/>";
	}else $searcherror="検索する項目を指定してください<br/>";
}

if(isset($_POST["change"])){
	header("location:change.php");
	exit();
}

if(isset($_POST["help"])){
	header("location:help.php");
	exit();
}

if(isset($_POST["deleteaccount"])){
	header("location:deleteaccount.php");
	exit();
}

if(isset($_POST["delete"])){
	if($_POST["dnum"]!=""){
		$delete=$_POST["dnum"];
		$sql="select number,id from comments ;";
		$result=$pdo->query($sql);
		foreach($result as $row){
			if($row["number"]==$delete){
				$exist=true;
				if($_SESSION["id"]==$row["id"]){
					$_SESSION["dnum"]=$delete;
					header("location:delete.php");
					exit();
				}else $deleteerror="他のユーザーの投稿は削除できません<br/>";
			}
		}
		if($exist!=true) $deleteerror="投稿番号".$delete."の投稿は存在しません<br/>";
	}else $deleteerror="削除したい投稿の番号を入力してください<br/>";
}

if(isset($_POST["edit"])){
	if($_POST["enum"]!=""){
		$edit=$_POST["enum"];
		$sql="select number,id from comments ;";
		$result=$pdo->query($sql);
		foreach($result as $row){
			if($row["number"]==$edit){
				$exist=true;
				if($_SESSION["id"]==$row["id"]){
					$_SESSION["enum"]=$_POST["enum"];
					header("location:edit.php");
					exit();
				}else $editerror="他のユーザーの投稿は編集できません<br/>";
			}
		}
		if($exist!=true) $editerror="投稿番号".$edit."の投稿は存在しません<br/>";
	}else $editerror="編集したい投稿の番号を入力してください<br/>";
}

?>

<form action="main.php" method="post">
<button type="submit" name="exit" style="WIDTH100px;HEIGHT:25px">ログアウト</button>

<button type="submit" name="change" style="WIDTH:80px;HEIGHT:25px">登録変更</button>

<button type="submit" name="deleteaccount" style="WIDTH:80px;HEIGHT:25px">退会</button>

<button type="submit" name="help" style="WIDTH:80px;HEIGHT:25px">ヘルプ</button></form>

<?php

if(isset($_POST["add"])){//追加
	if($_POST["comment"]==""&&$_FILES["userfile"]["size"]==null) $adderror="コメントを入力またはファイルを選択してください</br>";
	else{
		$name=$_POST["name"];
		$comment=$_POST["comment"];
		$time=date("Y/n/j G:i:s");//年/月/日 時:分:秒
		$id=$_SESSION["id"];
		$type=$_FILES["userfile"]["type"];
		if($type=="image/png"||$type=="image/jpg"||$type=="image/jpeg"||$type=="image/gif"){
			$size=getimagesize($_FILES["userfile"]["tmp_name"]);
			$width=$size[0];
			$height=$size[1];
			if($height>400||$width>600){
				$v1=$height/400;
				$v2=$width/600;
				if($v1>=$v2){
					$width=$width/$v1;
					$height=$height/$v1;
				}else{
					$width=$width/$v2;
					$height=$height/$v2;
				}
			}

			$sql="insert into comments(name,comment,id,time,type,width,height,data,filename) values(:name,:comment,:id,:time,:type,:width,:height,:data,:filename);";
			$insert=$pdo->prepare($sql);
			$insert->bindparam(":name",$name,pdo::PARAM_STR);
			$insert->bindparam(":comment",$comment,pdo::PARAM_STR);
			$insert->bindparam(":id",$id,pdo::PARAM_STR);
			$insert->bindparam(":time",$time,pdo::PARAM_STR);
			$insert->bindparam(":type",$type,pdo::PARAM_STR);
			$insert->bindparam(":width",$width,pdo::PARAM_STR);
			$insert->bindparam(":height",$height,pdo::PARAM_STR);
			$insert->bindparam(":data",file_get_contents($_FILES["userfile"]["tmp_name"]),pdo::PARAM_STR);
			$insert->bindparam(":filename",$_FILES["userfile"]["name"],pdo::PARAM_STR);
			$insert->execute();
		}
		else if($type=="video/mp4"){
			$sql="insert into comments(name,comment,id,time,data,type,filename) values(:name,:comment,:id,:time,:data,:type,:filename);";
			$insert=$pdo->prepare($sql);
			$insert->bindparam(":name",$name,pdo::PARAM_STR);
			$insert->bindparam(":comment",$comment,pdo::PARAM_STR);
			$insert->bindparam(":id",$id,pdo::PARAM_STR);
			$insert->bindparam(":time",$time,pdo::PARAM_STR);
			$insert->bindparam(":data",file_get_contents($_FILES["userfile"]["tmp_name"]),pdo::PARAM_STR);
			$insert->bindparam(":type",$type,pdo::PARAM_STR);
			$insert->bindparam(":filename",$_FILES["userfile"]["filename"],pdo::PARAM_STR);
			$insert->execute();
		}
		else{
			$sql="insert into comments(name,comment,id,time) values(:name,:comment,:id,:time);";
			$insert=$pdo->prepare($sql);
			$insert->bindparam(":name",$name,pdo::PARAM_STR);
			$insert->bindparam(":comment",$comment,pdo::PARAM_STR);
			$insert->bindparam(":id",$id,pdo::PARAM_STR);
			$insert->bindparam(":time",$time,pdo::PARAM_STR);
			$insert->execute();
		}
	}
}

?>

<form action="main.php" method="post" enctype="multipart/form-data">
ユーザー名:<input type="text" value=<?php echo $_SESSION["name"]; ?> name="name" readonly><br/>
<label>コメント:<br/><textarea name="comment" rows=5 cols=30 /></textarea></label><br/>
<input type="hidden" name="MAX_FILE_SIZE" value="5000000">
<input type="file" name="userfile" accept="image/jpg,image/gif,image/png,image/jpeg,video/mp4">

&nbsp;<input type="submit" name="add" value="投稿">&nbsp&nbsp&nbsp<?php echo $adderror;?></form> <!--投稿ボタン-->

<form action="main.php" method="post" />
<p><label>削除したい番号を入力:<input type="text" name="dnum" size=5 /></label><!--削除-->
&nbsp;<input type="submit" name="delete" value="削除">&nbsp&nbsp&nbsp<?php echo $deleteerror;?></p></form>

<form action="main.php" method="post" />
<p><label>編集したい番号を入力:<input type="text" name="enum" size=5 /></label><!--編集-->
&nbsp;<input type="submit" name="edit" value="編集">&nbsp&nbsp&nbsp<?php echo $editerror;?></p></form>

<form action="main.php" method="post">
<p><label>検索ワード:<input type="text" name="word"></label></p>
<p><label><input type="radio" name="column" value="name">ユーザー名</label>
<label><input type="radio" name="column" value="comment">コメント</label>
<label><input type="radio" name="column" value="id">ID</label>
&nbsp&nbsp&nbsp<input type="submit" name="search" value="検索">&nbsp&nbsp&nbsp<?php echo $searcherror;?></form>

<hr>
<?php
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