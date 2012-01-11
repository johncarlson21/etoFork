<?php
// site_logging.static.action.php
// Modified in 0.6.1 by Ralph
// Modified 2008-03-22 [v1.0] by Ralph to use system date|time formatting
// Modified 2008-05-10 [v1.1] by Ralph Dahlgren
// - HTML markup improvements

if(IN_ETOMITE_SYSTEM!="true") die($_lang["include_ordering_error"]);
?>

<div class="subTitle">
  <span class="floatRight">
    <img src="media/images/_tx_.gif" width="1" height="5"><br /><?php echo $site_name ;?> - <?php echo $_lang["visitor_stats"]; ?>
  </span>
</div>

<?php
// figure out what the user wants to look at, and find the corresponding start and end times
if(isset($_REQUEST['scope']) && isset($_REQUEST['start'])) {
  $startdate = $_REQUEST['start'];
  $scope = $_REQUEST['scope'];
  $date = getdate($startdate);
  $mon = $date['mon'];
  $year = $date['year'];
  switch($scope) {
    case "month" :
      $enddate = mktime(23, 59, 59, $mon, date('t', $startdate), $year);
    break;
    case "day" :
      $enddate = mktime(23, 59, 59, $mon, date('d', $startdate), $year);
    break;
  }
} else {
  $startdate = 0;
  $sql = "SELECT MIN(timestamp) AS first FROM $dbase.".$table_prefix."log_access";
  $rs = mysql_query($sql);
  $tmp = mysql_fetch_assoc($rs);
  $startdate = $tmp['first']-1;
  $enddate = time()+$server_offset_time;
  $scope = 'total';
}
// check to see the db's not empty...
$sql = "SELECT timestamp FROM $dbase.".$table_prefix."log_access LIMIT 1";
$rs = mysql_query($sql);
$count = mysql_num_rows($rs);
if($count==0) {
?>

<div class="sectionHeader">
  <img src='media/images/misc/dot.gif' alt="." />&nbsp;<?php echo $_lang["visitor_stats"]; ?>
</div>

<div class="sectionBody">
  <?php echo $_lang['no_logging_found']; ?>
</div>

<?php
  include("includes/footer.inc.php");
  exit;
}
?>

<style>
td { color: black; }
#visitorStatsPane { width:98%; }
body > #visitorStatsPane { width:auto; }
</style>

<script type="text/javascript">
function checkIM() {
  im_on = document.settings.im_plugin[0].checked; // check if im_plugin is on
  if(im_on==true) {
    showHide(/imRow/, 1);
  }
}

function showHide(what, onoff){

  var all = document.getElementsByTagName( "*" );
  var l = all.length;
  var buttonRe = what;
  var id, el, stylevar;

  if(onoff==1) {
    stylevar = "<?php echo $displayStyle; ?>";
  } else {
    stylevar = "none";
  }

  for ( var i = 0; i < l; i++ ) {
    el = all[i]
    id = el.id;
    if ( id == "" ) continue;
    if (buttonRe.test(id)) {
      el.style.display = stylevar;
    }
  }
}
</script>

<!--<link type="text/css" rel="stylesheet" href="media/style/tabs.css" />-->
<script type="text/javascript" src="media/script/tabpane.js"></script>

<div class="tab-pane" id="visitorStatsPane">

<script type="text/javascript">
  tpSettings = new WebFXTabPane(document.getElementById("visitorStatsPane"));
</script>

<!-- Visitor Stats -->

  <?php
    // get page impressions for all time
    $sql = "SELECT COUNT(*) FROM $dbase.".$table_prefix."log_access WHERE timestamp > $startdate AND timestamp < $enddate ";
    $rs = mysql_query($sql);
    $tmp = mysql_fetch_assoc($rs);
    $pitotal = $tmp['COUNT(*)'];

    // get visits for all time
    $sql = "SELECT COUNT(*) FROM $dbase.".$table_prefix."log_access WHERE entry='1' AND timestamp > $startdate AND timestamp < $enddate ";
    $rs = mysql_query($sql);
    $tmp = mysql_fetch_assoc($rs);
    $vitotal = $tmp['COUNT(*)'];

    // get visitors for all time
    $sql = "SELECT COUNT(DISTINCT(visitor)) FROM $dbase.".$table_prefix."log_access WHERE timestamp > $startdate AND timestamp < $enddate ";
    $rs = mysql_query($sql);
    $tmp = mysql_fetch_assoc($rs);
    $vistotal = $tmp['COUNT(DISTINCT(visitor))'];
  ?>

  <!-- Overall Site Stats -->
