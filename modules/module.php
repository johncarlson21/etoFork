<?php
/*
 * MAIN MODULE CLASS
 * All other modules should be extended from this on.
 * This module gives a validate request function to check for request vars
 * and also has a basic json ouput function. see below.
 */

define("IN_ETOMITE_SYSTEM", true);
require_once('../manager/includes/bootstrap.php');

class module {
    /**
     * Check that all the @fields were sent on the request
     * returns true/false.
     *
     * @fields has to be an array of strings
     */
    public function validateRequest($fields, $return = false) {
        // If @fields ain't an array return false and exit
        if (!is_array($fields)) {
            return false;
        }

        foreach ($fields as $field) {
            if (!isset($_REQUEST[$field])) {
                // If we specified that the function must return do so
                if ($return) {
                    return false;
                } else { // If not, send the default reponse and exit
                    $this->respond(false, "Not all params supplied.");
                }
            }
        }
    }

    /**
     * Sends a json encoded response back to the caller
     * with @succeeded and @message
     */
    public function respond($succeeded, $message, $params = null) {
        $response = array(
            'succeeded' => $succeeded,
            'message' => $message,
            'params' => $params
        );
        echo json_encode($response);
        exit(0);
    }
    
    public function manageModule() {
        $this->validateRequest(array('mod'));
        /*$System = new System();
        $System->manageModule();*/
        //require_once(absolute_base_path . 'modules/module.php');
        //$moduleClass = new module; // start the module for basic function
        
        if(isset($_REQUEST['mod']) && !empty($_REQUEST['mod']) && file_exists(absolute_base_path . "modules/" . $_REQUEST['mod'])){
            if(!isset($_REQUEST['moduleAction'])) {
                $_REQUEST['moduleAction'] = 'adminView';
            }
            $module = $_REQUEST['mod'];
            // load module config
            $xmlUrl = $module . "/" . $module . ".xml"; // XML feed file/URL
            $arrXml = array();
            if (file_exists($xmlUrl)) {
                $xmlStr = file_get_contents($xmlUrl);
                $xmlObj = simplexml_load_string($xmlStr);
                $arrXml = objectsIntoArray($xmlObj);
            }
            $var_name = $module . "Config";
            ${$var_name} = $arrXml; //simplexml_load_string($xmlStr);
            // load module
            require_once (absolute_base_path . 'modules/' . $module . "/" . $module . "_admin.php");
        }
    }
    
