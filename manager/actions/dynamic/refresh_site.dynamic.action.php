<?php
#if(IN_ETOMITE_SYSTEM!="true") die($_lang["include_ordering_error"]);

// (un)publishing of documents, version 2!
// first, publish document waiting to be published
$sql = "UPDATE $dbase.".$table_prefix."site_content SET published=1 WHERE $dbase.".$table_prefix."site_content.pub_date < ".time()." AND $dbase.".$table_prefix."site_content.pub_date!=0";
$rs = mysql_query($sql);
$num_rows_pub = mysql_affected_rows($etomiteDBConn);

$sql = "UPDATE $dbase.".$table_prefix."site_content SET published=0 WHERE $dbase.".$table_prefix."site_content.unpub_date < ".time()." AND $dbase.".$table_prefix."site_content.unpub_date!=0";
$rs = mysql_query($sql);
$num_rows_unpub = mysql_affected_rows($etomiteDBConn);

?>

<div class="subTitle">
<span class="floatRight"><img src="media/images/_tx_.gif" width="1" height="5"><br /><?php echo $site_name ;?> - <?php echo $_lang['refresh_title']; ?></span>
</div>

<div class="sectionHeader"><img src='media/images/misc/dot.gif' alt="." />&nbsp;<?php echo $_lang['refresh_title']; ?></div><div class="sectionBody">
<?php printf($_lang["refresh_published"], $num_rows_pub) ?><br />
<?php printf($_lang["refresh_unpublished"], $num_rows_unpub) ?><br />
<?php
include_once("./processors/cache_sync.class.processor.php");
$sync = new synccache();
$sync->setCachepath("../assets/cache/");
$sync->setReport(true);
$sync->emptyCache();
?>
</div>
