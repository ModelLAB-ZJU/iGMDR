<?php 
  require_once("../lib/mysql.php");
	
  if(isset($_GET['drug_id'])){
	  $query = $_GET['drug_id'];
	  $db = new mysql("iGMDR","conn","utf8");
	  $db -> query("SELECT * FROM model INNER JOIN (SELECT * FROM drug_model where drug_id='".$_GET['drug_id']."') b on model.model_id = b.model_id");
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