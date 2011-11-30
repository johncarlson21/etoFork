<?php
/*
 * This file is used by models and other files to indlude the config file
 * and include the etomite class and extenders.
 * this could also be used to include other files needed
 */

// include language file
include_once("./includes/lang/english.inc.php");
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
include_once("functions.php");

include_once(MANAGER_PATH . "models/etomite.class.php");

include_once(MANAGER_PATH . "models/etomiteExtender.php");
startCMSSession();
?>