<?php 
    include_once('lib/config.php');
	require_once("lib/mysql.php");
	$smarty -> assign("title","SEARCH");
	$smarty -> assign("info","");
	if(is_array($_GET) && count($_GET)>0){
		#GENE DISPLAY
	  if(isset($_GET['gene'])){
		  $query = $_GET['gene'];
		  $db = new mysql("iGMDR","conn","utf8");
		  if(preg_match("#^\d+$#",$query)){
		  	$db -> query("SELECT * FROM gene_id where gene_id=".$query);
			if($db->db_num_rows()){
				$db -> query("SELECT * FROM gene_info where gene_id=".$query);
			}
			else{
				$smarty -> assign("info","/ <a style='color:red'>No information in iGMDER!</a>");
				$smarty -> display("search.html");
				exit();
			}
		  }
		  else{
		  	$db -> query("SELECT * FROM gene_id where gene='".$query."'");
			if($db->db_num_rows()){
				$t = $db->fetch_array();
				$query=$t['gene_id'];
				$db -> query("SELECT * FROM gene_info where gene_id=".$query);	
			}
			else{
				$smarty -> assign("info","<a style='color:red'> / No information in iGMDER!</a>");
				$smarty -> display("search.html");
				exit();
			}
		  }
		  $gene_basic_info = array();
		  $gene_GO_info = "";
		  $gene_Pathway_info = "";
		  while($t = $db->fetch_array()){
			if(preg_match("#GO:(\w+)#",$t['key'],$go)){
				$des = str_replace("; ","</td><td>",$t['value']);
				$gene_GO_info .= "<tr><td>".$go[1]."</td><td>".$des."</td></tr>";
			}
			if(preg_match("#pathway:(.+)#",$t['key'],$pw)){
				$des = str_replace("; ","</td><td>",$t['value']);
				$gene_Pathway_info .= "<tr><td>".$pw[1]."</td><td>".$des."</td></tr>";
			}
			if(!preg_match("#:#",$t['key'])){
				$gene_basic_info[$t['key']] = $t['value'];
			}
		  }
		  $gene_basic_info_html = "<h2>".$gene_basic_info['symbol']."</h2><hr />";
		  if(array_key_exists("summary",$gene_basic_info)){
		  	$gene_basic_info_html .= "<p><b>Summary: </b>". $gene_basic_info['summary'] ."</p>";
		  }
		  $gene_basic_info_html .= '<table data-toggle="table" data-card-view="true"><thead><tr>';
		  $th="";$td="";
		  if(array_key_exists("name",$gene_basic_info)){
			  $th .= "<th>Name</th>";
			  $td .= "<td>".$gene_basic_info['name']."</td>";
		  }
		  if(array_key_exists("OMIM",$gene_basic_info)){
			  $th .= "<th>OMIM ID</th>";
			  $td .= "<td>".$gene_basic_info['OMIM']."</td>";
		  }
		  if(array_key_exists("ensembl",$gene_basic_info)){
			  $th .= "<th>Ensembl ID</th>";
			  $td .= "<td>".$gene_basic_info['ensembl']."</td>";
		  }
		  if(array_key_exists("HGNC",$gene_basic_info)){
			  $th .= "<th>HGNC ID</th>";
			  $td .= "<td>".$gene_basic_info['HGNC']."</td>";
		  }
		  if(array_key_exists("pharmgkb",$gene_basic_info)){
			  $th .= "<th>PHARMGKB ID</th>";
			  $td .= "<td>".$gene_basic_info['pharmgkb']."</td>";
		  }
		  if(array_key_exists("map_loc",$gene_basic_info)){
			  $th .= "<th>Map Location</th>";
			  $td .= "<td>".$gene_basic_info['map_loc']."</td>";
		  }
		  $gene_basic_info_html .= $th.'</tr></thead><tbody><tr>'.$td.'</tr></tbody></table>';
		  
		  $gene_Pathway_info_html =  '<h4>Pathways in '.$gene_basic_info['symbol'].'</h4><br /><table data-toggle="table" data-card-view="false" data-pagination="true" data-page-size=6><thead><tr><th>Database</th><th>Pathway ID</th><th>Pathway Des.</th></tr></thead><tbody>'.$gene_Pathway_info."</tbody></table>";
		  
		  $gene_GO_info_html =  '<h4>GO terms in '.$gene_basic_info['symbol'].'</h4><br /><table data-toggle="table" data-card-view="false" data-pagination="true" data-page-size=6><thead><tr><th>Term Type</th><th>Evidence Type</th><th>GO Term ID</th><th>GO Des.</th></tr></thead><tbody>'.$gene_GO_info."</tbody></table>";
		  
		  $db -> query("SELECT * FROM gene_drug_model_num_sort where gene_id=".$query);
		  $gene_drug_model_num_sort="";
		  while($t = $db->fetch_array()){
			  $gene_drug_model_num_sort .= "<tr><td>".$t['drug_id']."</td><td>".$t['drug']."</td><td>".$t['model_num']."</td></tr>";
		  }
		  $gene_drug_model_num_sort_html = '<table data-toggle="table" data-card-view="false" data-pagination="true" data-page-size=4><thead><tr><th>Drug ID</th><th>Drug Name</th><th data-sortable="true">Model Num.</th></tr></thead><tbody>'.$gene_drug_model_num_sort."</tbody></table>";
		  
		  
		  $smarty -> assign("gene_id",$query);
		  $smarty -> assign("query",$gene_basic_info['symbol']);
		  $smarty -> assign("gene_basic_info_html",$gene_basic_info_html);
		  $smarty -> assign("gene_Pathway_info_html",$gene_Pathway_info_html);
		  $smarty -> assign("gene_GO_info_html",$gene_GO_info_html);
		  $smarty -> assign("gene_drug_model_num_sort_html",$gene_drug_model_num_sort_html);
		  $smarty -> display("search_gene_result.html");
	  }
	  if(isset($_GET['drug'])){
		  $query = $_GET['drug'];
		  $drug_id="";
		  $db = new mysql("iGMDR","conn","utf8");
		  if(preg_match("#^iGMDRD\d+$#",$query)){
		  	$db -> query("SELECT * FROM drug_id where drug_id='".$query."'");
			if($db->db_num_rows()){
				$t = $db->fetch_array();
				$drug_id=$t['drug_id'];
				$db -> query("SELECT * FROM drug_info where drug_id='".$drug_id."'");
			}
			else{
				$smarty -> assign("info","/ <a style='color:red'>No information in iGMDER!</a>");
				$smarty -> display("search.html");
				exit();
			}
		  }
		  else{
		  	$db -> query("SELECT * FROM drug_id where drug='".$query."'");
			if($db->db_num_rows()){
				$t = $db->fetch_array();
				$drug_id=$t['drug_id'];
				$db -> query("SELECT * FROM drug_info where drug_id='".$drug_id."'");	
			}
			else{
				$smarty -> assign("info","<a style='color:red'> / No information in iGMDER!</a>");
				$smarty -> display("search.html");
				exit();
			}
		  }
		  $t = $db->fetch_array();
		  $query = $t['standard_name'];
		  $pubmed3d = "";
		  $drug_basic_info_html = "<h2>".$t['drug_id']."</h2><hr />";
		  $drug_basic_info_html .= '<table data-toggle="table" data-card-view="true"><thead><tr>';
		  $th="<th>Standard Name</th>";$td="<td>".$t['standard_name']."</td>";
		  if($t['Canonical_SMILES']!=""){$drug_basic_info_html .= "<p><b>Canonical SMILES: </b>". $t['Canonical_SMILES'] ."</p>";}  
		  if($t['InChI']!=""){$drug_basic_info_html .= "<p><b>InChI: </b>". $t['InChI'] ."</p>";} 
		  if($t['InChI_Key']!=""){$drug_basic_info_html .= "<p><b>InChI Key: </b>". $t['InChI_Key'] ."</p>";} 
		  if($t['CHEMBL']!=""){
			  $th .= "<th>CHEMBL</th>"; 
			  $td .= "<td>";
			  foreach (explode("; ",$t['CHEMBL']) as $u){
			  	$td .= "<a href='https://www.ebi.ac.uk/chembldb/index.php/compound/inspect/".$u."'>".$u."</a> ";
			  }
			  $td .= "</td>";
		  } 
		  if($t['pubchem_cid']!=0){
			  $th .= "<th>Pubchem CID</th>"; $td .= "<td><a href='https://pubchem.ncbi.nlm.nih.gov/compound/".$t['pubchem_cid']."'>".$t['pubchem_cid']."</a></td>";
			  $pubmed3d = '<div class="content-result"><iframe width="430" height="600" scrolling="no" frameborder="0" src="https://pubchem.ncbi.nlm.nih.gov/compound/'.$t['pubchem_cid'].'#section=3D-Conformer&amp;embed=true"></iframe></div>';
		  } 
		  if($t['kegg_drug_id']!=""){$th .= "<th>KEGG Drug ID</th>"; $td .= "<td><a href='https://www.genome.jp/dbget-bin/www_bget?dr:'".$t['kegg_drug_id'].">".$t['kegg_drug_id']."</a></td>";} 
		  if($t['drugbank_id']!=""){$th .= "<th>DRUGBANK ID</th>"; $td .= "<td><a href='https://www.drugbank.ca/drugs/".$t['drugbank_id']."'>".$t['drugbank_id']."</a></td>";} 		  
		  if($t['drug_staus']!=""){$th .= "<th>Drug Status</th>"; $td .= "<td>".$t['drug_staus']."</td>";} 
		  if($t['drug_targets_ttd_id']!=""){$th .= "<th>Drug targets (TTD)</th>"; $td .= "<td><a href='https://db.idrblab.org/ttd/drug/".$t['drug_targets_ttd_id']."'>".$t['drug_targets_ttd_id']."</a></td>";} 	  
		  if($t['drug_type']!=""){$th .= "<th>Drug Type</th>"; $td .= "<td>".$t['drug_type']."</td>";}
		  $drug_basic_info_html .= $th.'</tr></thead><tbody><tr>'.$td.'</tr></tbody></table>';
		  if($t['drug_class']!=""){
			  $drug_basic_info_html .= "<br /><div style='min-height:20px; display:block;';><p><b>Drug Class: </b></p>";
			  $classes = explode('; ',$t['drug_class']);
			  foreach($classes as $class){
			  	$drug_basic_info_html .= "<a class='druglabel'>". $class ."</a>";
			  }
			  $drug_basic_info_html .= "<div style='clear:both'></div></div>";
		  } 
		  if($t['drug_targets']!=""){
			  $drug_basic_info_html .= "<br /><div style='min-height:20px; display:block;';><p><b>Drug Targets: </b></p>";
		  	  $targets = explode('; ',$t['drug_targets']);
			  foreach($targets as $target){
			  	$drug_basic_info_html .= "<a class='druglabel'>". $target ."</a>";
			  }
			  $drug_basic_info_html .= "<div style='clear:both'></div></div>";
		  } 
		  if($t['drug_pathway']!=""){
			  $drug_basic_info_html .= "<br /><div style='min-height:20px; display:block;';><p><b>Drug Pathways: </b></p>";
		  	  $pathways = explode('; ',$t['drug_pathway']);
			  foreach($pathways as $pathway){
			  	$drug_basic_info_html .= "<a class='druglabel'>". $pathway ."</a>";
			  }
			  $drug_basic_info_html .= "<div style='clear:both'></div></div>";
		  } 
		  if($t['drug_synonyms']!=""){
			  $drug_basic_info_html .= "<br /><div style='min-height:20px; display:block;';><p><b>Drug Synonyms: </b></p>";
			  $synonyms = explode('; ',$t['drug_synonyms']);
			  foreach($synonyms as $synonym){
				$drug_basic_info_html .= "<a class='druglabel'>". $synonym ."</a>";
			  }
			  $drug_basic_info_html .= "<div style='clear:both'></div></div>";
		  } 
		  $db -> query("SELECT * FROM gene_id");
		  while($t = $db->fetch_array()){
			  $genename[$t['gene_id']] = $t['gene'];
		  }
		  $db -> query("SELECT * FROM gene_drug_model_num_sort where drug_id='".$drug_id."'");
		  $drug_gene_model_num_sort="";
		  while($t = $db->fetch_array()){
			  $drug_gene_model_num_sort .= "<tr><td>".$t['gene_id']."</td><td>".$genename[$t['gene_id']]."</td><td>".$t['model_num']."</td></tr>";
		  }
		  $drug_gene_model_num_sort_html = '<table data-toggle="table" data-card-view="false" data-pagination="true" data-page-size=4><thead><tr><th>Gene ID</th><th>Gene Name</th><th data-sortable="true">Model Num.</th></tr></thead><tbody>'.$drug_gene_model_num_sort."</tbody></table>";
		  
		  $smarty -> assign("drug_gene_model_num_sort_html",$drug_gene_model_num_sort_html);
		  $smarty -> assign("drug_basic_info_html",$drug_basic_info_html);
		  $smarty -> assign("pubmed3d",$pubmed3d);
		  $smarty -> assign("query",$query);
		  $smarty -> assign("drug_id",$drug_id);
		  $smarty -> display("search_drug_result.html");
	  }
	}
	else{
		$smarty -> display("search.html");
	}

?>