<?php 
    include_once('lib/config.php');
	require_once("lib/mysql.php");
	$smarty -> assign("title","STATISTICS / DATA TYPE");
	$db = new mysql("iGMDR","conn","utf8");
	if(isset($_GET['cancer']) || isset($_GET['tissue']) || isset($_GET['drug']) || isset($_GET['gene']) || isset($_GET['dataset'])){
	  if(isset($_GET['cancer'])){
		  $dataType_cancer_html="<h2>CANCER in iGMDR</h2><br />";
		  $db -> query("SELECT * FROM cancer_sort");
		  $dataType_cancer_html .= "<div class='data_type'>";
		  while($t=$db->fetch_array()){			
			  $dataType_cancer_html .= "<a class='data_type_label' href='explore.php?cancer=".strval($t['cancer'])."'>". $t['cancer'] ."</a>";
		  }
		  $dataType_cancer_html .= "<div style='clear:both'></div></div><br />";	
		  $smarty -> assign("nav",'<a href="data_type.php?cancer">CANCER</a>');
		  $smarty -> assign("data_type_html",$dataType_cancer_html);
	  }
	  if(isset($_GET['dataset'])){
		  $dataType_dataset_html="<h2>DATASET in iGMDR</h2><br />";
		  $db -> query("SELECT * FROM reference");
		  $dataType_dataset_html .= "<div class='data_type'>";
		  while($t=$db->fetch_array()){			
			  $dataType_dataset_html .= "<a class='data_type_label' href='".$t['ref_url']."'>". $t['ref_standard'] ."</a>";			  
		  }
		  $dataType_dataset_html .= "<div style='clear:both'></div></div><br />";	
		  $smarty -> assign("nav",'<a href="data_type.php?dataset">DATASET</a>');
		  $smarty -> assign("data_type_html",$dataType_dataset_html);
	  }
	  if(isset($_GET['tissue'])){
		  $dataType_tissue_html="<h2>TISSUE in iGMDR</h2><br />";
		  $db -> query("SELECT * FROM tissue_sort");
		  $dataType_tissue_html .= "<div class='data_type'>";
		  while($t=$db->fetch_array()){
			  $dataType_tissue_html .= "<a class='data_type_label' href='explore.php?tissue=".strval($t['tissue'])."'>". $t['tissue'] ."</a>";
		  }
		  $dataType_tissue_html .= "<div style='clear:both'></div></div><br />";	
		  $smarty -> assign("nav",'<a href="data_type.php?tissue">TISSUE</a>');
		  $smarty -> assign("data_type_html",$dataType_tissue_html);
	  }
	  if(isset($_GET['drug'])){
		  $dataType_drug_html="<h2>DRUG in iGMDR</h2><br />";
		  $db -> query("SELECT * FROM drug_sort");
		  $dataType_drug_html .= "<div class='data_type'>";
		  while($t=$db->fetch_array()){
			  $dataType_drug_html .= "<a class='data_type_label' href='search.php?drug=".strval($t['drug'])."'>". $t['drug'] ."</a>";
		  }
		  $dataType_drug_html .= "<div style='clear:both'></div></div><br />";	
		  $smarty -> assign("nav",'<a href="data_type.php?drug">DRUG</a>');
		  $smarty -> assign("data_type_html",$dataType_drug_html);
	  }
	  if(isset($_GET['gene'])){
		  $dataType_gene_html="<h2>GENE in iGMDR</h2><br />";
		  $db -> query("SELECT * FROM gene_sort");
		  $dataType_gene_html .= "<div class='data_type'>";
		  while($t=$db->fetch_array()){
			  $dataType_gene_html .= "<a class='data_type_label' href='search.php?gene=".$t['gene']."'>". $t['gene'] ."</a>";
		  }
		  $dataType_gene_html .= "<div style='clear:both'></div></div><br />";	
		  $smarty -> assign("nav",'<a href="data_type.php?gene">GENE</a>');
		  $smarty -> assign("data_type_html",$dataType_gene_html);
	  }
	  $smarty -> display("data_type.html");
	}
	else{
		$smarty -> assign("nav",'<a style="color:red;">No information in iGMDRE</a>');
		$smarty -> assign("data_type_html","");
		$smarty -> display("data_type.html");
	}
?>