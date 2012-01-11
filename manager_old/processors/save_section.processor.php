<?php
// save_section.processor.php

if(IN_ETOMITE_SYSTEM != "true")
{
  die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the Etomite Manager instead of accessing this file directly.");
}


if($_SESSION['permissions']['save_snippet'] != 1)
{
  $e->setError(3);
  $e->dumpError();
}

if(isset($_REQUEST['id']) && !empty($_REQUEST['id'])){
	// editing a section
	if(!isset($_REQUEST['name']) || !isset($_REQUEST['section_type'])){
		die("<b>YOU MUST GO BACK AND COMPLETE THE FORM! THERE WERE ERRORS</b>");
	}else{
		//save it
		$sql = "update $dbase.".$table_prefix."site_section set name='".$_REQUEST['name']."',description='".$_REQUEST['description']."',section_type='".$_REQUEST['section_type']."' where id=".$_REQUEST['id'];
		$result = mysql_query($sql);
	}
}else{
	// new section
	$sql = "insert into $dbase.".$table_prefix."site_section (name,description,section_type) VALUES ('".$_REQUEST['name']."','".$_REQUEST['description']."','".$_REQUEST['section_type']."')";
	$result = mysql_query($sql);
}

$header="Location: index.php?a=76&r=2";
header($header);

?>