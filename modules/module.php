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
            $xmlStr = file_get_contents($xmlUrl);
            $xmlObj = simplexml_load_string($xmlStr);
            $arrXml = objectsIntoArray($xmlObj);
            $var_name = $module . "Config";
            ${$var_name} = $arrXml; //simplexml_load_string($xmlStr);
            // load module
            require_once (absolute_base_path . 'modules/' . $module . "/" . $module . "_admin.php");
        }
    }
    
    public function listModules() {
        
    }
    
    public function installModule() {
        // form to ask for the module name basically the first part of the .tgz file
        include_once(MANAGER_PATH . 'views/install_module.phtml');
    }
    
    public function runModuleInstall() {
        $this->validateRequest(array('module'));
        $module = $_REQUEST['module'];
        // first try to extract the module
        include_once(MANAGER_PATH . 'lib/easyarchives/EasyArchive.class.php');
        $arch = new archive;
        if (!$arch->extract(absolute_base_path . 'tmp/packages/'.$module.'.tar', absolute_base_path . 'tmp/packages/')) {
            $this->respond(false, 'There was an error extracting the module!');
        }
        // run the install of the file (moving them and running the creation of sections of snippets)
        $Resource = new Resource();
        if (file_exists(absolute_base_path . 'tmp/packages/'.$module.'/install.php')) {
            include_once(absolute_base_path . 'tmp/packages/'.$module.'/install.php');
            $snSectionId = 1; // set to default section
            $chSectionId = 2; // set to default section
            if (isset($module_name) && isset($resources)) {
                // don't create sections unless we have resources for them
                // create new sections for snippets and chunks
                // create new snippets and chunks
                if (isset($resources['snippets']) && count($resources['snippets']) > 0){
                    if (!$Resource->sectionExists($module_name, 'snippet')) {
                        $Resource->createSection($module_name, 'Module: '.$module_name.' snippet section', 'snippet');
                        $snSectionId = $Resource->insertId();
                    } else {
                        $section = $Resource->getSection($module_name, 'snippet');
                        $snSectionId = $section['id'];
                    }
                    // create any snippets
                    foreach($resources['snippets'] as $snippet) {
                        // check for file first
                        if (file_exists(absolute_base_path . 'tmp/packages/'.$module.'/resources/snippets/'.$snippet['ref'].'.php')) {
                            $snCode = @file_get_contents(absolute_base_path . 'tmp/packages/'.$module.'/resources/snippets/'.$snippet['ref'].'.php');
                            $result = $Resource->putIntTableRow(array(
                                'name'=>$snippet['name'],
                                'description'=>$snippet['description'],
                                'snippet'=>$snCode,
                                'section'=>$snSectionId
                            ), 'site_snippets');
                        }
                    }
                }// end if for snippets
                if (isset($resources['chunks']) && count($resources['chunks']) > 0){
                    if (!$Resource->sectionExists($module_name, 'chunk')) {
                        $Resource->createSection($module_name, 'Module: '.$module_name.' chunk section', 'chunk');
                        $chSectionId = $Resource->insertId();
                    } else {
                        $section = $Resource->getSection($module_name, 'chunk');
                        $chSectionId = $section['id'];
                    }
                    foreach($resources['chunks'] as $chunk) {
                        // check for file first
                        if (file_exists(absolute_base_path . 'tmp/packages/'.$module.'/resources/chunks/'.$chunk['ref'].'.php')) {
                            $chCode = @file_get_contents(absolute_base_path . 'tmp/packages/'.$module.'/resources/chunks/'.$chunk['ref'].'.php');
                            $result = $Resource->putIntTableRow(array(
                                'name'=>$chunk['name'],
                                'description'=>$chunk['description'],
                                'snippet'=>$chCode,
                                'section'=>$chSectionId
                            ), 'site_htmlsnippets');
                        }
                    }
                }// end if for chunks
            }// end check for module name and resources
            
        } // end if for install script
        
        // move folder to modules folder
        $Resource->rcopy(absolute_base_path . 'tmp/packages/' . $module . "/", absolute_base_path . 'modules/' . $module);
        // remove tmp module directory
        $Resource->rrmdir(absolute_base_path . 'tmp/packages/' . $module . "/");
        // remove package file
        @unlink(absolute_base_path . 'tmp/packages/' . $module . ".tar");
        
        $this->respond(true, 'Module Installed!');
    }

}

$action_server = new module();
$action_server->validateRequest(array('action'));

$action = $_REQUEST['action'];
$action_server->$action();

?>