<?php
	require_once("mysql.php");
	$db = new mysql("iGMDR","conn","utf8");
	$db -> query("SELECT * FROM gene_id");
	while($t = $db->fetch_array()){
		$genename[$t['gene_id']] = $t['gene'];
	}
	$db -> query("SELECT * FROM drug_id");
	$drug = array();		
	while($t = $db->fetch_array()){
		$drug[$t["drug_id"]] = $t['drug'];
	}
	$db -> query("SELECT * FROM drug_gene_model_num_sort");
	$drug_gene = array();
	while($t = $db->fetch_array()){
		$drug_gene[$t["gene_id"]][$t['drug_id']]['gene_name']=$genename[$t['gene_id']];
		$drug_gene[$t["gene_id"]][$t['drug_id']]['model_num']=intval($t['model_num']);
	}
	
	$db -> query("SELECT * FROM drug_gene_model_num_sort where drug_id ='".$_GET['drug']."'");

	$links = array();
	$nodes = array();
	while($t = $db->fetch_array()){
		$linksi = array();
		$nodesi = array();
		foreach (array_keys($drug_gene{$t["gene_id"]}) as $dr){
			$js['source']=$drug[$dr];
			$js['target']=$drug_gene[$t['gene_id']][$dr]['gene_name'];
			$js['value']=$drug_gene[$t['gene_id']][$dr]['model_num'];
			array_push($linksi,json_encode($js));
			if($dr != $_GET['drug']){
				$ds['id']=$drug[$dr];
				$ds['group']=1;
				array_push($nodesi,json_encode($ds));
			}
		}
		$n=1;
		while(sizeof($nodesi)>20){
			$linksi = array();
			$nodesi = array();
			foreach (array_keys($drug_gene{$t["gene_id"]}) as $dr){
			  if($drug_gene{$t["gene_id"]}{$dr}{'model_num'} > $n){
				$js['source']=$drug[$dr];
				$js['target']=$drug_gene[$t['gene_id']][$dr]['gene_name'];
				$js['value']=$drug_gene[$t['gene_id']][$dr]['model_num'];
				array_push($linksi,json_encode($js));
				if($dr != $_GET['drug']){
				  $ds['id']=$drug[$dr];
				  $ds['group']=1;
				  array_push($nodesi,json_encode($ds));
			  	}
			  }
			}
			$n++;
		}
		$links=array_merge($links,$linksi);
		$nodes=array_merge($nodes,$nodesi);
		$js['source']=$drug[$_GET['drug']];
		$js['target']=$drug_gene[$t['gene_id']][$_GET['drug']]['gene_name'];
		$js['value']=$drug_gene[$t['gene_id']][$_GET['drug']]['model_num'];
		array_push($links,json_encode($js));
		
		$ds['id'] = $drug_gene[$t['gene_id']][$_GET['drug']]['gene_name'];
		$ds['group'] = 3;
		array_push($nodes,json_encode($ds));
	}
	$ds['id']=$drug[$_GET['drug']];
	$ds['group']=2;
	array_push($nodes,json_encode($ds));
	$nodes=array_unique($nodes);
	$links=array_unique($links);
	$jsons = '{"nodes":['.join(",",$nodes).'],"links":['.join(",",$links).']}';
	echo $jsons;
?>