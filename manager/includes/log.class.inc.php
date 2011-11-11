<?php

// logger class.
/*

Usage:

include_once "log.class.inc.php"; // include_once the class
$log = new logHandler; // create the object
$log->initAndWriteLog($msg); // write $msg to log, and populate all other fields as best as possible
$log->initAndWriteLog($msg, $internalKey, $username, $action, $id, $itemname); // write $msg and other data to log

*/

class logHandler{
  // first, declare class variables
  var $msg; // required
  var $internalKey; // required
  var $action; // required
  var $username; // not required
  var $itemId; // not required
  var $itemName; // not required, not used?
  var $loggingError; // internal variable



  function logError($msg) {
    include_once("error.class.inc.php");
    $e = new errorHandler;
    $e->setError(9, "Logging error: ".$msg);
    $e->dumpError();
    return;
  }

  function initAndWriteLog($msg="", $internalKey="", $username="", $action="", $itemid="", $itemname="") {
    $this->msg = $msg=="" ? "" : $msg; // writes testmessage to the object
    $this->internalKey = $internalKey=="" ? $_SESSION['internalKey'] : $internalKey; // writes the key to the object
    $this->username = $_SESSION['shortname']; // writes the key to the object
    $this->action = $action=="" ? $_REQUEST['a'] : $action; // writes the action to the object
    $this->itemId = $itemid=="" ? $_REQUEST['id'] : $itemid; // writes the id to the object
    if($this->itemId==0) $this->itemId="-"; // to stop items having id 0
    $this->itemName = $itemname=="" ? $_SESSION['itemname'] : $itemname; // writes the id to the object
    if($this->itemName=="") $this->itemName="-"; // to stop item name being empty
    $this->itemName = addslashes($this->itemName);
    $this->writeToLog();
    return;
  }


  // function to write to the log
  // collects all required info, and
  // writes it to the logging table
  function writeToLog() {

    global $table_prefix;
    global $dbase;

    if($this->internalKey == "") {
      $this->logError("internalKey not set.");
      return;
    }
    if($this->action == "") {
      $this->logError("action not set.");
      return;
    }
    if($this->msg == "") {
      include_once("actionlist.inc.php");
      $this->msg = getAction($this->action, $this->itemId);
      if($this->msg=="") {
        $this->logError("couldn't find message to write to log.");
        return;
      }
    }

    $sql = "INSERT INTO $dbase.".$table_prefix."manager_log(timestamp, internalKey, username, action, itemid, itemname, message) VALUES('".time()."', '".$this->internalKey."', '".$this->username."'";
    $sql .= ", '".$this->action."', '".$this->itemId."', '".$this->itemName."', '".$this->msg."')";
    if(!$rs=mysql_query($sql)) {
      $this->logError("couldn't save log to table!");
      return true;
    }
  }
}
?>
