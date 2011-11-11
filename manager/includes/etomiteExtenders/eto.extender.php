<?

/*
Version: 1.0
Author: John Carlson
Description:
This class extends the etomite class to allow for extra url functions
instead of just using the normal alias style: page.html
it allows for extra style like page/category/cars

added a new variable to the system config for zend_urls = 1 to use the new style of urls


*/
class etomiteExtender extends etomite {

	public $_request = array(); // variable to hold the request/post/get from a url format of /varname/value/varname2/value2
	public $_vars = array(); // variable use to pass information  such as an ID that you want to grab in another snippet
	
	// used to make changes to the head or sections of the html
	public $headJSCSS = ''; // variable to add js and css to the head of the document.
	public $_meta = ''; // variable to add meta to the head
	
	public $breadCrumbs = array();
	public $bcSep = " &raquo; "; // default breadCrumb separator
	
	/*public $_dbSelect;
	public $_dbFromTable; // main select table
	public $_dbFromFields; // main select table fields
	public $_dbJoin;
	public $_dbJoinFields;
	public $_dbWhere; // query where statement
	public $_dbOrder; // select order
	public $_dbLimit;	// query limit*/
		
	// parses the url to pull out and set the request data from /page/this/style url
	function parseUrl($q){

		function checkNum($num){
		  return ($num%2) ? TRUE : FALSE;
		}
		//print_r($q); exit;
		if(empty($q)) return;
		$q = trim($q,"/"); // remove leading and ending slashes
		$parts = explode("/",$q); // put the url into a split array
		
		if(checkNum(count($parts)) === TRUE){ array_push($parts,""); }
		$keys = array();
		$values = array();
		foreach($parts as $p){
			if($p=='0'){ $p = strval($p); }
			((++$i%2==0)?array_push($values,$p):array_push($keys,$p));
		}
		
		return array_combine($keys,$values);
	}
	
	function getDocumentIdentifier($method) {
		// function to test the query and find the retrieval method
		switch($method) {
		  case "alias" :
		  	if(strpos($_REQUEST['rw'], "/")>0) {
			  $qOrig = substr($_REQUEST['rw'], 0, strpos($_REQUEST['rw'], "/")); // old line
			}else{ $qOrig = $_REQUEST['rw']; }
			//return preg_replace("/[^\w\.@-]/", "", htmlspecialchars($_REQUEST['rw']));
			return preg_replace("/[^\w\.@-]/", "", htmlspecialchars($qOrig));
			break;
		  case "id" :
			return is_numeric($_REQUEST['id']) ? $_REQUEST['id'] : "";
			break;
		  case "none" :
			return $this->config['site_start'];
			break;
		  default :
			return $this->config['site_start'];
		}
	}
	
