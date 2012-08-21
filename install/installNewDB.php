<?php
// installNewDB.php
// Performs a new installation of the Etomite CMS
// Last Modified: 2008-04-08 [v1.0] by Ralph A. Dahlgren

session_start();
// the SQL file to import
$sqlFile = "sql/".$_SESSION['sqlFile'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>etoFork - Installation</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta name="robots" content="noindex, nofollow" />
<link type="text/css" href="../manager/css/smoothness/jquery-ui-1.8.16.custom.css" rel="stylesheet" />
<link href="../manager/css/manager_style.css" media="screen" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>
<script type="text/javascript" src="../manager/js/etomite.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    Etomite.inAdmin = false;
    Etomite.init();
});
</script>
<style type="text/css">
.error {
    font-size: 11px;
    color: #F00;
    text-align: center;
    font-weight: bold;
}
</style>
<style type="text/css">
.ok { color:green; }
.notok { color:red; }
.labelHolder {
    width : 210px;
    display : inline-block;
    font-weight: bold;
}
</style>
<script type="text/javascript" src="extLinks.js"> </script>
</head>
<body id="installPage">
    <div class="header">etoFork - Installation</div>
    <div class="wrapper" id="mainContent">
        <div class="login-box" style="margin: 20px 0;">
            <div class="login-info">
                <div class="eto-logo"><img src='../manager/images/misc/etofork_logo.png' alt='<?php echo $release; ?>' title='<?php echo $release; ?>' /></div>
                <div class="eto-login-msg"></div>
                <div style="clear:both"></div>
            </div>
            <div class="eto-install-msg">
<?php

if(!isset($_POST['licenseOK']) || empty($_POST['licenseOK']))
{
  echo "<p>You need to agree to the license before proceeding with the setup!</p>";
?>
            </div>
        </div>
    </div>
</body>
</html>
<?php
  exit;
}

echo "<h2>Etomite setup will now attempt to setup the database</h2>";

$create = false;
$errors = 0;

// get db info from session
$host = $_SESSION['databasehost'];
$name = $_SESSION['databaseloginname'];
$pass = $_SESSION['databaseloginpassword'];
$db = $_SESSION['databasename'];
$table_prefix = $_SESSION['tableprefix'];
$adminname = $_SESSION['cmsadmin'];
$adminpass = $_SESSION['cmspassword'];

// attempt to connect to the MySQL server
echo "<p>Creating connection to the database: ";
if(!@$conn = mysql_connect($host, $name, $pass))
{
  echo "<span class='notok'>Failed!</span></p><p>Please check the database login details and try again.</p>";
  echo $pageFooter;
  exit;
}
else
{
  echo "<span class='ok'>OK!</span></p>";
}

// attempt to connect to the desired database
echo "<p>Selecting database `".$db."`: ";
if(!@mysql_select_db($db, $conn))
{
  echo "<span class='notok'>Failed...</span> - database does not exist. Will attempt to create:</p>";
  $errors += 1;
  $create = true;
}
else
{
  echo "<span class='ok'>OK!</span></p>";
}

// attempt to create the database
if($create)
{
  echo "<p>Creating database `".$db."`: ";
  if(!@mysql_create_db($db, $conn))
  {
    echo "<span class='notok'>Failed!</span> - Could not create database!</p>";
    $errors += 1;
    echo "<p>Etomite setup could not create the database, and no existing database with the same name was found. </p><p>Please create a database, and run setup again.</p>";
    echo $pageFooter;
    exit;
  }
  else
  {
    echo "<span class='ok'>OK!</span></p>";
  }
}

// cehck to see if the desired table prefix is alreay in use
echo "<p>Checking table prefix `".$table_prefix."`: ";
if(@$rs=mysql_query("SELECT COUNT(*) FROM $db.".$table_prefix."site_content"))
{
  echo "<span class='notok'>Failed!</span> - Table prefix is already in use in this database!</p>";
  $errors += 1;
  echo "<p>Etomite setup couldn't install into the selected database, as it already contains Etomite tables. Please choose a new table_prefix, and run setup again.</p>";
  echo $pageFooter;
    exit;
}
else
{
  echo "<span class='ok'>OK!</span></p>";
}

// load the sqlParser class and attempt to load the desired SQL file
include("sqlParser.class.php");
$sqlParser = new SqlParser($host, $name, $pass, $db, $table_prefix, $adminname, $adminpass);
$sqlParser->connect();
$sqlParser->process($sqlFile);
$sqlParser->close();

// handle errors
echo "<p>Importing default site: ";

if($sqlParser->installFailed==true) {
  echo "<span class='notok'>Failed!</span> - Installation failed!</p>";
  $errors += 1;
  echo "<p>Etomite setup couldn't install the default site into the selected database. The last error to occur was <i>".$sqlParser->mysqlErrors[count($sqlParser->mysqlErrors)-1]['error']."</i> during the execution of SQL statement <span class=\"mono\">".strip_tags($sqlParser->mysqlErrors[count($sqlParser->mysqlErrors)-1]['sql'])."</span></p>";
  echo $pageFooter;
  exit;
}
else
{
  echo "<span class='ok'>OK!</span></p>";
}

// attempt to write the manager/includes/config.inc.php file
echo "<p>Writing configuration file: ";
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

$search = array('{HOST}','{USER}','{PASS}','{DBASE}','{PREFIX}','{ABSOLUTE_PATH}','{WWW_PATH}','{RELATIVE_PATH}');
$replace = array($host,$name,$pass,$db,$table_prefix,dirname(dirname(__FILE__)),$www_url,);
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

// display config file write error or success message
if($configFileFailed==true)
{
  echo "<span class='notok'>Failed!</span></p>";
  $errors += 1;
  echo "<p>Etomite couldn't write the config file. Please copy the following into the <span class=\"mono\">manager/includes/config.inc.php</span> file:</p>
  <textarea style=\"width:400px; height:160px;\">
  $configString
  </textarea>
  Once that's been done, you can log into Etomite by pointing your browser at yoursite/manager/.</p>";
  echo $pageFooter;
  exit;
}
else
{
  echo "<span class='ok'>OK!</span></p>";
}

// installation completed successfully
echo "<p>Installation was successful! You can now log into the <a href=\"../manager/\"><b><u>Etomite manager</u></b></a>. First thing you need to do is to update and save the Etomite configuration. Etomite will ask you to do so once you've logged in.</p><p>Please make sure you CHMOD the config.inc.php file so it is not writeable by anyone other than yourself... Also, don't forget to remove the installer folder, as it is no longer needed.</p>";
?>
            </div>
        </div>
    </div>
</body>
</html>