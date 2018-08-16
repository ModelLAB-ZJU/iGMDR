<?php
	require_once("mysql.php");
	$db = new mysql("iGMDR","conn","utf8");
	$db -> query("SELECT * FROM drug_pathway");
	$pathway = array();
	while($t = $db->fetch_array()){
		$tt=explode(",",$t['related_drugs']);
		foreach ($tt as $i){
			$pathway[$i] = $t['pathway_des'];
		}
	}
	$db -> query("SELECT * FROM gene_drug_model_num_sort where gene_id=".$_GET['gene']);
	$drug_pathway = array();
	$n=0;		
	while($t = $db->fetch_array()){
		//if($pathway[$t["drug_id"]] != "undefined"){}
			$drug_pathway[$pathway[$t["drug_id"]]][$n]=0;
			$n++;
		
	}
	$ids=array();
	foreach (array_keys($drug_pathway) as $id){
		$ids[$id] = sizeof(array_keys($drug_pathway[$id]));
	}
	arsort($ids);
	if(sizeof($ids)>9){
		$ids=array_slice($ids,0,10);
	}
	$jsons = '{"labels":["'.join('","',array_keys($ids)).'"],"series":[{"label":"pathway","values":['.join(",",array_values($ids)).']}]}';
	echo $jsons;
?>