<?php

/***************************************************************************
 Filename: index.php
 Function: This file loads and executes the parser.
/***************************************************************************/

// before we do anything, let's help avoid XSS attacks
$_SERVER['PHP_SELF'] = htmlentities($_SERVER['PHP_SELF']);

// first, set some settings, and do some stuff
$mtime = microtime(); $mtime = explode(" ",$mtime); $mtime = $mtime[1] + $mtime[0]; $tstart = $mtime;
@ini_set('session.use_trans_sid', false);
@ini_set("url_rewriter.tags","");

// header for weird cookie stuff. Blame IE.
//header('P3P: CP="NOI NID ADMa OUR IND UNI COM NAV"');

ob_start();

// set error reporting level (can be changed in a production environment)
error_reporting(E_ALL);
// let scripts know that it is the parser calling
define("IN_ETOMITE_PARSER", "true");
// let scripts know that it is the manager calling
//define("IN_ETOMITE_SYSTEM", "true");

// get the required includes and/or additional classes
// contents of manager/includes/config.inc.php can be copied and pasted here for a small speed increase
@include("./manager/includes/config.inc.php");
// If config.inc.php doesn't exist or isn't complete, display installer link and die
if(empty($database_type) || !file_exists("./manager/includes/config.inc.php"))
{
 die("Please run the Etomite <a href=\"./install/\">install utility</a>!");
}



// create a customized session
startCMSSession();
// initiate a new document parser and additional classes
// $etomite = new etomite;

// changed by John Carlson to add url functionality

$INCPATH = ini_get("include_path").PATH_SEPARATOR.dirname(__FILE__)."/manager/includes/".PATH_SEPARATOR.dirname(__FILE__)."/models/";
ini_set("include_path", $INCPATH);

// include functions file
include_once(MANAGER_PATH . "includes/functions.php");

include_once(MANAGER_PATH . "models/etomite.class.php");

include_once(MANAGER_PATH . "models/etomiteExtender.php");

$etomite = new etomiteExtender;


// set some options
$etomite->printable = "Printable Page"; // Name of Printable Page template
// the following settings are for blocking search bot page hit logging
$etomite->useblockLogging = true;
$etomite->blockLogging = "/(google|bot|msn|slurp|spider|agent|validat|miner|walk|crawl|robozilla|search|combine|theophrastus|larbin|dmoz)/i";
// these settings allow for fine tuning the parser recursion
$etomite->snippetParsePasses = 5; # Original default: 3
$etomite->nonCachedSnippetParsePasses = 5; # Original default: 2
// feed the parser the execution start time
$etomite->tstart = $tstart;
// execute the parser
$etomite->executeParser();

// flush the content buffer
ob_end_flush();
// ANY SETTINGS YOU DIDN'T FIND HERE HAVE BEEN MOVED TO THE CONFIGURATION PANEL
// END: index.php -- Etomite parser
?>
