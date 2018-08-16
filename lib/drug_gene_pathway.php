<?php
	require_once("mysql.php");
	$db = new mysql("iGMDR","conn","utf8");
	$dbi = $db;
	$db -> query("SELECT * FROM drug_gene where drug_id='".$_GET['drug']."'");
	$gene = array();
	$pathway_enrichment = array();
	while($t = $db->fetch_array()){
		array_push($gene,$t['gene_id']);
	}
	foreach($gene as $g){
		$dbi -> query("SELECT * FROM gene_info where gene_id=".$g);
		
		while($ti = $dbi->fetch_array()){
			
			if(preg_match("#pathway:(.+)#",$ti['key'],$pw)){
				
				$des = explode("; ",$ti['value']);
				if(array_key_exists($pw[1].": ".$des[1],$pathway_enrichment)){
					$pathway_enrichment[$pw[1].": ".$des[1]] += 1;
				}
				else{
					$pathway_enrichment[$pw[1].": ".$des[1]] = 1;
				}
			}
		}
	}
	arsort($pathway_enrichment);
	if(sizeof($pathway_enrichment)>9){
		$pathway_enrichment=array_slice($pathway_enrichment,0,10);
	}
	$jsons = '{"labels":["'.join('","',array_keys($pathway_enrichment)).'"],"series":[{"label":"go","values":['.join(",",array_values($pathway_enrichment)).']}]}';
	echo $jsons;
?>