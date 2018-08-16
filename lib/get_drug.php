<?php
	require_once("mysql.php");
	$db = new mysql("iGMDR","conn","utf8");
	$db -> query("SELECT * FROM drug_info");
	$drug = array();		
	while($t = $db->fetch_array()){
		$drug[$t["drug_id"]] = $t['standard_name']." (Drug ID: ".$t['drug_id'].")";	
	}
	ksort($drug);
	$json = json_encode($drug);
	echo $json;
?>