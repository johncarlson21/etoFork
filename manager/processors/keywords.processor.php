<?php
// keywords.processor.php
// Last Modified 2006-06-03

if(IN_ETOMITE_SYSTEM != "true")
{
  die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the Etomite Manager instead of accessing this file directly.");
}

if($_SESSION['permissions']['new_document'] != 1 && $_REQUEST['a'] == 82)
{
  $e->setError(3);
  $e->dumpError();
}

$delete_keywords = isset($_POST['delete_keywords']) ? $_POST['delete_keywords'] : array() ;
$orig_keywords = isset($_POST['orig_keywords']) ? $_POST['orig_keywords'] : array() ;
$rename_keywords = isset($_POST['rename_keywords']) ? $_POST['rename_keywords'] : array() ;

// do any renaming that has to be done
foreach($orig_keywords as $key => $value)
{
  if($rename_keywords[$key]!=$value)
  {
    $sql = "SELECT * FROM $dbase.".$table_prefix."site_keywords WHERE keyword='".addslashes($rename_keywords[$key])."'";
    $rs = mysql_query($sql);
    $limit = mysql_num_rows($rs);
    if($limit > 0)
    {
      echo "  - This keyword has already been defined!";
      exit;
    }
    else
    {
      $sql = "UPDATE $dbase.".$table_prefix."site_keywords SET keyword='".addslashes($rename_keywords[$key])."' WHERE keyword='".addslashes($value)."'";
      $rs = mysql_query($sql);
    }
  }
}

// delete any keywords that need to be deleted
if(count($delete_keywords) > 0)
{
  $keywords_array = array();
  foreach($delete_keywords as $key => $value)
  {
    $keywords_array[] = $key;
  }

  $sql = "DELETE FROM $dbase.".$table_prefix."keyword_xref WHERE keyword_id IN(".join($keywords_array, ",").")";
  $rs = mysql_query($sql);
  if(!$rs)
  {
    echo "Failure on deletion of xref keys: ".mysql_error();
    exit;
  }

  $sql = "DELETE FROM $dbase.".$table_prefix."site_keywords WHERE id IN(".join($keywords_array, ",").")";
  $rs = mysql_query($sql);
  if(!$rs)
  {
    echo "Failure on deletion of keywords ".mysql_error();
    exit;
  }

}

/*
// add new keyword (original)
if(!empty($_POST['new_keyword'])) {
  $nk = $_POST['new_keyword'];

  $sql = "SELECT * FROM $dbase.".$table_prefix."site_keywords WHERE keyword='".addslashes($nk)."'";
  $rs = mysql_query($sql);
  $limit = mysql_num_rows($rs);
  if($limit > 0) {
    echo "Keyword $nk already exists!";
    exit;
  } else {
    $sql = "INSERT INTO $dbase.".$table_prefix."site_keywords(keyword) VALUES('".addslashes($nk)."')";
    $rs = mysql_query($sql);
  }
}
*/

/* --------------------------------------------------------
ADD NEW KEYWORD(S)
Modified 2006-06-03 for multi-keyword submission by xrepins
---------------------------------------------------------*/

// Ensure we have at least one keyword first.
if(!empty($_POST['new_keyword']))
{
  // Check for a comma seperated keyword list.
  if(!$array_marker = strstr($_POST['new_keyword'], ','))
  {
    // No comma found, process as single keyword.
    $nk = $_POST['new_keyword'];
    $sql = "SELECT * FROM $dbase.".$table_prefix."site_keywords WHERE keyword='".addslashes($nk)."'";
    $rs = mysql_query($sql);
    $limit = mysql_num_rows($rs);
    if($limit > 0)
    {
      echo "Keyword $nk already exists!";
      exit;
    }
    else
    {
      $sql = "INSERT INTO $dbase.".$table_prefix."site_keywords(keyword) VALUES('".addslashes($nk)."')";
      $rs = mysql_query($sql);
    }
  }
  else
  {
    // Comma found, process as keyword array.
    $keyword_array = explode(',', $_POST['new_keyword']);

    // Perform insert for each element array.
    foreach($keyword_array as $val)
    {
      $sql = "SELECT * FROM $dbase.".$table_prefix."site_keywords WHERE keyword='".addslashes($val)."'";
      $rs = mysql_query($sql);
      $limit = mysql_num_rows($rs);
      if($limit > 0)
      {
        echo "Keyword $val already exists!";
        exit;
      }
      else
      {
        $sql = "INSERT INTO $dbase.".$table_prefix."site_keywords(keyword) VALUES('".addslashes($val)."')";
        $rs = mysql_query($sql);
      }

    }
  }
}

// empty cache
include_once("cache_sync.class.processor.php");
$sync = new synccache();
$sync->setCachepath("../assets/cache/");
$sync->setReport(false);
$sync->emptyCache();

//$header="Location: index.php?a=81";
$header="Location: index.php?a=76";
header($header);

?>
