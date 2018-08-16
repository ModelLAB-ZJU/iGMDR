<?php
require_once("mysql.php");
$db = new mysql("iGMDR","conn","utf8");
$db -> query("SELECT * FROM model INNER JOIN (SELECT * FROM gene_model where gene_id=".$_GET['gene'].") b on model.model_id = b.model_id");
$model = array();
while($t=$db->fetch_array()){
	array_push($model,json_encode($t));
}
$gene_model = '['.join(",",$model).']';
echo $gene_model;
?>