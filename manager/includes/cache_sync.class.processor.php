<?php
// cache_sync.class.processor.php
// cache & synchronise class
// Modified 2008-04-25 [v1.0] by Ralph A. Dahlgren
// * Modified code to use $this->cachePath properly

class synccache{
  var $cachePath;
  var $showReport;
  var $deletedfiles = array();
  var $etomite;
  var $db;
  var $_lang;
  
  function __construct($etomite, $lang) {
      $this->etomite = $etomite;
      $this->_lang = $lang;
      $this->db = $etomite->db;
  }

  function setCachepath($path) {
    $this->cachePath = $path;
  }

  function setReport($bool) {
    $this->showReport = $bool;
  }

  function emptyCache() {
    if(!isset($this->cachePath)) {
      echo "Cache path not set.";
      exit;
    }
    $filesincache = 0;
    $deletedfilesincache = 0;
	// normal cache directory for settings and documents cache
    if ($handle = opendir($this->cachePath)) {
      while (false !== ($file = readdir($handle))) {
        if ($file != "." && $file != "..") {
          $filesincache += 1;
          if (preg_match ("/\.etoCache/", $file)) {
            $deletedfilesincache += 1;
            $deletedfiles[] = $file;
            @unlink($this->cachePath.$file);
          }
        }
      }
      closedir($handle);
    }
	// empty chunks cache directory
	if ($handle = opendir($this->cachePath."chunks/")) {
      while (false !== ($file = readdir($handle))) {
        if ($file != "." && $file != ".." && $file != "index.html") {
          $filesincache += 1;
          if (preg_match ("/\.etoCache/", $file)) {
            $deletedfilesincache += 1;
			// don't output the chunk cache file name
            //$deletedfiles[] = $file;
            @unlink($this->cachePath."chunks/".$file);
          }
        }
      }
      closedir($handle);
    }
	
	// empty snippets cache directory
	if ($handle = opendir($this->cachePath."snippets/")) {
      while (false !== ($file = readdir($handle))) {
        if ($file != "." && $file != ".." && $file != "index.html") {
          $filesincache += 1;
          if (preg_match ("/\.etoCache/", $file)) {
            $deletedfilesincache += 1;
			// don't output the snippet cache file name
            //$deletedfiles[] = $file;
            @unlink($this->cachePath."snippets/".$file);
          }
        }
      }
      closedir($handle);
    }
	

/****************************************************************************/
/*  BUILD CACHE FILES                            */
/****************************************************************************/
    // SETTINGS & DOCUMENT LISTINGS CACHE

    $tmpPHP = "<?php\n";

    // get settings
    $sql = "SELECT * FROM ".$this->db."system_settings";
    $rs = $this->etomite->dbQuery($sql);
    $limit_tmp = $this->etomite->recordCount($rs);
    while(list($key,$value) = $this->etomite->fetchRow($rs, 'num')) {
       $tmpPHP .= '$this->config[\''.$key.'\']'."='".str_replace("'", "\'", $value)."';\n";
    }

    // get aliases
    // $sql = "SELECT id, alias, template FROM ".$this->db."site_content WHERE LENGTH(".$this->db."site_content.alias) > 1";
    $sql = "SELECT id, alias, template, parent, authenticate FROM ".$this->db."site_content";
    $rs = $this->etomite->dbQuery($sql);
    $limit_tmp = $this->etomite->recordCount($rs);
    for ($i_tmp=0; $i_tmp<$limit_tmp; $i_tmp++) {
       $tmp1 = $this->etomite->fetchRow($rs);
       if($tmp1['alias']!="") {
         $tmpPHP .= '$this->documentListing[\''.$tmp1['alias'].'\']'." = ".$tmp1['id'].";\n";
       }
       $tmpPHP .= '$this->aliasListing[]'."=array('id'=>".$tmp1['id'].",'alias'=>'".$tmp1['alias']."','template'=>".$tmp1['template'].",'parent'=>".$tmp1['parent'].",'authenticate'=>".$tmp1['authenticate'].");\n";
    }

    // get content types
    $sql = "SELECT id, contentType FROM ".$this->db."site_content";
    $rs = $this->etomite->dbQuery($sql);
    $limit_tmp = $this->etomite->recordCount($rs);
    for ($i_tmp=0; $i_tmp<$limit_tmp; $i_tmp++) {
       $tmp1 = $this->etomite->fetchRow($rs);
       $tmpPHP .= '$this->contentTypes['.$tmp1['id'].']'."='".$tmp1['contentType']."';\n";
    }

    // WRITE templates to cache file
    $sql = "SELECT * FROM ".$this->db."site_templates";
    $rs = $this->etomite->dbQuery($sql);
    $limit_tmp = $this->etomite->recordCount($rs);
    for ($i_tmp=0; $i_tmp<$limit_tmp; $i_tmp++) {
       $tmp1 = $this->etomite->fetchRow($rs);
       $tmpPHP .= '$this->tpl_list['.$tmp1[$i_tmp].']'."=".$tmp1['id'].";\n";
    }
	
    // close and write the file
    $tmpPHP .= "?>";
    $filename = $this->cachePath.'etomiteCache.idx.php';
    $somecontent = $tmpPHP;

    if (!$handle = fopen($filename, 'w')) {
       echo "Cannot open file ($filename)";
       exit;
    }

    // Write $somecontent to our opened file.
    if (fwrite($handle, $somecontent) === FALSE) {
       echo "Cannot write main Etomite cache file! Make sure the assets/cache directory is writable!";
       exit;
    }
    fclose($handle);
	
	// reload cache file. fixes issues with not being able to use updated cache settings immediately
	if (file_exists(absolute_base_path . "assets/cache/etomiteCache.idx.php")) {
		include_once(absolute_base_path . "assets/cache/etomiteCache.idx.php");
	}

/****************************************************************************/
/*  END OF BUILD CACHE FILES                        */
/*  CREATE CHUNK AND SNIPPET CACHE FILES  */
	
	//changed to new file based cache for chunks and snippets to keep the initial cached settings load small
    // WRITE Chunks to cache file if cache is enabled
	if ($this->etomite->config['cache_resources'] == 1) {
		$sql = "SELECT * FROM ".$this->db."site_htmlsnippets";
		$rs = $this->etomite->dbQuery($sql);
		$limit_tmp = $this->etomite->recordCount($rs);
		for ($i_tmp=0; $i_tmp<$limit_tmp; $i_tmp++) {
		   $tmp1 = $this->etomite->fetchRow($rs);
		   @file_put_contents($this->cachePath."chunks/".$tmp1['name'].".etoCache", $tmp1['snippet']);
		}
	}

    // WRITE snippets to cache file if cache is enabled
	if ($this->etomite->config['cache_resources'] == 1) {
		$sql = "SELECT * FROM ".$this->db."site_snippets";
		$rs = $this->etomite->dbQuery($sql);
		$limit_tmp = $this->etomite->recordCount($rs);
		for ($i_tmp=0; $i_tmp<$limit_tmp; $i_tmp++) {
		   $tmp1 = $this->etomite->fetchRow($rs);
		   @file_put_contents($this->cachePath."snippets/".$tmp1['name'].".etoCache", $tmp1['snippet']);
		}
	}

/*  END OF CHUNK AND SNIPPET CHACHE FILES  */
/*  PUBLISH TIME FILE                            */
/****************************************************************************/
    // update publish time file
    $timesArr = array();
    $sql = "SELECT MIN(pub_date) AS minpub FROM ".$this->db."site_content WHERE pub_date>".time();
    if(@!$result = $this->etomite->dbQuery($sql)) {
      echo "Couldn't determine next publish event!";
    }

    $tmpRow = $this->etomite->fetchRow($result);
    $minpub = $tmpRow['minpub'];
    if($minpub!=NULL) {
      $timesArr[] = $minpub;
    }

    $sql = "SELECT MIN(unpub_date) AS minunpub FROM ".$this->db."site_content WHERE unpub_date>".time();
    if(@!$result = $this->etomite->dbQuery($sql)) {
      echo "Couldn't determine next unpublish event!";
    }
    $tmpRow = $this->etomite->fetchRow($result);
    $minunpub = $tmpRow['minunpub'];
    if($minunpub!=NULL) {
      $timesArr[] = $minunpub;
    }

    if(count($timesArr)>0) {
      $nextevent = min($timesArr);
    } else {
      $nextevent = 0;
    }

    // write the file
    $filename = $this->cachePath.'etomitePublishing.idx';
    $somecontent = "<?php \$cacheRefreshTime=$nextevent; ?>";

    if (!$handle = fopen($filename, 'w')) {
       echo "Cannot open file ($filename)";
       exit;
    }

    // Write $somecontent to our opened file.
    if (fwrite($handle, $somecontent) === FALSE) {
       echo "Cannot write publishing info file! Make sure the assets/cache directory is writable!";
       exit;
    }

    fclose($handle);

/****************************************************************************/
/*  END OF PUBLISH TIME FILE                        */
/****************************************************************************/
    // finished cache stuff.
    if($this->showReport==true) {
      printf($this->_lang["refresh_cache"], $filesincache, $deletedfilesincache);
      $limit = count($deletedfiles);
      if($limit > 0) {
        echo "<p />".$this->_lang['cache_files_deleted']."<ul>";
        for($i=0;$i<$limit; $i++) {
          echo "<li>".$deletedfiles[$i]."</li>";
        }
        echo "</ul>";
      }
    }
  }
}
?>
