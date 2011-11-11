<?php
// WebFx Menu Generator
if(IN_ETOMITE_SYSTEM!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the Etomite Manager instead of accessing this file directly.");
?>
<html>
<head>
<title>Fancy WebFx navigation menu :)</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $etomite_charset;?>" />

<link rel="stylesheet" type="text/css" href="media/script/iemenu/officexp/officexp.css" />
<link rel="stylesheet" type="text/css" href="media/style/style.css" />
<script type="text/javascript" src="media/script/ieemu.js"></script>

<script type="text/javascript">

var ie55 = /MSIE ((5\.[56789])|([6789]))/.test( navigator.userAgent ) &&
      navigator.platform == "Win32";

if ( !ie55 ) {
  window.onerror = function () {
    return true;
  };
}

function writeNotSupported() {
  if ( !ie55 ) {
    document.write( "<p class=\"warning\">" +
      "This script only works in Internet Explorer 5.5" +
      " or greater for Windows</p>" );
  }
}

function removeWait() {
  try {
    parent.topFrame.document.getElementById('buildText').innerHTML='';
  } catch(oException) {
    x=window.setTimeout('removeWait()', 1000);
  }
}

function openURL(url) {
  url = prompt("<?php echo $_lang['openurl_message'];?>", "http://wiki.etomite.com/");
  if (url!="" && url!=null) {
    parent.main.document.location.href = url;
    xwwd = window.setTimeout('stopIt()', 2000);
  }
}

function openCredits() {
  parent.main.document.location.href = "http://www.etomite.com/credits.html";
  xwwd = window.setTimeout('stopIt()', 2000);
}


function stopIt() {
  top.scripter.stopWork();
}

</script>
<script type="text/javascript" src="media/script/iemenu/poslib.js"></script>
<script type="text/javascript" src="media/script/iemenu/scrollbutton.js"></script>
<script type="text/javascript" src="media/script/iemenu/menu4.js"></script>

</head>
<body onload="removeWait();">

<script type="text/javascript">
function launch() {
  window.open('../');
}
</script>

<?php
// function to read and return document templates as nodes
function getTemplates() {
  global $dbase, $table_prefix;
  $sql = "SELECT * FROM $dbase.".$table_prefix."site_templates ORDER BY templatename ASC;";
  $rs = mysql_query($sql);
  $limit = mysql_num_rows($rs);
  for ($i = 0; $i < $limit; $i++) {
      $row=mysql_fetch_assoc($rs);
      // echo the data retrieved
  echo "editTemplatesMenu.add(tmp2 = new MenuItem('".$row['templatename']."', 'index.php?id=".$row['id']."&a=16', 'media/images/icons/template16.gif'));
  tmp2.target = 'main';
  ";
  echo $_SESSION['permissions']['edit_template']!=1  ? "tmp2.disabled=true\n" : "" ;
  }
}

// function to read and return snippets as nodes
function getSnippets() {
  global $dbase, $table_prefix;
  $sql = "SELECT * FROM $dbase.".$table_prefix."site_snippets ORDER BY name ASC;";
  $rs = mysql_query($sql);
  $limit = mysql_num_rows($rs);
  for ($i = 0; $i < $limit; $i++) {
      $row=mysql_fetch_assoc($rs);
      // echo the data retrieved
  echo "editSnippetsMenu.add(tmp2 = new MenuItem('<span style=\"color:#888\">[[</span>&nbsp;".$row['name']."&nbsp;<span style=\"color:#888\">]]</span>', 'index.php?id=".$row['id']."&a=22', 'media/images/icons/menu_settings.gif'));
  tmp2.target = 'main';
  ";
  echo $_SESSION['permissions']['edit_snippet']!=1 ? "tmp2.disabled=true\n" : "" ;
  }
}

// function to read and return snippets as nodes
function getHTMLSnippets() {
  global $dbase, $table_prefix;
  $sql = "SELECT * FROM $dbase.".$table_prefix."site_htmlsnippets ORDER BY name ASC;";
  $rs = mysql_query($sql);
  $limit = mysql_num_rows($rs);
  for ($i = 0; $i < $limit; $i++) {
    $row=mysql_fetch_assoc($rs);
    // echo the data retrieved
    echo "editHTMLSnippetsMenu.add(tmp2 = new MenuItem('<span style=\"color:#888\">{{</span>&nbsp;".$row['name']."&nbsp;<span style=\"color:#888\">}}</span>', 'index.php?id=".$row['id']."&a=77', 'media/images/icons/menu_settings.gif'));
    tmp2.target = 'main';
    ";
    echo $_SESSION['permissions']['edit_snippet']!=1 ? "tmp2.disabled=true\n" : "" ;
  }
}

