<?php 
    include_once('lib/config.php');
	$smarty -> assign("title","STATISTICS");
	require_once("lib/mysql.php");
	$db = new mysql("iGMDR","conn","utf8");
	$db -> query("SELECT * FROM stat_source");
	$model_source="<div>";
	while($t = $db->fetch_array()){
	  $model_source .= '<a class="stat_model_level">'.$t['field1'].' / '.$t['field2'].'</a>';
	}
	$model_source.='<div style="clear:both"></div></div>';
	$smarty -> assign("model_source",$model_source);
	
	$db -> query("SELECT * FROM stat_model_level");
	$model_level="<div>";
	while($t = $db->fetch_array()){
	  $model_level .= '<a class="stat_model_level">'.$t['field1'].'</a>';
	}
	$model_level.='<div style="clear:both"></div></div>';
	$smarty -> assign("model_levels",$model_level);
	
	$db -> query("SELECT * FROM stat_reference");
	$ref_dataset="<div>";
	while($t = $db->fetch_array()){
	  $ref_dataset .= '<a class="stat_model_level">'.$t['dataset'].'</a>';
	}
	$ref_dataset.='<div style="clear:both"></div></div>';
	$smarty -> assign("ref_dataset",$ref_dataset);
	
	$db -> query("SELECT * FROM model_descriptor");
	$model_descriptor="<div>";
	while($t = $db->fetch_array()){
	  $model_descriptor .= '<a class="stat_model_level" onsubmit="return '.$t['des'].'">'.$t['descriptor'].'</a>';
	}
	$model_descriptor.='<div style="clear:both"></div></div>';
	$smarty -> assign("model_descriptor",$model_descriptor);
	
	$smarty -> display("stat.html");
?>