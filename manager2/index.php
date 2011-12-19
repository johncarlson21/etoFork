<?php
$mtime = microtime();
$mtime = explode(" ",$mtime);
$mtime = $mtime[1] + $mtime[0];
$tstart = $mtime;

$_SERVER['PHP_SELF'] = htmlentities($_SERVER['PHP_SELF']);
// send anti caching headers
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// set error reporting
// error_reporting(E_ALL ^ E_NOTICE);

// set some runtime options this will change
$INCPATH = ini_get("include_path").PATH_SEPARATOR.dirname(__FILE__)."/includes/".PATH_SEPARATOR.dirname(__FILE__)."/models/";
ini_set("include_path", $INCPATH);

@set_magic_quotes_runtime(0);

// define various system constant values
if(!defined("ENT_COMPAT")) define("ENT_COMPAT", 2);
if(!defined("ENT_NOQUOTES")) define("ENT_NOQUOTES", 0);
if(!defined("ENT_QUOTES")) define("ENT_QUOTES", 3);
if(!defined("IN_ETOMITE_SYSTEM")) define("IN_ETOMITE_SYSTEM", "true");

// set the document_root
if(!isset($_SERVER["DOCUMENT_ROOT"]) || empty($_SERVER["DOCUMENT_ROOT"]))
{
  $_SERVER["DOCUMENT_ROOT"] = str_replace($_SERVER["PATH_INFO"], "", ereg_replace("[\][\]", "/", $_SERVER["PATH_TRANSLATED"]))."/";
}
// include language file
$_lang = array();
include_once("./includes/lang/english.inc.php");
$length_eng_lang = count($_lang);
// include_once config file
$config_filename = "./includes/config.inc.php";
if(!file_exists($config_filename))
{
   print "Main configuration file not found. Please run the Etomite installer.<p>Check the documentation for more information.";
   exit;
}

// include the configuration file
include_once($config_filename);

// include functions file
include_once("./includes/functions.php");

include_once("./models/etomite.class.php");

startCMSSession();

$etomite = new etomiteExtender;

if (isset($_REQUEST['logout']) && $_REQUEST['logout'] == 1) {
    $etomite->userLogout();
}

// send the charset header
header('Content-Type: text/html; charset='.$etomite->config['etomite_charset']);

// login check

if(!$etomite->userLoggedIn()){
    // show login form and exit
    include_once("views/login.phtml");
    exit;
}

if(!isset($_SESSION['validated']))
{
  echo "Not Logged In!";
  exit;
}

include_once('views/admin.phtml');
?>