<?php
if(IN_ETOMITE_SYSTEM != "true")
{
  die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the Etomite Manager instead of accessing this file directly.");
}

if($_SESSION['permissions']['settings'] != 1 && $_REQUEST['a'] == 67)
{
  $e->setError(3);
  $e->dumpError();
}

// Remove locks
$sql = "TRUNCATE $dbase.".$table_prefix."active_users";
$rs = mysql_query($sql);
if(!$rs)
{
  echo "Something went wrong while trying to remove the locks!";
  exit;
}

$header="Location: index.php?a=7";
header($header);
?>
