<?php
$pdo=new PDO("mysql:host=(�z�X�g��);dbname=(�f�[�^�x�[�X��);charset=utf8","(���[�U�[��)","(�p�X���[�h)"); //�ڑ�
$number=$_GET["number"];
$sql="select data,type from comments where number=$number;";
$result=$pdo->query($sql);
foreach($result as $row){
	$data=$row["data"];
	$type=$row["type"];
}

header("content-type:$type");
echo $data;

$pdo=null;

?>