<?php
	require_once("mysql.php");
	$db = new mysql("iGMDR","conn","utf8");
	$db -> query("SELECT * FROM gene_id");
	while($t = $db->fetch_array()){
		$genename[$t['gene_id']] = $t['gene'];
	}
	
	$db -> query("SELECT * FROM drug_gene_model_num_sort");
	$links = array();
	$nodes = array();
	while($t = $db->fetch_array()){
	  $js['source']=$t['drug_name'];
	  $js['target']=$genename[$t['gene_id']];
	  $js['value']= intval($t['model_num']);
	  array_push($links,json_encode($js));
	  $ds['id']=$t['drug_name'];
	  $ds['group']=1;
	  array_push($nodes,json_encode($ds));
	  $gs['id']=$genename[$t['gene_id']];
	  $gs['group']=2;
	  array_push($nodes,json_encode($gs));
	}
	$nodes=array_unique($nodes);
	$links=array_unique($links);
	$jsons = '{"nodes":['.join(",",$nodes).'],"links":['.join(",",$links).']}';
	echo $jsons;
?>