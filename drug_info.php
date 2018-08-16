<?php 
    include_once('lib/config.php');
	require_once("lib/mysql.php");
	$smarty -> assign("title","STATISTICS / CLASSIFICATION");
	$db = new mysql("iGMDR","conn","utf8");
	if(isset($_GET['drug_class']) || isset($_GET['drug_target']) || isset($_GET['drug_pathway'])){
	  if(isset($_GET['drug_class'])){
		  $drug_class_html="<h2>DRUG CLASS in iGMDR</h2><br />";
		  $db -> query("SELECT * FROM drug_class");
		  while($t=$db->fetch_array()){
			  $drug_class_html .= "<div class='drug_info'><h4>".$t['drug_class']."</h4>";
			  foreach(explode(',',$t['related_drugs']) as $u){
				  $drug_class_html .= "<a class='drug_info_label' href='search.php?drug=".$u."'>". $u ."</a>";
			  }
			  $drug_class_html .= "<div style='clear:both'></div></div><br />";	
		  }
		  $smarty -> assign("nav",'<a href="drug_info.php?drug_class">DRUG CLASS</a>');
		  $smarty -> assign("drug_info_html",$drug_class_html);
	  }
	  if(isset($_GET['drug_target'])){
		  $drug_target_html="<h2>DRUG TARGET in iGMDR</h2><br />";
		  $db -> query("SELECT * FROM drug_targets");
		  while($t=$db->fetch_array()){
			  $drug_target_html .= "<div class='drug_info'><h4>".$t['target_des']."</h4>";
			  foreach(explode(',',$t['related_drugs']) as $u){
				  $drug_target_html .= "<a class='drug_info_label' href='search.php?drug=".$u."'>". $u ."</a>";
			  }
			  $drug_target_html .= "<div style='clear:both'></div></div><br />";	
		  }
		  $smarty -> assign("nav",'<a href="drug_info.php?drug_targets">DRUG TARGETS</a>');
		  $smarty -> assign("drug_info_html",$drug_target_html);
	  }
	  if(isset($_GET['drug_pathway'])){
		  $drug_pathway_html="<h2>DRUG PATHWAY in iGMDR</h2><br />";
		  $db -> query("SELECT * FROM drug_pathway");
		  while($t=$db->fetch_array()){
			  $drug_pathway_html .= "<div class='drug_info'><h4>".$t['pathway_des']."</h4>";
			  foreach(explode(',',$t['related_drugs']) as $u){
				  $drug_pathway_html .= "<a class='drug_info_label' href='search.php?drug=".$u."'>". $u ."</a>";
			  }
			  $drug_pathway_html .= "<div style='clear:both'></div></div><br />";	
		  }
		  $smarty -> assign("nav",'<a href="drug_info.php?drug_pathway">DRUG PAPTHWAY</a>');
		  $smarty -> assign("drug_info_html",$drug_pathway_html);
	  }
	  $smarty -> display("drug_info.html");
	}
	else{
		$smarty -> assign("nav",'<a style="color:red;">No information in iGMDRE</a>');
		$smarty -> assign("drug_info_html","");
		$smarty -> display("drug_info.html");
	}
?>