    public function listModules() {
        $etomite = new etomite();
        $modules = $etomite->getIntTableRows('*', 'modules');
        if (count($modules) > 0) {
            echo "<table width='100%' cellpadding='7' cellspacing='0' border='0' class='table-striped'>";
            echo "<tr><th>Module Name</th><th>Description</th><th>Author</th><th>Version</th><th style='text-align:center;'>Active</th><th style='text-align:center;'>Menu Item</th></tr>";
            foreach($modules as $module) {
                echo "<tr>";
                echo "<td>".ucwords($module['name'])."</td><td>".stripslashes($module['description'])."</td><td>" .
                    $module['author']."</td><td>".$module['version']."</td>";
                echo "<td align='center'>".($module['active'] == 1 ? "Yes":"NO")."</td>";
                echo "<td align='center'>".($module['admin_menu'] == 1 ? "Yes":"NO")."</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>Sorry but you have no modules installed! Use the \"Install Modules\" tab to add modules!</p>";
        }
    }
    
    public function moduleExists($module) {
        $etomite = new etomite();
        $modules = $etomite->getIntTableRows('*', 'modules', '`internal_key`="'.$module.'"');
        
        if (count($modules) > 0) {
            return $modules[0];
        }
        return false;
    }
    
    public function removeModule($id) {
        $etomite = new Resource();
        $modules = $etomite->getIntTableRows('*', 'modules', 'id='.$id);
        $snippets = array();
        $chunks = array();
        if (count($modules) > 0) {
            $module = $modules[0];
            // remove module from db
            $result = $etomite->dbQuery("DELETE FROM ".$etomite->db."modules WHERE id=".$id);
            // remove snippets and chunks if needed
            if (!empty($module['resources'])) {
                $resources = explode("|", $module['resources']);
                $snR = isset($resources[0]) ? explode(":", $resources[0]) : '';
                $snippets = isset($snR[1]) ? explode(",", $snR[1]) : array();
                $chR = isset($resources[1]) ? explode(":", $resources[1]) : '';
                $chunks = isset($chR[1]) ? explode(",", $chR[1]) : array();
            }
            if (count($snippets) > 0) {
                foreach($snippets as $snippet) {
                    if ($s = $etomite->getResourceFromName($snippet, 'snippet')) {
                        $etomite->deleteResource($s['id'], 'snippet');
                    }
                }
            }
            if (count($chunks) > 0) {
                foreach($chunks as $chunk) {
                    if ($c = $etomite->getResourceFromName($chunk, 'chunk')) {
                        $etomite->deleteResource($c['id'], 'chunk');
                    }
                }
            }
            // delete system events
            $etomite->dbQuery("DELETE FROM ".$etomite->db."system_events WHERE module_name='".$module['internal_key']."'");
            // remove old module directory
            $etomite->rrmdir(absolute_base_path . 'modules/' . $module['internal_key'] . "/");
        }
        return false;
    }
    
    public function installModule() {
        // form to ask for the module name basically the first part of the .tgz file
        $etomite = new etomite();
        include_once(MANAGER_PATH . 'views/install_module.phtml');
    }
    
    public function runModuleInstall() {
		$etomite = new etomite();
        $this->validateRequest(array('module'));
        $module = $_REQUEST['module'];
        // first try to extract the module
        include_once(MANAGER_PATH . 'lib/easyarchives/EasyArchive.class.php');
        $arch = new archive;
        $ext = '';
        // check for either tar or zip file
        if (file_exists(absolute_base_path . 'tmp/packages/'.$module.'.tar')) {
            if (!$arch->extract(absolute_base_path . 'tmp/packages/'.$module.'.tar', absolute_base_path . 'tmp/packages/')) {
                $this->respond(false, 'There was an error extracting the module!');
            }
            $ext = ".tar";
        } elseif (file_exists(absolute_base_path . 'tmp/packages/'.$module.'.zip')) {
            if (!$arch->extract(absolute_base_path . 'tmp/packages/'.$module.'.zip', absolute_base_path . 'tmp/packages/')) {
                $this->respond(false, 'There was an error extracting the module!');
            }
            $ext = ".zip";
        } else {
            $this->respond(false, "<p>No package file found!</p><p>Make sure your file has an extension of either .tar or .zip</p>");
        }
        // run the install of the file (moving them and running the creation of sections of snippets)
        $Resource = new Resource();
        $admin_menu = false;
        $version = "0.0";
        $module_description = '';
        $module_name = $module;
        $author = "";
        $updating = false;
        
        if (file_exists(absolute_base_path . 'tmp/packages/'.$module.'/install.php')) {
            include_once(absolute_base_path . 'tmp/packages/'.$module.'/install.php');
            // check for old module
            $oldModule = $this->moduleExists($module_key);
            if ($oldModule) {
                // check version
                if ($version > $oldModule['version']) {
                    $updating = true;
                    $this->removeModule($oldModule['id']); // remove old module and resources (snippets, chunks and module folder)
                } else {
                    $Resource->rrmdir(absolute_base_path . 'tmp/packages/' . $module . "/");
                    $this->respond(false, '<p>Module already installed!</p><p>The version you are trying to install is either the same or an older version.</p>');
                }
            }
            
            $snSectionId = 1; // set to default section
            $chSectionId = 2; // set to default section
            $snippets = array();
            $chunks = array();
            
            if (isset($module_name) && isset($resources)) {
                // don't create sections unless we have resources for them
                // create new sections for snippets and chunks
                // create new snippets and chunks
                if (isset($resources['snippets']) && count($resources['snippets']) > 0){
                    if (!$Resource->sectionExists($module_name)) {
                        $Resource->createSection($module_name, 'Module: '.$module_name.' category');
                        $snSectionId = $Resource->insertId();
                    } else {
                        $section = $Resource->getSection($module_name);
                        $snSectionId = $section['id'];
                    }
                    // create any snippets
                    foreach($resources['snippets'] as $snippet) {
                        // check for file first
                        if (file_exists(absolute_base_path . 'tmp/packages/'.$module.'/resources/snippets/'.$snippet['ref'].'.php')) {
                            $snCode = @file_get_contents(absolute_base_path . 'tmp/packages/'.$module.'/resources/snippets/'.$snippet['ref'].'.php');
                            $result = $Resource->putIntTableRow(array(
                                'name'=>mysql_real_escape_string($snippet['name']),
                                'description'=>mysql_real_escape_string($snippet['description']),
                                'snippet'=>mysql_real_escape_string($snCode),
                                'section'=>$snSectionId
                            ), 'site_snippets');
                            $snippets[] = $snippet['name'];
                        }
                    }
                }// end if for snippets
                if (isset($resources['chunks']) && count($resources['chunks']) > 0){
                    if (!$Resource->sectionExists($module_name)) {
                        $Resource->createSection($module_name, 'Module: '.$module_name.' category');
                        $chSectionId = $Resource->insertId();
                    } else {
                        $section = $Resource->getSection($module_name);
                        $chSectionId = $section['id'];
                    }
                    foreach($resources['chunks'] as $chunk) {
                        // check for file first
                        if (file_exists(absolute_base_path . 'tmp/packages/'.$module.'/resources/chunks/'.$chunk['ref'].'.php')) {
                            $chCode = @file_get_contents(absolute_base_path . 'tmp/packages/'.$module.'/resources/chunks/'.$chunk['ref'].'.php');
                            $result = $Resource->putIntTableRow(array(
                                'name'=>mysql_real_escape_string($chunk['name']),
                                'description'=>mysql_real_escape_string($chunk['description']),
                                'snippet'=>mysql_real_escape_string($chCode),
                                'section'=>$chSectionId
                            ), 'site_htmlsnippets');
                            $chunks[] = $chunk['name'];
                        }
                    }
                }// end if for chunks
            }// end check for module name and resources
            if (isset($events) && count($events) > 0) {
                foreach ($events as $ev) {
                    $result = $Resource->putIntTableRow(array(
                        'event_name' => $ev['event_name'],
                        'module_name' => $module_key,
                        'method_name' => $ev['method_name']
                    ), 'system_events');
                }
            }// end for system events
            
            // add module to the modules table
            // build resources data
            $module_resources = '';
            if (count($snippets) > 0) {
                $module_resources .= "snippets:" . implode(",", $snippets);
            }
            if (count($chunks) > 0) {
                if (!empty($module_resources)) {
                    $module_resources .= "|";
                }
                $module_resources .= "chunks:" . implode(",", $chunks);
            }
			
            $result = $Resource->putIntTableRow(array('name'=>$module_name, 'description'=>$module_description, 'version'=>$version, 'author'=>$author, 'admin_menu'=>($admin_menu) ? 1:0, 'active'=>1, 'internal_key'=>$module_key, 'resources'=>$module_resources), 'modules');
            
            // move folder to modules folder
            $Resource->rcopy(absolute_base_path . 'tmp/packages/' . $module . "/", absolute_base_path . 'modules/' . $module);
            // remove tmp module directory
            $Resource->rrmdir(absolute_base_path . 'tmp/packages/' . $module . "/");
            // remove package file
            @unlink(absolute_base_path . 'tmp/packages/' . $module . $ext);
			if (isset($db_file) && !empty($db_file) && file_exists(MODULES_PATH . $module . "/sql/" . $db_file)) {
				// install db tables
				include(MANAGER_PATH . "includes/sqlParser.class.php");
				$sqlFile = MODULES_PATH . $module . "/sql/" . $db_file;
				$sqlParser = new SqlParser($etomite->dbConfig['host'], $etomite->dbConfig['user'], $etomite->dbConfig['pass'], str_replace("`", "", $etomite->dbConfig['dbase']), $etomite->dbConfig['table_prefix'], '', '');
				$sqlParser->connect();
				$sqlParser->process($sqlFile);
				if ($sqlParser->installFailed) {
					error_log(print_r($sqlParser->mysqlErrors,true));
				}
				$sqlParser->close();
			}
			
            if ($updating) {
                $this->respond(true, 'Module Updated');
            } else {
                $this->respond(true, 'Module Installed! Reloading Manager!');
            }
            
        } else {
            $this->respond(false, "<p>This package does not contain an install.php file! This is required!</p>");
        }// end if for install script
        
    }

}

$action_server = new module();
$action_server->validateRequest(array('action'));

$action = $_REQUEST['action'];
$action_server->$action();

?>