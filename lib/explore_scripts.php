<?php
ini_set('memory_limit', '128000M');
	set_time_limit(0);
	require_once("mysql.php");
	$db = new mysql("iGMDR","conn","utf8");
	
	$model = array();
	if(isset($_GET['cancer']) || isset($_GET['tissue'])){
		if(isset($_GET['cancer'])){
			$db -> query("SELECT * FROM model where cancer='".$_GET['cancer']."'");
		}
		if(isset($_GET['tissue'])){
			$db -> query("SELECT * FROM model where tissue='".$_GET['tissue']."'");
		}
	}
	else{
		$db -> query("SELECT * FROM model");
	}
	while($t=$db->fetch_array()){
		array_push($model,json_encode($t));
	}
	$js = '['.join(",",$model).']';
	echo $js;
	
?>