<?php 
  require_once("../lib/mysql.php");
  $db = new mysql("iGMDR","conn","utf8");
  $db -> query("SELECT * FROM gene_id");
  $model = array();
  while($t=$db->fetch_array()){
	$m=array();
	$m['gene_symbol']=$t['gene'];
	$m['gene_id']=$t['gene_id'];
	array_push($model,$m);
  }
  echo json_encode($model,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
?>          