	// extend this function
	function cleanDocumentIdentifier($qOrig) {
		
		if($this->config['zend_urls']==1){ // sets the zend style url to a request of $key=>$val
			
			$this->_request = $this->parseUrl(substr($_REQUEST['rw'], strpos($_REQUEST['rw'], "/")));
			
			$this->_request = array_merge($this->_request,$_POST);
			unset($this->_request['rw']);
		}else{ // set the _get var to the _request var
			$this->_request = $_GET;
			unset($this->_request['rw']); // unset the q variable that is sent by the .htaccess file
		}
		
		$q = str_replace($this->config['friendly_url_prefix'], "", $qOrig);
		$q = str_replace($this->config['friendly_url_suffix'], "", $q);
		// we got an ID returned unless the error_page alias is "404"
		if(is_numeric($q) && ($q != $this->aliases[$this->config['error_page']])) {
		  $this->documentMethod = 'id';
		  return $q;
		// we didn't get an ID back, so instead we assume it's an alias
		} else {
		  $this->documentMethod = 'alias';
		  return $q;
		}
	}
	
function makeUrl($id, $alias='', $args='') {
	// Last Modified: 2010-03-17 by John Carlson to add the new zend framework style of urls like this /page/var1/value1/var2/value2
	// Fixed the code to accept a $key=>$value array for the arguments
	// Modified by mikef
	// Last Modified: 2006-04-08 by Ralph Dahlgren
	// returns a properly formatted URL as of 0.6.1 Final
	// $id is a valid document id and is optional when sending an alias
	// $alias can now be sent without $id but may cause failures if the alias doesn't exist
	// $args is a URL compliant text string of $_GET key=value pairs
	// Examples: makeURL(45,'','?cms=Etomite') OR makeURL('','my_alias','?cms=Etomite')
	// ToDo: add conditional code to create $args from a $key=>$value array
	
	// USAGE 
	// assuming our page id is 4 with an alias of test and our domain is domain.com
	// zend_urls = 1 // using zend urls instead of normal urls
	// $ar = array('var1'=>'value1','var2'=>'value2');
	// $url = $etomite->makeUrl('4','',$ar); // returns http://domain.com/test/var1/value1/var2/value2
	//
	// OR using regular string style of parameters passed with the zend urls will convert it to the normal url
	//
	// $params = "?var1=value1&var2=value2";
	// $url = $etomite->makeUrl('4','',$params); // returns http://domain.com/test.html?var1=value1&var2=value2
	// 
	// OR using regular style urls with the zend urls off or zend_urls=0
	//
	// $params = "?var1=value1&var2=value2";
	// $url = $etomite->makeUrl('4','',$params); // returns http://domain.com/test.html?var1=value1&var2=value2
	//
	// and also will work the same with a $key=>$value style of parameters
	// $ar = array('var1'=>'value1','var2'=>'value2');
	// $url = $etomite->makeUrl('4','',$ar); // returns http://domain.com/test.html?var1=value1&var2=value2
	
	
	// make sure $id data type is not string
	if(!is_numeric($id) && $id!="") {
	  $this->messageQuit("`$id` is not numeric and may not be passed to makeUrl()");
	}
	// assign a shorter base URL variable
	$baseURL=$this->config['www_base_path'];
	if($this->config['zend_urls']==1 && !empty($alias) && isset($this->documentListing[$alias])){
		$url = $baseURL.$alias;
	}elseif($this->config['zend_urls']==1 && $this->aliases[$id]!=""){
		if($id!=$this->config['site_start']){
			$url = $baseURL.$this->aliases[$id];
		}else{
			$url = $baseURL;
		}
	}
	// if $alias was sent in the function call and the alias exists, use it
	elseif($this->config['friendly_alias_urls']==1 && isset($this->documentListing[$alias])) {
		$url = $baseURL.$this->config['friendly_url_prefix'].$alias.$this->config['friendly_url_suffix'];
	}
	// $alias wasn't sent or doesn't exist so try to get the documents alias based on id if it exists
	elseif($this->config['friendly_alias_urls']==1 && $this->aliases[$id]!="") {
		if($id!=$this->config['site_start']){
			$url = $baseURL.$this->config['friendly_url_prefix'].$this->aliases[$id].$this->config['friendly_url_suffix'];
		}else{
			$url = $baseURL;
		}
	}
	// only friendly URL's are enabled or previous alias attempts failed
	elseif($this->config['friendly_urls']==1) {
	  $url = $baseURL.$this->config['friendly_url_prefix'].$id.$this->config['friendly_url_suffix'];
	}
	// for some reason nothing else has workd so revert to the standard URL method
	else {
	  $url = $baseURL."index.php?id=$id";
	}
	
	if($this->config['zend_urls']==1 && is_array($args)){
		$params = array();
		foreach($args as $key=>$val){
			if(!empty($val)){
				array_push($params,$key);
				array_push($params,$val);
			}
		}
		
		if(!empty($params)){ $args = "/".implode("/",$params); }/*else{ $args = $this->config['friendly_url_suffix']; }*/
		else{ $args = ''; }
	}elseif($this->config['zend_urls']==1){
		//$url .= $this->config['friendly_url_suffix'];
		if(strlen($args)&&strpos($url, "?")){ 
			$url .= $this->config['friendly_url_suffix'];
			$args="&amp;".substr($args,1);
		}
	}else{
		// make sure only the first argument parameter is preceded by a "?"
		if(is_array($args)){ // fix the arguments if they are in an array form
			$argsArray = array();
			foreach($args as $key=>$val){
				$argsArray[] = $key."=".$val;
			}
			$args = "?".implode("&amp;",$argsArray);
		}
		if(strlen($args)&&strpos($url, "?")) $args="&amp;".substr($args,1);
	}
	return $url.$args;
}

// frontend user functions

function getWebUser($internalKey){
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
	$tbl = $this->db."web_users";
	$sql = "SELECT * FROM $tbl WHERE $tbl.id=".$internalKey;
	$result = $this->dbQuery($sql);
	$limit = $this->recordCount($result);
	if($limit < 1) {
	  return false;
	} else {
	  $user = $this->fetchRow($result);
	  unset($user['password'],$user['hash']);
	  return $user;
	}
}


function webUserLoggedIn() {
	// returns an array of user details if logged in else returns false
	// array components returned are self-explanatory
	$userdetails = array();
	if(isset($_SESSION['validated'])) {
	  $userdetails['loggedIn']=true;
	  $userdetails['id']=strip_tags($_SESSION['internalKey']);
	  $userdetails['username']=strip_tags($_SESSION['shortname']);
	  $userdetails['role']=(int)$_SESSION['role'];
	  // need to add more to this
	  return $userdetails;
	} else {
	  return false;
	}
}

function webUserLogin($username,$password,$rememberme=0,$url="",$id="",$alias="",$use_captcha=0,$captcha_code="") {
	// Performs user login and permissions assignment
	// And combination of the following variables can be sent
	// Defaults to current document
	// $url   = and fully qualified URL (no validation performed)
	// $id    = an existing document ID (no validation performed)
	// $alias = any document alias (no validation performed)
	
	// include the crypto thing
	include_once("./manager/includes/crypt.class.inc.php");
	
	// include_once the error handler
	include_once("./manager/includes/error.class.inc.php");
	$e = new errorHandler;
	
	if($use_captcha==1) {
	  if($_SESSION['veriword']!=$captcha_code) {
		unset($_SESSION['veriword']);
		$e->setError(905);
		$e->dumpError();
		$newloginerror = 1;
	  }
	}
	unset($_SESSION['veriword']);
	
	$username = htmlspecialchars($username);
	$givenPassword = htmlspecialchars($password);
	
	$sql = "SELECT * FROM ".$this->db."web_users WHERE ".$this->db."web_users.username REGEXP BINARY '^".$username."$'";
	$rs = $this->dbQuery($sql);
	$limit = $this->recordCount($rs);
	
	if($limit==0 || $limit>1)
	{
		$e->setError(900);
		$e->dumpError();
	}
	
	$row = $this->fetchRow($rs);
	
	$_SESSION['shortname']         = $username;
	$_SESSION['fullname']          = $row['firstName']." ".$row['lastName'];
	$_SESSION['email']             = $row['email'];
	$_SESSION['phone']             = $row['phone'];
	$_SESSION['phone2']       = $row['phone2'];
	$_SESSION['internalKey']       = $row['id'];
	//$_SESSION['failedlogins']      = $row['failedlogincount'];
	$_SESSION['lastlogin']         = $row['lastlogin'];
	$_SESSION['role']              = $row['role'];
	//$_SESSION['nrlogins']          = $row['logincount'];
	
	// fix this later
	/*
	if($row['failedlogincount']>=$this->config['max_attempts'] && $row['blockeduntil']>time())
	{
		session_destroy();
		session_unset();
		$e->setError(902);
		$e->dumpError();
	}
	
	if($row['failedlogincount']>=$this->config['max_attempts'] && $row['blockeduntil']<time())
	{
	  $sql = "UPDATE ".$this->db."user_attributes SET failedlogincount='0', blockeduntil='".(time()-1)."' where internalKey=".$row['internalKey'].";";
	  $rs = $this->dbQuery($sql);
	}
	
	if($row['blocked']=="1")
	{
	  session_destroy();
	  session_unset();
	  $e->setError(903);
	  $e->dumpError();
	}
	
	if($row['blockeduntil']>time())
	{
	  session_destroy();
	  session_unset();
	  $e->setError(904);
	  $e->dumpError();
	}
	*/
	
	if($row['password'] != md5($givenPassword))
	{
		session_destroy();
		session_unset();
		$e->setError(901);
		$newloginerror = 1;
		$e->dumpError();
	}
	
	$sql="SELECT * FROM ".$this->db."web_user_roles where id=".$row['role'].";";
	$rs = $this->dbQuery($sql);
	$row = $this->fetchRow($rs);
	//$_SESSION['permissions'] = $row;
	//$_SESSION['frames'] = 0;
	$_SESSION['validated'] = 1;
	
	// set last login
	$sql = "UPDATE ".$this->db."web_users SET lastlogin=NOW() WHERE id=".$_SESSION['internalKey'];
	$rs = $this->dbQuery($sql);
	
	if($url=="") {
	  $url = $this->makeURL($id,$alias);
	}
	$this->sendRedirect($url);
}
	
function webUserLogout($url="",$id="",$alias="") {
	// Use the managers logout routine to end the current session
	// And combination of the following variables can be sent
	// Defaults to index.php in the current directory
	// $url   = any fully qualified URL (no validation performed)
	// $id    = an existing document ID (no validation performed)
	// $alias = any document alias (no validation performed)
	if($url == "") {
	  if($alias == "") {
		$id = ($id != "") ? $id : $this->documentIdentifier;
		$rs = $this->getDocument($id,'alias');
		$alias = $rs['alias'];
	  } else {
		$id = 0;
	  }
	  $url = $this->makeURL($id,$alias);
	}
	if($url != "") {
	  include_once("manager/processors/logout.processor.php");
	}
}

function parseChunkExtended($chunkFile='',$chunkType=true, $chunkArr, $prefix="{", $suffix="}") {
// returns chunk code with marker tags replaced with $key=>$value values
// $chunkFile = the filename or string chunk to be parsed
// $chunkType = flag for type: true=file, false=string; default of true
// $chunkArr = a single dimensional $key=>$value array of tags and values
// $prefix and $suffix = tag begin and end markers which can be customized when called
// Modified 2007-09-28 by Ralph to allow $key=>array($keys=>$values) to be
// sent which will be processed by looping through code wrapped within {tag}{/tag} pairs.
// Example: {tag}<tr><td>{col1}</td><td>{col2}</td></tr>{/tag}
if(!is_array($chunkArr) || count($chunkArr) < 1 || empty($chunkFile)) {
  return false;
}
if($chunkType){
	// file so grab it
	if(!file_exists($chunkFile)){ return false; }
	$chunk = file_get_contents($chunkFile);
	
}else{
	// string
	$chunk = $chunkFile;
}
foreach($chunkArr as $key => $value)
{
  if(!is_array($value))
  {
	$chunk = str_replace($prefix.$key.$suffix, $value, $chunk);
  }
  else
  {
	if(preg_match("|".$prefix.$key.$suffix."(.+)".$prefix.'/'.$key.$suffix."|s", $chunk, $match)
	&& count($value) > 0)
	{
	  $loopData = '';
	  foreach($value as $row)
	  {
		$loopTemp = $match['1'];
		foreach($row as $loopKey => $loopValue)
		{
		  $loopTemp = str_replace($prefix.$loopKey.$suffix, $loopValue, $loopTemp);
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
function setJSCSS($code){
	if(!empty($code)){
		$this->headJSCSS .= $code."\n";
	}
}

// add the js and css to the document
function addJSCSS($content) {
	if(!empty($this->headJSCSS)){
		if(strpos($content, "</head>")>0) {
		  $content = str_replace("</head>", $this->headJSCSS."</head>", $content);
		} elseif(strpos($content, "</HEAD>")>0) {
		  $content = str_replace("</HEAD>", $this->headJSCSS."</HEAD>", $content);
		} else {
		  $content .= $this->headJSCSS;
		}
	}
	$this->headJSCSS = '';
	return $content;
}

// set the key value for meta example $etomite->setMeta('title','This is my Title Text');
function setMeta($key,$val){
	$this->_meta[$key] = $val;
}

function addMeta($content){
	if(count($this->_meta)>0 && !empty($this->_meta)){
		$meta = '';
		foreach($this->_meta as $key=>$val){
			if($key == 'title'){
				$content = eregi_replace("<title[^>]*>.*</title>","",$content);
				if(!empty($this->config['site_name'])){
					$val = $this->config['site_name']." - ".$val;
				}
				$meta .= "<title>".htmlspecialchars($val)."</title>\n";
			}else{
				$content = eregi_replace("<meta name=\"".$key."\"[^>]*>","",$content);
				$meta .= "<meta name='".$key."' content='".htmlspecialchars($val)."' />\n";
			}
		}
		// add the meta to the head
		if(!empty($meta)){
			if(strpos($content, "<head>")>0) {
			  $content = str_replace("<head>", "<head>\n".$meta,  $content);
			} elseif(strpos($content, "<HEAD>")>0) {
			  $content = str_replace("<HEAD>", "<HEAD>\n".$meta,  $content);
			} else {
			  $content .= $meta;
			}
		}
		$this->_meta = '';
		return $content;
	}// end if for meta count
	return $content;
}


function setDefaultBreadCrumb(){
	$sep = $this->bcSep;
	$ptarr = array();
	$pid = $this->documentObject['parent'];
	$ptarr[] = "<a href='[~".$this->documentObject['id']."~]'>".$this->documentObject['pagetitle']."</a>";
	
	while ($parent=$this->getParent($pid)) {
		$ptarr[] = "<a href='[~".$parent['id']."~]'>".$parent['pagetitle']."</a>";
		$pid = $parent['parent'];
	}
	
	// need to add the sitestart link
	$sitestart = $this->getDocument($this->config['site_start']);
	if(!empty($sitestart)){
		$ptarr[] = "<a href='/'>".$sitestart['pagetitle']."</a>";
	}
	
	$ptarr = array_reverse($ptarr);
	$this->breadCrumbs[] = join($ptarr, $sep);
}

// add to the system breadcrumb
function setBreadCrumb($crumb){
	if(!empty($crumb)){
	$this->breadCrumbs[] = $crumb;
	}
}

// reset the system breadcrumb to empty
function resetBreadCrumb(){
	$this->breadCrumbs = array();
}

function setBreadCrumbSep($sep){
	$this->bcSep = $sep;
}

// add breadcrumb to the document content
function addBreadCrumb($content){
	if(!empty($this->breadCrumbs) && count($this->breadCrumbs)>0){
		$crumbs = implode($this->bcSep,$this->breadCrumbs);
		$content = str_replace("[+BreadCrumb+]",$crumbs,$content);
	}else{
		$content = str_replace("[+BreadCrumb+]","",$content);
	}
	$content = $this->rewriteUrls($content);
	return $content;
}

// extended output function

  function outputContent() {
  	$this->setDefaultBreadCrumb();
    $output = $this->documentContent;

    // check for non-cached snippet output
    if(strpos($output, '[!')>-1) {
      $output = str_replace('[!', '[[', $output);
      $output = str_replace('!]', ']]', $output);

      $this->nonCachedSnippetParsePasses = empty($this->nonCachedSnippetParsePasses) ? 1 : $this->nonCachedSnippetParsePasses;
      for($i=0; $i<$this->nonCachedSnippetParsePasses; $i++) {
        if($this->config['dumpSnippets']==1) {
          echo "<fieldset style='text-align: left'><legend>NONCACHED PARSE PASS ".($i+1)."</legend>The following snipppets (if any) were parsed during this pass.<div style='width:100%' align='center'>";
        }
        // replace settings referenced in document
        $output = $this->mergeSettingsContent($output);
        // replace HTMLSnippets in document
        $output = $this->mergeHTMLSnippetsContent($output);
        // find and merge snippets
        $output = $this->evalSnippets($output);
        if($this->config['dumpSnippets']==1) {
          echo "</div></fieldset><br />";
        }
      }
    }

    $output = $this->rewriteUrls($output);

    $totalTime = ($this->getMicroTime() - $this->tstart);
    $queryTime = $this->queryTime;
    $phpTime = $totalTime-$queryTime;

    $queryTime = sprintf("%2.4f s", $queryTime);
    $totalTime = sprintf("%2.4f s", $totalTime);
    $phpTime = sprintf("%2.4f s", $phpTime);
    $source = $this->documentGenerated==1 ? "database" : "cache";
    $queries = isset($this->executedQueries) ? $this->executedQueries : 0 ;

    // send out content-type headers
    $type = !empty($this->contentTypes[$this->documentIdentifier]) && !$this->aborting
    ? $this->contentTypes[$this->documentIdentifier]
    : "text/html";

    header('Content-Type: '.$type.'; charset='.$this->config['etomite_charset']);

    if(!$this->checkSiteStatus() && ($this->documentIdentifier != $this->offline_page))
    {
      header("HTTP/1.0 307 Temporary Redirect");
    }

    if(($this->documentIdentifier == $this->config['error_page']) && ($this->config['error_page'] !=  $this->config['site_start']))
    {
      header("HTTP/1.0 404 Not Found");
    }


    // Check to see whether or not addNotice should be called
    if($this->config['useNotice'] || !isset($this->config['useNotice'])){
      $documentOutput = $this->addNotice($output, $type);
    } else {
      $documentOutput = $output;
    }
	
	// added by John Carlson
	$documentOutput = $this->addPageClass($documentOutput,$type);
	$documentOutput = $this->addMeta($documentOutput);
	$documentOutput = $this->addJSCSS($documentOutput);
	$documentOutput = $this->addBreadCrumb($documentOutput);
	// end added


    if($this->config['dumpSQL']) {
      $documentOutput .= $this->queryCode;
    }
    $documentOutput = str_replace("[^q^]", $queries, $documentOutput);
    $documentOutput = str_replace("[^qt^]", $queryTime, $documentOutput);
    $documentOutput = str_replace("[^p^]", $phpTime, $documentOutput);
    $documentOutput = str_replace("[^t^]", $totalTime, $documentOutput);
    $documentOutput = str_replace("[^s^]", $source, $documentOutput);

    // Check to see if document content contains PHP tags.
    // PHP tag support contributed by SniperX
    if( preg_match("/(<\?php|<\?)(.*?)\?>/", $documentOutput) && $type == "text/html" && $this->config['allow_embedded_php'] )
    {
      $documentOutput = '?'.'>' . $documentOutput . '<'.'?php ';
      // Parse the PHP tags.
      eval($documentOutput);
    }
    else
    {
      // No PHP tags so just echo out the content.
      echo $documentOutput;
    }
  }
  
	function get_time_difference( $start, $end )
	{
		$uts['start']      =    strtotime( $start );
		$uts['end']        =    strtotime( $end );
		if( $uts['start']!==-1 && $uts['end']!==-1 )
		{
			if( $uts['end'] >= $uts['start'] )
			{
				$diff    =    $uts['end'] - $uts['start'];
				if( $days=intval((floor($diff/86400))) )
					$diff = $diff % 86400;
				if( $hours=intval((floor($diff/3600))) )
					$diff = $diff % 3600;
				if( $minutes=intval((floor($diff/60))) )
					$diff = $diff % 60;
				$diff    =    intval( $diff );            
				return( array('days'=>$days, 'hours'=>$hours, 'minutes'=>$minutes, 'seconds'=>$diff) );
			}
			else
			{
				trigger_error( "Ending date/time is earlier than the start date/time", E_USER_WARNING );
			}
		}
		else
		{
			trigger_error( "Invalid date/time data detected", E_USER_WARNING );
		}
		return( false );
	}
  

} // end class

?>