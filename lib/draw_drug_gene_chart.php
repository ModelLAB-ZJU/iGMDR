<?php
	require_once("mysql.php");
	$db = new mysql("iGMDR","conn","utf8");
	$project = $_GET['project'];
	$db -> query("SELECT * FROM ncbi_exp where gene_id=".$_GET['gene']." and project_id='".$project."'");
	$tissue_exp = array();
	while($t=$db->fetch_array()){
		$tissue_exp[$t['tissue_type']]=$t['exp'];
	}
	$json = json_encode($tissue_exp);
	echo $json;
?>