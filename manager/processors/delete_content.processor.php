<?php
// delete_content.processor.php

if(IN_ETOMITE_SYSTEM != "true")
{
  die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the Etomite Manager instead of accessing this file directly.");
}

if($_SESSION['permissions']['delete_document'] != 1 && $_REQUEST['a'] == 6)
{
  $e->setError(3);
  $e->dumpError();
}

// check the document doesn't have any children
$id = $_GET['id'];
$deltime = time();
$children = array();

// check permissions on the document
include("user_documents_permissions.class.php");
$udperms = new udperms();
$udperms->user = $_SESSION['internalKey'];
$udperms->document = $id;
$udperms->role = $_SESSION['role'];

if(!$udperms->checkPermissions())
{
  include("../includes/header.inc.php");
?>

<br /><br /><div class="sectionHeader"><img src='media/images/misc/dot.gif' alt="." />&nbsp;<?php echo $_lang['access_permissions']; ?></div><div class="sectionBody">
  <p><?php echo $_lang['access_permission_denied']; ?></p>

<?php
  include("includes/footer.inc.php");
  exit;
}

function getChildren($parent)
{
  global $dbase;
  global $table_prefix;
  global $children;
  global $site_start;

  $db->debug = true;

  $sql = "SELECT id FROM $dbase.".$table_prefix."site_content WHERE $dbase.".$table_prefix."site_content.parent=".$parent." AND deleted=0;";
  $rs = mysql_query($sql);
  $limit = mysql_num_rows($rs);
  if($limit > 0)
  {
    // the document has children documents, we'll need to delete those too
    for($i=0; $i < $limit; $i++)
    {
      $row=mysql_fetch_assoc($rs);
      if($row['id']==$site_start)
      {
        echo "The document you are trying to delete is a folder containing document ".$row['id'].". This document is registered as site_start, and cannot be deleted. Please assign another document as site_start and try again.";
        exit;
      }
      $children[] = $row['id'];
      getChildren($row['id']);
      //echo "Found childNode of parentNode $parent: ".$row['id']."<br />";
    }
  }
}

getChildren($id);

if(count($children) > 0)
{
  $docs_to_delete = implode(" ,", $children);
  $sql = "UPDATE $dbase.".$table_prefix."site_content SET deleted=1, deletedby=".$_SESSION['internalKey'].", deletedon=$deltime WHERE id IN($docs_to_delete);";
  $rs = @mysql_query($sql);
  if(!$rs)
  {
    echo "Something went wrong while trying to set the document's children to deleted status...";
    exit;
  }
}

if($site_start == $id)
{
  echo "Document is site_start and cannot be deleted!";
  exit;
}

//ok, 'delete' the document.
$sql = "UPDATE $dbase.".$table_prefix."site_content SET deleted=1, deletedby=".$_SESSION['internalKey'].", deletedon=$deltime WHERE id=$id;";
$rs = mysql_query($sql);
if(!$rs)
{
  echo "Something went wrong while trying to set the document to deleted status...";
  exit;
}
else
{
  // empty cache
  include("cache_sync.class.processor.php");
  $sync = new synccache();
  $sync->setCachepath("../assets/cache/");
  $sync->setReport(false);
  $sync->emptyCache(); // first empty the cache
  // finished emptying cache - redirect
  $header="Location: index.php?r=1&a=7";
  header($header);
}
?>
