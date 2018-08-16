<?php
	require_once("mysql.php");
	$db = new mysql("iGMDR","conn","utf8");
	$db -> query("SELECT * FROM model INNER JOIN (SELECT * FROM gene_model where gene_id=".$_GET['gene'].") b on model.model_id = b.model_id");
	$tissue_cancer = array();
	$n=0;		
	while($t = $db->fetch_array()){
		$tissue_cancer[$t['tissue']][$t['cancer']]{$n}=1;
		$n++;
	}
	$tsca = array();
	foreach (array_keys($tissue_cancer) as $tis){
		$tsc['id'] = $tis;
		$tsc['value'] = '';	
		array_push($tsca,json_encode($tsc));
		
		foreach(array_keys($tissue_cancer[$tis]) as $can){
			$tsc['id'] = $tis.'.'.$can;
			$tsc['value'] = sizeof(array_keys($tissue_cancer[$tis][$can]));	
			array_push($tsca,json_encode($tsc));
		}	
	}
	$tscas = '['.join(",",$tsca).']';
	echo $tscas;
?>