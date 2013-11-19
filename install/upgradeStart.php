<?php
// upgradeStart.php
// Etomite CMS upgrade instructions
// Modified: 2006-04-08 by Ralph Dahlgren
// Modified: 2007-05-03 by Ralph Dahlgren
// Modified: 2008-04-25 [v1.0] by Ralph Dahlgren
// Modified 2008-05-08 [v1.1] by Ralph Dahlgren
// Modified 2013-11-06 [v2.0] by John Carlson

session_start();
$_SESSION['session_test'] = 1;
include("../manager/includes/version.inc.php");
$errors = 0;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
  "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <title>etoFork - Installation</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style type="text/css">
      @import url('../assets/site/style.css');
      .ok
      {
        color:green;
        font-weight: bold;
      }
      .notok
      {
        color:red;
        font-weight: bold;
      }
      .labelHolder
      {
        width : 180px;
        float : left;
        font-weight: bold;
      }
    </style>
  <script type="text/javascript" src="extLinks.js"> </script>
</head>

<body>
<table border="0" cellpadding="0" cellspacing="0" class="mainTable">
  <tr class="fancyRow">
    <td><span class="headers">&nbsp;<img src="../manager/images/misc/dot.gif" alt="" style="margin-top: 1px;" />&nbsp;etoFork <?php echo $code_name." v".$release; ?></span></td>
    <td align="right"><span class="headers">Etomite 1.x Upgrade</span></td>
  </tr>
  <tr class="fancyRow2">
    <td colspan="2" class="border-top-bottom smallText" align="right">&nbsp;</td>
  </tr>
  <tr align="left" valign="top">
    <td colspan="2"><table width="100%"  border="0" cellspacing="0" cellpadding="1">
      <tr align="left" valign="top">
        <td class="pad" id="content" colspan="2">

          <h1>Releases Not Supported</h1>

          <p>Pre Etomite 1.0 is not supported!</p>

          <h1>Performing System Checks</h1>

<?php
echo "Checking if <span class='mono'>manager/includes/config.inc.php</span> file exists: ";
if(!file_exists("../manager/includes/config.inc.php")) {
  echo "<span class='notok'>Failed!</span>";
  $errors += 1;
  $noConfig = true;
} else {
  echo "<span class='ok'>OK!</span>";
}

if($noConfig != true) {
  echo "<br />Checking if <span class='mono'>manager/includes/config.inc.php</span> is writable: ";
  if(!is_writable("../manager/includes/config.inc.php")) {
    echo "<span class='notok'>Failed!</span>";
    $errors += 1;
    $notWritable = true;
  } else {
    echo "<span class='ok'>OK!</span>";
  }

  echo "<br />Checking if <span class='mono'>manager/includes/config.inc.php</span> is valid: ";
  @include("../manager/includes/config.inc.php");
  // If config.inc.php doesn't exist or isn't complete, display installer link and die
  if(empty($database_server)
  || empty($database_user)
  || empty($database_user)
  || empty($database_password)
  || empty($dbase)
  || empty($table_prefix)
  )
  {
    echo "<span class='notok'>Failed!</span>";
    $errors += 1;
    $notValid = true;
  } else {
    echo "<span class='ok'>OK!</span>";
  }
}