// function to read and return users as nodes
function getUsers() {
  global $dbase, $table_prefix;
  $sql = "SELECT * FROM $dbase.".$table_prefix."manager_users ORDER BY username ASC;";
  $rs = mysql_query($sql);
  $limit = mysql_num_rows($rs);
    for ($i = 0; $i < $limit; $i++) {
      $row=mysql_fetch_assoc($rs);
      // echo the data retrieved
      echo "editUsersMenu.add(tmp2 = new MenuItem('".$row['username']."', 'index.php?id=".$row['id']."&a=12&n=".$row['username']."', 'media/images/icons/user.gif'));
      tmp2.target = 'main';
      ";
      echo $_SESSION['permissions']['edit_user']!=1 ? "tmp2.disabled=true\n" : "" ;
    }
}

// function to read and return roles as nodes
function getRoles() {
  global $dbase, $table_prefix;
  $sql = "SELECT * FROM $dbase.".$table_prefix."user_roles ORDER BY name ASC;";
  $rs = mysql_query($sql);
  $limit = mysql_num_rows($rs);
  for ($i = 0; $i < $limit; $i++) {
    $row=mysql_fetch_assoc($rs);
    // echo the data retrieved
    echo "editRolesMenu.add(tmp2 = new MenuItem('".$row['name']."', 'index.php?id=".$row['id']."&a=35', 'media/images/icons/user.gif'));
    tmp2.target = 'main';
    ";
    echo ($_SESSION['permissions']['edit_role']!=1 || $row['id']==1) ? "tmp2.disabled=true\n" : "" ;
  }
}
?>

<script type="text/javascript">

Menu.prototype.cssFile = "media/script/iemenu/officexp/officexp.css";
Menu.prototype.mouseHoverDisabled = false;

var tmp;
var tmp2;
var mb = new MenuBar;

///////////////////////////////////////////////////////////////////////////////
// Site Menu
//

var siteMenu = new Menu();

siteMenu.add( tmp = new MenuItem( "<?php echo $_lang['home']; ?>", "index.php?a=2", "media/images/icons/new1-05.gif") );
  tmp.target="main";
siteMenu.add( tmp = new MenuItem( "<?php echo $_lang['launch_site']; ?>", "javascript:launch();", "media/images/icons/new4-0591.gif") );
siteMenu.add( tmp = new MenuItem( "<?php echo $_lang['refresh_site']; ?>", "index.php?a=26", "media/images/icons/refresh.gif") );
  tmp.target="main";
siteMenu.add( tmp = new MenuItem( "<?php echo $_lang['site_schedule']; ?>", "index.php?a=70", "media/images/icons/date.gif") );
  tmp.target="main";
siteMenu.add( tmp = new MenuItem( "<?php echo $_lang['visitor_stats']; ?>", "index.php?a=68", "media/images/icons/context_view.gif") );
  tmp.target="main";
siteMenu.add( tmp = new MenuItem( "<?php echo $_lang['visitor_stats_online']; ?>", "index.php?a=69", "media/images/icons/context_view.gif") );
  tmp.target="main";
siteMenu.add( new MenuSeparator() );
siteMenu.add( tmp = new MenuItem( "<?php echo $_lang['export_site']; ?>", "index.php?a=83", "media/images/icons/save.gif") );
  tmp.target="main";
siteMenu.add( new MenuSeparator() );
siteMenu.add( tmp = new MenuItem( "<?php echo $_lang['logout']; ?>", "index.php?a=8", "media/images/icons/delete.gif") );
  tmp.target="_top";

mb.add( tmp = new MenuButton( "<?php echo $_lang['site']; ?>", siteMenu ) );

///////////////////////////////////////////////////////////////////////////////
// Content Menu
//

var contentMenu = new Menu();

contentMenu.add( tmp = new MenuItem( "<?php echo $_lang['add_document']; ?>", "index.php?a=4", "media/images/icons/newdoc.gif") );
  tmp.target="main";
<?php echo $_SESSION['permissions']['new_document']!=1 ? "\ttmp.disabled=true;" : "" ; ?>
contentMenu.add( tmp = new MenuItem( "<?php echo $_lang['add_weblink']; ?>", "index.php?a=72", "media/images/icons/weblink.gif") );
  tmp.target="main";
<?php echo $_SESSION['permissions']['new_document']!=1 ? "\ttmp.disabled=true;" : "" ; ?>
contentMenu.add( new MenuSeparator() );
contentMenu.add( tmp = new MenuItem( "<?php echo $_lang['search']; ?>", "index.php?a=71", "media/images/icons/tree_search.gif") );
  tmp.target="main";