<div class="tab-page" id="tabPage1">
  <div class="tab">
  <img src='media/images/misc/dot.gif' alt="." />&nbsp;<?php echo $_lang['stats_summary_tab']; ?>
  </div>
  <script type="text/javascript">
    tpSettings.addTabPage(document.getElementById("tabPage1"));
  </script>
  <table width="100%" border="0" cellspacing="1" cellpadding="3" bgcolor="#000000">
    <thead>
      <tr>
        <td colspan="2"><img src='media/images/misc/dot.gif' alt="." />&nbsp;<b><?php printf($_lang['stats_title'], $site_name, strftime($date_format, $startdate), strftime($date_format, $enddate)); ?></b></td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class='row3'><?php echo $_lang['page_impressions']; ?></td>
        <td class='row1' width="25%"><?php echo number_format($pitotal); ?></td>
      </tr>
      <tr>
        <td class='row3'><?php echo $_lang['visits']; ?></td>
        <td class='row1'><?php echo number_format($vitotal); ?></td>
      </tr>
      <tr>
        <td class='row3'><?php echo $_lang['visitors']; ?></td>
        <td class='row1'><?php echo number_format($vistotal); ?></td>
      </tr>
    </tbody>
  </table>

  <p style="text-align:center;">
    <input type="button" name='clear_visitor_logs' value="<?php echo $_lang['empty_visitor_logs']; ?>" onclick="if(confirm('<?php echo $_lang['empty_visitor_logs_confirm']; ?>')==true) document.location.href='index.php?a=56';" />
  </p>

  <?php

  switch($scope) {
    case "total" :
  ?>

  <!-- Monthly Stats -->
  <table width="100%" border="0" cellspacing="1" cellpadding="3" bgcolor="#000000">
    <thead>
      <tr>
        <td colspan="7"><img src='media/images/misc/dot.gif' alt="." />&nbsp;<b><?php echo $_lang['stats_monthly_breakup']; ?></b></td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class='row3' width="25%">&nbsp;</td>
        <td class='row3' width="25%" colspan="2"><b><?php echo $_lang['page_impressions']; ?></b></td>
        <td class='row3' width="25%" colspan="2"><b><?php echo $_lang['visits']; ?></b></td>
      </tr>
  <?php
  $startYear = date('Y', $startdate);
  $years = (date('Y', time())-$startYear)+1;
  $monthCount = 0;
  $firstResultsShown = 0;
  $maxResult = time(); // this is so we don't output anything for the future

  for($i=0; $i<(12*$years); $i++) {

    $monthStart = mktime(0,   0,  0, $i+1, 1, $startYear); // month start, the year will be incremented by the $i :)
    $monthEnd   = mktime(23, 59, 59, $i+1, date('t', $monthStart), $startYear); // month end, the year will be incremented by the $i :)

    // get page impressions for time period
    $sql = "SELECT COUNT(*) FROM $dbase.".$table_prefix."log_access WHERE timestamp > $monthStart AND timestamp < $monthEnd ";
    $rs = mysql_query($sql);
    $tmp = mysql_fetch_assoc($rs);
    $pi = $tmp['COUNT(*)'];

    // get visits for time period
    $sql = "SELECT COUNT(*) FROM $dbase.".$table_prefix."log_access WHERE entry='1' AND timestamp > $monthStart AND timestamp < $monthEnd ";
    $rs = mysql_query($sql);
    $tmp = mysql_fetch_assoc($rs);
    $vi = $tmp['COUNT(*)'];

    // get visitors for time period
    $sql = "SELECT COUNT(DISTINCT(visitor)) FROM $dbase.".$table_prefix."log_access WHERE timestamp > $monthStart AND timestamp < $monthEnd ";
    $rs = mysql_query($sql);
    $tmp = mysql_fetch_assoc($rs);
    $vis = $tmp['COUNT(DISTINCT(visitor))'];
    if(($vis>0 || $vi>0 || $pi>0 || $firstResultsShown>0) && $monthStart<$maxResult) {
      $firstResultsShown = 1; // this is so we don't show emtpy months where no logging was done before we've actually output anything.
  ?>
      <tr>
        <td class='row3' width="25%"><a href="index.php?a=68&scope=month&start=<?php echo $monthStart; ?>"><?php echo $_lang['months'][$monthCount]." ".date('Y', $monthStart); ?></a></td>
        <td class='row1' style="width:12.5%;"><?php echo $pi==0 ? "-" : number_format($pi); ?></td>
        <td class='row1' style="width:12.5%;"><?php echo $pi==0 ? "-" : number_format(($pi/$pitotal)*100, 1)." %"; ?></td>
        <td class='row1' style="width:12.5%;"><?php echo $vi==0 ? "-" : number_format($vi); ?></td>
        <td class='row1' style="width:12.5%;"><?php echo $vi==0 ? "-" : number_format(($vi/$vitotal)*100, 1)." %"; ?></td>
      </tr>
  <?php
    }
    if($monthCount==11) {
      $monthCount = 0;
    } else {
      $monthCount += 1;
    }
  }
  ?>
    </tbody>
  </table>
  <?php
    break;
    case "month" :
  ?>

  <!-- Daily Stats -->
  <table width="100%" border="0" cellspacing="1" cellpadding="3" bgcolor="#000000">
    <thead>
      <tr>
        <td colspan="7"><img src='media/images/misc/dot.gif' alt="." />&nbsp;<b><?php echo $_lang['stats_daily_breakup']; ?></b></td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class='row3' width="25%">&nbsp;</td>
        <td class='row3' width="25%" colspan="2"><b><?php echo $_lang['page_impressions']; ?></b></td>
        <td class='row3' width="25%" colspan="2"><b><?php echo $_lang['visits']; ?></b></td>
      </tr>
  <?php
  $days = date('t', $startdate);
  $month = date('n', $startdate);
  for($i=0; $i<$days; $i++) {

    $dayStart = mktime(0,   0,  0, $month, $i+1, date('Y', $startdate)); // month start, the year will be incremented by the $i :)
    $dayEnd   = mktime(23, 59, 59, $month, $i+1, date('Y', $startdate)); // month end, the year will be incremented by the $i :)

    // get page impressions for time period
    $sql = "SELECT COUNT(*) FROM $dbase.".$table_prefix."log_access WHERE timestamp > $dayStart AND timestamp < $dayEnd ";
    $rs = mysql_query($sql);
    $tmp = mysql_fetch_assoc($rs);
    $pi = $tmp['COUNT(*)'];

    // get visits for time period
    $sql = "SELECT COUNT(*) FROM $dbase.".$table_prefix."log_access WHERE entry='1' AND timestamp > $dayStart AND timestamp < $dayEnd ";
    $rs = mysql_query($sql);
    $tmp = mysql_fetch_assoc($rs);
    $vi = $tmp['COUNT(*)'];

    // get visitors for time period
    $sql = "SELECT COUNT(DISTINCT(visitor)) FROM $dbase.".$table_prefix."log_access WHERE timestamp > $dayStart AND timestamp < $dayEnd ";
    $rs = mysql_query($sql);
    $tmp = mysql_fetch_assoc($rs);
    $vis = $tmp['COUNT(DISTINCT(visitor))'];
  ?>
      <tr>
        <td class='row3' width="25%"><a href="index.php?a=68&scope=day&start=<?php echo $dayStart; ?>"><?php echo $_lang['days'][date('w', $dayStart)]." ".strftime($date_format, $dayStart); ?></a></td>
        <td class='row1' style="width:12.5%;"><?php echo $pi==0 ? "-" : number_format($pi); ?></td>
        <td class='row1' style="width:12.5%;"><?php echo $pi==0 ? "-" : number_format(($pi/$pitotal)*100, 1)." %"; ?></td>
        <td class='row1' style="width:12.5%;"><?php echo $vi==0 ? "-" : number_format($vi); ?></td>
        <td class='row1' style="width:12.5%;"><?php echo $vi==0 ? "-" : number_format(($vi/$vitotal)*100, 1)." %"; ?></td>
      </tr>
  <?php
  }
  ?>
    </tbody>
  </table>
  <?php
    break;
    case "day" :
  ?>

  <!-- Hourly Stats -->
  <table width="100%" border="0" cellspacing="1" cellpadding="3" bgcolor="#000000">
    <thead>
      <tr>
        <td colspan="7"><img src='media/images/misc/dot.gif' alt="." />&nbsp;<b><?php echo $_lang['stats_hourly_breakup']; ?></b></td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class='row3' width="25%">&nbsp;</td>
        <td class='row3' width="25%" colspan="2"><b><?php echo $_lang['page_impressions']; ?></b></td>
        <td class='row3' width="25%" colspan="2"><b><?php echo $_lang['visits']; ?></b></td>
      </tr>
  <?php
  for($i=0; $i<24; $i++) {

    $hourStart = mktime($i,   0,  0, date('n', $startdate), date('d', $startdate), date('Y', $startdate)); // month start, the year will be incremented by the $i :)
    $hourEnd   = mktime($i, 59, 59, date('n', $startdate), date('d', $startdate), date('Y', $startdate)); // month end, the year will be incremented by the $i :)

    // get page impressions for time period
    $sql = "SELECT COUNT(*) FROM $dbase.".$table_prefix."log_access WHERE timestamp > $hourStart AND timestamp < $hourEnd ";
    $rs = mysql_query($sql);
    $tmp = mysql_fetch_assoc($rs);
    $pi = $tmp['COUNT(*)'];

    // get visits for time period
    $sql = "SELECT COUNT(*) FROM $dbase.".$table_prefix."log_access WHERE entry='1' AND timestamp > $hourStart AND timestamp < $hourEnd ";
    $rs = mysql_query($sql);
    $tmp = mysql_fetch_assoc($rs);
    $vi = $tmp['COUNT(*)'];

    // get visitors for time period
    $sql = "SELECT COUNT(DISTINCT(visitor)) FROM $dbase.".$table_prefix."log_access WHERE timestamp > $hourStart AND timestamp < $hourEnd ";
    $rs = mysql_query($sql);
    $tmp = mysql_fetch_assoc($rs);
    $vis = $tmp['COUNT(DISTINCT(visitor))'];

    $hour = strlen($i)==1 ? "0".$i : $i ;
  ?>
      <tr>
        <td class='row3' width="25%"><?php echo "$hour:00:00 - $hour:59:59"; ?></td>
        <td class='row1' style="width:12.5%;"><?php echo $pi==0 ? "-" : number_format($pi); ?></td>
        <td class='row1' style="width:12.5%;"><?php echo $pi==0 ? "-" : number_format(($pi/$pitotal)*100, 1)." %"; ?></td>
        <td class='row1' style="width:12.5%;"><?php echo $vi==0 ? "-" : number_format($vi); ?></td>
        <td class='row1' style="width:12.5%;"><?php echo $vi==0 ? "-" : number_format(($vi/$vitotal)*100, 1)." %"; ?></td>
      </tr>
  <?php
  }
  ?>
    </tbody>
  </table>
<?php
  break;
}
?>
</div>

