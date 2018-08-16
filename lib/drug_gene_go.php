<?php
	require_once("mysql.php");
	$db = new mysql("iGMDR","conn","utf8");
	$dbi = $db;
	$db -> query("SELECT * FROM drug_gene where drug_id='".$_GET['drug']."'");
	$go_enrichment = array();
	$gene = array();
	while($t = $db->fetch_array()){
		array_push($gene,$t['gene_id']);
	}
	foreach($gene as $g){
		
		$dbi -> query("SELECT * FROM gene_info where gene_id=".$g);
		
		while($ti = $dbi->fetch_array()){
			if(preg_match("#GO:(\w+)#",$ti['key'],$go)){
				$des = explode("; ",$ti['value']);
				if(array_key_exists($go[1].": ".$des[2],$go_enrichment)){
					$go_enrichment[$go[1].": ".$des[2]] += 1;
				}
				else{
					$go_enrichment[$go[1].": ".$des[2]] = 1;
				}
			}
		}
	}
	arsort($go_enrichment);
	if(sizeof($go_enrichment)>9){
		$go_enrichment=array_slice($go_enrichment,0,10);
	}
	$jsons = '{"labels":["'.join('","',array_keys($go_enrichment)).'"],"series":[{"label":"go","values":['.join(",",array_values($go_enrichment)).']}]}';
	echo $jsons;
?>