if($errors > 0) {
?>

<p>Unfortunately, the etoFork install / upgrade cannot continue at the moment, due to the above <?php echo $errors > 1 ? $errors." " : "" ; ?>error<?php echo $errors > 1 ? "s" : "" ; ?>. Please correct the error<?php echo $errors > 1 ? "s" : "" ; ?>, and <a href="./upgradeStart.php" title="Try Again">try again</a>. If the explanation below doesn't help, please visit the  <a class="external" href="http://www.etomite.com/forums" title="Get Help Now" target="_blank">Etomite Forums</a>.</p>

<?php
if($noConfig) {
?>

<p>The configuration file was not found. There are two main reasons why the file may be missing - either you have deleted or moved this file, or you have accidentally chosed to upgrade instead of performing a new installation.</p>

<?php } ?>

<?php
if($notWritable) {
?>

<p>The <span class='mono'>manager/includes/config.inc.php</span> file must be writable so that this script can make any required changes to that file.</p>

<?php } ?>

<?php
if($notValid) {
?>

<p>Because you have chosen to upgrade a pervious Etomite installation a valid configuration file should already be in place. While a configuration file was found, it does not currently contain all of the required valid settings.</p>

<?php } ?>

<?php
if($noConfig || $notValid) {
?>

<p>If you have accidentally deleted this file, and don't have a backup, you can edit <span class='mono'>manager/includes/config.inc.generic.php</span> by replacing each of the placeholder <b>{TAG}</b>'s with the appropriate database settings for your installation and save it as <span class='mono'>manager/includes/config.inc.php</span> and then <a href="./upgradeStart.php" title="Try Again">try again</a>. If custom sessions were being used then those settings will also need to be reset.</p>

<?php } ?>

<p>If you are attempting to perform a <b>New installation</b>, please <a href="./index.php" title="Go Back">go back</a> and make the proper selection.</p>

<?php
// everything checks out ok so we can proceed with the upgrade
} else {
  // check the current configuration file to see if it needs to be upgraded
  echo "<br />Checking if <span class='mono'>manager/includes/config.inc.php</span> file needs to be upgraded: ";
  // need to overwrite the absolute_base_path before it is loaded.
  define('absolute_base_path', dirname(dirname(__FILE__)));
  include("../manager/includes/config.inc.php");
  if($config_release != $release) {
    // need to update the config file
	echo "<p>Updating configuration file: ";
	// read in the config.inc.php template
	$filename = "./config.inc.php";
	$handle = fopen($filename, "r");
	$contents = fread($handle, filesize($filename));
	fclose($handle);
	
	// perform global search and replace of tags in the SQL
	$self = str_replace("/install/", "", $_SERVER["PHP_SELF"]);
	$self = str_replace(basename(__FILE__), "", $self);
	$urlPieces = explode("/", $self);
	
	$www = $_SERVER['HTTP_HOST'];
	$www_url = $www . implode("/", $urlPieces);
	$relative_path = implode("/", $urlPieces);
	
	$dbase = str_replace("`","",$dbase);
	
	$search = array('{HOST}','{USER}','{PASS}','{DBASE}','{PREFIX}','{ABSOLUTE_PATH}','{WWW_PATH}','{RELATIVE_PATH}');
	$replace = array($database_server,$database_user,$database_password,$dbase,$table_prefix,dirname(dirname(__FILE__)),$www_url,$relative_path);
	$configString = str_replace($search,$replace,$contents);
	
	// open config.inc.php
	$filename = '../manager/includes/config.inc.php';
	$configFileFailed = false;
	if (@!$handle = fopen($filename, 'w'))
	{
	  $configFileFailed = true;
	}
	
	// write $configString to our opened file.
	if(@fwrite($handle, $configString) === FALSE)
	{
	  $configFileFailed = true;
	}
	@fclose($handle);
	
	if ($configFileFailed == true) {
		echo "<span class='notok'>Failed!</span></p>";
	} else {
		echo "<span class='ok'>OK!</span></p>";
		
		echo "<p>Updating Database Tables:<br />";
		
		// load the sqlParser class and attempt to load the desired SQL file
		include("sqlParser.class.php");
		$sqlFile = "sql/from_1.1.sql";
		$sqlParser = new SqlParser($database_server, $database_user, $database_password, $dbase, $table_prefix, '', '');
		$sqlParser->connect();
		$sqlParser->process($sqlFile);
		$sqlParser->close();
		
		if($sqlParser->installFailed==true) {
		  echo "<span class='notok'>Failed!</span> - Installation failed!</p>";
		  $errors += 1;
		  echo "<p>etoFork setup couldn't update the database. The last error to occur was <i>".$sqlParser->mysqlErrors[count($sqlParser->mysqlErrors)-1]['error']."</i> during the execution of SQL statement <span class=\"mono\">".strip_tags($sqlParser->mysqlErrors[count($sqlParser->mysqlErrors)-1]['sql'])."</span></p>";
		  echo $pageFooter;
		  exit;
		}else{
		  echo "<span class='ok'>OK!</span></p>";
		  
		  // refresh site cache
		  include("../manager/includes/config.inc.php");
		  include_once('../manager/models/etomite.class.php');
		  $etomite = new etomite;
		  $etomite->syncsite();
		}
	}
  } else {
    echo "<span class='ok'>OK!</span>";
  }
?>
      <p></p>
      <h1>Upgrade from Etomite v 1.0 and newer</h1>

      <p>Everything should be updated.</p>
      <p>Once in the manager you need to clear the site cache. "Site -> Clear Cache".<br />This will reload the system configuration.</p>
      <p>Please login to the <a href="../manager">manager</a>.</p>

<?php } ?>

    </td>
      </tr>
    </table></td>
  </tr>
  <tr class="fancyRow2">
    <td class="border-top-bottom smallText">&nbsp;</td>
    <td class="border-top-bottom smallText" align="right">&nbsp;</td>
  </tr>
</table>
</body>
</html>