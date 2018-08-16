<?php
	require_once("mysql.php");
	$db = new mysql("iGMDR","conn","utf8");
	$db -> query("SELECT * FROM gene_info");
	$gene = array();		
	while($t = $db->fetch_array()){
		if($t['key'] == "symbol"){
			$gene[$t["gene_id"]] = $t['value']." (Gene ID: ".$t['gene_id'].")";
		}
		
	}
	ksort($gene);
	$json = json_encode($gene);
	echo $json;
?>