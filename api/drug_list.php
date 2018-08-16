<?php 
  require_once("../lib/mysql.php");
  $db = new mysql("iGMDR","conn","utf8");
  $db -> query("SELECT * FROM drug_id");
  $model = array();
  while($t=$db->fetch_array()){
	$m=array();
	$m['drug_name']=$t['drug'];
	$m['drug_id']=$t['drug_id'];
	array_push($model,$m);
  }
  echo json_encode($model,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
?>          