<!--投稿の編集-->

<?php
session_start();
?>
<head>
<meta http-equiv="content-language" content="ja">
<meta charset="UTF-8">
</head>

<title>(掲示板名)</title>
<h1>(掲示板名)　投稿編集ページ<br/></h1>

<?php

$pdo=new PDO("mysql:host=(ホスト名);dbname=(データベース名);charset=utf8","(ユーザー名)","(パスワード)"); //接続

$enum=$_SESSION["enum"];
$error=null;

if($enum==""){//URL直接入力による誤動作防止
	header("location:main.php");
	exit();
}

if(isset($_POST["edit"])){
	if($_POST["comment"]!=""||$_POST["media"]!="delete"){//コメントもしくはメディアのいずれかが入力されているか
		$time=date("Y/n/j G:i:s");//年/月/日 時:分:秒
		$comment=$_POST["comment"];
		switch($_POST["media"]){
			case "change":
				$type=$_FILES["userfile"]["type"];
				if($type=="image/png"||$type=="image/jpg"||$type=="image/jpeg"||$type=="image/gif"){//画像を挿入するとき
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
					$sql="update comments set comment=:comment,time=:time,data=:data,width=:width,height=:height,type=:type,filename=:filename where number=:number;";
					$stmt=$pdo->prepare($sql);
					$stmt->bindvalue(":comment",$comment,pdo::PARAM_STR);
					$stmt->bindvalue(":time",$time,pdo::PARAM_STR);
					$stmt->bindvalue(":data",file_get_contents($_FILES["userfile"]["tmp_name"]),pdo::PARAM_STR);
					$stmt->bindvalue(":width",$width,pdo::PARAM_INT);
					$stmt->bindvalue(":height",$height,pdo::PARAM_INT);
					$stmt->bindvalue(":type",$type,pdo::PARAM_STR);
					$stmt->bindvalue(":filename",$_FILES["userfile"]["name"],pdo::PARAM_STR);

					$stmt->bindvalue(":number",$enum,pdo::PARAM_STR);
					$stmt->execute();
					unset($_SESSION["enum"]);
					header("location:main.php");
					exit();

				}
				else if($type=="video/mp4"){//動画を挿入するとき
					$sql="update comments set comment=:comment,time=:time,data=:data,type=:type,filename=:filename where number=:number;";
					$stmt=$pdo->prepare($sql);
					$stmt->bindvalue(":comment",$comment,pdo::PARAM_STR);
					$stmt->bindvalue(":time",$time,pdo::PARAM_STR);
					$stmt->bindvalue(":data",file_get_contents($_FILES["userfile"]["tmp_name"]),pdo::PARAM_STR);
					$stmt->bindvalue(":type",$type,pdo::PARAM_STR);
					$stmt->bindvalue(":filename",$_FILES["userfile"]["name"],pdo::PARAM_STR);
					
					$stmt->bindvalue(":number",$enum,pdo::PARAM_STR);
					$stmt->execute();
					unset($_SESSION["enum"]);
					header("location:main.php");
					exit();

				}
				else $error="ファイルを選択してください<br/>";
				break;

			case "nothing"://メディアに変更がない場合
				$sql="update comments set comment=:comment,time=:time where number=:number;";
				$stmt=$pdo->prepare($sql);
				$stmt->bindvalue(":comment",$comment,pdo::PARAM_STR);
				$stmt->bindvalue(":time",$time,pdo::PARAM_STR);
				$stmt->bindvalue(":number",$enum,pdo::PARAM_STR);
				$stmt->execute();
				unset($_SESSION["enum"]);
				header("location:main.php");
				exit();
				break;

			case "delete"://メディア削除の場合
				$sql="update comments set comment=:comment,time=:time,data=:data,width=:width,height=:height,type=:type,filename=:filename where number=:number;";
				$stmt=$pdo->prepare($sql);
				$stmt->bindvalue(":comment",$comment,pdo::PARAM_STR);
				$stmt->bindvalue(":time",$time,pdo::PARAM_STR);
				$stmt->bindvalue(":data",null,pdo::PARAM_STR);
				$stmt->bindvalue(":width",null,pdo::PARAM_INT);
				$stmt->bindvalue(":height",null,pdo::PARAM_INT);
				$stmt->bindvalue(":type",null,pdo::PARAM_STR);
				$stmt->bindvalue(":filename",null,pdo::PARAM_STR);

				$stmt->bindvalue(":number",$enum,pdo::PARAM_STR);
				$stmt->execute();
				unset($_SESSION["enum"]);
				header("location:main.php");
				exit();
				break;

		}
	}else $error="画像・動画またはコメントの少なくとも1つが必要です<br/>";
}

if(isset($_POST["cancel"])){
	unset($_SESSION["enum"]);
	header("location:main.php");
	exit();
}

echo "投稿を編集してください<br/>画像・動画に関しては「変更する」「変更しない」「削除する」から選択し、変更する場合はファイルを選択してください<br/>「変更する」を選択していない場合、ファイルが選択されていても変更は行われません<br/><br/>";
$sql="select * from comments where number=$enum;";
$result=$pdo->query($sql);
foreach($result as $out){
	$number=$out["number"];
	$type=$out["type"];
	$comment=$out["comment"];
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

?>

<form action="edit.php" method="post" enctype="multipart/form-data">
<p>ユーザー名:<input type="text" value=<?php echo $_SESSION["name"]; ?> readonly><br/>
<label>コメント:<br/><textarea name="comment" rows=5 cols=30 /><?php echo $comment; ?></textarea></label></p><!--コメント-->
動画/画像:<label><input type="radio" name="media" value="delete">削除する</label>&nbsp&nbsp
<label><input type="radio" name="media" value="nothing" checked="checked">変更しない</label>&nbsp&nbsp
<label><input type="radio" name="media" value="change">変更する</label>&nbsp&nbsp
<input type="hidden" name="MAX_FILE_SIZE" value="5000000">
<input type="file" name="userfile" accept="image/jpg,image/gif,image/png,image/jpeg,video/mp4">

<p><button type="submit" name="edit" style="WIDTH:100px;HEIGHT:25px">編集</button><!--編集確定-->
<button type="submit" name="cancel" style="WIDTH:100px;HEIGHT:25px">キャンセル</button></p></form> <!--キャンセルボタン-->

<?php
echo $error;
$pdo=null;
?>