<?php
// now show pages
$toplimit = !isset($top_howmany) || !is_numeric($top_howmany) ? 10 : $top_howmany ; // figure out the top_limit
?>

<!-- Top xx Documents -->
<div class="tab-page" id="tabPage2">
  <div class="tab">
    <img src='media/images/misc/dot.gif' alt="." />&nbsp;<?php echo $_lang['stats_documents_tab']; ?>
  </div>
  <script type="text/javascript">
    tpSettings.addTabPage(document.getElementById("tabPage2"));
  </script>

  <table width="100%" border="0" cellspacing="1" cellpadding="3" bgcolor="#000000">
    <thead>
      <tr>
        <td colspan="4"><img src='media/images/misc/dot.gif' alt="." />&nbsp;<b><?php printf($_lang['stats_top_pages'], $toplimit); ?></b></td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class='row3'><b><?php echo $_lang['rank']; ?></b></td>
        <td class='row3' width="25%"><b><?php echo $_lang['document']; ?></b></td>
        <td class='row3' width="25%" colspan="2"><b><?php echo $_lang['page_impressions']; ?></b></td>
      </tr>
  <?php
  // get page titles
  $sql = "SELECT id, pagetitle FROM $dbase.".$table_prefix."site_content";
  $rs = mysql_query($sql);
  $pagetitles = array();
  while ($row = mysql_fetch_row($rs)) {
    $pagetitles[$row[0]] = $row[1];
  }

  $sql = "SELECT COUNT(document) AS hits, document FROM $dbase.".$table_prefix."log_access WHERE timestamp > $startdate AND timestamp < $enddate GROUP BY document ORDER BY hits DESC LIMIT $toplimit";
  $rs = mysql_query($sql);
  $actualRows = mysql_num_rows($rs);
  for($i=0;$i<$actualRows; $i++) {
    $tmp = mysql_fetch_assoc($rs);
  ?>
      <tr>
        <td class='row3' style="width:12.5%;"><?php echo $i+1; ?></td>
        <td class='row1'><a href="index.php?a=3&id=<?php echo $tmp['document']; ?>"><?php echo $pagetitles[$tmp['document']]; ?></a></td>
        <td class='row1' style="width:12.5%;"><?php echo $tmp['hits']; ?></td>
        <td class='row1' style="width:12.5%;"><?php echo $tmp['hits']==0 ? "-" : number_format(($tmp['hits']/$pitotal)*100, 1)." %"; ?></td>
      </tr>
  <?php
  }
  if($actualRows==0) {
  ?>
      <tr>
        <td class='row1' colspan="4"><?php echo $_lang['no_results']; ?></td>
      </tr>
  <?php
  }
  ?>
    </tbody>
  </table>
