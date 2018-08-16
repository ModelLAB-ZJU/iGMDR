<?php 
    include_once('lib/config.php');
	$smarty -> assign("title","EXPLORE");
	if(isset($_GET['cancer']) || isset($_GET['tissue'])){
		if(isset($_GET['tissue'])){
			$smarty -> assign("info","?tissue=".$_GET['tissue']);
		}
		if(isset($_GET['cancer'])){
			$smarty -> assign("info","?cancer=".$_GET['cancer']);
		}
		$smarty -> display("explore.html");
	}
	else{
		$smarty -> assign("info","");
		$smarty -> display("explore.html");
	}
?>