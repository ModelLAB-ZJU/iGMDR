<?php
require_once("mysql.php");
$db = new mysql("iGMDR","conn","utf8");
$db -> query("SELECT * FROM model INNER JOIN (SELECT * FROM drug_model where drug_id=".$_GET['drug'].") b on model.model_id = b.model_id");
$model = array();
while($t=$db->fetch_array()){
	array_push($model,json_encode($t));
}
$drug_model = '['.join(",",$model).']';
echo $drug_model;
?>