mb.add( tmp = new MenuButton( "<?php echo $_lang['content'];?>", contentMenu ) );

///////////////////////////////////////////////////////////////////////////////
// My Etomite Menu
//

var myetomiteMenu = new Menu();

myetomiteMenu.add( tmp = new MenuItem( "<?php echo $_lang['messages']; ?>", "index.php?a=10", "media/images/icons/messages.gif") );
  tmp.target="main";
<?php echo $_SESSION['permissions']['messages']!=1 ? "\ttmp.disabled=true;" : "" ; ?>
myetomiteMenu.add( tmp = new MenuItem( "<?php echo $_lang['change_password']; ?>", "index.php?a=28", "media/images/icons/password.gif") );
  tmp.target="main";
<?php echo $_SESSION['permissions']['change_password']!=1 ? "\ttmp.disabled=true;" : "" ; ?>

mb.add( tmp = new MenuButton( "<?php echo $_lang['my_etomite'];?>", myetomiteMenu ) );

///////////////////////////////////////////////////////////////////////////////
// User Menus
//

  // first generate the submenus
  var editUsersMenu = new Menu();
<?php getUsers(); ?>

  var editRolesMenu = new Menu();
<?php getRoles(); ?>

var usersMenu = new Menu();

usersMenu.add( tmp = new MenuItem( "<?php echo $_lang['user_management_title']; ?>", "index.php?a=75", "media/images/icons/new4-098.gif") );
  tmp.target="main";

usersMenu.add( new MenuSeparator() );

usersMenu.add( tmp = new MenuItem( "<?php echo $_lang['new_user']; ?>", "index.php?a=11", "media/images/icons/user.gif") );
  tmp.target="main";
<?php echo $_SESSION['permissions']['new_user']!=1 ? "\ttmp.disabled=true;" : "" ; ?>
usersMenu.add( tmp = new MenuItem( "<?php echo $_lang['edit_user']; ?>", null, null, editUsersMenu) );
  tmp.target="main";

usersMenu.add( new MenuSeparator() );

usersMenu.add( tmp = new MenuItem( "<?php echo $_lang['new_role']; ?>", "index.php?a=38", "media/images/icons/new4-098.gif") );
  tmp.target="main";
<?php echo $_SESSION['permissions']['new_role']!=1 ? "\ttmp.disabled=true;" : "" ; ?>
usersMenu.add( tmp = new MenuItem( "<?php echo $_lang['edit_role']; ?>", null, null, editRolesMenu) );
  tmp.target="main";

usersMenu.add( new MenuSeparator() );

usersMenu.add( tmp = new MenuItem( "<?php echo $_lang['access_permissions']; ?>", "index.php?a=40", null) );
  tmp.target="main";
<?php echo $_SESSION['permissions']['access_permissions']!=1 ? "\ttmp.disabled=true;" : "" ; ?>

mb.add( tmp = new MenuButton( "<?php echo $_lang['users'];?>", usersMenu ) );

///////////////////////////////////////////////////////////////////////////////
// Resources Menus
//
  // first generate the submenus
  var editTemplatesMenu = new Menu();
<?php getTemplates(); ?>

  var editSnippetsMenu = new Menu();
<?php getSnippets(); ?>

  var editHTMLSnippetsMenu = new Menu();
<?php getHTMLSnippets(); ?>

var resourcesMenu = new Menu();

resourcesMenu.add( tmp = new MenuItem( "<?php echo $_lang['resource_management']; ?>", "index.php?a=76", "media/images/icons/menu_settings.gif") );
  tmp.target="main";

resourcesMenu.add( new MenuSeparator() );

resourcesMenu.add( tmp = new MenuItem( "<?php echo $_lang['new_template']; ?>", "index.php?a=19", "media/images/icons/template16.gif") );
  tmp.target="main";
<?php echo $_SESSION['permissions']['new_template']!=1 ? "\ttmp.disabled=true;" : "" ; ?>
resourcesMenu.add( tmp = new MenuItem( "<?php echo $_lang['edit_template']; ?>", null, null, editTemplatesMenu) );
  tmp.target="main";

resourcesMenu.add( new MenuSeparator() );

resourcesMenu.add( tmp = new MenuItem( "<?php echo $_lang['new_snippet']; ?>", "index.php?a=23", "media/images/icons/menu_settings.gif") );
  tmp.target="main";
<?php echo $_SESSION['permissions']['new_snippet']!=1 ? "\ttmp.disabled=true;" : "" ; ?>
resourcesMenu.add( tmp = new MenuItem( "<?php echo $_lang['edit_snippet']; ?>", null, null, editSnippetsMenu) );
  tmp.target="main";