</div>

<!-- Top xx Entry Documents -->
<div class="tab-page" id="tabPage3">
  <div class="tab">
    <img src='media/images/misc/dot.gif' alt="." />&nbsp;<?php echo $_lang['stats_entry_tab']; ?>
  </div>
  <script type="text/javascript">
    tpSettings.addTabPage(document.getElementById("tabPage3"));
  </script>

  <table width="100%" border="0" cellspacing="1" cellpadding="3" bgcolor="#000000">
    <thead>
      <tr>
        <td colspan="4"><img src='media/images/misc/dot.gif' alt="." />&nbsp;<b><?php printf($_lang['stats_top_entrypages'], $toplimit); ?></b></td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class='row3'><b><?php echo $_lang['rank']; ?></b></td>
        <td class='row3' width="25%"><b><?php echo $_lang['document']; ?></b></td>
        <td class='row3' width="25%" colspan="2"><b><?php echo $_lang['visits_started_here']; ?></b></td>
      </tr>
  <?php
  $sql = "SELECT COUNT(document) AS hits, document FROM $dbase.".$table_prefix."log_access WHERE timestamp > $startdate AND timestamp < $enddate AND entry=1 GROUP BY document ORDER BY hits DESC LIMIT $toplimit";
  $rs = mysql_query($sql);
  $actualRows = mysql_num_rows($rs);
  for($i=0;$i<$actualRows; $i++) {
    $tmp = mysql_fetch_assoc($rs);
  ?>
      <tr>
        <td class='row3' style="width:12.5%;"><?php echo $i+1; ?></td>
        <td class='row1'><a href="index.php?a=3&id=<?php echo $tmp['document']; ?>"><?php echo $pagetitles[$tmp['document']]; ?></a></td>
        <td class='row1' style="width:12.5%;"><?php echo $tmp['hits']; ?></td>
        <td class='row1' style="width:12.5%;"><?php echo $tmp['hits']==0 ? "-" : number_format(($tmp['hits']/$vitotal)*100, 1)." %"; ?></td>
      </tr>
  <?php
  }
  if($actualRows==0) {
  ?>
      <tr>
        <td class='row1' colspan="4"><?php echo $_lang['no_results']; ?></td>
      </tr>
  <?php
  }
  ?>
    </tbody>
  </table>
