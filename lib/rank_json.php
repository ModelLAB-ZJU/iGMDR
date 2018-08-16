<?php
	require_once("mysql.php");
	$db = new mysql("iGMDR","conn","utf8");
	
	$json="";
	if(isset($_GET['cancer'])){
	$db -> query("SELECT * FROM cancer_sort");
	  $cancer_sort = array();		
	  while($t = $db->fetch_array()){
		  $cs['name']=$t['cancer'];
		  $cs['size']=intval($t['model_num']);
		  array_push($cancer_sort,'{"name":"'.$t['cancer'].'","children":['.json_encode($cs).']}');
	  }
	  $json = '{"name":"iGMDR","children":['.join(",",$cancer_sort).']}';
	}
	
	if(isset($_GET['tissue'])){
	  $tissue_sort = array();
	  $db -> query("SELECT * FROM tissue_sort");
	  while($t = $db->fetch_array()){
		  $cs['name']=$t['tissue'];
		  $cs['size']=intval($t['model_num']);
		  array_push($tissue_sort,'{"name":"'.$t['tissue'].'","children":['.json_encode($cs).']}');
	  }
	  $json = '{"name":"iGMDR","children":['.join(",",$tissue_sort).']}';
	}

	if(isset($_GET['drug'])){
	  $drug_sort = array();
	  $db -> query("SELECT * FROM drug_sort");
	  while($t = $db->fetch_array()){
		  $cs['name']=$t['drug'];
		  $cs['size']=intval($t['model_num']);
		  array_push($drug_sort,'{"name":"'.$t['drug'].'","children":['.json_encode($cs).']}');
	  }
	  $json = '{"name":"iGMDR","children":['.join(",",$drug_sort).']}';
	}
	
	if(isset($_GET['gene'])){
	  $gene_sort = array();
	  $db -> query("SELECT * FROM gene_sort");
	  while($t = $db->fetch_array()){
		  $cs['name']=$t['gene'];
		  $cs['size']=intval($t['model_num']);
		  array_push($gene_sort,'{"name":"'.$t['gene'].'","children":['.json_encode($cs).']}');
	  }
	  $json = '{"name":"iGMDR","children":['.join(",",$gene_sort).']}';
	}
	echo $json;
?>