resourcesMenu.add( new MenuSeparator() );

resourcesMenu.add( tmp = new MenuItem( "<?php echo $_lang['new_htmlsnippet']; ?>", "index.php?a=78", "media/images/icons/menu_settings.gif") );
  tmp.target="main";
<?php echo $_SESSION['permissions']['new_snippet']!=1 ? "\ttmp.disabled=true;" : "" ; ?>
resourcesMenu.add( tmp = new MenuItem( "<?php echo $_lang['edit_htmlsnippet']; ?>", null, null, editHTMLSnippetsMenu) );
  tmp.target="main";

resourcesMenu.add( new MenuSeparator() );
resourcesMenu.add( tmp = new MenuItem( "<?php echo $_lang['keywords']; ?>", "index.php?a=81", null) );
<?php echo $_SESSION['permissions']['new_document']!=1 ? "\ttmp.disabled=true;" : "" ; ?>
  tmp.target="main";

mb.add( tmp = new MenuButton( "<?php echo $_lang['resources'];?>", resourcesMenu ) );

//===============================================================================
// Administration menu

var administrationMenu = new Menu();

administrationMenu.add( tmp = new MenuItem( "<?php echo $_lang['edit_settings']; ?>", "index.php?a=17", "media/images/icons/menu_settings.gif") );
  tmp.target="main";
<?php echo $_SESSION['permissions']['settings']!=1 ? "\ttmp.disabled=true;" : "" ; ?>

administrationMenu.add( tmp = new MenuItem( "<?php echo $_lang['view_sysinfo']; ?>", "index.php?a=53", "media/images/icons/sysinfo.gif") );
  tmp.target="main";
<?php echo $_SESSION['permissions']['settings']!=1 ? "\ttmp.disabled=true;" : "" ; ?>

administrationMenu.add( tmp = new MenuItem( "<?php echo $_lang['view_logging']; ?>", "index.php?a=13", "media/images/icons/logging.gif") );
  tmp.target="main";
<?php echo $_SESSION['permissions']['logs']!=1 ? "\ttmp.disabled=true;" : "" ; ?>

administrationMenu.add( tmp = new MenuItem( "<?php echo $_lang['remove_locks']; ?>", "javascript:top.scripter.removeLocks();", "media/images/icons/lock.gif") );
<?php echo $_SESSION['permissions']['settings']!=1 ? "\ttmp.disabled=true;" : "" ; ?>

administrationMenu.add( tmp = new MenuItem( "<?php echo $_lang['manage_files']; ?>", "index.php?a=31", "media/images/icons/folder.gif") );
  tmp.target="main";
<?php echo $_SESSION['permissions']['file_manager']!=1 ? "\ttmp.disabled=true;" : "" ; ?>

//administrationMenu.add( new MenuSeparator() );

// administrationMenu.add( tmp = new MenuItem( "<?php echo $_lang['manage_modules']; ?>", "index.php?a=84", "media/images/icons/menu_settings.gif") );
// tmp.target="main";
// <?php echo $_SESSION['permissions']['settings']!=1 ? "\ttmp.disabled=true;" : "" ; ?>

mb.add( tmp = new MenuButton( "<?php echo $_lang['administration'];?>", administrationMenu ) );


//===============================================================================
// Help menu

var helpMenu = new Menu();

helpMenu.add(tmp = new MenuItem("<?php echo $_lang['help'];?>", "index.php?a=9", "media/images/icons/b02.gif"));
tmp.target = "main";

helpMenu.add(new MenuSeparator);

helpMenu.add(tmp = new MenuItem("<?php echo $_lang['credits'];?>", "javascript:openCredits();"));

helpMenu.add(tmp = new MenuItem("<?php echo $_lang['about'];?>", "index.php?a=59"));
tmp.target = "main";

helpMenu.add(tmp = new MenuItem("<?php echo $_lang['openurl'];?>", "javascript:openURL('');"));

mb.add( tmp = new MenuButton( "<?php echo $_lang['help'];?>", helpMenu ) );

</script>

<script type="text/javascript">
  writeNotSupported();
</script>

<table width="100%"  border="0" cellspacing="0" cellpadding="0"  class="menu-body">
  <tr style="height: 21px;">
    <td valign="middle">
      <script type="text/javascript">
        mb.write();
      </script>
    </td>
    <td id="button1" align="right" width="1" onclick="top.location='index.php?a=8';" style="background-image:url('media/images/misc/buttonbar.gif');">
      <span class="doSomethingButton"><?php echo $_lang["logout"]; ?></span>
    </td>
  </tr>
</table>

</body>
</html>