</div>

<!-- Top xx Referrers -->
<div class="tab-page" id="tabPage4">
  <div class="tab">
  <img src='media/images/misc/dot.gif' alt="." />&nbsp;<?php echo $_lang['stats_referrers_tab']; ?>
  </div>
  <script type="text/javascript">
    tpSettings.addTabPage(document.getElementById("tabPage4"));
  </script>

  <table width="100%" border="0" cellspacing="1" cellpadding="3" bgcolor="#000000">
    <thead>
      <tr>
        <td colspan="4"><img src='media/images/misc/dot.gif' alt="." />&nbsp;<b><?php printf($_lang['stats_top_referrers'], $toplimit); ?></b></td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class='row3'><b><?php echo $_lang['rank']; ?></b></td>
        <td class='row3' width="25%"><b><?php echo $_lang['referrer']; ?></b></td>
        <td class='row3' width="25%" colspan="2"><b><?php echo $_lang['page_impressions']; ?></b></td>
      </tr>
  <?php
  $sql = "SELECT COUNT(t1.document) AS hits, t2.data AS referer FROM $dbase.".$table_prefix."log_access AS t1, $dbase.".$table_prefix."log_referers AS t2 WHERE t1.referer = t2.id AND t1.timestamp > $startdate AND t1.timestamp < $enddate AND t2.data!='Unknown' AND t2.data!='Internal' GROUP BY referer ORDER BY hits DESC LIMIT $toplimit";
  $rs = mysql_query($sql);
  $actualRows = mysql_num_rows($rs);
  for($i=0;$i<$actualRows; $i++) {
    $tmp = mysql_fetch_assoc($rs);
    $referer = $tmp['referer'];
    $refererString = $referer!='Internal' && $referer!='Unknown' ? "<a href='$referer' target='_blank'>$referer</a>" : "-";
  ?>
      <tr>
        <td class='row3' style="width:12.5%;"><?php echo $i+1; ?></td>
        <td class='row1'><?php echo $refererString; ?></td>
        <td class='row1' style="width:12.5%;"><?php echo $tmp['hits']; ?></td>
        <td class='row1' style="width:12.5%;"><?php echo $tmp['hits']==0 ? "-" : number_format(($tmp['hits']/$pitotal)*100, 1)." %"; ?></td>
      </tr>
  <?php
  }
  if($actualRows==0) {
  ?>
      <tr>
        <td class='row1' colspan="4"><?php echo $_lang['no_results']; ?></td>
      </tr>
  <?php
  }
  ?>
    </tbody>
  </table>
