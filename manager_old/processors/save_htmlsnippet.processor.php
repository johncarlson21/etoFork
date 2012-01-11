<?php
// save_htmlsnippet.processor.php

if(IN_ETOMITE_SYSTEM != "true")
{
  die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the Etomite Manager instead of accessing this file directly.");
}

if($_SESSION['permissions']['save_snippet'] != 1 && $_REQUEST['a'] == 79)
{
  $e->setError(3);
  $e->dumpError();
}

function createBackup($name,$snippet){
	@file_put_contents("../assets/site/chunks/".$name.".php",$snippet);
}

switch ($_POST['mode'])
{
  case '78':
    //do stuff to save the new doc
    $snippet = mysql_escape_string($_POST['post']);
    $name = mysql_escape_string(htmlentities($_POST['name']));
    $description = mysql_escape_string(htmlentities($_POST['description']));
	$section = (int)$_POST['section'];
    $locked = $_POST['locked'] == 'on' ? 1 : 0 ;
    if($name == "")
    {
      $name = "Untitled HTMLSsnippet";
    }
    $sql = "INSERT INTO $dbase.".$table_prefix."site_htmlsnippets(name, description, snippet, locked, section) VALUES('".$name."', '".$description."', '".$snippet."', '".$locked."',".$section.");";
    $rs = mysql_query($sql);
    if(!$rs)
    {
      echo "\$rs not set! New htmlsnippet not saved!";
    }
    else
    {
      // get the id
      if(!$newid = mysql_insert_id())
      {
        echo "Couldn't get last insert key!";
        exit;
      }
	  
	  // create backup file
	  	createBackup($name,$_POST['post']);
		
      // empty cache
      include_once("cache_sync.class.processor.php");
      $sync = new synccache();
      $sync->setCachepath("../assets/cache/");
      $sync->setReport(false);
      $sync->emptyCache(); // first empty the cache
      // finished emptying cache - redirect
      if($_POST['stay'] != '')
      {
        $header="Location: index.php?a=77&id=$newid&r=2";
        header($header);
      }
      else
      {
        $header = "Location: index.php?a=76&r=2";
        header($header);
      }
    }
  break;

  case '77':
    //do stuff to save the edited doc
    $snippet = mysql_escape_string($_POST['post']);
    $name = mysql_escape_string(htmlentities($_POST['name']));
    $description = mysql_escape_string(htmlentities($_POST['description']));
	$section = (int)$_POST['section'];
    $locked = $_POST['locked'] == 'on' ? 1 : 0 ;
    if($name == "")
    {
      $name = "Untitled snippet";
    }
    $id = $_POST['id'];
    $sql = "UPDATE $dbase.".$table_prefix."site_htmlsnippets SET name='".$name."', description='".$description."', snippet='".$snippet."', locked='".$locked."', section=".$section." WHERE id='".$id."';";
    $rs = mysql_query($sql);
    if(!$rs)
    {
      echo "\$rs not set! Edited htmlsnippet not saved!";
    }
    else
    {
		// added by john carlson to save versions
		$sql = "INSERT INTO $dbase.".$table_prefix."site_htmlsnippets_versions (date_mod,htmlsnippet_id,name, description, snippet, locked, section) VALUES(NOW(),".$id.",'".$name."', '".$description."', '".$snippet."', '".$locked."',".$section.");";
    	$rs = @mysql_query($sql);
		
		// create backup file
	  	createBackup($name,$_POST['post']);
	
      // empty cache
      include_once("cache_sync.class.processor.php");
      $sync = new synccache();
      $sync->setCachepath("../assets/cache/");
      $sync->setReport(false);
      $sync->emptyCache(); // first empty the cache
      // finished emptying cache - redirect
      if($_POST['stay'] != '')
      {
        $header="Location: index.php?a=77&id=$id&r=2";
        header($header);
      }
      else
      {
        $header="Location: index.php?a=76&r=2";
        header($header);
      }
    }
  break;

  default: echo "You supposed to be here now?";
}
?>
