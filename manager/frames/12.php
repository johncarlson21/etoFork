<?php
// Generates bar at top of the manager page
if(IN_ETOMITE_SYSTEM!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the Etomite Manager instead of accessing this file directly.");
$enable_debug=false;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <title>Frame 12</title>
  <link rel="stylesheet" type="text/css" href="media/style/style.css" />
  <meta http-equiv="Content-Type" content="text/html; charset=<?php echo $etomite_charset; ?>">

<!-- Beginning of compulsory code below -->

<link href="media/css_drop_down/css/dropdown/dropdown.linear.css" media="screen" rel="stylesheet" type="text/css" />
<link href="media/css_drop_down/css/dropdown/themes/flickr.com/default.ultimate.linear.css" media="screen" rel="stylesheet" type="text/css" />
<!-- file to overwrite default theme settings -->
<link href="media/css_drop_down/css/manager_styles.css" media="screen" rel="stylesheet" type="text/css" />
<!--[if lt IE 7]>
<script type="text/javascript" src="media/css_drop_down/js/jquery/jquery.js"></script>
<script type="text/javascript" src="media/css_drop_down/js/jquery/jquery.dropdown.js"></script>
<![endif]-->

<!-- / END -->
<script type="text/javascript">
function openCredits() {
  parent.main.document.location.href = "http://www.etomite.com/credits.html";
  xwwd = window.setTimeout('stopIt()', 2000);
}

function stopIt() {
  top.scripter.stopWork();
}
</script>
</head>
<body class="topFrame"><img src='media/images/misc/topbarlogo.gif' class='topBarLogo' title='<?php echo $full_appname; ?>'/>
        <div id="tocText"></div>
        <span id="workText">&nbsp;<img src='media/images/icons/delete.gif' align='absmiddle' width='16' height='16'>&nbsp;<?php echo $_lang['working']; ?></span>
        <span id="buildText">&nbsp;&nbsp;<img src='media/images/icons/b02.gif' align='absmiddle' width='16' height='16'>&nbsp;<?php echo $_lang['loading_doc_tree']; ?></span>
        <span id="appnameText"><?php echo $full_appname; ?></span>
    <div style="clear:both;"></div>
    <!-- NEW TOP MENU -->
    <ul id="nav" class="dropdown dropdown-linear">
        <li><a onclick="this.blur();" href="index.php?a=2" target="main"><?php echo $_lang["home"]; ?></a></li>
        <li><span class="dir"><?php echo $_lang["site"]; ?></span>
            <ul>
                <li><a onclick="this.blur();" href="../?z=manprev" target="_blank"><?php echo $_lang["launch_site"]; ?></a></li>
                <li><a onclick="this.blur();" href="index.php?a=26" target="main"><?php echo $_lang["refresh_site"]; ?></a></li>
                <li><a onclick="this.blur();" href="index.php?a=70" target="main"><?php echo $_lang["site_schedule"]; ?></a></li>
                <li><a onclick="this.blur();" href="index.php?a=68" target="main"><?php echo $_lang["visitor_stats"]; ?></a></li>
                <li><a onclick="this.blur();" href="index.php?a=69" target="main"><?php echo $_lang["visitor_stats_online"]; ?></a></li>
            </ul>
        </li>
        <?php  if($_SESSION['permissions']['new_document']==1) { ?>
        <li><span class="dir"><?php echo $_lang["content"]; ?></span>
            <ul>
                <li><a onclick="this.blur();" href="index.php?a=4" target="main"><?php echo $_lang["add_document"]; ?></a></li>
                <li><a onclick="this.blur();" href="index.php?a=72" target="main"><?php echo $_lang["add_weblink"]; ?></a></li>
            </ul>
        </li>
        <?php } ?>
        <?php if($_SESSION['permissions']['messages']==1 || $_SESSION['permissions']['change_password']==1) { ?>
        <li><span class="dir"><?php echo $_lang["my_etomite"]; ?></span>
            <ul>
            <?php if($_SESSION['permissions']['messages']==1) { ?>
                <li><a onclick="this.blur();" href="index.php?a=10" target="main"><?php echo $_lang["messages"]; ?></a></li>
            <?php } ?>
            <?php if($_SESSION['permissions']['change_password']==1) { ?>
                <li><a onclick="this.blur();" href="index.php?a=28" target="main"><?php echo $_lang["change_password"]; ?></a></li>
            <?php } ?>
            </ul>
        </li>
        <?php } ?>
        <?php if($_SESSION['permissions']['new_user']==1 || $_SESSION['permissions']['edit_user']==1 || $_SESSION['permissions']['new_role']==1 || $_SESSION['permissions']['edit_role']==1 || $_SESSION['permissions']['access_permissions']==1) { ?>
        <li><span class="dir"><?php echo $_lang["users"]; ?></span>
            <ul>
            <?php if($_SESSION['permissions']['edit_user']==1) { ?>
                <li><a onclick="this.blur();" href="index.php?a=75" target="main"><?php echo $_lang["user_management_title"]; ?></a></li>
            <?php } ?>
            <?php if($_SESSION['permissions']['access_permissions']==1) { ?>
                <li><a onclick="this.blur();" href="index.php?a=40" target="main"><?php echo $_lang["access_permissions"]; ?></a></li>
            <?php } ?>
            </ul>
        </li>
        <?php } ?>
        <?php if($_SESSION['permissions']['new_template']==1 || $_SESSION['permissions']['edit_template']==1 || $_SESSION['permissions']['new_snippet']==1 || $_SESSION['permissions']['edit_snippet']==1) { ?>
        <li><a onclick="this.blur();" href="index.php?a=76" target="main"><?php echo $_lang["resource_management"]; ?></a></li>
        <?php } ?>
        <li><span class="dir"><?php echo $_lang['module_management']; ?></span>
            <?php
            echo $etomite->buildAdminModuleMenu();
            ?>
        </li>
        <?php if($_SESSION['permissions']['settings']==1 || $_SESSION['permissions']['edit_parser']==1 || $_SESSION['permissions']['logs']==1 || $_SESSION['permissions']['file_manager']==1 || $_SESSION['permissions']['export_html']==1) { ?>
        <li><span class="dir"><?php echo $_lang["administration"]; ?></span>
            <ul>
            <?php if($_SESSION['permissions']['settings']==1) { ?>
                <li><a onclick="this.blur();" href="index.php?a=17" target="main"><?php echo $_lang["edit_settings"]; ?></a></li>
                <li><a onclick="this.blur();" href="index.php?a=53" target="main"><?php echo $_lang["view_sysinfo"]; ?></a></li>
                <li><a onclick="this.blur();" href="javascript:top.scripter.removeLocks();"><?php echo $_lang["remove_locks"]; ?></a></li>
            <?php } ?>
            <?php if($_SESSION['permissions']['logs']==1) { ?>
                <li><a onclick="this.blur();" href="index.php?a=13" target="main"><?php echo $_lang["view_logging"]; ?></a></li>
            <?php } ?>
            <?php if($_SESSION['permissions']['file_manager']==1) { ?>
                <li><a onclick="this.blur();" href="index.php?a=31" target="main"><?php echo $_lang["manage_files"]; ?></a></li>
            <?php } ?>
            <?php   if($_SESSION['permissions']['export_html']==1) { ?>
                <li><a onclick="this.blur();" href="index.php?a=83" target="main"><?php echo $_lang["export_site"]; ?></a></li>
            <?php } ?>
            </ul>
        </li>
        <?php } ?>
        <?php if($_SESSION['permissions']['help']==1) { ?>
        <li><span class="dir"><?php echo $_lang["help"]; ?></span>
            <ul>
                <li><a onclick="this.blur();" href="javascript:openCredits();"><?php echo $_lang["credits"]; ?></a></li>
                <li><a onclick="this.blur();" href="index.php?a=9" target="main"><?php echo $_lang["help"]; ?></a></li>
                <li><a onclick="this.blur();" href="index.php?a=59" target="main"><?php echo $_lang["about"]; ?></a></li>
            </ul>
        </li>
        <?php } ?>
        <li><a onclick="this.blur();" href="index.php?a=8" target="_top"><?php echo $_lang["logout"]; ?></a></li>
    </ul>
</body>
</html>