</div>

<!-- Top xx User Agents -->
<div class="tab-page" id="tabPage5">
  <div class="tab">
  <img src='media/images/misc/dot.gif' alt="." />&nbsp;<?php echo $_lang['stats_ua_tab']; ?>
  </div>
  <script type="text/javascript">
    tpSettings.addTabPage(document.getElementById("tabPage5"));
  </script>

  <table width="100%" border="0" cellspacing="1" cellpadding="3" bgcolor="#000000">
    <thead>
      <tr>
        <td colspan="4"><img src='media/images/misc/dot.gif' alt="." />&nbsp;<b><?php printf($_lang['stats_top_ua'], $toplimit); ?></b></td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class='row3'><b><?php echo $_lang['rank']; ?></b></td>
        <td class='row3' width="25%"><b><?php echo $_lang['ua']; ?></b></td>
        <td class='row3' width="25%" colspan="2"><b><?php echo $_lang['visitors_used_this']; ?></b></td>
      </tr>
  <?php
  $sql = "SELECT COUNT(DISTINCT(t3.visitor)) AS hits, t2.data AS ua FROM $dbase.".$table_prefix."log_visitors AS t1, $dbase.".$table_prefix."log_user_agents AS t2, $dbase.".$table_prefix."log_access AS t3 WHERE t3.visitor = t1.id AND t1.ua_id = t2.id AND t3.timestamp > $startdate AND t3.timestamp < $enddate GROUP BY ua ORDER BY hits DESC LIMIT $toplimit";
  $rs = mysql_query($sql);
  $actualRows = mysql_num_rows($rs);
  for($i=0;$i<$actualRows; $i++) {
    $tmp = mysql_fetch_assoc($rs);
  ?>
      <tr>
        <td class='row3' style="width:12.5%;"><?php echo $i+1; ?></td>
        <td class='row1'><?php echo $tmp['ua']; ?></td>
        <td class='row1' style="width:12.5%;"><?php echo $tmp['hits']; ?></td>
        <td class='row1' style="width:12.5%;"><?php echo $tmp['hits']==0 ? "-" : number_format(($tmp['hits']/$vistotal)*100, 1)." %"; ?></td>
      </tr>
  <?php
  }
  if($actualRows==0) {
  ?>
      <tr>
        <td class='row1' colspan="4"><?php echo $_lang['no_results']; ?></td>
      </tr>
  <?php
  }
  ?>
    </tbody>
  </table>
</div>

