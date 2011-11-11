<?php
// delete_template.processor.php

if(IN_ETOMITE_SYSTEM != "true")
{
  die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the Etomite Manager instead of accessing this file directly.");
}

if($_SESSION['permissions']['delete_template'] != 1 && $_REQUEST['a'] == 21)
{
  $e->setError(3);
  $e->dumpError();
}

// delete the template, but first check it doesn't have any documents using it
$id = $_GET['id'];
$sql = "SELECT id, pagetitle FROM $dbase.".$table_prefix."site_content WHERE $dbase.".$table_prefix."site_content.template=".$id." and $dbase.".$table_prefix."site_content.deleted=0;";
$rs = mysql_query($sql);
$limit = mysql_num_rows($rs);
if($limit > 0)
{
  echo "This template is in use. Please set the documents using the template to another template. Documents using this template:<br />";
  for ($i=0; $i < $limit; $i++)
  {
    $row = mysql_fetch_assoc($rs);
    echo $row['id']." - ".$row['pagetitle']."<br />\n";
  }
  exit;
}

if($id == $default_template)
{
  echo "This template is set as the default template. Please choose a different default template in Etomite configuration before deleting this template.<br />";
  exit;
}

//ok, delete the document.
$sql = "DELETE FROM $dbase.".$table_prefix."site_templates WHERE $dbase.".$table_prefix."site_templates.id=".$id.";";
$rs = mysql_query($sql);
if(!$rs)
{
  echo "Something went wrong while trying to delete the template...";
  exit;
}
else
{
  $header="Location: index.php?a=76&r=2";
  header($header);
}
?>
