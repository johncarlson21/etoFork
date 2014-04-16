<?php

// config.inc.php
// Modified: 2006-12-08  By: Ralph A. Dahlgren
// Modified to create Etomite installation specific session directories
// The front end parser and manager now use separate directories for session storage
// Modified 2008-04-29 By: Ralph A. Dahlgren
// - Added absolute_base_path constant
// - Incorporated PHP constant DIRECTORY_SEPARATOR
// - Added cleanup routine for custom sessions
// Modified: 2008-05-08 [v1.1] by Ralph A. Dahlgren
// - Added $config_release variable for upgrade purposes

/*
 *  // uncomment the lines below to turn on some error logging
 * error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', $_SERVER['DOCUMENT_ROOT'].'/tmp/etofork.errors');
ini_set('display_errors', 1);
ini_set('track_errors', 'On');
ini_set('ignore_repeated_errors','On');

*/

// Etomite database connection parameters
$config_release = "2.5 r239";
$database_type = "mysql";
$database_server = "{HOST}";
$database_user = "{USER}";
$database_password = "{PASS}";
$dbase = "`{DBASE}`";
$table_prefix = "{PREFIX}";

define('dbase', $dbase);
define('table_prefix', $table_prefix);
define('debug', false);



// NO CHANGES REQUIRED BELOW THIS LINE UNLESS CUSTOM SESSIONS NEED TO BE MODIFIED

error_reporting(E_ALL ^ E_NOTICE);

// detect current protocol
$protocol = (isset($_SERVER["HTTPS"]) && strtolower($_SERVER["HTTPS"]) == "on") ? "https://" : "http://";

define("absolute_base_path", '{ABSOLUTE_PATH}/');
define('MANAGER_PATH', '{ABSOLUTE_PATH}/manager/');

define("www_base_path", $protocol . '{WWW_PATH}/');
define("MANAGER_URL", $protocol . '{WWW_PATH}/manager/');

define("ASSETS_PATH", '{ABSOLUTE_PATH}/assets/');
define("ASSETS_URL", $protocol . '{WWW_PATH}/assets/');

define("MODULES_PATH", '{ABSOLUTE_PATH}/modules/');
define("MODULES_URL", $protocol . '{WWW_PATH}/modules/');

$relative_base_path = '{RELATIVE_PATH}/';

define("relative_base_path", $relative_base_path);
define("file_manager_path", str_replace("/", "", $relative_base_path));

// create an installation specific site id and session name
$site_id = str_replace("`","",$dbase)."_" . $table_prefix;

// determine the proper session suffix
if(IN_ETOMITE_PARSER == "true"){
  $site_sessionname = $site_id . "web";
}elseif(IN_ETOMITE_SYSTEM == "true"){
  $site_sessionname = $site_id . "mgr";
}else{
  $site_sessionname = $site_id . "web";
}

// YOU CAN ASSIGN THE DIRECTORY WHERE SESSIONS WILL BE STORED.
// THE $sessdir VARIABLE CAN BE SET TO ANY ABSOLUTE DIRECTORY LOCATION WHERE
// ETOMITE WILL HAVE FULL READ AND WRITE PERMISSIONS.
// EXAMPLES WOULD BE:
// THE ABSOLUTE PATH TO YOUR assets/cache DIRECTORY /var/www/assets/cache
// OR $_SERVER['DOCUMENT_ROOT']."/assets/cache";
// SUBDIRECTORIES WILL BE CREATED FOR BOTH THE FRONT END PARSER AND THE MANAGER
// YOU WILL ALSO NEED TO SET $use_custom_sessions = true; TO ENABLE THE FEATURE
// LEAVE BLANK TO STORE SESSIONS UNDER session.save_path
$sessdir = ""; // no trailing slash

// flag to determine whether or not to use custom session paths [true|false]
$use_custom_sessions = false;

// START: custom session handling

if($use_custom_sessions)
{
  // timeout value for the cookie (seconds * minutes * hours * days)
  // $cookie_timeout = 60 * 30; // in seconds
  // $cookie_timeout = 3600 * 24; // in hours
  $cookie_timeout = 86400 * 7; // in days


  // Provide session handling information

  // path for cookies
  //$cookie_path = "/";
  $cookie_path = relative_base_path;

  // timeout value for the garbage collector
  //   we add 300 seconds, just in case the user's computer clock
  //   was synchronized meanwhile; 600 secs (10 minutes) should be
  //   enough - just to ensure there is session data until the
  //   cookie expires
  $garbage_timeout = $cookie_timeout + 600; // in seconds

  // set the PHP session id (PHPSESSID) cookie to a custom value
  session_set_cookie_params($cookie_timeout, $cookie_path);

  // set the garbage collector - who will clean the session files -
  //   to our custom timeout
  @ini_set('session.gc_maxlifetime', $garbage_timeout);

  // we need a distinct directory for the session files,
  //   otherwise another garbage collector with a lower gc_maxlifetime
  //   will clean our files as well - but in an own directory, we only
  //   clean sessions with our "own" garbage collector (which has a
  //   custom timeout/maxlifetime set each time one of our scripts is
  //   executed)

  // get the current session save path
  $sessdir = ($sessdir != "") ? $sessdir : ini_get('session.save_path');

  // if the session save path doesn't include $site_sessionname then append it
  if(!strpos($sessdir,$site_sessionname))
  {
    $sessdir .= DIRECTORY_SEPARATOR.$site_sessionname;
  }

  // if our desired session directory doesn't exist, create and chmod it
  if(!is_dir($sessdir))
  {
    mkdir($sessdir, 0777);
  }

  // assign our desired session save path
  @ini_set('session.save_path', $sessdir);

}

// if using custom sessions, perform custom general cleanup
if($use_custom_sessions)
{
  // get a list of custom sessions
  foreach(glob($sessdir."/sess_*") as $filename)
  {
    // if the session is empty or expired, delete it
    if(filesize($filename) == 0 || filectime($filename) < time() - $garbage_timeout)
    {
      unlink($filename);
    }
  }
}

// END: custom session handling


// Conceptual credit: MODx CMS ( Etomite Fork )
if(!function_exists("startCMSSession")){
  function startCMSSession(){
    global $site_sessionname;
    session_name($site_sessionname);
    session_start();
  }
}

define('CONFIG_LOADED', true);

?>
