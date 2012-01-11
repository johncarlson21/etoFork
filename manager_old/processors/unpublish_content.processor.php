<?php
if(IN_ETOMITE_SYSTEM != "true")
{
  die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the Etomite Manager instead of accessing this file directly.");
}

if($_SESSION['permissions']['save_document']!=1 && $_REQUEST['a']==62)
{
  $e->setError(3);
  $e->dumpError();
}

$id = $_REQUEST['id'];

// check permissions on the document
include_once("user_documents_permissions.class.php");
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
  include("../includes/footer.inc.php");
  exit;
}

// update the document
$sql = "UPDATE $dbase.".$table_prefix."site_content SET published=0, pub_date=0, unpub_date=0, editedby=".$_SESSION['internalKey'].", editedon=".time()." WHERE id=$id;";

$rs = mysql_query($sql);
if(!$rs)
{
  echo "An error occured while attempting to unpublish the document.";
}

include_once("cache_sync.class.processor.php");
$sync = new synccache();
$sync->setCachepath("../assets/cache/");
$sync->setReport(false);
$sync->emptyCache(); // first empty the cache

$header="Location: index.php?r=1&id=$id&a=7";
header($header);

?>
