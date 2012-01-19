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

}

$action_server = new module();
$action_server->validateRequest(array('action'));

$action = $_REQUEST['action'];
$action_server->$action();

?>