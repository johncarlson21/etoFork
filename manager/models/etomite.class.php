<?php
/**************************************************************************
etoFork Content Management System
Copyright (c) 2011 All Rights Reserved
John Carlson - <johncarlson21@gmail.com>

Please see other info below as to what etoFork was derived from.

etoFork is a fork of Etomite Content Management System. It has quite a few
updates to the main functionality but uses some of the original code from
etomite.

/**************************************************************************/
/**************************************************************************
Etomite Content Management System
Copyright (c) 2003 - 2007, The Etomite Project. All Rights Reserved.

Originally Created by Alexander Andrew Butter, upto 03/2005.
Development continued 03/2005 by Ralph A. Dahlgren with Etomite 0.6.1

This file and all dependant and otherwise related files are part of Etomite.

Etomite is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

Etomite is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Etomite; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
/***************************************************************************/
// Class Name: etomite [v1.1]
// Function: This class contains the main parsing functions
// Modified: 2007-03-07 By: Ralph A. Dahlgren & Randy Casburn
// Modified: 2007-05-05 By: Ralph A. Dahlgren
// Modified: 2008-04-22 [v1.0] by: Ralph A. Dahlgren
// Modified: 2008-05-08 [v1.1] by: Ralph A. Dahlgren
/***************************************************************************/
/* MODIFIED BY JOHN CARLSON 3-23-2010
Modified the $_REQUEST['q'] to $_REQUEST['rw']
becuase some scripts, namely Google Custom Search uses the q parameter which will overwrite the regular request.
also added a new function to add the page alias to the <body> tag example: domain.com/classifieds.html
<body class="page-classifieds">
*/

/**
* etoFork Main Class
*
* etoFork is a fork of Etomite Content Management System. It has quite a few
* updates to the main functionality but uses some of the original code from
* Etomite.
* Etomite CMS is no longer in development, so we've picked up where it left off.
*
* @version 2.4
* @author John Carlson <johncarlson21@gmail.com>
*/
class etomite
{
    public $db, $rs, $result, $sql, $table_prefix, $debug, $documentIdentifier, $documentMethod, $documentGenerated, $documentContent, $tstart, $snippetParsePasses, $documentObject, $templateObject, $snippetObjects, $stopOnNotice, $executedQueries, $queryTime, $currentSnippet, $documentName, $aliases, $aliasListing, $visitor, $entrypage, $documentListing, $dumpSnippets, $chunkCache, $snippetCache, $contentTypes, $dumpSQL, $queryCode, $tbl, $error404page, $version, $code_name, $notice, $blockLogging, $useblockLogging, $offline_page;
    // new variables for the new functions and code
    public $config = array();
    public $_request = array(); // variable to hold the request/post/get from a url format of /varname/value/varname2/value2
    public $_vars = array(); // variable use to pass information  such as an ID that you want to grab in another snippet
    // used to make changes to the head or sections of the html
    public $headJSCSS = ''; // variable to add js and css to the head of the document.
    public $_meta = ''; // variable to add meta to the head
    public $breadCrumbs = array();
    public $bcSep = " &raquo; "; // default breadCrumb separator
    public $_lang = array();
    public $documentTVs = array();
    public $parseAgain = true;
    public $moduleAction = '';
	public $rsExt = ''; // fix for external db connect
	
	/**
	* The class constructor
	*
	* This runs the function runStatndalone() that sets up som f the main functions
	* for the class and variables
	*/
    public function __construct()
    {
        $this->runStandalone();
    }
	
	/**
	* runStandalone function
	*
	* Setup function to starup the main class and set some configuration items.
	* Connects the db and gets the site settings
	*/
    public function runStandalone()
    {
        $this->dbConfig['host']             = $GLOBALS['database_server'];
        $this->dbConfig['dbase']            = $GLOBALS['dbase'];
        $this->dbConfig['user']             = $GLOBALS['database_user'];
        $this->dbConfig['pass']             = $GLOBALS['database_password'];
        $this->dbConfig['table_prefix']     = $GLOBALS['table_prefix'];
        $this->db                           = $this->dbConfig['dbase'] . "." . $this->dbConfig['table_prefix'];
        $this->_lang                        = isset($GLOBALS['_lang']) ? $GLOBALS['_lang'] : array();
        // convert variables initially calculated in config.inc.php into config variables
        $this->config['absolute_base_path'] = absolute_base_path;
        $this->config['relative_base_path'] = relative_base_path;
        $this->config['www_base_path']      = www_base_path;
        $this->dbConnect();
        // get the settings
        $this->getSettings();
    }
	/**
	* parseUrl
	*
	* parses the url to pull out and set the request data from /page/this/style url
	*
	* @param string $q
	*/
    public function parseUrl($q)
    {
		/**
		* Check number of parts in an array to see if there is an even amount
		*
		* @param integer $num
		*/
        function checkNum($num)
        {
            return ($num % 2) ? TRUE : FALSE;
        }
        
        if (empty($q))
            return;
        $q     = trim($q, "/"); // remove leading and ending slashes
        $parts = explode("/", $q); // put the url into a split array
        if (checkNum(count($parts)) === TRUE) {
            array_push($parts, "");
        }
        $keys   = array();
        $values = array();
        foreach ($parts as $p) {
            if ($p == '0') {
                $p = strval($p);
            }
            ((++$i % 2 == 0) ? array_push($values, $p) : array_push($keys, $p));
        }
        return array_combine($keys, $values);
    }
	
	/**
	* Check if there is a session running
	*/
    public function checkSession()
    {
        if (isset($_SESSION['validated'])) {
            return true;
        } else {
            return false;
        }
    }
	
	/**
	* Check for a Logging Cookie
	*/
    public function checkCookie()
    {
        if (isset($_COOKIE['etomiteLoggingCookie'])) {
            $this->visitor = $_COOKIE['etomiteLoggingCookie'];
            if (isset($_SESSION['_logging_first_hit'])) {
                $this->entrypage = 0;
            } else {
                $this->entrypage                = 1;
                $_SESSION['_logging_first_hit'] = 1;
            }
        } else {
            if (function_exists('posix_getpid')) {
                $visitor = crc32(microtime() . posix_getpid());
            } else {
                $visitor = crc32(microtime() . session_id());
            }
            $this->visitor   = $visitor;
            $this->entrypage = 1;
            setcookie('etomiteLoggingCookie', $visitor, time() + (365 * 24 * 60 * 60), '', '');
        }
    }
	
