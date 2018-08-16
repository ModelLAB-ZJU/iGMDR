<?php 
  require_once("../lib/mysql.php");
	
  if(isset($_GET['gene'])){
	  $query = $_GET['gene'];
	  $db = new mysql("iGMDR","conn","utf8");
	  if(preg_match("#^\d+$#",$query)){
		  $db -> query("SELECT * FROM model INNER JOIN (SELECT * FROM gene_model where gene_id=".$_GET['gene'].") b on model.model_id = b.model_id");
	  }
	  else{
	  	$db -> query("SELECT * FROM gene_id where gene='".$query."'");
		if($db->db_num_rows()){
			$t = $db->fetch_array();
			$query=$t['gene_id'];
			$db -> query("SELECT * FROM model INNER JOIN (SELECT * FROM gene_model where gene_id=".$query.") b on model.model_id = b.model_id");	
		}
	  }
	  $model = array();
	  while($t=$db->fetch_array()){
		  $m=array();
		  $m['model']=$t['model'];
		  $m['level']=$t['level'];
		  $m['tissue']=$t['tissue'];
		  $m['cancer']=$t['cancer'];
		  $m['drug']=$t['drug'];
		  $m['source']=$t['source'];
		  $m['model_id']=$t['model_id'];
		  $m['reference']=$t['reference'];
		  $m['clinical_significance']=$t['clinical_response'];
		  array_push($model,$m);
	  }
	  echo json_encode($model,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
  }
?>          