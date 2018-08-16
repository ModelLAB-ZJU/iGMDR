<?php
	require_once("mysql.php");
	$db = new mysql("iGMDR","conn","utf8");
	$db -> query("SELECT * FROM gene_id");
	$gene = array();		
	while($t = $db->fetch_array()){
		$gene[$t["gene_id"]] = $t['gene'];
	}
	$db -> query("SELECT * FROM gene_drug_model_num_sort");
	$gene_drug = array();
	while($t = $db->fetch_array()){
		$gene_drug[$t["drug_id"]][$t['gene_id']]['drug_name']=$t['drug'];
		$gene_drug[$t["drug_id"]][$t['gene_id']]['model_num']=intval($t['model_num']);
	}
	
	$db -> query("SELECT * FROM gene_drug_model_num_sort where gene_id =".$_GET['gene']);

	$links = array();
	$nodes = array();
	while($t = $db->fetch_array()){
		$linksi = array();
		$nodesi = array();
		foreach (array_keys($gene_drug{$t["drug_id"]}) as $ge){
			$js['source']=$gene[$ge];
			$js['target']=$gene_drug[$t['drug_id']][$ge]['drug_name'];
			$js['value']=$gene_drug[$t['drug_id']][$ge]['model_num'];
			array_push($linksi,json_encode($js));
			if($ge != $_GET['gene']){
				$gs['id']=$gene[$ge];
				$gs['group']=1;
				array_push($nodesi,json_encode($gs));
			}
		}
		$n=1;
		while(sizeof($nodesi)>20){
			$linksi = array();
			$nodesi = array();
			foreach (array_keys($gene_drug{$t["drug_id"]}) as $ge){
			  if($gene_drug{$t["drug_id"]}{$ge}{'model_num'} > $n){
				$js['source']=$gene[$ge];
				$js['target']=$gene_drug[$t['drug_id']][$ge]['drug_name'];
				$js['value']=$gene_drug[$t['drug_id']][$ge]['model_num'];
				array_push($linksi,json_encode($js));
				if($ge != $_GET['gene']){
				  $gs['id']=$gene[$ge];
				  $gs['group']=1;
				  array_push($nodesi,json_encode($gs));
			  	}
			  }
			}
			$n++;
		}
		$links=array_merge($links,$linksi);
		$nodes=array_merge($nodes,$nodesi);
		$js['source']=$gene[$_GET['gene']];
		$js['target']=$gene_drug[$t['drug_id']][$_GET['gene']]['drug_name'];
		$js['value']=$gene_drug[$t['drug_id']][$_GET['gene']]['model_num'];
		array_push($links,json_encode($js));
		
		$ds['id'] = $gene_drug[$t['drug_id']][$_GET['gene']]['drug_name'];
		$ds['group'] = 3;
		array_push($nodes,json_encode($ds));
	}
	$gs['id']=$gene[$_GET['gene']];
	$gs['group']=2;
	array_push($nodes,json_encode($gs));
	$nodes=array_unique($nodes);
	$links=array_unique($links);
	$jsons = '{"nodes":['.join(",",$nodes).'],"links":['.join(",",$links).']}';
	echo $jsons;
?>