	/**
	* Get current microtime
	*/
    public function getMicroTime()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float) $usec + (float) $sec);
    }
	
	/**
	* Get Site Settings
	*
	* If there are current site settings setup in a cache file use those, if not run the processor
	* to create the settings and then create the cache file. This also sets up the array of aliases,
	* templates, parents and authenticate documents
	*/
    public function getSettings()
    {
        if (file_exists($this->config['absolute_base_path'] . "assets/cache/etomiteCache.idx.php")) {
            include($this->config['absolute_base_path'] . "assets/cache/etomiteCache.idx.php");
        } else {
            /*$result = $this->dbQuery("SELECT setting_name, setting_value FROM ".$this->db."system_settings");
            while ($row = $this->fetchRow($result, 'both')) {
            $this->config[$row[0]] = $row[1];
            }*/
            include(MANAGER_PATH . "includes/cache_sync.class.processor.php");
            $sync = new synccache($this, $this->_lang);
            $sync->setCachepath($this->config['absolute_base_path'] . "assets/cache/");
            $sync->setReport(false);
            $sync->emptyCache();
            if (file_exists($this->config['absolute_base_path'] . "assets/cache/etomiteCache.idx.php")) {
                include($this->config['absolute_base_path'] . "assets/cache/etomiteCache.idx.php");
            }
        }
        // get current version information
        include(MANAGER_PATH . "includes/version.inc.php");
        $this->config['release']       = $release;
        $this->config['patch_level']   = $patch_level;
        $this->config['code_name']     = $code_name;
        $this->config['full_appname']  = $full_appname;
        $this->config['small_version'] = $small_version;
        $this->config['slogan']        = $full_slogan;
        // if site_unavailable_message is a number then we assume that it is a
        // document id and we use that number for redirecting to the proper document.
        $this->offline_page            = (is_numeric($this->config['site_unavailable_message'])) ? $this->config['site_unavailable_message'] : "";
        // compile array of document aliases
        // relocated from rewriteUrls() for greater flexibility in 0.6.1 Final
        // we always run this routine now so that the template info gets populated too
        // a blind array(), $this->tpl_list, is also included for comparisons
        $aliases                       = array();
        $templates                     = array();
        $parents                       = array();
		$authenticates = array();
        $limit_tmp                     = count($this->aliasListing);
        for ($i_tmp = 0; $i_tmp < $limit_tmp; $i_tmp++) {
            if ($this->aliasListing[$i_tmp]['alias'] != "") {
                $aliases[$this->aliasListing[$i_tmp]['id']] = $this->aliasListing[$i_tmp]['alias'];
            }
            $templates[$this->aliasListing[$i_tmp]['id']]     = $this->aliasListing[$i_tmp]['template'];
            $parents[$this->aliasListing[$i_tmp]['id']]       = $this->aliasListing[$i_tmp]['parent'];
            $authenticates[$this->aliasListing[$i_tmp]['id']] = $this->aliasListing[$i_tmp]['authenticate'];
        }
        $this->aliases       = $aliases;
        $this->templates     = $templates;
        $this->parents       = $parents;
        $this->authenticates = $authenticates;
    }
	
	/**
	* Redirect Function
	*
	* Function used to redirect to a specified url
	*
	* @param string $url a valid url
	* @param integer $count_attempts amount of redirect attempts
	* @param string $type type of redirect, refresh, header or meta
	*/
    public function sendRedirect($url, $count_attempts = 3, $type = '')
    {
        if (empty($url)) {
            return false;
        } else {
            if ($count_attempts == 1) {
                // append the redirect count string to the url
                $currentNumberOfRedirects = isset($_REQUEST['err']) ? $_REQUEST['err'] : 0;
                if ($currentNumberOfRedirects > 3) {
                    $this->messageQuit("Redirection attempt failed - please ensure the document you're trying to redirect to exists. Redirection URL: <i>$url</i>");
                } else {
                    $currentNumberOfRedirects += 1;
                    if (strpos($url, "?") > 0) {
                        $url .= "&err=$currentNumberOfRedirects";
                    } else {
                        $url .= "?err=$currentNumberOfRedirects";
                    }
                }
            }
            if ($type == "REDIRECT_REFRESH") {
                $header = "Refresh: 0;URL=" . $url;
            } elseif ($type == "REDIRECT_META") {
                $header = "<META HTTP-EQUIV=Refresh CONTENT='0; URL=" . $url . "' />";
                echo $header;
                exit;
            } elseif ($type == "REDIRECT_HEADER" || empty($type)) {
                $header = "Location: $url";
            }
            header($header);
            $this->postProcess();
        }
    }
	
	/**
	* Check for preview of page
	*
	* This checks if we are just previewing the page.
	*
	* @return boolean true | false
	*/
    public function checkPreview()
    {
        if ($this->checkSession() == true) {
            if (isset($_REQUEST['z']) && $_REQUEST['z'] == 'manprev') {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
	
	/**
	* Check site status
	*
	* Checks site config to see if the site is online or offline.
	*
	* @return boolean true | false
	*/
    public function checkSiteStatus()
    {
        if ($this->config['site_status'] == 1) {
            return true;
        } else {
            return false;
        }
    }
	
	/**
	* Site Sync function
	*
	* This runs the process of creating the site cache files
	*
	* @param string $cache_path path to cache directory
	*/
    public function syncsite($cache_path = false)
    {
        // clears and rebuilds the site cache
        // added in 0.6.1.1
        // Modified 2008-03-17 by Ralph for improved cachePath handling
        include_once(MANAGER_PATH . "includes/cache_sync.class.processor.php");
        $sync = new synccache($this, $this->_lang);
        if ($cache_path && $cache_path !== false) {
            $sync->setCachepath($cache_path);
        } else {
            $sync->setCachepath(absolute_base_path . "assets/cache/");
        }
        $sync->setReport(false);
        $sync->emptyCache();
    }
	
	/**
	* Check if document is cached
	*
	* @param integer $id id of document
	* @return empty or cachefile
	*/
    public function checkCache($id)
    {
        $cacheFile = absolute_base_path . "assets/cache/docid_" . $id . ".etoCache";
        if (file_exists($cacheFile)) {
            $this->documentGenerated = 0;
            return join("", file($cacheFile));
        } else {
            $this->documentGenerated = 1;
            return "";
        }
    }
	
	/**
	* Check and Get Cached resource
	*
	* @param varchar $resourceName name of resource
	* @param varchar $type type of resource; default chunk
	* @return return either cached resource or resource directly from db
	*/
	public function getResource($resourceName=false, $type='chunk') {
		if ($resourceName !== false) {
			// check if resource exists
			$resourceName = trim($resourceName);
			$tbl = '';
			$resource = '';
			switch($type) {
				case "chunk":
					$tbl = 'site_htmlsnippets';
					$cacheFolder = 'chunks/';
				break;
				case "snippet":
					$tbl = 'site_snippets';
					$cacheFolder = 'snippets/';
				break;	
			}
			
			// check for cached file if enabled
			if ($this->config['cache_resources'] == 1) {
				if (file_exists(absolute_base_path."assets/cache/".$cacheFolder.$resourceName.".etoCache")) {
					$resource = @file_get_contents(absolute_base_path."assets/cache/".$cacheFolder.$resourceName.".etoCache");
				}
			}
			// pull from db no cache file exists or resouce cache disabled
			if (empty($resource)) {
				if ($result = $this->getIntTableRows('*', $tbl, "name='".$resourceName."'", '', '', 1)) {
					$resourceData = $result[0];
					$resource = $resourceData['snippet'];
					if ($this->config['cache_resources'] == 1) {
						@file_put_contents(absolute_base_path."assets/cache/".$cacheFolder.$resourceName.".etoCache", $resource);
					}
					//$resource = $resourceData['snippet'];
				}
			}
			// return nothing if no resource found
			return $resource;
		}
	}
    
	/**
	* Discover document method
	*
	* Tests the request to see if the document is getting pulled based on alias or id
	*
	* @return alias | id | none
	*/
    public function getDocumentMethod()
    {
        // function to test the query and find the retrieval method
        if (isset($_REQUEST['rw'])) {
            return "alias";
        } elseif (isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])) {
            return "id";
        } else {
            return "none";
        }
    }
	
	/**
	* Get Document Identifier from method
	*
	* Function returns document identifier based on method given
	*
	* @param mixed $method method from getDocumentMethod()
	* @see function getDocumentMethod
	*/
    public function getDocumentIdentifier($method)
    {
        // function to test the query and find the retrieval method
        switch ($method) {
            case "alias":
                if (strpos($_REQUEST['rw'], "/") > 0) {
                    $qOrig = substr($_REQUEST['rw'], 0, strpos($_REQUEST['rw'], "/")); // old line
                } else {
                    $qOrig = $_REQUEST['rw'];
                }
                //return preg_replace("/[^\w\.@-]/", "", htmlspecialchars($_REQUEST['rw']));
                return preg_replace("/[^\w\.@-]/", "", htmlspecialchars($qOrig));
                break;
            case "id":
                return is_numeric($_REQUEST['id']) ? $_REQUEST['id'] : "";
                break;
            case "none":
                return $this->config['site_start'];
                break;
            default:
                return $this->config['site_start'];
        }
    }
	
	/**
	* Clean up document identifier
	*
	* This function cleans up the document identifier and sets request variables
	* checks if this is a zend style url or normaal eto style url. Also checks for suffix and 
	* prefix from site configuration, and removes if needed.
	*
	* @param string @qOrig the original request
	* @return document identifier
	*/
    public function cleanDocumentIdentifier($qOrig)
    {
        if ($this->config['zend_urls'] == 1) { // sets the zend style url to a request of $key=>$val
            $this->_request = $this->parseUrl(substr($_REQUEST['rw'], strpos($_REQUEST['rw'], "/")));
            $this->_request = array_merge($this->_request, $_POST);
            unset($this->_request['rw']);
        } else { // set the _get var to the _request var
            $this->_request = $_GET;
            unset($this->_request['rw']); // unset the rw variable that is sent by the .htaccess file
        }
        $q = str_replace($this->config['friendly_url_prefix'], "", $qOrig);
        $q = str_replace($this->config['friendly_url_suffix'], "", $q);
        // we got an ID returned unless the error_page alias is "404"
        if (is_numeric($q) && ($q != $this->aliases[$this->config['error_page']])) {
            $this->documentMethod = 'id';
            return $q;
            // we didn't get an ID back, so instead we assume it's an alias
        } else {
            $this->documentMethod = 'alias';
            return $q;
        }
    }
	
	/**
	* Add Powered by Notice
	*
	* Adds powered by Notice to the document content
	*
	* @param string $content document content
	* @param string $type type of document; text/html etc.
	* @return content with added notice
	*/
    public function addNotice($content, $type = "text/html")
    {
        /* LEGAL STUFF REMOVED TO SHRINK FILE */
        if ($type == "text/html") {
            $notice = "<div id='etoNotice'>\n" . "\tPowered by <a href='http://www.etofork.com' title='Powered by the etoFork Content Management System'>etoFork CMS</a>.\n" . "</div>\n\n";
        }
        // insert the message into the document
        if (strpos($content, "</body>") > 0) {
            $content = str_replace("</body>", $notice . "</body>", $content);
        } elseif (strpos($content, "</BODY>") > 0) {
            $content = str_replace("</BODY>", $notice . "</BODY>", $content);
        } else {
            $content .= $notice;
        }
        return $content;
    }

	/**
	* Add page class
	*
	* Add page alias as a class to the body tag
	*
	* @param string $content document content
	* @param string $type type of document; text/html etc.
	* @return content with added body tag class
	*/
    public function addPageClass($content, $type = "text/html")
    {
        if ($type != "text/html") {
            return $content;
        }
        $doc   = $this->documentObject;
        $alias = '';
        if (empty($doc)) {
            return $content;
        }
        if (!empty($doc['alias'])) {
            $alias = $doc['alias'];
        }
        if (empty($alias)) {
            $alias = $doc['id'];
        }
        // insert the message into the document
        if (strpos($content, "<body>") > 0) {
            $content = str_replace("<body>", "<body class='page-" . $alias . "'>", $content);
        } elseif (strpos($content, "<BODY>") > 0) {
            $content = str_replace("<BODY>", "<BODY class='page-" . $alias . "'>", $content);
        } else {
            return $content;
        }
        return $content;
    }
	
	/**
	* Output Page
	*
	* Outputs main page content. parses content for snippets, chunks, modules, settings and content
	*
	* @return returns page content
	*/
    public function outputContent()
    {
        $this->setDefaultBreadCrumb();
        $output = $this->documentContent;
        // check for non-cached snippet output
        if (strpos($output, '[!') > -1 || strpos($output, '[module') > -1) {
            $output           = str_replace('[!', '[[', $output);
            $output           = str_replace('!]', ']]', $output);
            $this->parseAgain = true;
            while ($this->parseAgain == true) {
				$output           = str_replace('[!', '[[', $output);
            	$output           = str_replace('!]', ']]', $output);
                $this->parseCount++;
                $this->parseAgain = false;
                if ($this->config['dumpSnippets'] == 1) {
                    echo "<fieldset style='text-align: left'><legend>NONCACHED PARSE PASS " . ($i + 1) . "</legend>The following snipppets (if any) were parsed during this pass.<div style='width:100%' align='center'>";
                }
                // re check for document content
                $output = $this->mergeDocumentContent($output);
                // replace settings referenced in document
                $output = $this->mergeSettingsContent($output);
                // replace HTMLSnippets in document
                $output = $this->mergeHTMLSnippetsContent($output);
                // find and merge snippets
                $output = $this->evalSnippets($output);
                // parse modules
                $output = $this->evalModules($output);
                if ($this->config['dumpSnippets'] == 1) {
                    echo "</div></fieldset><br />";
                }
            }
        }
        $output    = $this->rewriteUrls($output);
        $totalTime = ($this->getMicroTime() - $this->tstart);
        $queryTime = $this->queryTime;
        $phpTime   = $totalTime - $queryTime;
        $queryTime = sprintf("%2.4f s", $queryTime);
        $totalTime = sprintf("%2.4f s", $totalTime);
        $phpTime   = sprintf("%2.4f s", $phpTime);
        $source    = $this->documentGenerated == 1 ? "database" : "cache";
        $queries   = isset($this->executedQueries) ? $this->executedQueries : 0;
        // send out content-type headers
        $type      = !empty($this->contentTypes[$this->documentIdentifier]) && !$this->aborting ? $this->contentTypes[$this->documentIdentifier] : "text/html";
        header('Content-Type: ' . $type . '; charset=' . $this->config['etomite_charset']);
        if (!$this->checkSiteStatus() && ($this->documentIdentifier != $this->offline_page)) {
            header("HTTP/1.0 307 Temporary Redirect");
        }
        if (($this->documentIdentifier == $this->config['error_page']) && ($this->config['error_page'] != $this->config['site_start'])) {
            header("HTTP/1.0 404 Not Found");
        }
        // Check to see whether or not addNotice should be called
        if ($this->config['useNotice'] || !isset($this->config['useNotice'])) {
            $documentOutput = $this->addNotice($output, $type);
        } else {
            $documentOutput = $output;
        }
        // added by John Carlson
        $documentOutput = $this->addPageClass($documentOutput, $type);
        $documentOutput = $this->addMeta($documentOutput);
        $documentOutput = $this->addJSCSS($documentOutput);
        $documentOutput = $this->addBreadCrumb($documentOutput);
        // end added
        if ($this->config['dumpSQL']) {
            $documentOutput .= $this->queryCode;
        }
        $documentOutput = str_replace("[^q^]", $queries, $documentOutput);
        $documentOutput = str_replace("[^qt^]", $queryTime, $documentOutput);
        $documentOutput = str_replace("[^p^]", $phpTime, $documentOutput);
        $documentOutput = str_replace("[^t^]", $totalTime, $documentOutput);
        $documentOutput = str_replace("[^s^]", $source, $documentOutput);
        // clean up all document vars
        $documentOutput = $this->mergeDocumentContent($documentOutput, true);
        // Check to see if document content contains PHP tags.
        // PHP tag support contributed by SniperX
        if (preg_match("/(<\?php|<\?)(.*?)\?>/", $documentOutput) && $type == "text/html" && $this->config['allow_embedded_php']) {
            $documentOutput = '?' . '>' . $documentOutput . '<' . '?php ';
            // Parse the PHP tags.
            eval($documentOutput);
        } else {
            // No PHP tags so just echo out the content.
            echo $documentOutput;
        }
    }
	
	/**
	* Check Publish status of document
	*
	* Checks if a document is set to be published
	*/
    public function checkPublishStatus()
    {
        include(absolute_base_path . "assets/cache/etomitePublishing.idx");
        $timeNow = time() + $this->config['server_offset_time'];
        if (($cacheRefreshTime <= $timeNow && $cacheRefreshTime != 0) || !isset($cacheRefreshTime)) {
            // now, check for documents that need publishing
            $sql = "UPDATE " . $this->db . "site_content SET published=1 WHERE " . $this->db . "site_content.pub_date <= " . $timeNow . " AND " . $this->db . "site_content.pub_date!=0";
            if (@!$result = $this->dbQuery($sql)) {
                $this->messageQuit("Execution of a query to the database failed", $sql);
            }
            // now, check for documents that need un-publishing
            $sql = "UPDATE " . $this->db . "site_content SET published=0 WHERE " . $this->db . "site_content.unpub_date <= " . $timeNow . " AND " . $this->db . "site_content.unpub_date!=0";
            if (@!$result = $this->dbQuery($sql)) {
                $this->messageQuit("Execution of a query to the database failed", $sql);
            }
            // clear the cache
            $basepath = dirname(__FILE__);
            if ($handle = opendir($basepath . "/assets/cache")) {
                $filesincache        = 0;
                $deletedfilesincache = 0;
                while (false !== ($file = readdir($handle))) {
                    if ($file != "." && $file != "..") {
                        $filesincache += 1;
                        if (preg_match("/\.etoCache/", $file)) {
                            $deletedfilesincache += 1;
                            while (!unlink($basepath . "/assets/cache/" . $file));
                        }
                    }
                }
                closedir($handle);
            }
            // update publish time file
            $timesArr = array();
            $sql      = "SELECT MIN(" . $this->db . "site_content.pub_date) AS minpub FROM " . $this->db . "site_content WHERE " . $this->db . "site_content.pub_date >= " . $timeNow . ";";
            if (@!$result = $this->dbQuery($sql)) {
                $this->messageQuit("Failed to find publishing timestamps", $sql);
            }
            $tmpRow = $this->fetchRow($result);
            $minpub = $tmpRow['minpub'];
            if ($minpub != NULL) {
                $timesArr[] = $minpub;
            }
            $sql = "SELECT MIN(" . $this->db . "site_content.unpub_date) AS minunpub FROM " . $this->db . "site_content WHERE " . $this->db . "site_content.unpub_date >= " . $timeNow . ";";
            if (@!$result = $this->dbQuery($sql)) {
                $this->messageQuit("Failed to find publishing timestamps", $sql);
            }
            $tmpRow   = $this->fetchRow($result);
            $minunpub = $tmpRow['minunpub'];
            if ($minunpub != NULL) {
                $timesArr[] = $minunpub;
            }
            if (count($timesArr) > 0) {
                $nextevent = min($timesArr);

            } else {
                $nextevent = 0;
            }
            $basepath = dirname(__FILE__);
            $fp       = @fopen($basepath . "/assets/cache/etomitePublishing.idx", "wb");
            if ($fp) {
                @flock($fp, LOCK_EX);
                $data = "<?php \$cacheRefreshTime=" . $nextevent . "; ?>";
                $len  = strlen($data);
                @fwrite($fp, $data, $len);
                @flock($fp, LOCK_UN);
                @fclose($fp);
            }
        }
    }
	
	/**
	* Post process of document rendering
	*
	* Runs post processes before document is rendered
	*/
    public function postProcess()
    {
        // if enabled, do logging
        if ($this->config['track_visitors'] == 1 && ($_REQUEST['z'] != "manprev")) {
            if (!((preg_match($this->blockLogging, $_SERVER['HTTP_USER_AGENT'])) && $this->useblockLogging))
                $this->log();
        }
        // if the current document was generated, cache it, unless an alternate template is being used!
        if (isset($_SESSION['tpl']) && ($_SESSION['tpl'] != $this->documentObject['template']))
            return;
        if ($this->documentGenerated == 1 && $this->documentObject['cacheable'] == 1 && $this->documentObject['type'] == 'document') {
            $basepath = absolute_base_path;
            if ($fp = @fopen($basepath . "assets/cache/docid_" . $this->documentIdentifier . ".etoCache", "w")) {
                fputs($fp, $this->documentContent);
                fclose($fp);
            }
        }
    }
	
	/**
	* Merge Document Content
	*
	* Processes the template for document content
	*
	* @param string $template template
	* @param boolean $cleanup cleanup content true | false
	* @return returns template with document content
	*/
    public function mergeDocumentContent($template, $cleanup = false)
    {
        if (empty($this->documentObject)) {
            return '';
        }
        foreach ($this->documentObject as $key => $value) {
            $template = str_replace("[*" . $key . "*]", stripslashes($value), $template);
        }
        if ($cleanup) {
            $count = preg_match_all('~\[\*(.*?)\*\]~', $template, $matches);
            if ($count > 0) {
                for ($i = 0; $i < $count; $i++) {
                    $replace[$i] = "";
                }
                $template = str_replace($matches[0], $replace, $template);
            }
        }
        return $template;
    }
	
	/**
	* Merge Settings
	*
	* Merges site settings into the content and template
	*
	* @param string $template template content
	* @return returns the template with the settings inserted
	*/
    public function mergeSettingsContent($template)
    {
        $settingsCount = preg_match_all('~\[\((.*?)\)\]~', $template, $matches);
        if ($settingsCount)
            $this->parseAgain = true; // [v1.2]
        for ($i = 0; $i < $settingsCount; $i++) {
            $replace[$i] = $this->config[$matches[1][$i]];
        }
        $template = str_replace($matches[0], $replace, $template);
        return $template;
    }
	
	/**
	* Merge Chunks
	*
	* Merges Chunks into the content
	*
	* @param string $content document
	* @return returns document with chunks replaced
	*/
    public function mergeHTMLSnippetsContent($content)
    {
        $settingsCount = preg_match_all('~{{(.*?)}}~', $content, $matches);
		$replace = array();
        if ($settingsCount)
            $this->parseAgain = true; // [v1.2]
        for ($i = 0; $i < $settingsCount; $i++) {
			$replace[$i] = $this->getChunk($matches[1][$i]);
        }
        $content = str_replace($matches[0], $replace, $content);
        return $content;
    }
	
	/**
	* Eval Snippet
	*
	* Processes a snippet and then returns it to be set in the document
	*
	* @param string $snippet snippet content
	* @param string $params snippet parameters
	* @return returns document with snippets replaced
	* @see function evalSnippets
	*/
    public function evalSnippet($snippet, $params)
    {
        $etomite = $this;
        if (is_array($params)) {
            extract($params, EXTR_SKIP);
        }
        $snip = eval($snippet);
        return $snip;
    }
	
	/**
	* Eval Snippets
	*
	* Processes document for snippets and then pass them to the evalSnippet function
	*
	* @param string $documentSource document
	* @return returns document content
	* @see function evalSnippet
	*/
    public function evalSnippets($documentSource)
    {
        $matchCount = preg_match_all('~\[\[(.*?)\]\]~', $documentSource, $matches);
        $etomite    = $this;
        if ($matchCount)
            $this->parseAgain = true; // [v1.2]
        for ($i = 0; $i < $matchCount; $i++) {
            $spos = strpos($matches[1][$i], '?', 0);
            if ($spos !== false) {
                $params = substr($matches[1][$i], $spos, strlen($matches[1][$i]));
            } else {
                $params = '';
            }
            $matches[1][$i]    = str_replace($params, '', $matches[1][$i]);
            $snippetParams[$i] = $params;
        }
        $nrSnippetsToGet = count($matches[1]);
		$snippets = array();
        for ($i = 0; $i < $nrSnippetsToGet; $i++) {
			$snippet = $this->getResource($matches[1][$i], 'snippet');
			$snippet = trim($snippet);
			// check the snippet for php tags
			if (preg_match("/^(<\?php|<\?)/", $snippet)) {
				$snippet = "?>" . $snippet;
			}
			if (substr($snippet, 0, -2) == "?>") {
				$snippet .= "<?php ";
			}
			$snippets[$i]['name'] = $matches[1][$i];
			$snippets[$i]['snippet'] = $snippet;
        }
        for ($i = 0; $i < $nrSnippetsToGet; $i++) {
            $parameter            = array();
            $snippetName          = $this->currentSnippet = $snippets[$i]['name'];
            $currentSnippetParams = $snippetParams[$i];
            if (!empty($currentSnippetParams)) {
                $tempSnippetParams = str_replace("?", "", $currentSnippetParams);
                $splitter          = strpos($tempSnippetParams, "&amp;") > 0 ? "&amp;" : "&";
                $tempSnippetParams = explode($splitter, $tempSnippetParams);
                for ($x = 0; $x < count($tempSnippetParams); $x++) {
                    $parameterTemp                = explode("=", $tempSnippetParams[$x], 2);
                    $parameter[$parameterTemp[0]] = $parameterTemp[1];
                }
            }
            $executedSnippets[$i] = $this->evalSnippet($snippets[$i]['snippet'], $parameter);
            if ($this->config['dumpSnippets'] == 1) {
                echo "<fieldset><legend><b>$snippetName</b></legend><textarea style='width:60%; height:200px'>" . htmlentities($executedSnippets[$i]) . "</textarea></fieldset><br />";
            }
            $documentSource = str_replace("[[" . $snippetName . $currentSnippetParams . "]]", $executedSnippets[$i], $documentSource);
        }
        return $documentSource;
    }
	
	/**
	* Rewrite URLs
	*
	* Processes document content for URL codes to replace. This replaces [~4~] with the url
	* (FURL or regular url) of that document.
	*
	* @param string $documentSource document
	* @return returns document with url codes replaced
	*/
    public function rewriteUrls($documentSource)
    {
        // modified [v1.2] by Ralph for improved performance
        $search_regexp  = '!\[\~(.*?)\~\]!is';
        $replace_regexp = "index.php?id=" . '\1';
        // rewrite the urls
        if ($this->config['friendly_urls'] == 1) {
            if ($count = preg_match_all($search_regexp, $documentSource, $matches)) {
                for ($i = 0; $i < $count; $i++) {
                    $id = $matches[1][$i];
                    if ($this->config['friendly_alias_urls'] == 1 && isset($this->aliases[$id])) {
                        $furl = $this->config['friendly_url_prefix'] . $this->aliases[$id] . $this->config['friendly_url_suffix'];
                    } else {
                        $furl = $this->config['friendly_url_prefix'] . $id . $this->config['friendly_url_suffix'];
                    }
                    $documentSource = str_replace($matches[0][$i], $furl, $documentSource);
                }
            }
        } else {
            $documentSource = preg_replace($search_regexp, $replace_regexp, $documentSource);
        }
        return $documentSource;
    }
	
	/**
	* Execute Document Parser
	*
	* This is the main parser for the class. This bundles all the main document functions and outputs
	* the resulting document, error page or error
	*
	* @return document output
	*/
    public function executeParser()
    {
        set_error_handler(array(
            $this,
            "phpError"
        ));
        // convert variables initially calculated in config.inc.php into config variables
        $this->config['absolute_base_path'] = absolute_base_path;
        $this->config['relative_base_path'] = relative_base_path;
        $this->config['www_base_path']      = www_base_path;
        // get the settings
        $this->getSettings();
        // detect current protocol
        $protocol           = (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on') ? "https://" : "http://";
        // get server host name
        $host               = $_SERVER['HTTP_HOST'];
        // create 404 Page Not Found error url
        $this->error404page = $this->makeURL($this->config['error_page']);
        // make sure the cache doesn't need updating
        $this->checkPublishStatus();
        // check the logging cookie
        if ($this->config['track_visitors'] == 1 && !isset($_REQUEST['z'])) {
            $this->checkCookie();
        }
        // find out which document we need to display
        $this->documentMethod     = $this->getDocumentMethod();
        $this->documentIdentifier = $this->getDocumentIdentifier($this->documentMethod);
        // now we know the site_start, change the none method to id
        if ($this->documentMethod == "none") {
            $this->documentMethod = "id";
        }
        if ($this->documentMethod == "alias") {
            $this->documentIdentifier = $this->cleanDocumentIdentifier($this->documentIdentifier);
        }
        if ($this->documentMethod == "alias") {
            // jbc added to remove case sensitivity
            $tmpArr = array();
            foreach ($this->documentListing as $key => $value) {
                $tmpArr[strtolower($key)] = $value;
            }
            $this->documentIdentifier = $tmpArr[strtolower($this->documentIdentifier)];
            $this->documentMethod     = 'id';
        }
        // stop processing here, as the site's offline
        if (!$this->checkSiteStatus() && ($_REQUEST['z'] != "manprev") && ($this->offline_page == "")) {
            $this->documentContent = $this->config['site_unavailable_message'];
            $this->aborting        = true; // added in [v1.0] by Ralph to resolve header issues
            $this->outputContent();
            ob_end_flush();
            exit;
        } elseif (!$this->checkSiteStatus() && $this->documentIdentifier != $this->offline_page) {
            $this->sendRedirect($this->makeURL($this->offline_page));
        }
        // check for frontend logout
        if (isset($_REQUEST['web_logout']) && $_REQUEST['web_logout'] == 1) {
            $this->userLogout(www_base_path);
        }
        // check for frontend login
        if (isset($_REQUEST['web_login']) && $_REQUEST['web_login'] == 1) {
            $url = isset($_REQUEST['login_redirect']) && !empty($_REQUEST['login_redirect']) ? $_REQUEST['login_redirect'] : www_base_path;
            if (isset($_REQUEST['username']) && !empty($_REQUEST['username']) && isset($_REQUEST['password']) && !empty($_REQUEST['password'])) {
                // perform login
                $this->userLogin($_REQUEST['username'], $_REQUEST['password'], $_REQUEST['rememberme'], $url);
            } else {
                $_SESSION['web_login_error'] = "Username and Password are required!";
                $this->sendRedirect($url);
            }
        }
        // if document level authentication is required, authenticate now
        if ($this->authenticates[$this->documentIdentifier]) {
            if (($this->config['use_uvperms'] && !$this->checkFrontPermissions()) || !$_SESSION['validated']) {
                //if($this->config['use_uvperms'] || !$_SESSION['validated']) {
                include_once(MANAGER_PATH . "includes/lang/" . $this->config['manager_language'] . ".inc.php");
                $msg = ($this->config['access_denied_message'] != "") ? $this->config['access_denied_message'] : $_lang['access_permission_denied'];
                //echo $msg;
                $url = rtrim(www_base_path, "/") . $_SERVER['REQUEST_URI'];
                // show frontend login and message
                include_once('views/front_end_login.phtml');
                exit;
            }
        }
        $template = $this->templates[$this->documentIdentifier];
        // we now know the method and identifier, let's check the cache based on conditions below
        if ((
        // page uses default template
            $template == $this->config['default_template']
        // no new alternate template has been selected
            && $_GET['tpl'] == ''
        // no alternate template was previously selected
            && $_SESSION['tpl'] == ''
        // Printable Page template was not requested
            && !isset($_GET['printable'])) || 
        // no alternate template is currently being used
            $template != $this->config['default_template']) {
            $this->documentContent = $this->checkCache($this->documentIdentifier);
        }
        // add by John Carlson
        // always get document object
        /*$sql = "SELECT * FROM ".$this->db."site_content WHERE ".$this->db."site_content.".$this->documentMethod." = '".$this->documentIdentifier."';";
        $result = $this->dbQuery($sql);
        $rowCount = $this->recordCount($result);
        if($this->recordCount($result) < 1) {*/
        if (!$this->documentObject = $this->getDocumentObject($this->documentMethod, $this->documentIdentifier)) {
            // no match found, send the visitor to the error_page
            $this->sendRedirect($this->error404page);
            ob_clean();
            exit;
        }
        if ($this->documentContent == "") {
            if ($rowCount > 1) {
                // no match found, send the visitor to the error_page
                $this->messageQuit("More than one result returned when attempting to translate `alias` to `id` - there are multiple documents using the same alias");
            }
            // write the documentName to the object
            $this->documentName = $this->documentObject['pagetitle'];
            // validation routines
            if ($this->documentObject['deleted'] == 1) {
                // no match found, send the visitor to the error_page
                $this->sendRedirect($this->error404page);
            }
            if ($this->documentObject['published'] == 0) {
                // no match found, send the visitor to the error_page
                $this->sendRedirect($this->error404page);
            }
            // check whether it's a reference
            if ($this->documentObject['type'] == "reference") {
                $this->sendRedirect($this->documentObject['content']);
                ob_clean();
                exit;
            }
            // get the template and start parsing!
            // if a request for a template change was passed, save old template and use the new one
            if (($_GET['tpl'] != "") && ($template == $this->config['default_template']) && (in_array($_GET['tpl'], $this->tpl_list))) {
                $template    = strip_tags($_GET['tpl']);
                $_GET['tpl'] = "";
                // if the session template has been set, use it
            } elseif (isset($_SESSION['tpl']) && ($template == $this->config['default_template']) && (in_array($_SESSION['tpl'], $this->tpl_list))) {
                $template = strip_tags($_SESSION['tpl']);
            }
            // if a printable page was requested, switch to the proper template
            if (isset($_GET['printable'])) {
                //$_GET['printable'] = "";
                $sql = "SELECT * FROM " . $this->db . "site_templates WHERE " . $this->db . "site_templates.templatename = '" . $this->printable . "';";
                // otherwise use the assigned template
            } else {
                $sql = "SELECT * FROM " . $this->db . "site_templates WHERE " . $this->db . "site_templates.id = '" . $template . "';";
            }
            // run query and process the results
            $result   = $this->dbQuery($sql);
            $rowCount = $this->recordCount($result);
            // if the template wasn't found, send an error
            if ($rowCount != 1) {
                $this->messageQuit("Row count error in template query result.", $sql, true);
            }
            // assign this template to be the active template on success
            if (($template != $this->config['default_template']) && ($this->templates[$this->documentIdentifier] == $this->config['default_template'])) {
                $_SESSION['tpl'] = $template;
            } else {
                if ($template == $this->config['default_template']) {
                    unset($_SESSION['tpl']);
                }
            }
            $row              = $this->fetchRow($result);
            $documentSource   = stripslashes($row['content']);
            // get snippets and parse them the required number of times
            $this->parseAgain = true;
            while ($this->parseAgain == true) {
                $this->parseCount++;
                $this->parseAgain = false;
                if ($this->config['dumpSnippets'] == 1) {
                    echo "<fieldset><legend><b style='color: #821517;'>PARSE PASS " . ($i + 1) . "</b></legend>The following snipppets (if any) were parsed during this pass.<div style='width:100%' align='center'>";
                }
                // combine template and content
                $documentSource = $this->mergeDocumentContent($documentSource);
                // replace settings referenced in document
                $documentSource = $this->mergeSettingsContent($documentSource);
                // replace HTMLSnippets in document
                $documentSource = $this->mergeHTMLSnippetsContent($documentSource);
                // find and merge snippets
                $documentSource = $this->evalSnippets($documentSource);
                if ($this->config['dumpSnippets'] == 1) {
                    echo "</div></fieldset><br />";
                }
            }
            $this->documentContent = $documentSource;
        }
        register_shutdown_function(array(
            $this,
            "postProcess"
        )); // tell PHP to call postProcess when it shuts down
        $this->outputContent();
    }
    
	/**
	* PHP Error function
	*
	* This is used in place of the regular php error output.
	*
	*/
    public function phpError($nr, $text, $file, $line)
    {
		if (error_reporting() == 0)
			return true; // added to fix issues with @
        if ($nr == 2048)
            return true; // added by mfx 10-18-2005 to ignore E_STRICT erros in PHP5
        if ($nr == 8 && $this->stopOnNotice == false) {
            return true;
        }
        if (is_readable($file)) {
            $source = file($file);
            $source = htmlspecialchars($source[$line - 1]);
        } else {
            $source = "";
        } //Error $nr in $file at $line: <div><code>$source</code></div>
        $this->messageQuit("PHP Parse Error", '', true, $nr, $file, $source, $text, $line);
    }
    public function messageQuit($msg = 'unspecified error', $query = '', $is_error = true, $nr = '', $file = '', $source = '', $text = '', $line = '')
    {
		// just log the error
		error_log('Error:');
		error_log($msg."\n".$query."\n".$nr."\n".$file."\n".$source."\n".$text."\n".$line);
        $this->aborting = true; // added in [v1.0] by Ralph to resolve header issues
        $pms            = "<html><head><title>Etomite " . $this->config['release'] . " " . $this->config['code_name'] . "</title>
    <style>TD, BODY { font-size: 11px; font-family:verdana; }</style>
    <script type='text/javascript'>
      function copyToClip()
      {
        holdtext.innerText = sqlHolder.innerText;
        Copied = holdtext.createTextRange();
        Copied.execCommand('Copy');
      }
    </script>
    </head><body>
    ";
        // jbc: added link back to home page, removed "Etomite parse" and left just "error"
        $homePage       = $_SERVER['PHP_SELF'];
        $siteName       = $this->config['site_name'];
        if ($is_error) {
            $pms .= "<h2><a href='$homePage' title='$siteName'>$siteName</a></h2>
      <h3 style='color:red'>&laquo; Error &raquo;</h3>
      <table border='0' cellpadding='1' cellspacing='0'>
      <tr><td colspan='3'>Etomite encountered the following error while attempting to parse the requested resource:</td></tr>
      <tr><td colspan='3'><b style='color:red;'>&laquo; $msg &raquo;</b></td></tr>";
        } else {
            $pms .= "<h2><a href='$homePage'
title='$siteName'>$siteName</a></h2>
      <h3 style='color:#003399'>&laquo; Etomite Debug/ stop message &raquo;</h3>
      <table border='0' cellpadding='1' cellspacing='0'>
      <tr><td colspan='3'>The Etomite parser recieved the following debug/ stop message:</td></tr>
      <tr><td colspan='3'><b style='color:#003399;'>&laquo; $msg &raquo;</b></td></tr>";
        }
        // end jbc change
        if (!empty($query)) {
            $pms .= "<tr><td colspan='3'><b style='color:#999;font-size: 9px;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SQL:&nbsp;<span id='sqlHolder'>$query; " . mysql_error() . "</span></b>
      <br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='javascript:copyToClip();' style='color:#821517;font-size: 9px; text-decoration: none'>[Copy SQL to ClipBoard]</a><textarea id='holdtext' style='display:none;'></textarea></td></tr>";
        }
        if ($text != '') {
            $errortype = array(
                E_ERROR => "Error",
                E_WARNING => "Warning",
                E_PARSE => "Parsing Error",
                E_NOTICE => "Notice",
                E_CORE_ERROR => "Core Error",
                E_CORE_WARNING => "Core Warning",
                E_COMPILE_ERROR => "Compile Error",
                E_COMPILE_WARNING => "Compile Warning",
                E_USER_ERROR => "User Error",
                E_USER_WARNING => "User Warning",
                E_USER_NOTICE => "User Notice"
            );
            $pms .= "<tr><td>&nbsp;</td></tr><tr><td colspan='3'><b>PHP error debug</b></td></tr>";
            $pms .= "<tr><td valign='top'>&nbsp;&nbsp;Error: </td>";
            $pms .= "<td colspan='2'>$text</td><td>&nbsp;</td>";
            $pms .= "</tr>";
            $pms .= "<tr><td valign='top'>&nbsp;&nbsp;Error type/ Nr.: </td>";
            $pms .= "<td colspan='2'>" . $errortype[$nr] . " - $nr</b></td><td>&nbsp;</td>";
            $pms .= "</tr>";
            $pms .= "<tr><td>&nbsp;&nbsp;File: </td>";
            $pms .= "<td colspan='2'>$file</td><td>&nbsp;</td>";
            $pms .= "</tr>";
            $pms .= "<tr><td>&nbsp;&nbsp;Line: </td>";
            $pms .= "<td colspan='2'>$line</td><td>&nbsp;</td>";
            $pms .= "</tr>";
            if ($source != '') {
                $pms .= "<tr><td valign='top'>&nbsp;&nbsp;Line $line source: </td>";
                $pms .= "<td colspan='2'>$source</td><td>&nbsp;</td>";
                $pms .= "</tr>";
            }
        }
        $pms .= "<tr><td>&nbsp;</td></tr><tr><td colspan='3'><b>Parser timing</b></td></tr>";
        $pms .= "<tr><td>&nbsp;&nbsp;MySQL: </td>";
        $pms .= "<td><i>[^qt^] s</i></td><td>(<i>[^q^] Requests</i>)</td>";
        $pms .= "</tr>";
        $pms .= "<tr><td>&nbsp;&nbsp;PHP: </td>";
        $pms .= "<td><i>[^p^] s</i></td><td>&nbsp;</td>";
        $pms .= "</tr>";
        $pms .= "<tr><td>&nbsp;&nbsp;Total: </td>";
        $pms .= "<td><i>[^t^] s</i></td><td>&nbsp;</td>";
        $pms .= "</tr>";
        $pms .= "</table>";
        $pms .= "</body></html>";
        $this->documentContent = $pms;
        $this->outputContent();
        exit;
    }
    
	/**
	* Visitor Logging
	* Parsing functions used in this class are based on/ inspired by code by Sebastian Bergmann.
	* The regular expressions used in this class are taken from the ModLogAn 
	* (http://jan.kneschke.de/projects/modlogan/) project.
	*/
    public function log()
    {
        // if we are tracking visitors and this is not the 404 error page, log the hit
        if ($this->config['track_visitors'] && $this->documentIdentifier != $this->config['error_page']) {
            include_once(MANAGER_PATH . "includes/visitor_logging.inc.php");
        }
    }
    
	/**
	* Get All Children
	*
	* Get all Children for a document
	*
	* @param integer $id document ID defaults to 0 (root)
	* @param string $sort sort column
	* @param string $dir Order of sort ASC | DESC
	* @param string $fields The columns you would like to pull from the table
	* @param integer $limit limit number of how many to grab
	* @param string $showhidden true | false do we show hidden documents
	* @return array of child documents
	*/
    public function getAllChildren($id = 0, $sort = 'menuindex', $dir = 'ASC', $fields = 'id, pagetitle, longtitle, description, parent, alias', $limit = "", $showhidden = false)
    {
        // returns a two dimensional array of $key=>$value data for all existing documents regardless of activity status
        // $id = id of the document whose children have been requested
        // $sort = the field to sort the result by
        // $dir = sort direction (ASC|DESC)
        // $fields = comma delimited list of fields to be returned for each record
        // $limit = maximun number of records to return (default=all)
        // $showhidden = setting to [true|1] will override the showinmenu flag setting (default=false)
        $limit = ($limit != "") ? "LIMIT $limit" : "";
        $tbl   = $this->db . "site_content";
		$showinmenu = '';
        if ($showhidden == 0)
            $showinmenu = "AND $tbl.showinmenu=1";
        $sql           = "SELECT $fields FROM $tbl WHERE $tbl.parent=$id $showinmenu ORDER BY $sort $dir $limit;";
        $result        = $this->dbQuery($sql);
        $resourceArray = array();
        for ($i = 0; $i < @$this->recordCount($result); $i++) {
            array_push($resourceArray, @$this->fetchRow($result));
        }
        return $resourceArray;
    }
	
	/**
	* Get Active Children
	*
	* Get Active Children of a document
	*
	* @param integer $id document ID defaults to 0 (root)
	* @param string $sort sort column
	* @param string $dir sort direction (ASC|DESC)
	* @param string $fields comma delimited list of fields to be returned for each record
	* @param integer $limit maximun number of records to return (default=all)
	* @param string $showhidden setting to [true|1] will override the showinmenu flag setting (default=false)
	* @return returns a two dimensional array of $key=>$value data for active documents only
	*/
    public function getActiveChildren($id = 0, $sort = 'menuindex', $dir = '', $fields = 'id, pagetitle, longtitle, description, parent, alias, showinmenu', $limit = "", $showhidden = false)
    {
        $limit = ($limit != "") ? "LIMIT $limit" : "";
        $tbl   = $this->db . "site_content";
        if ($showhidden == 0)
            $showinmenu = "AND $tbl.showinmenu=1";
        $sql           = "SELECT $fields FROM $tbl WHERE $tbl.parent=$id AND $tbl.published=1 AND $tbl.deleted=0 $showinmenu ORDER BY $sort $dir $limit;";
        $result        = $this->dbQuery($sql);
        $resourceArray = array();
        for ($i = 0; $i < @$this->recordCount($result); $i++) {
            array_push($resourceArray, @$this->fetchRow($result));
        }
        return $resourceArray;
    }
	
	/**
	* Get Documents
	*
	* Modified getDocuments function which includes LIMIT capabilities - Ralph
	*
	* @param integer $id the identifier of the document whose data is being requested
	* @param string $fields a comma delimited list of fields to be returned in a $key=>$value array (defaults to all)
	* @param string $where an optional WHERE clause to be used inthe query
	* @param string $sort the field to sort the result by
	* @param string $dir sort direction (ASC|DESC)
	* @param integer $limit maximun number of records to return (default=all)
	* @param string $showhidden setting to [true|1] will override the showinmenu flag setting (default=false)
	* @return returns $key=>$values for an array of document id's
	*/
    public function getDocuments($ids = array(), $published = 1, $deleted = 0, $fields = "*", $where = '', $sort = "menuindex", $dir = "ASC", $limit = "", $showhidden = false)
    {
        if (count($ids) == 0) {
            return false;
        } else {
            $limit = ($limit != "") ? "LIMIT $limit" : "";
            $tbl   = $this->db . "site_content";
            if ($showhidden == 0)
                $showinmenu = "AND $tbl.showinmenu=1";
            $sql           = "SELECT $fields FROM $tbl WHERE $tbl.id IN (" . join($ids, ",") . ") AND $tbl.published=$published AND $tbl.deleted=$deleted $showinmenu $where ORDER BY $sort $dir $limit;";
            $result        = $this->dbQuery($sql);
            $resourceArray = array();
            for ($i = 0; $i < @$this->recordCount($result); $i++) {
                array_push($resourceArray, @$this->fetchRow($result));
            }
            return $resourceArray;
        }
    }
	
	/**
	* Get Document
	*
	* Get document based on id and choose what fields to return in the array
	*
	* @param integer $id the identifier of the document whose data is being requested
	* @param string $fields a comma delimited list of fields to be returned in a $key=>$value array (defaults to all)
	* @return returns $key=>$values for a specific document
	*/
    public function getDocument($id = 0, $fields = "*")
    {
        if ($id == 0) {
            return false;
        } else {
            $docs = $this->getIntTableRows($fields, 'site_content', 'id=' . $id);
            if ($docs != false) {
                return $docs[0];
            } else {
                return false;
            }
        }
    }
	
	/**
	* Get Full Document Information
	*
	* Gets All Document information including template variables for the document
	*
	* @param string $method The column/field to get document by
	* @param mixed $identifier The value to get the document by (id of document or string value of document)
	* @return Returns array of document data
	*/
    public function getDocumentObject($method = false, $identifier = false)
    {
        if (!$method || !$identifier) {
            return false;
        }
        $sql    = "SELECT * FROM " . $this->db . "site_content WHERE " . $this->db . "site_content." . $method . " = '" . $identifier . "';";
        $result = $this->dbQuery($sql);
        if ($this->recordCount($result) > 0) {
            $doc    = $this->fetchRow($result);
            // get tvs for this document
            //$result = $this->getIntTableRows('tv_id,tv_value', 'site_content_tv_val', 'doc_id='.$doc['id']);
            $sql    = "SELECT tvv.*, tv.field_name, tv.output_type, tv.opts, tvt.template_id FROM " . $this->db . "site_content_tv_val tvv" . " LEFT JOIN " . $this->db . "template_variables tv" . " ON tvv.tv_id=tv.tv_id" . " LEFT JOIN " . $this->db . "template_variable_templates tvt" . " ON tvt.tv_id=tv.tv_id" . " WHERE tvv.doc_id=" . $doc['id'] . " AND tvt.template_id=" . $doc['template'];
            $result = $this->dbQuery($sql);
            if ($this->recordCount($result) > 0) {
                while ($r = $this->fetchRow($result)) {
                    $doc[$r['field_name']] = $this->formatTVOutput($r['tv_value'], $r['output_type'], $r['opts']);
                }
            }
            return $doc;
        }
        return false;
    }
	
	/**
	* Get document info
	*
	* Get a document based on id and active or not
	*
	* @param integer $id id of the document whose data is being requested
	* @param integer $active boolean (0=false|1=true) determines whether to return data for any or only an active document
	* @param string $fields a comma delimited list of fields to be returned in a $key=>$value array
	* @return a $key=>$value array of information for a single document
	*/
    public function getPageInfo($id = -1, $active = 1, $fields = 'id, pagetitle, description, alias')
    {
        if ($id == 0) {
            return false;
        } else {
            $tbl       = $this->db . "site_content";
            $activeSql = $active == 1 ? "AND $tbl.published=1 AND $tbl.deleted=0" : "";
            $sql       = "SELECT $fields FROM $tbl WHERE $tbl.id=$id $activeSql";
            $result    = $this->dbQuery($sql);
            $pageInfo  = @$this->fetchRow($result);
            return $pageInfo;
        }
    }
	
	/**
	* Get Parent of specified document
	*
	* @param integer $id id of the document whose data is being requested
	* @param integer $active boolean (0=false|1=true) determines whether to return data for any or only an active document
	* @param string $fields a comma delimited list of fields to be returned in a $key=>$value array
	* @return a $key=>$value array of information for a single document
	*/
    public function getParent($id = -1, $active = 1, $fields = 'id, pagetitle, description, alias, parent')
    {
        if ($id == -1 || $id == "") {
            $id = $this->documentObject['parent'];
        }
        if ($id == 0) {
            return false;
        } else {
            $tbl       = $this->db . "site_content";
            $activeSql = $active == 1 ? "AND $tbl.published=1 AND $tbl.deleted=0" : "";
            $sql       = "SELECT $fields FROM $tbl WHERE $tbl.id=$id $activeSql";
            $result    = $this->dbQuery($sql);
            $parent    = @$this->fetchRow($result);
            return $parent;
        }
    }
	
	/**
	* Current Snippet Name
	*
	* @return returns the textual name of the calling snippet
	*/
    public function getSnippetName()
    {
        return $this->currentSnippet;
    }
	
	/**
	* Delete cache files
	*
	* Deletes all cache documents in the ./assets/cache directory
	*
	* @return returns boolean (true|false)
	*/
    public function clearCache()
    {
        $basepath = dirname(__FILE__);
        if (@$handle = opendir($basepath . "/assets/cache")) {
            $filesincache        = 0;
            $deletedfilesincache = 0;
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != "..") {
                    $filesincache += 1;
                    if (preg_match("/\.etoCache/", $file)) {
                        $deletedfilesincache += 1;
                        unlink($basepath . "/assets/cache/" . $file);
                    }
                }
            }
            closedir($handle);
            return true;
        } else {
            return false;
        }
    }
	
	/**
	* Make a site URL
	*
	* Creates a url based on id or alias of document and creates url parameters.
	* Examples: makeURL(45,'','?cms=Etomite') OR makeURL('','my_alias','?cms=Etomite')
	* Or if zend_urls = 1
	* $ar = array('var1'=>'value1','var2'=>'value2');
	* $url = $etomite->makeUrl('4','',$ar); // returns http://domain.com/test/var1/value1/var2/value2
	*
	* @param integer $id a valid document id and is optional when sending an alias
	* @param string $alias can now be sent without $id but may cause failures if the alias doesn't exist
	* @param mixed $args is a URL compliant text string of $_GET key=value pairs
	* @return returns a properly formatted site URL
	*/
    public function makeUrl($id, $alias = '', $args = '')
    {
        if (!is_numeric($id) && $id != "") {
            $this->messageQuit("`$id` is not numeric and may not be passed to makeUrl()");
        }
        // assign a shorter base URL variable
        $baseURL = $this->config['www_base_path'];
        if ($this->config['zend_urls'] == 1 && !empty($alias) && isset($this->documentListing[$alias])) {
            $url = $baseURL . $alias;
        } elseif ($this->config['zend_urls'] == 1 && isset($this->aliases[$id]) && $this->aliases[$id] != "") {
            if ($id != $this->config['site_start']) {
                $url = $baseURL . $this->aliases[$id];
            } else {
                $url = $baseURL;
            }
        }
        // if $alias was sent in the function call and the alias exists, use it
            elseif ($this->config['friendly_alias_urls'] == 1 && isset($this->documentListing[$alias])) {
            $url = $baseURL . $this->config['friendly_url_prefix'] . $alias . $this->config['friendly_url_suffix'];
        }
        // $alias wasn't sent or doesn't exist so try to get the documents alias based on id if it exists
            elseif ($this->config['friendly_alias_urls'] == 1 && isset($this->aliases[$id]) && $this->aliases[$id] != "") {
            if ($id != $this->config['site_start']) {
                $url = $baseURL . $this->config['friendly_url_prefix'] . $this->aliases[$id] . $this->config['friendly_url_suffix'];
            } else {
                $url = $baseURL;
            }
        }
        // only friendly URL's are enabled or previous alias attempts failed
            elseif ($this->config['friendly_urls'] == 1) {
            $url = $baseURL . $this->config['friendly_url_prefix'] . $id . $this->config['friendly_url_suffix'];
        }
        // for some reason nothing else has workd so revert to the standard URL method
        else {
            $url = $baseURL . "index.php?id=$id";
        }
        if ($this->config['zend_urls'] == 1 && is_array($args)) {
            $params = array();
            foreach ($args as $key => $val) {
                if (!empty($val)) {
                    array_push($params, $key);
                    array_push($params, $val);
                }
            }
            if (!empty($params)) {
                $args = "/" . implode("/", $params);
            }
            /*else{ $args = $this->config['friendly_url_suffix']; }*/
            else {
                $args = '';
            }
            if (empty($args)) {
                $url .= $this->config['friendly_url_suffix'];
            }
        } elseif ($this->config['zend_urls'] == 1) {
            //$url .= $this->config['friendly_url_suffix'];
            if (strlen($args) && strpos($url, "?")) {
                $url .= $this->config['friendly_url_suffix'];
                $args = "&amp;" . substr($args, 1);
            }
            //if(empty($args) && $id != $this->config['site_start']) {
            if ($id != $this->config['site_start']) {
                $url .= $this->config['friendly_url_suffix'];
            }
        } else {
            // make sure only the first argument parameter is preceded by a "?"
            if (is_array($args)) { // fix the arguments if they are in an array form
                $argsArray = array();
                foreach ($args as $key => $val) {
                    $argsArray[] = $key . "=" . $val;
                }
                $args = "?" . implode("&amp;", $argsArray);
            }
            if (strlen($args) && strpos($url, "?"))
                $args = "&amp;" . substr($args, 1);
        }
        return $url . $args;
    }
	
	/**
	* Get configuration value from config name
	*
	* @param string $name name from config
	* @return returns config value
	*/
    public function getConfig($name = '')
    {
        // returns the requested configuration setting_value to caller
        // based on $key=>$value records stored in system_settings table
        // $name can be any valid setting_name
        // Example: getConfig('site_name')
        if (!empty($this->config[$name])) {
            return $this->config[$name];
        } else {
            return false;
        }
    }
	
	/**
	* Get Version Data
	*
	* @return returns version data in an array
	*/
    public function getVersionData()
    {
        // returns a $key=>$value array of software package information to caller
        include(MANAGER_PATH . "includes/version.inc.php");
        $version                 = array();
        $version['release']      = $release; // Current Etomite release
        $version['code_name']    = $code_name; // Current Etomite codename
        $version['version']      = $small_version; // Current Etomite version
        $version['patch_level']  = $patch_level; // Revision number/suffix
        $version['full_appname'] = $full_appname; // Etomite Content Management System + $version + $patch_level + ($code_name)
        $version['full_slogan']  = $full_slogan; // Current Etomite slogan
        return $version;
    }
	
	/**
	* Make List
	*
	* @param array $array can be a single or multi-dimensional $key=>$value array
	* @param string $urlroot the lists root CSS class name for controlling list-item appearance
	* @param string $ulprefix the prefix to send with recursive calls to this function
	* @param string $type can be used to specifiy the type of the list-item marker (examples:disc,square,decimal,upper-roman,etc...)
	* @param mixed $ordered determines whether the list is alphanumeric or symbol based (true=alphanumeric|false=symbol)
	* @param integer $tablevel an internally used variable for determining depth of indentation on recursion
	* @param string $tabstr can be used to send an alternative indentation string in place of the default tab character
	* @return returns either ordered or unordered lists based on passed parameters
	*/
    public function makeList($array, $ulroot = 'root', $ulprefix = 'sub_', $type = '', $ordered = false, $tablevel = 0, $tabstr = "")
    {
        // first find out whether the value passed is an array
        if (!is_array($array)) {
            return "<ul><li>Bad list</li></ul>";
        }
        if (!empty($type)) {
            $typestr = " style='list-style-type: $type'";
        } else {
            $typestr = "";
        }
        $tabs = "";
        for ($i = 0; $i < $tablevel; $i++) {
            $tabs .= $tabstr;
        }
        $listhtml = $ordered == true ? $tabs . "<ol class='$ulroot'$typestr>" : $tabs . "<ul class='$ulroot'$typestr>";
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $listhtml .= $tabs . "<li>";
                if ($ordered)
                    $listhtml .= $key;
                $listhtml .= $this->makeList($value, $ulprefix . $ulroot, $ulprefix, $type, $ordered, $tablevel + 1, $tabstr) . $tabs . "</li>";
            } else {
                $listhtml .= $tabs . "<li>" . $value . "</li>";
            }
        }
        $listhtml .= $ordered == true ? $tabs . "</ol>" : $tabs . "</ul>";
        return $listhtml;
    }
	
	/**
	* User Logged In
	*
	* @return returns user data if user is logged in or false if not
	*/
    public function userLoggedIn()
    {
        // returns an array of user details if logged in else returns false
        // array components returned are self-explanatory
        $userdetails = array();
        if (isset($_SESSION['validated']) && $_SESSION['validated'] == 1) {
            $userdetails['loggedIn'] = true;
            $userdetails['id']       = strip_tags($_SESSION['internalKey']);
            $userdetails['username'] = strip_tags($_SESSION['shortname']);
            return $userdetails;
        } else {
            return false;
        }
    }
	
	/**
	* Get Keywords
	* 
	* @param integer $id the identifier of the document for which keywords have been requested
	* @deprecated No longer used, now there is a meta_keywords field for each document
	* @return returns a single dimensional array of document specific keywords
	*/
    public function getKeywords($id = 0)
    {
        if ($id == 0 || $id == "") {
            $id = $this->documentIdentifier;
        }
        $tbl      = $this->db;
        $sql      = "SELECT keywords.keyword FROM " . $tbl . "site_keywords AS keywords INNER JOIN " . $tbl . "keyword_xref AS xref ON keywords.id=xref.keyword_id WHERE xref.content_id = $id";
        $result   = $this->dbQuery($sql);
        $limit    = $this->recordCount($result);
        $keywords = array();
        if ($limit > 0) {
            for ($i = 0; $i < $limit; $i++) {
                $row        = $this->fetchRow($result);
                $keywords[] = $row['keyword'];
            }
        }
        return $keywords;
    }
	
	/**
	* Parse a Snippet
	* 
	* @param string $snippetName name of given snippet
	* @param array $params array of $key=>$value parameter pairs passed to the snippet
	* @return returns the processed results of a snippet
	*/
    public function runSnippet($snippetName, $params = array())
    {
        return $this->evalSnippet($this->getResource($snippetName, 'snippet'), $params);
    }
	
	/**
	* Get a Chunk from cache
	* 
	* @param string $chunkName textual name of the chunk to be returned
	* @return returns the contents of a cached chunk as code
	*/
    public function getChunk($chunkName)
    {
        //return base64_decode($this->chunkCache[$chunkName]);
		return $this->getResource($chunkName, 'chunk');
    }
	
	/**
	* Get a Chunk from cache
	* 
	* @deprecated no longer used. it just runs the getChunk function
	* @param string $chunkName textual name of the chunk to be returned
	* @return runs the getChunk returns the contents of a cached chunk as code
	* @see function getChunk
	*/
    public function putChunk($chunkName)
    {
        return $this->getChunk($chunkName);
    }
	
	/**
	* Parse a Chunk
	*
	* Example: {tag}<tr><td>{col1}</td><td>{col2}</td></tr>{/tag}
	* 
	* @param string $chunkName textual name of the chunk to be returned
	* @param array $chunkArr a single dimensional $key=>$value array of tags and values
	* @param string $prefix the begining marker to look for a chunk replacement value
	* @param string $suffix the ending marker of a chunk replacement value
	* @return returns chunk code with marker tags replaced with $key=>$value values
	*/
    public function parseChunk($chunkName, $chunkArr, $prefix = "{", $suffix = "}")
    {
        if (!is_array($chunkArr) || count($chunkArr) < 1) {
            return false;
        }
        $chunk = $this->getChunk($chunkName);
        foreach ($chunkArr as $key => $value) {
            if (!is_array($value)) {
                $chunk  = str_replace($prefix . $key . $suffix, $value, $chunk);
                ${$key} = !empty($value) ? $value : "";
            } else {
                if (preg_match("|" . $prefix . $key . $suffix . "(.+)" . $prefix . '/' . $key . $suffix . "|s", $chunk, $match) && count($value) > 0) {
                    $loopData = '';
                    foreach ($value as $row) {
                        $loopTemp = $match['1'];
                        foreach ($row as $loopKey => $loopValue) {
                            $loopTemp   = str_replace($prefix . $loopKey . $suffix, $loopValue, $loopTemp);
                            ${$loopKey} = !empty($loopValue) ? $loopValue : "";
                        }
                        $loopData .= $loopTemp;
                    }
                    $chunk = str_replace($match['0'], $loopData, $chunk);
                }
            }
        }
        ob_start();
        $chunk = '?' . '>' . $chunk . '<' . '?';
        eval($chunk);
        $chunk = ob_get_contents();
        ob_end_clean();
        return $chunk;
    }
    public function getUser($id)
    {
        if (!isset($id) || !is_numeric($id)) {
            return false;
        } else {
            $db = new ExtDB($this);
            $db->select(array(
                'manager_users' => 'mu'
            ), array(
                'id',
                'username'
            ));
            $db->where('mu.id = ' . $id);
            $db->leftJoin(array(
                'user_attributes' => 'ua'
            ), 'ua.internalKey=mu.id', array(
                'id as ua_id',
                'fullname',
                'role',
                'email',
                'phone',
                'mobilephone',
                'blocked',
                'blockeduntil',
                'logincount',
                'lastlogin',
                'thislogin',
                'failedlogincount',
                'address',
                'city',
                'state',
                'zip',
                'mailmessages'
            ));
            $db->limit('1');
            $db->create();
            if ($user = $db->fetch()) {
                return $user;
            }
            return false;
        }
    }
    public function getUserGroups($id = false)
    {
        if (!$id) {
            if (isset($_SESSION['internalKey'])) {
                $id = $_SESSION['internalKey'];
            }
        }
        if (!$id) {
            return array();
        }
        $groups = $this->getIntTableRows('*', 'member_groups', 'member=' . $id);
        if ($groups && count($groups) > 0) {
            $gdata = array();
            foreach ($groups as $g) {
                $gdata[] = $g['user_group'];
            }
            return $gdata;
        }
        return array();
    }
    // returns all manager users
    public function getUsers()
    {
        $sql   = "select username, id from " . $this->db . "manager_users order by username";
        $rs    = $this->dbQuery($sql);
        $users = array();
        while ($row = $this->fetchRow($rs)) {
            $users[] = $this->getUser($row['id']);
        }
        return $users;
    }
    public function getUserData()
    {
        // returns user agent related (browser) info in a $key=>$value array using the phpSniff class
        // can be used to perform conditional operations based on visitors browser specifics
        // items returned: ip,ua,browser,long_name,version,maj_ver,min_vermin_ver,letter_ver,javascript,platform,os,language,gecko,gecko_ver,html,images,frames,tables,java,plugins,css2,css1,iframes,xml,dom,hdml,wml,must_cache_forms,avoid_popup_windows,cache_ssl_downloads,break_disposition_header,empty_fil,e_input_value,scrollbar_in_way
        include_once(MANAGER_PATH . "models/getUserData.extender.php");
        return $tmpArray;
    }
    public function getSiteStats()
    {
        // returns a single dimensional $key=>$value array of the visitor log totals
        // array $keys are  today, month, piDay, piMonth, piAll, viDay, viMonth, viAll, visDay, visMonth, visAll
        // today = date in YYYY-MM-DD format
        // month = two digit month (01-12)
        // pi = page impressions per Day, Month, All
        // vi = total visits
        // vis = unique visitors
        $tbl    = $this->db . "log_totals";
        $sql    = "SELECT * FROM $tbl";
        $result = $this->dbQuery($sql);
        $tmpRow = $this->fetchRow($result);
        return $tmpRow;
    }
    //
    // START: Database abstraction layer related functions
    //
    public function dbConnect()
    {
        // function to connect to the database
        $tstart = $this->getMicroTime();
        if (@!$this->rs = mysql_connect($this->dbConfig['host'], $this->dbConfig['user'], $this->dbConfig['pass'])) {
            $this->messageQuit("Failed to create the database connection! ERROR: " . mysql_error());
        } else {
            mysql_select_db($this->dbConfig['dbase']);
            $tend      = $this->getMicroTime();
            $totaltime = $tend - $tstart;
            if (isset($this->config['dumpSQL']) && $this->config['dumpSQL']) {
                $this->queryCode .= "<fieldset style='text-align:left'><legend>Database connection</legend>" . sprintf("Database connection was created in %2.4f s", $totaltime) . "</fieldset><br />";
            }
            $this->queryTime = $this->queryTime + $totaltime;
        }
    }
    public function dbQuery($query)
    {
        // function to query the database
        // check the connection and create it if necessary
        //error_log($query);
        if (empty($this->rs)) {
            $this->dbConnect();
        }
        $tstart = $this->getMicroTime();
        if (@!$result = mysql_query($query, $this->rs)) {
            $this->messageQuit("Execution of a query to the database failed! ERROR: " . mysql_error(), $query);
        } else {
            $tend            = $this->getMicroTime();
            $totaltime       = $tend - $tstart;
            $this->queryTime = $this->queryTime + $totaltime;
            if ($this->config['dumpSQL']) {
                $this->queryCode .= "<fieldset style='text-align:left'><legend>Query " . ($this->executedQueries + 1) . " - " . sprintf("%2.4f s", $totaltime) . "</legend>" . $query . "</fieldset><br />";
            }
            $this->executedQueries = $this->executedQueries + 1;
            if (count($result) > 0) {
                return $result;
            } else {
                return false;
            }
        }
    }
    public function recordCount($rs)
    {
        // function to count the number of rows in a record set
        return mysql_num_rows($rs);
    }
    public function fetchRow($rs, $mode = 'assoc')
    {
        // [0614] object mode added by Ralph
        if ($mode == 'assoc') {
            return mysql_fetch_assoc($rs);
        } elseif ($mode == 'num') {
            return mysql_fetch_row($rs);
        } elseif ($mode == 'both') {
            return mysql_fetch_array($rs, MYSQL_BOTH);
        } elseif ($mode == 'object') {
            return mysql_fetch_object($rs);
        } else {
            $this->messageQuit("Unknown get type ($mode) specified for fetchRow - must be empty, 'assoc', 'num', 'both', or 'object'.");
        }
    }
    public function affectedRows()
    {
        // returns the number of rows affected by the last query
        return mysql_affected_rows($this->rs);
    }
    public function insertId()
    {
        // returns auto-increment id of the last insert
        return mysql_insert_id($this->rs);
    }
    public function dbClose()
    {
        // function to close a database connection
        mysql_close($this->rs);
    }
    public function getIntTableRows($fields = "*", $from = "", $where = "", $sort = "", $dir = "ASC", $limit = "", $push = true, $addPrefix = true)
    {
        // function to get rows from ANY internal database table
        // This function works much the same as the getDocuments() function. The main differences are that it will accept a table name and can use a LIMIT clause.
        // $fields = a comma delimited string: $fields="name,email,age"
        // $from = name of the internal Etomite table which data will be selected from without database name or table prefix ($from="user_messages")
        // $where = any optional WHERE clause: $where="parent=10 AND published=1 AND type='document'"
        // $sort = field you wish to sort by: $sort="id"
        // $dir = ASCending or DESCending sort order
        // $limit = maximum results returned: $limit="3" or $limit="10,3"
        // $push = ( true = [default] array_push results into a multi-demensional array | false = return MySQL resultset )
        // $addPrefix = whether to check for and/or add $this->dbConfig['table_prefix'] to the table name
        // Returns FALSE on failure.
        if ($from == "")
            return false;
        // added multi-table abstraction capability
        if (is_array($from)) {
            $tbl = "";
            foreach ($from as $_from)
                $tbl .= $this->db . $_from . ", ";
            $tbl = substr($tbl, 0, -2);
        } else {
            $tbl = ($this->dbConfig['table_prefix'] != '' && strpos($from, $this->dbConfig['table_prefix']) === 0 || !$addPrefix) ? $this->dbConfig['dbase'] . "." . $from : $this->db . $from;
        }
        $where  = ($where != "") ? "WHERE $where" : "";
        $sort   = ($sort != "") ? "ORDER BY $sort $dir" : "";
        $limit  = ($limit != "") ? "LIMIT $limit" : "";
        $sql    = "SELECT $fields FROM $tbl $where $sort $limit;";
        $result = $this->dbQuery($sql);
        if (!$push)
            return $result;
        $resourceArray = array();
        for ($i = 0; $i < @$this->recordCount($result); $i++) {
            array_push($resourceArray, @$this->fetchRow($result));
        }
        return $resourceArray;
    }
    public function putIntTableRow($fields = "", $into = "", $addPrefix = true)
    {
        // function to put a row into ANY internal database table
        // INSERT's a new table row into ANY internal Etomite database table. No data validation is performed.
        // $fields = a $key=>$value array: $fields=("name"=>$name,"email"=$email,"age"=>$age)
        // $into = name of the internal Etomite table which will receive the new data row without database name or table prefix: $into="user_messages"
        // $addPrefix = whether to check for and/or add $this->dbConfig['table_prefix'] to the table name
        // Returns FALSE on failure.
        if (($fields == "") || ($into == "")) {
            return false;
        } else {
            $tbl = ($this->dbConfig['table_prefix'] != '' && strpos($into, $this->dbConfig['table_prefix']) === 0 || !$addPrefix) ? $this->dbConfig['dbase'] . "." . $into : $this->db . $into;
            $sql = "INSERT INTO $tbl SET ";
            foreach ($fields as $key => $value) {
                $sql .= "`" . $key . "`=";
                //if (is_numeric($value)) $sql .= $value.",";
                //else
                $sql .= "'" . $value . "',";
            }
            $sql = rtrim($sql, ",");
            $sql .= ";";
            $result = $this->dbQuery($sql);
            return $result;
        }
    }
    public function updIntTableRows($fields = "", $into = "", $where = "", $sort = "", $dir = "ASC", $limit = "", $addPrefix = true)
    {
        // function to update a row into ANY internal database table
        // $fields = a $key=>$value array: $fields=("name"=>$name,"email"=$email,"age"=>$age)
        // $into = name of the internal Etomite table which will receive the new data row without database name or table prefix: $into="user_messages"
        // $where = any optional WHERE clause: $where="parent=10 AND published=1 AND type='document'"
        // $sort = field you wish to sort by: $sort="id"
        // $dir = ASCending or DESCending sort order
        // $limit = maximum results returned: $limit="3" or $limit="10,3"
        // $addPrefix = whether to check for and/or add $this->dbConfig['table_prefix'] to the table name
        // Returns FALSE on failure.
        if (($fields == "") || ($into == "")) {
            return false;
        } else {
            $where = ($where != "") ? "WHERE $where" : "";
            $sort  = ($sort != "") ? "ORDER BY $sort $dir" : "";
            $limit = ($limit != "") ? "LIMIT $limit" : "";
            $tbl   = ($this->dbConfig['table_prefix'] != '' && strpos($into, $this->dbConfig['table_prefix']) === 0 || !$addPrefix) ? $this->dbConfig['dbase'] . "." . $into : $this->db . $into;
            $sql   = "UPDATE $tbl SET ";
            foreach ($fields as $key => $value) {
                $sql .= "`" . $key . "`=";
                //if (is_numeric($value)) $sql .= $value.",";
                //else
                $sql .= "'" . $value . "',";
            }
            $sql = rtrim($sql, ",");
            $sql .= " $where $sort $limit;";
            $result = $this->dbQuery($sql);
            return $result;
        }
    }
    public function getExtTableRows($host = "", $user = "", $pass = "", $dbase = "", $fields = "*", $from = "", $where = "", $sort = "", $dir = "ASC", $limit = "", $push = true)
    {
        // function to get table rows from an external MySQL database
        // Performance is identical to getIntTableRows plus additonal information regarding the external database.
        // $host is the hostname where the MySQL database is located: $host="localhost"
        // $user is the MySQL username for the external MySQL database: $user="username"
        // $pass is the MySQL password for the external MySQL database: $pass="password"
        // $dbase is the MySQL database name to which you wish to connect: $dbase="extdata"
        // $fields should be a comma delimited string: $fields="name,email,age"
        // $from is the name of the External database table that data rows will be selected from: $from="contacts"
        // $where can be any optional WHERE clause: $where="parent=10 AND published=1 AND type='document'"
        // $sort can be set to whichever field you wish to sort by: $sort="id"
        // $dir can be set to ASCending or DESCending sort order
        // $limit can be set to limit results returned: $limit="3" or $limit="10,3"
        // $push = ( true = [default] array_push results into a multi-demensional array | false = return MySQL resultset )
        // Returns FALSE on failure.
        if (($host == "") || ($user == "") || ($pass == "") || ($dbase == "") || ($from == "")) {
            return false;
        } else {
            $where = ($where != "") ? "WHERE  $where" : "";
            $sort  = ($sort != "") ? "ORDER BY $sort $dir" : "";
            $limit = ($limit != "") ? "LIMIT $limit" : "";
            $tbl   = $dbase . "." . $from;
            //$this->dbExtConnect($host, $user, $pass, $dbase);
            $sql    = "SELECT $fields FROM $tbl $where $sort $limit;";
            $result = $this->dbExtQuery($host, $user, $pass, $dbase, $sql);
            if (!$push)
                return $result;
            $resourceArray = array();
            for ($i = 0; $i < @$this->recordCount($result); $i++) {
                array_push($resourceArray, @$this->fetchRow($result));
            }
            return $resourceArray;
        }
    }
    public function putExtTableRow($host = "", $user = "", $pass = "", $dbase = "", $fields = "", $into = "")
    {
        // function to update a row into an external database table
        // $host = hostname where the MySQL database is located: $host="localhost"
        // $user = MySQL username for the external MySQL database: $user="username"
        // $pass = MySQL password for the external MySQL database: $pass="password"
        // $dbase = MySQL database name to which you wish to connect: $dbase="extdata"
        // $fields = a $key=>$value array: $fields=("name"=>$name,"email"=$email,"age"=>$age)
        // $into = name of the external database table which will receive the new data row: $into="contacts"
        // $where = optional WHERE clause: $where="parent=10 AND published=1 AND type='document'"
        // $sort = whichever field you wish to sort by: $sort="id"
        // $dir = ASCending or DESCending sort order
        // $limit = limit maximum results returned: $limit="3" or $limit="10,3"
        // Returns FALSE on failure.
        if (($host == "") || ($user == "") || ($pass == "") || ($dbase == "") || ($fields == "") || ($into == "")) {
            return false;
        } else {
            //$this->dbExtConnect($host, $user, $pass, $dbase);
            $tbl = $dbase . "." . $into;
            $sql = "INSERT INTO $tbl SET ";
            foreach ($fields as $key => $value) {
                $sql .= "`" . $key . "`=";
                //if (is_numeric($value)) $sql .= $value.",";
                //else
                $sql .= "'" . $value . "',";
            }
            $sql    = rtrim($sql, ",");
            $result = $this->dbExtQuery($host, $user, $pass, $dbase, $sql);
            return $result;
        }
    }
    public function updExtTableRows($host = "", $user = "", $pass = "", $dbase = "", $fields = "", $into = "", $where = "", $sort = "", $dir = "ASC", $limit = "")
    {
        // function to put a row into an external database table
        // INSERT's a new table row into an external database table. No data validation is performed.
        // $host = hostname where the MySQL database is located: $host="localhost"
        // $user = MySQL username for the external MySQL database: $user="username"
        // $pass = MySQL password for the external MySQL database: $pass="password"
        // $dbase = MySQL database name to which you wish to connect: $dbase="extdata"
        // $fields = a $key=>$value array: $fields=("name"=>$name,"email"=$email,"age"=>$age)
        // $into = name of the external database table which will receive the new data row: $into="user_messages"
        // Returns FALSE on failure.
        if (($fields == "") || ($into == "")) {
            return false;
        } else {
            //$this->dbExtConnect($host, $user, $pass, $dbase);
            $tbl   = $dbase . "." . $into;
            $where = ($where != "") ? "WHERE $where" : "";
            $sort  = ($sort != "") ? "ORDER BY $sort $dir" : "";
            $limit = ($limit != "") ? "LIMIT $limit" : "";
            $sql   = "UPDATE $tbl SET ";
            foreach ($fields as $key => $value) {
                $sql .= "`" . $key . "`=";
                //if (is_numeric($value)) $sql .= $value.",";
                //else
                $sql .= "'" . $value . "',";
            }
            $sql = rtrim($sql, ",");
            $sql .= " $where $sort $limit;";
            $result = $this->dbExtQuery($host, $user, $pass, $dbase, $sql);
            return $result;
        }
    }
    public function dbExtConnect($host, $user, $pass, $dbase)
    {
        // function used to connect to external database
        // This function is called by other functions and should not need to be called directly.
        // $host = hostname where the MySQL database is located: $host="localhost"
        // $user = MySQL username for the external MySQL database: $user="username"
        // $pass = MySQL password for the external MySQL database: $pass="password"
        // $dbase = MySQL database name to which you wish to connect: $dbase="extdata"
        $tstart = $this->getMicroTime();
        if (@!$this->rsExt = mysql_connect($host, $user, $pass)) {
            $this->messageQuit("Failed to create connection to the $dbase database!");
        } else {
            mysql_select_db($dbase, $this->rsExt);
            $tend      = $this->getMicroTime();
            $totaltime = $tend - $tstart;
            if ($this->config['dumpSQL']) {
                $this->queryCode .= "<fieldset style='text-align:left'><legend>Database connection</legend>" . sprintf("Database connection to %s was created in %2.4f s", $dbase, $totaltime) . "</fieldset><br />";
            }
            $this->queryTime = $this->queryTime + $totaltime;
        }
    }
    public function dbExtQuery($host, $user, $pass, $dbase, $query)
    {
        // function to query an external database
        // This function can be used to perform queries on any external MySQL database.
        // $host = hostname where the MySQL database is located: $host="localhost"
        // $user = MySQL username for the external MySQL database: $user="username"
        // $pass = MySQL password for the external MySQL database: $pass="password"
        // $dbase = MySQL database name to which you wish to connect: $dbase="extdata"
        // $query = SQL query to be performed: $query="DELETE FROM sometable WHERE somefield='somevalue';"
        // Returns error on fialure.
        $tstart = $this->getMicroTime();
        $this->dbExtConnect($host, $user, $pass, $dbase);
        if (@!$result = mysql_query($query, $this->rsExt)) {
            $this->messageQuit("Execution of a query to the database failed", $query);
        } else {
            $tend            = $this->getMicroTime();
            $totaltime       = $tend - $tstart;
            $this->queryTime = $this->queryTime + $totaltime;
            if ($this->config['dumpSQL']) {
                $this->queryCode .= "<fieldset style='text-align:left'><legend>Query " . ($this->executedQueries + 1) . " - " . sprintf("%2.4f s", $totaltime) . "</legend>" . $query . "</fieldset><br />";
            }
            $this->executedQueries = $this->executedQueries + 1;
            return $result;
        }
    }
	public function fetchExtRow($rs, $mode = 'assoc')
    {
        // [0614] object mode added by Ralph
        if ($mode == 'assoc') {
            return mysql_fetch_assoc($rs);
        } elseif ($mode == 'num') {
            return mysql_fetch_row($rs);
        } elseif ($mode == 'both') {
            return mysql_fetch_array($rs, MYSQL_BOTH);
        } elseif ($mode == 'object') {
            return mysql_fetch_object($rs);
        } else {
            $this->messageQuit("Unknown get type ($mode) specified for fetchRow - must be empty, 'assoc', 'num', 'both', or 'object'.");
        }
    }
    public function intTableExists($table)
    {
        // Modified 2008-05-10 [v1.1] by Ralph to use FROM clause
        // function to determine whether or not a specific database table exists
        // $table = the table name, including prefix, to check for existence
        // example: $table = "etomite_new_table"
        // Returns boolean TRUE or FALSE
        if ($table == null)
            return false;
        $query = "SHOW TABLE STATUS FROM " . $this->dbConfig['dbase'] . " LIKE '" . $table . "'";
        $rs    = $this->dbQuery($query);
        return ($row = $this->fetchRow($rs)) ? true : false;
    }
    public function extTableExists($host, $user, $pass, $dbase, $table)
    {
        // Added 2006-04-15 by Ralph Dahlgren
        // function to determine whether or not a specific database table exists
        // $host = hostname where the MySQL database is located: $host="localhost"
        // $user = MySQL username for the external MySQL database: $user="username"
        // $pass = MySQL password for the external MySQL database: $pass="password"
        // $dbase = MySQL database name to which you wish to connect: $dbase="extdata"
        // $table = the table name to check for existence: $table="some_external_table"
        // Returns boolean TRUE or FALSE
        $query = "SHOW TABLE STATUS LIKE '" . $table . "'";
        $rs    = $this->dbExtQuery($host, $user, $pass, $dbase, $query);
        return ($row = $this->fetchRow($rs)) ? true : false;
    }
    //
    // END: Database abstraction layer related functions
    //
    public function getFormVars($method = "", $prefix = "", $trim = "", $REQUEST_METHOD)
    {
        // function to retrieve form results into an associative $key=>$value array
        // This function is intended to be used to retrieve an associative $key=>$value array of form data which can be sent directly to the putIntTableRow() or putExttableRow() functions. This function performs no data validation. By utilizing $prefix it is possible to // retrieve groups of form results which can be used to populate multiple database tables. This funtion does not contain multi-record form capabilities.
        // $method = form method which can be POST or GET and is not case sensitive: $method="POST"
        // $prefix = used to specifiy prefixed groups of form variables so that a single form can be used to populate multiple database // tables. If $prefix is omitted all form fields will be returned: $prefix="frm_"
        // $trim = boolean value ([true or 1]or [false or 0]) which tells the function whether to trim off the field prefixes for a group // resultset
        // $RESULT_METHOD is sent so that if $method is omitted the function can determine the form method internally. This system variable cannot be assigned a user-specified value.
        // Returns FALSE if form method cannot be determined
        $results = array();
        $method  = strtoupper($method);
        if ($method == "")
            $method = $REQUEST_METHOD;
        if ($method == "POST")
            $method =& $_POST;
        elseif ($method == "GET")
            $method =& $_GET;
        elseif ($method == "FILES")
            $method =& $_FILES;
        else
            return false;
        reset($method);
        foreach ($method as $key => $value) {
            if (($prefix != "") && (substr($key, 0, strlen($prefix)) == $prefix)) {
                if ($trim) {
                    $pieces        = explode($prefix, $key, 2);
                    $key           = $pieces[1];
                    $results[$key] = $value;
                } else
                    $results[$key] = $value;
            } elseif ($prefix == "")
                $results[$key] = $value;
        }
        return $results;
    }
    public function arrayValuesToList($rs, $col)
    {
        // Converts a column of a resultset array into a comma delimited list (col,col,col)
        // $rs = query resultset OR an two dimensional associative array
        // $col = the target column to compile into a comma delimited string
        // Returns error on fialure.
        if (is_array($col))
            return false;
        $limit = $this->recordCount($rs);
        $tmp   = "";
        if ($limit > 0) {
            for ($i = 0; $i < $limit; $i++) {
                $row   = $this->fetchRow($rs);
                $tmp[] = $row[$col];
            }
            return implode(",", $tmp);
        } else {
            return false;
        }
    }
    public function mergeCodeVariables($content = "", $rs = "", $prefix = "{", $suffix = "}", $oddStyle = "", $evenStyle = "", $tag = "")
    {
        //  parses any string data for template tags and populates from a resultset or single associative array
        //  $content = the string data to be parsed
        //  $rs = the resultset or associateve array which contains the data to check for possible insertion
        //  $prefix & $suffix = the tags start and end characters for search and replace purposes
        //  $oddStyle & $evenStyle = CSS info sent as style='inline styles' or class='className'
        //  $tag = the HTML tag to use as a container for each template object record
        if ((!is_array($rs)) || ($content == ""))
            return false;
        if (!is_array($rs[0]))
            $rs = array(
                $rs
            );
        $i = 1;
        foreach ($rs as $row) {
            //$rowStyle = fmod($i,2) ? $oddStyle : $evenStyle;
            $_SESSION['rowStyle'] = ($_SESSION['rowStyle'] == $oddStyle) ? $evenStyle : $oddStyle;
            $tmp                  = $content;
            $keys                 = array_keys($row);
            foreach ($keys as $key) {
                $tmp = str_replace($prefix . $key . $suffix, $row[$key], $tmp);
            }
            if ((($oddStyle > "") || ($evenStyle > "")) && ($tag > "")) {
                //$output .= "\n<$tag ".$rowStyle.">$tmp</$tag>\n";
                $output .= "\n<$tag " . $_SESSION['rowStyle'] . ">$tmp</$tag>\n";
            } else {
                $output .= "$tmp\n";
            }
            $i++;
        }
        return $output;
    }
    public function getAuthorData($internalKey)
    {
        // returns a $key=>$value array of information from the user_attributes table
        // $internalKey which correlates with a documents createdby value.
        // Uasge: There are several ways in which this function can be called.
        //   To call this function from within a snippet you could use
        //   $author = $etomite->getAuthorData($etomite->documentObject['createdby'])
        //   or $author = $etomite->getAuthorData($row['createdby']) or $author = $etomite->getAuthorData($rs[$i]['createdby']).
        //   Once the $key=>$value variable, $author, has been populated you can access the data by using code similar to
        //   $name = $author['fullname'] or $output .= $author['email'] for example.
        //   There is also a snippet named GetAuthorData which uses the format:
        //   [[GetAuthorData?internalKey=[*createdby*]&field=fullname]]
        // Last Modified: 2008-04-17 [v1.0] by Ralph A. Dahlgren
        // * fixed to return false if user record not found
        $tbl    = $this->db . "user_attributes";
        $sql    = "SELECT * FROM $tbl WHERE $tbl.internalKey = " . $internalKey;
        $result = $this->dbQuery($sql);
        $limit  = $this->recordCount($result);
        if ($limit < 1) {
            return false;
        } else {
            $user = $this->fetchRow($result);
            return $user;
        }
    }
    //
    // Permissions and Authentication related functions
    //
    public function checkUserRole($action = "", $user = "", $id = "")
    {
        //  determine document permissions for a user
        //  $action = any role action name (edit_document,delete_document,etc.)
        //  $user = user id or internalKey
        //  $id = id of document in question
        //  because user permissions are stored in the session data the users role is not required
        // Returns error on failure.
        if (($this->config['use_udperms'] == 0) || ($_SESSION['role'] == 1))
            return true;
        if ($user == "")
            $user = $_SESSION['internalKey']; // Modified 2006-08-04 Ralph
        if ($user == "" || !is_numeric($user) || $user < 1)
            return false;
        // no need for document, because there is a check permissions function
        //if($id == "") $id = $this->documentIdentifier;
        // changed and moved up
        //if($user == "" || $id == "" || $_SESSION['role'] == "") return false;
        if (($action != "") && ($_SESSION['permissions'][$action] != 1))
            return false;
        //if(($document == 0) && ($this->config['udperms_allowroot'] == 1)) return true;
        if (isset($_SESSION['permissions'][$action]) && $_SESSION['permissions'][$action] == 1) {
            return true;
        } else {
            return false;
        }
    }
    public function checkFrontPermissions($id = false)
    {
        //  determines user permissions for the current document in the frontend
        $user     = $_SESSION['internalKey'];
        $document = ($id !== false) ? $id : $this->documentIdentifier;
        $role     = $_SESSION['role'];
        $groups   = $this->getUserGroups($user);
        if ($_SESSION['internalKey'] == "")
            return false;
        if ($role == 1)
            return true; // administrator - grant all document permissions
        if ($document == 0 && $this->config['udperms_allowroot'] == 0)
            return false;
        if ($this->config['use_udperms'] == 0 || $this->config['use_udperms'] == "" || !isset($this->config['use_udperms'])) {
            return true; // user document permissions aren't in use
        }
        // Added by Ralph 2006-07-07 to handle visitor permissions checks properly
        // Modified by Randy and nalagar in [0614]
        if ($this->config['use_uvperms'] == 0 || $this->config['use_uvperms'] == "" || !isset($this->config['use_uvperms'])) {
            return true; // visitor document permissions aren't in use
        }
        // need to check groups against document
        // first check if document has groups attached to it
        $dr = $this->getIntTableRows('*', 'document_groups', 'document=' . $document);
        if ($dr && count($dr) > 0) {
            if (empty($groups) || count($groups) == 0) {
                return false;
            }
            $perms = $this->getIntTableRows('id', 'document_groups', 'document=' . $document . ' AND member_group IN (' . implode(",", $groups) . ')');
            if ($perms && count($perms) > 0) {
                return true;
            }
        } else {
            // document doesn't have groups assigned to it
            return true;
        }
        return false;
    }
    public function checkPermissions($id = "")
    {
        //  determines user permissions for the current document
        // Returns error on fialure.
        // $id = id of document whose permissions are to be checked against the current user
        // Modified 2007-03-07 by Randy Casburn for improved overall performance
        $user     = $_SESSION['internalKey'];
        $document = ($id != "") ? $id : $this->documentIdentifier;
        $role     = $_SESSION['role'];
        if ($_SESSION['internalKey'] == "")
            return false;
        if ($role == 1)
            return true; // administrator - grant all document permissions
        if ($document == 0 && $this->config['udperms_allowroot'] == 0)
            return false;
        if ($this->config['use_udperms'] == 0 || $this->config['use_udperms'] == "" || !isset($this->config['use_udperms'])) {
            return true; // user document permissions aren't in use
        }
        // Added by Ralph 2006-07-07 to handle visitor permissions checks properly
        // Modified by Randy and nalagar in [0614]
        if ($this->config['use_uvperms'] == 0 || $this->config['use_uvperms'] == "" || !isset($this->config['use_uvperms'])) {
            return true; // visitor document permissions aren't in use
        }
        // Returns true (1) or false (0) depending on if
        // the user is/is not in any group that has permissions
        // to access this document
        $sql              = "SELECT count(" . $this->db . "member_groups.member) as Auth
    FROM " . $this->db . "document_groups,
        " . $this->db . "membergroup_access,
        " . $this->db . "member_groups
    WHERE
        " . $this->db . "document_groups.document = " . $document . " AND
        " . $this->db . "document_groups.document_group = " . $this->db . "membergroup_access.documentgroup AND
        " . $this->db . "membergroup_access.membergroup = " . $this->db . "member_groups.user_group AND
        " . $this->db . "member_groups.member = " . $user . ";";
        $rs               = $this->dbQuery($sql);
        $checkPermissions = $this->fetchRow($rs);
        // Query will only return the value of 1 or 0
        // 1 = the user is in a group that has permission to access this document
        // 0 = the user is NOT in a group that has permission to access this document
        if ($checkPermissions['Auth']) {
            return true;
        }
        // if all else fails, return false just to be safe
        return false;
    }
    public function userLogin($username, $password, $rememberme = 0, $url = "", $id = "", $alias = "", $use_captcha = 0, $captcha_code = "")
    {
        // Performs user login and permissions assignment
        // And combination of the following variables can be sent
        // Defaults to current document
        // $url   = and fully qualified URL (no validation performed)
        // $id    = an existing document ID (no validation performed)
        // $alias = any document alias (no validation performed)
        // include the crypto thing
        include_once(MANAGER_PATH . "includes/crypt.class.inc.php");
        if ($use_captcha == 1) {
            if ($_SESSION['veriword'] != $captcha_code) {
                unset($_SESSION['veriword']);
                return false;
            }
        }
        unset($_SESSION['veriword']);
        $username      = htmlspecialchars($username);
        $givenPassword = htmlspecialchars($password);
        $sql           = "SELECT " . $this->db . "manager_users.*, " . $this->db . "user_attributes.* FROM " . $this->db . "manager_users, " . $this->db . "user_attributes WHERE " . $this->db . "manager_users.username REGEXP BINARY '^" . $username . "$' and " . $this->db . "user_attributes.internalKey=" . $this->db . "manager_users.id;";
        $rs            = $this->dbQuery($sql);
        $limit         = $this->recordCount($rs);
        if ($limit == 0 || $limit > 1) {
            return false;
        }
        $row                      = $this->fetchRow($rs);
        $_SESSION['shortname']    = $username;
        $_SESSION['fullname']     = $row['fullname'];
        $_SESSION['email']        = $row['email'];
        $_SESSION['phone']        = $row['phone'];
        $_SESSION['mobilephone']  = $row['mobilephone'];
        $_SESSION['internalKey']  = $row['internalKey'];
        $_SESSION['failedlogins'] = $row['failedlogincount'];
        $_SESSION['lastlogin']    = $row['lastlogin'];
        $_SESSION['role']         = $row['role'];
        $_SESSION['nrlogins']     = $row['logincount'];
        $_SESSION['ip']           = $_SERVER['REMOTE_ADDR'];
        if ($row['failedlogincount'] >= $this->config['max_attempts'] && $row['blockeduntil'] > time()) {
            session_destroy();
            session_unset();
            return false;
        }
        if ($row['failedlogincount'] >= $this->config['max_attempts'] && $row['blockeduntil'] < time()) {
            $sql = "UPDATE " . $this->db . "user_attributes SET failedlogincount='0', blockeduntil='" . (time() - 1) . "' where internalKey=" . $row['internalKey'] . ";";
            $rs  = $this->dbQuery($sql);
        }
        if ($row['blocked'] == "1") {
            session_destroy();
            session_unset();
            return false;
        }
        if ($row['blockeduntil'] > time()) {
            session_destroy();
            session_unset();
            return false;
        }
        if ($row['password'] != md5($givenPassword)) {
            session_destroy();
            session_unset();
            return false;
        }
        $oldrow = $row;
        // changing this to reflect only given permissions
        $sql    = "SELECT * FROM " . $this->db . "user_roles where id=" . $row['role'] . ";";
        $rs     = $this->dbQuery($sql);
        $row    = $this->fetchRow($rs);
        $tmpRow = array();
        foreach ($row as $key => $val) {
            if ($val == 1) {
                $tmpRow[$key] = 1;
            }
        }
        $row                     = $tmpRow;
        $_SESSION['permissions'] = $row;
        $_SESSION['frames']      = 0;
        $_SESSION['validated']   = 1;
        // need to update last login
        $sql                     = "UPDATE " . $this->db . "user_attributes SET lastlogin='" . $oldrow['thislogin'] . "', thislogin='" . time() . "' where internalKey=" . $oldrow['internalKey'] . ";";
        $rs                      = $this->dbQuery($sql);
        if ($url != "") {
            $this->sendRedirect($url);
        }
        return true;
    }
    public function userLogout($url = "", $id = "", $alias = "")
    {
        // Use the managers logout routine to end the current session
        // And combination of the following variables can be sent
        // Defaults to index.php in the current directory
        // $url   = any fully qualified URL (no validation performed)
        // $id    = an existing document ID (no validation performed)
        // $alias = any document alias (no validation performed)
        if ($url != "") {
            $_SESSION = array();
            session_destroy();
            $this->sendRedirect($url);
        }
        if ($alias != "") {
            /*$rs = $this->getDocument($id,'alias');
            $alias = $rs['alias'];*/
            $url      = $this->makeURL('', $alias);
            $_SESSION = array();
            session_destroy();
            $this->sendRedirect($url);
        }
        if ($id != '') {
            $url      = $this->makeURL($id);
            $_SESSION = array();
            session_destroy();
            $this->sendRedirect($url);
        }
        $_SESSION = array();
        session_destroy();
        $this->sendRedirect(MANAGER_URL);
    }
    public function getCaptchaNumber($length, $alt = 'Captcha Number', $title = 'Security Code')
    {
        // returns a Captcha Number image to caller and stores value in $_SESSION['captchNumber']
        // $length = number of digits to return
        // $alt = alternate text if image cannot be displayed
        // $title = message to display for onhover event
        if ($length < 1)
            return false;
        return '<img src="./manager/includes/captchanumbers/captchaNumber.php?size=' . $length . '" alt="' . $alt . '" title="' . $title . '" />';
    }
    public function validCaptchaNumber($number)
    {
        // returns Captcha Number validation back to caller - boolean (true|false)
        // $number = number entered by user for validation (example: $_POST['captchaNumber'])
        $result = (isset($_SESSION['captchaNumber']) && $_SESSION['captchaNumber'] == $number) ? true : false;
        return $result;
    }
    public function getCaptchaCode($alt = 'CaptchaCode', $title = 'Security Code', $width = "148", $height = "80", $refresh = false)
    {
        // returns a CaptchaCode image to caller and stores value in $_SESSION['captchCode']
        // $alt = alternate text if image cannot be displayed
        // $title = message to display for onhover event
        // $width & height = desired width and height of returned image
        // $refresh = boolean [true|false] flag to turn on|off link creation [v1.0] - Ralph
        // $dummy = rand();
        $code = '<img src="manager/includes/captchaCode.php?dummy=' . rand() . '&amp;sessid=' . session_id() . '&amp;realm=IN_ETOMITE_PARSER" width="' . $width . '" height="' . $height . '" alt="' . $_lang["login_captcha_message"] . '" title="' . $title . '" />';
        if ($refresh) {
            $code = "<a href=\"\">$code</a>";
        }
        return $code;
    }
    public function validCaptchaCode($captchaCode)
    {
        // returns CaptchaCode validation back to caller - boolean (true|false)
        // $captchaCode = code entered by user for validation (example: $_POST['captchaCode'])
        $result = ($_SESSION['veriword'] == $captchaCode) ? true : false;
        return $result;
    }
    //
    // END: Permissions and Authentication related functions
    //
    public function parseChunkExtended($chunkFile = '', $chunkType = true, $chunkArr, $prefix = "{", $suffix = "}")
    {
        // returns chunk code with marker tags replaced with $key=>$value values
        // $chunkFile = the filename or string chunk to be parsed
        // $chunkType = flag for type: true=file, false=string; default of true
        // $chunkArr = a single dimensional $key=>$value array of tags and values
        // $prefix and $suffix = tag begin and end markers which can be customized when called
        // Modified 2007-09-28 by Ralph to allow $key=>array($keys=>$values) to be
        // sent which will be processed by looping through code wrapped within {tag}{/tag} pairs.
        // Example: {tag}<tr><td>{col1}</td><td>{col2}</td></tr>{/tag}
        if (!is_array($chunkArr) || count($chunkArr) < 1 || empty($chunkFile)) {
            return false;
        }
        if ($chunkType) {
            // file so grab it
            if (!file_exists($chunkFile)) {
                return false;
            }
            $chunk = file_get_contents($chunkFile);
        } else {
            // string
            $chunk = $chunkFile;
        }
        foreach ($chunkArr as $key => $value) {
            if (!is_array($value)) {
                $chunk = str_replace($prefix . $key . $suffix, $value, $chunk);
            } else {
                if (preg_match("|" . $prefix . $key . $suffix . "(.+)" . $prefix . '/' . $key . $suffix . "|s", $chunk, $match) && count($value) > 0) {
                    $loopData = '';
                    foreach ($value as $row) {
                        $loopTemp = $match['1'];
                        foreach ($row as $loopKey => $loopValue) {
                            $loopTemp = str_replace($prefix . $loopKey . $suffix, $loopValue, $loopTemp);
                        }
                        $loopData .= $loopTemp;
                    }
                    $chunk = str_replace($match['0'], $loopData, $chunk);
                }
            }
        }
        return $chunk;
    }
    // set the class head and js and css code to the variable
    public function setJSCSS($code)
    {
        if (!empty($code)) {
            $this->headJSCSS .= $code . "\n";
        }
    }
    // add the js and css to the document
    public function addJSCSS($content)
    {
        if (!empty($this->headJSCSS)) {
            if (strpos($content, "</head>") > 0) {
                $content = str_replace("</head>", $this->headJSCSS . "</head>", $content);
            } elseif (strpos($content, "</HEAD>") > 0) {
                $content = str_replace("</HEAD>", $this->headJSCSS . "</HEAD>", $content);
            } else {
                $content .= $this->headJSCSS;
            }
        }
        $this->headJSCSS = '';
        return $content;
    }
    // set the key value for meta example $etomite->setMeta('title','This is my Title Text');
    public function setMeta($key, $val)
    {
        $this->_meta[$key] = $val;
    }
    public function addMeta($content)
    {
        if (count($this->_meta) > 0 && !empty($this->_meta)) {
            $meta = '';
            foreach ($this->_meta as $key => $val) {
                if ($key == 'title') {
                    $content = preg_replace("/(<title>.+?)+(<\/title>)/i", "", $content);
                    if (!empty($this->config['site_name'])) {
                        $val = $this->config['site_name'] . " - " . $val;
                    }
                    $meta .= "<title>" . htmlspecialchars($val) . "</title>\n";
                } else {
                    $content = preg_replace("/<meta name=\"" . $key . "\"[^>]*>/i", "", $content);
                    $meta .= "<meta name='" . $key . "' content='" . htmlspecialchars($val) . "' />\n";
                }
            }
            // add the meta to the head
            if (!empty($meta)) {
                if (strpos($content, "<head>") > 0) {
                    $content = str_replace("<head>", "<head>\n" . $meta, $content);
                } elseif (strpos($content, "<HEAD>") > 0) {
                    $content = str_replace("<HEAD>", "<HEAD>\n" . $meta, $content);
                } else {
                    $content .= $meta;
                }
            }
            $this->_meta = '';
            return $content;
        } // end if for meta count
        return $content;
    }
    public function langToJS()
    {
        $output = "<script language='javascript'>";
        $l      = array();
        foreach ($this->_lang as $key => $val) {
            if (is_array($val)) {
                $l[$key] = $val;
            } else {
                $l[$key] = urlencode($val);
                $l[$key] = htmlentities($val, ENT_QUOTES);
                $l[$key] = nl2br($val);
                $l[$key] = str_replace("\\n", "<br />", $l[$key]);
            }
        }
        $output .= "var eLang = " . json_encode($l) . ";"; //, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE
        $output .= "</script>";
        return $output;
    }
    public function setDefaultBreadCrumb()
    {
        $sep     = $this->bcSep;
        $ptarr   = array();
        $pid     = $this->documentObject['parent'];
        $ptarr[] = "<a href='[~" . $this->documentObject['id'] . "~]'>" . $this->documentObject['pagetitle'] . "</a>";
        while ($parent = $this->getParent($pid)) {
            $ptarr[] = "<a href='[~" . $parent['id'] . "~]'>" . $parent['pagetitle'] . "</a>";
            $pid     = $parent['parent'];
        }
        // need to add the sitestart link
        $sitestart = $this->getDocument($this->config['site_start']);
        if (!empty($sitestart)) {
            $ptarr[] = "<a href='/'>" . $sitestart['pagetitle'] . "</a>";
        }
        $ptarr               = array_reverse($ptarr);
        $this->breadCrumbs[] = join($ptarr, $sep);
    }
    // add to the system breadcrumb
    public function setBreadCrumb($crumb)
    {
        if (!empty($crumb)) {
            $this->breadCrumbs[] = $crumb;
        }
    }
    // reset the system breadcrumb to empty
    public function resetBreadCrumb()
    {
        $this->breadCrumbs = array();
    }
    public function setBreadCrumbSep($sep)
    {
        $this->bcSep = $sep;
    }
    // add breadcrumb to the document content
    public function addBreadCrumb($content)
    {
        if (!empty($this->breadCrumbs) && count($this->breadCrumbs) > 0) {
            $crumbs  = implode($this->bcSep, $this->breadCrumbs);
            $content = str_replace("[+BreadCrumb+]", $crumbs, $content);
        } else {
            $content = str_replace("[+BreadCrumb+]", "", $content);
        }
        $content = $this->rewriteUrls($content);
        return $content;
    }
    public function get_time_difference($start, $end)
    {
        $uts['start'] = strtotime($start);
        $uts['end']   = strtotime($end);
        if ($uts['start'] !== -1 && $uts['end'] !== -1) {
            if ($uts['end'] >= $uts['start']) {
                $diff = $uts['end'] - $uts['start'];
                if ($days = intval((floor($diff / 86400))))
                    $diff = $diff % 86400;
                if ($hours = intval((floor($diff / 3600))))
                    $diff = $diff % 3600;
                if ($minutes = intval((floor($diff / 60))))
                    $diff = $diff % 60;
                $diff = intval($diff);
                return (array(
                    'days' => $days,
                    'hours' => $hours,
                    'minutes' => $minutes,
                    'seconds' => $diff
                ));
            } else {
                trigger_error("Ending date/time is earlier than the start date/time", E_USER_WARNING);
            }
        } else {
            trigger_error("Invalid date/time data detected", E_USER_WARNING);
        }
        return (false);
    }
    // returns an array with the multi dimensional arra of parent -> children
    public function generateDocTree($id = 0, $orderby = "menuindex", $sortDir = "ASC")
    {
        $parents = $this->getAllChildren($docId = $id, $orderby, $sortDir, $fields = 'id, type, pagetitle, alias, published, parent, isfolder, menuindex, deleted, showinmenu', '', true);
        $tree    = array();
        if ($parents && count($parents) > 0) {
            /*if ($id == 0) {
                $root[0]['title']    = $this->config['site_name'];
				$root[0]['label']	 = $this->config['site_name'];
				$root[0]['id']		 = '0';
                $root[0]['key']      = 'id_0';
                $root[0]['tooltip']  = "Root Site";
                $root[0]['is_folder'] = 1;
                $root[0]['expand']   = true;
                $root[0]['icon']     = 'globe.gif';
            }*/
            for ($i = 0; $i < count($parents); $i++) {
                $p                      = $parents[$i];
		$p['pagetitle'] = stripslashes($p['pagetitle']);
		$p['pagetitle'] = htmlentities($p['pagetitle']);
                $tree[$i]['title']      = $p['pagetitle'] . " (" . $p['id'] . ")";
				$tree[$i]['label']		= $p['pagetitle'] . " (" . $p['id'] . ")";
				$tree[$i]['id']			= $p['id'];
                //$tree[$i]['key']        = "id_" . $p['id'];
                $tree[$i]['docUrl']     = $this->makeUrl($p['id']);
                $tree[$i]['tooltip']    = "Alias: " . $p['alias'] . " - Menu index: " . $p['menuindex'];
                $tree[$i]['showinmenu'] = $p['showinmenu'];
                $tree[$i]['published']  = $p['published'];
                $tree[$i]['deleted']    = $p['deleted'];
                $tree[$i]['weblink']    = ($p['type'] == 'reference') ? true : false;
                if ($p['isfolder'] == 1) {
                    $tree[$i]['is_folder'] = true;
                }
				/*
                if ($p['type'] == 'reference') {
                    if ($p['isfolder'] == 1) {
                        $tree[$i]['icon'] = 'weblinkfolder.gif';
                        if ($p['deleted'] == 1) {
                            $tree[$i]['icon'] = 'deletedweblinkfolder.gif';
                        }
                        if ($p['published'] == 0) {
                            $tree[$i]['icon'] = 'unpublishedweblinkfolder.gif';
                        }
                    } else {
                        $tree[$i]['icon'] = 'weblink.gif';
                        if ($p['deleted'] == 1) {
                            $tree[$i]['icon'] = 'deletedweblink.gif';
                        }
                        if ($p['published'] == 0) {
                            $tree[$i]['icon'] = 'unpublishedweblink.gif';
                        }
                    }
                } else {
                    if ($p['isfolder'] == 1) {
                        if ($p['deleted'] == 1) {
                            $tree[$i]['icon'] = 'deletedfolder.gif';
                        }
                        if ($p['published'] == 0) {
                            $tree[$i]['icon'] = 'unpublishedfolder.gif';
                        }
                    } else {
                        if ($p['deleted'] == 1) {
                            $tree[$i]['icon'] = 'deletedpage.gif';
                        }
                        if ($p['published'] == 0) {
                            $tree[$i]['icon'] = 'unpublishedpage.gif';
                        }
                    }
                }*/
                if ($children = $this->generateDocTree($p['id'])) {
                    if (count($children) > 0) {
                        $tree[$i]['children'] = $children;
                    }
                }
            }
            /*if ($id == 0) {
                $root[0]['children'] = $tree;
                return $root;
            } else {*/
                return $tree;
            //}
        }
        return false;
    }
    /*
     * Send Message to user from the messages system
     */
    public function sendMessageToUser($to = array(), $message)
    {
        if (empty($to) || !is_array($to) || empty($message)) {
            return false;
        }
        $curUser = $this->getUser($_SESSION['internalKey']);
        require_once(MANAGER_PATH . "/lib/phpmailer/class.phpmailer.php");
        $mail = new PHPMailer();
        $mail->IsMail(); // telling the class to use PHP's Mail()
        $mail->AddReplyTo('nobody@yourdomain.com', 'Nobody');
        $mail->From     = $this->config['emailsender'];
        $mail->FromName = $this->config['site_name'];
        foreach ($to as $r) {
            $mail->AddAddress($r['email'], $r['name']);
        }
        $mail->Subject  = "You Have a Message!";
        $mail->AltBody  = html_entity_decode($message);
        $mail->WordWrap = 80;
        $mail->MsgHTML($message);
        try {
            if (!$mail->Send()) {
                $error = "Unable to send to: " . print_r($to, 1) . "<br />";
                throw new phpmailerAppException($error);
            }
            return true;
        }
        catch (phpmailerAppException $e) {
            $errorMsg[] = $e->errorMessage();
        }
        return false;
    }
    public function evalModules($content)
    {
        /*
         * $matches contains the module info.
         * $matches[2][0] contains the module info we need to pass
         */
        $matchCount = preg_match_all("/(\[module\])(.*?)(\[\/module\])/", $content, $matches);
        if ($matchCount)
            $this->parseAgain = true;
        if (count($matches) > 0) {
            foreach ($matches[2] as $match) {
                $mod    = $match;
                $params = '';
                $spos   = strpos($mod, '?', 0);
                if ($spos !== false) {
                    $params = substr($mod, $spos, strlen($mod));
                    $mod    = str_replace($params, '', $mod);
                }
                $parts  = explode("/", $mod);
                $module = (isset($parts[0]) && !empty($parts[0])) ? $parts[0] : '';
                $action = (isset($parts[1]) && !empty($parts[1])) ? $parts[1] : '';
                if (!empty($module)) {
                    // run the module here..
                    $moduleOutput = $this->runModule($module, $action, $params);
                    // now replace module
                    $content      = str_replace("[module]" . $match . "[/module]", $moduleOutput, $content);
                }
            }
        }
        return $content;
    }
    /*
     * $module is the module name
     * $action is the module function you would like to access
     * $params is a key=>val pair (example: ?id=5&name=Jeff)
     *
     * to use the params, it is suggested that you pass the params to the class when
     * initializing it. (example: $module = new Module($moduleParams);) and then
     * set them to a variable such as $classParams
     */
    public function runModule($module, $action = '', $params = '')
    {
        $etomite      = $this;
        $moduleOutput = '';
        ob_start();
        if (!isset($action) || empty($action) && (isset($_REQUEST['action']) && !empty($_REQUEST['action']))) {
            $action = $_REQUEST['action'];
        }
        if (file_exists(absolute_base_path . 'modules/' . $module . "/" . $module . ".php")) {
            $tempParams   = str_replace("?", "", $params);
            $splitter     = strpos($tempParams, "&amp;") > 0 ? "&amp;" : "&";
            $tempParams   = explode($splitter, $tempParams);
            $moduleParams = array();
            foreach ($tempParams as $p) {
                $pPart                   = explode("=", $p);
                $moduleParams[$pPart[0]] = isset($pPart[1]) && !empty($pPart[1]) ? $pPart[1] : '';
            }
            require_once(absolute_base_path . 'modules/' . $module . "/" . $module . ".php");
            $action             = (isset($action) && !empty($action)) ? $action : 'index'; // defaults to adminView
            $this->moduleAction = $action;
            $modClass           = new $module($moduleParams, $etomite);
            $modClass->$action();
            $moduleOutput = ob_get_clean();
        }
        return $moduleOutput;
    }
    public function buildAdminModuleMenu()
    {
        // grab modules list
        $modules = $this->getIntTableRows('*', 'modules', 'active=1 AND admin_menu=1', 'name', 'ASC');
        $output  = '';
        if (count($modules) > 0) {
            $output = '<ul class="treeview-menu">';
            foreach ($modules as $module) {
                $output .= "<li><a onclick='this.blur(); Etomite.manageModule(\"" . $module['internal_key'] . "\");' href='javascript:;'><i class='fa fa-angle-double-right'></i> " . $module['name'] . "</a></li>";
            }
            $output .= "</ul>";
        }
        return $output;
    }
    public function checkManagerLogin()
    {
        if (!$this->userLoggedIn()) {
            echo "<script>window.top.location.href='" . MANAGER_URL . "';</script>";
            exit(0);
        }
    }
    public function formatTVOutput($value, $output = 'text', $opts)
    {
        if (empty($value)) {
            return "";
        }
        switch ($output) {
            case 'text':
            default:
                return $value;
                break;
            case 'image':
                return "<img src='" . $value . "' " . $opts . " />";
                break;
            case 'link':
                return "<a href='" . $value . "' " . $opts . ">" . $value . "</a>";
                break;
            case 'date':
                if (!empty($opts)) {
                    $format = $opts;
                }
                return !empty($value) ? date($format, $value) : ''; // to do later
                break;
        }
        return "";
    }
    /* Run system events that are put in different areas of code to run modules or parts of modules
     * Current system events: OnBeforeManagerHeadEnd, OnAfterDocumentFormLoad
     * 
     */
    public function runSystemEvent($event_name = false)
    {
        if ($event_name) {
            $events = $this->getIntTableRows("*", "system_events", "event_name='" . $event_name . "'");
            if (count($events) > 0) {
                foreach ($events as $e) {
                    echo $this->runModule($e['module_name'], $e['method_name']);
                }
            }
        }
        return;
    }
    /***************************************************************************************/
    /* END: Etomite API functions
    /***************************************************************************************/
    // End of etomite class.
}
class phpmailerAppException extends Exception
{
    public function errorMessage()
    {
        $errorMsg = '<strong>' . $this->getMessage() . "</strong><br />";
        return $errorMsg;
    }
}
?>