<!-- Top xx Operating Systems -->
<div class="tab-page" id="tabPage6">
  <div class="tab">
  <img src='media/images/misc/dot.gif' alt="." />&nbsp;<?php echo $_lang['stats_os_tab']; ?>
  </div>
  <script type="text/javascript">
    tpSettings.addTabPage(document.getElementById("tabPage6"));
  </script>

  <table width="100%" border="0" cellspacing="1" cellpadding="3" bgcolor="#000000">
    <thead>
      <tr>
        <td colspan="4"><img src='media/images/misc/dot.gif' alt="." />&nbsp;<b><?php printf($_lang['stats_top_os'], $toplimit); ?></b></td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class='row3'><b><?php echo $_lang['rank']; ?></b></td>
        <td class='row3' width="25%"><b><?php echo $_lang['os']; ?></b></td>
        <td class='row3' width="25%" colspan="2"><b><?php echo $_lang['visitors_used_this']; ?></b></td>
      </tr>
  <?php
  $sql = "SELECT COUNT(DISTINCT(t3.visitor)) AS hits, t2.data AS os FROM $dbase.".$table_prefix."log_visitors AS t1, $dbase.".$table_prefix."log_operating_systems AS t2, $dbase.".$table_prefix."log_access AS t3 WHERE t3.visitor = t1.id AND t1.os_id = t2.id AND t3.timestamp > $startdate AND t3.timestamp < $enddate GROUP BY os ORDER BY hits DESC LIMIT $toplimit";
  $rs = mysql_query($sql);
  $actualRows = mysql_num_rows($rs);
  for($i=0;$i<$actualRows; $i++) {
    $tmp = mysql_fetch_assoc($rs);
  ?>
      <tr>
        <td class='row3' style="width:12.5%;"><?php echo $i+1; ?></td>
        <td class='row1'><?php echo $tmp['os']; ?></td>
        <td class='row1' style="width:12.5%;"><?php echo $tmp['hits']; ?></td>
        <td class='row1' style="width:12.5%;"><?php echo $tmp['hits']==0 ? "-" : number_format(($tmp['hits']/$vistotal)*100, 1)." %"; ?></td>
      </tr>
  <?php
  }
  if($actualRows==0) {
  ?>
      <tr>
        <td class='row1' colspan="4"><?php echo $_lang['no_results']; ?></td>
      </tr>
  <?php
  }
  ?>
    </tbody>
  </table>
</div>

<!-- Top xx Hosts -->
<div class="tab-page" id="tabPage7">
  <div class="tab">
  <img src='media/images/misc/dot.gif' alt="." />&nbsp;<?php echo $_lang['stats_hosts_tab']; ?>
  </div>
  <script type="text/javascript">
    tpSettings.addTabPage(document.getElementById("tabPage7"));
  </script>

  <table width="100%" border="0" cellspacing="1" cellpadding="3" bgcolor="#000000">
    <thead>
      <tr>
        <td colspan="4"><img src='media/images/misc/dot.gif' alt="." />&nbsp;<b><?php printf($_lang['stats_top_hosts'], $toplimit); ?></b></td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class='row3'><b><?php echo $_lang['rank']; ?></b></td>
        <td class='row3' width="25%"><b><?php echo $_lang['hostname']; ?></b></td>
        <td class='row3' width="25%" colspan="2"><b><?php echo $_lang['visitors_came_from_here']; ?></b></td>
      </tr>
  <?php
  $sql = "SELECT COUNT(DISTINCT(t3.visitor)) AS hits, t2.data AS hostname FROM $dbase.".$table_prefix."log_visitors AS t1, $dbase.".$table_prefix."log_hosts AS t2, $dbase.".$table_prefix."log_access AS t3 WHERE t3.visitor = t1.id AND t1.host_id = t2.id AND t3.timestamp > $startdate AND t3.timestamp < $enddate GROUP BY hostname ORDER BY hits DESC LIMIT $toplimit";
  $rs = mysql_query($sql);
  $actualRows = mysql_num_rows($rs);
  for($i=0;$i<$actualRows; $i++) {
    $tmp = mysql_fetch_assoc($rs);
  ?>
      <tr>
        <td class='row3' style="width:12.5%;"><?php echo $i+1; ?></td>
        <td class='row1'><?php echo $tmp['hostname']; ?></td>
        <td class='row1' style="width:12.5%;"><?php echo $tmp['hits']; ?></td>
        <td class='row1' style="width:12.5%;"><?php echo $tmp['hits']==0 ? "-" : number_format(($tmp['hits']/$vistotal)*100, 1)." %"; ?></td>
      </tr>
  <?php
  }
  if($actualRows==0) {
  ?>
      <tr>
        <td class='row1' colspan="4"><?php echo $_lang['no_results']; ?></td>
      </tr>
  <?php
  }
  ?>
    </tbody>
  </table>
</div>
</div> <!-- tab-pane -->
