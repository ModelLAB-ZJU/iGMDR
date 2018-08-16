<?php 
  require_once("../lib/mysql.php");
	
  if(isset($_GET['drug_id'])){
	  $query = $_GET['drug_id'];
	  $db = new mysql("iGMDR","conn","utf8");
	  $db -> query("SELECT * FROM drug_info where drug_id='".$_GET['drug_id']."'");
	  $model = array();
	  while($t=$db->fetch_array()){
		  $m=array();
		  $m['drug_name']=$t['standard_name'];
		  $m['smiles']=$t['Canonical_SMILES'];
		  $m['InChI']=$t['InChI'];
		  $m['InChI_Key']=$t['InChI_Key'];
		  $m['pubchem_cid']=$t['pubchem_cid'];
		  $m['chembl_id']=$t['CHEMBL'];
		  $m['kegg_id']=$t['kegg_drug_id'];
		  $m['ttd_id']=$t['drug_targets_ttd_id'];
		  $m['drugbank_id']=$t['drugbank_id'];
		  $m['drug_type']=$t['drug_type'];
		  $nn=array();
		  foreach(explode('; ',$t['drug_targets']) as $target){
			  $n['description'] = $target;
			  array_push($nn,$n);
	  	  }
		  $m['drug_target']=$nn;
		  $nn=array();
		  foreach(explode('; ',$t['drug_pathway']) as $target){
			  $n['description'] = $target;
			  array_push($nn,$n);
	  	  }
		  $m['drug_pathway']=$nn;
		  $nn=array();
		  foreach(explode('; ',$t['drug_synonyms']) as $target){
			  $n['description'] = $target;
			  array_push($nn,$n);
	  	  }
		  $m['drug_synonyms']=$nn;
		  $nn=array();
		  foreach(explode('; ',$t['drug_class']) as $target){
			  $n['description'] = $target;
			  array_push($nn,$n);
	  	  }
	  	  $m['drug_class']=$nn;
	  	  
		  array_push($model,$m);
	  }
	  echo json_encode($model,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
  }
?>          