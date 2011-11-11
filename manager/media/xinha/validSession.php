<?php
// validSession.php
// Determines whether a valid front end or manager session is present.
// Returns TRUE or FALSE
// This script can be used to validate whether other scripts should be run.
// Originally created to avoid Xinha Image/File Manager security exploits.
// Requires that manager/includes/config.inc.php be included before this file.
// Created 2008-05-14 [v1.2] by Ralph Dahlgren

$validSession = false;

// create installation specific site id for front end and manager based on
// config.inc.php settings already included
$mgr = trim($dbase, "`")."_".$table_prefix."mgr";
$web = trim($dbase, "`")."_".$table_prefix."web";

// if the request was from within the manager/ check the manager session
if(strstr($_SERVER['HTTP_REFERER'], "manager/"))
{
  if(!empty($_COOKIE[$mgr]))
  {
    session_name($mgr);
    session_id($_COOKIE[$mgr]);
    session_start();

    if(!empty($_SESSION['validated']))
    {
      $validSession = true;
    }
    else
    {
      $_SESSION = array();
      $_SESSION = "";
      session_destroy();
      $validSession = false;
    }
  }
}

// if the request was from outside the manager/ check the front end session
if(!strstr($_SERVER['HTTP_REFERER'], "manager/"))
{
  if(!empty($_COOKIE[$web]))
  {
    session_name($web);
    session_id($_COOKIE[$web]);
    session_start();

    if(!empty($_SESSION['validated']))
    {
      $validSession = true;
    }
    else
    {
      $_SESSION = array();
      $_SESSION = "";
      session_destroy();
      $validSession = false;
    }
  }
}

?>