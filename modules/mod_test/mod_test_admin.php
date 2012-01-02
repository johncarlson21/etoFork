<?php
/* the module_admin.php file must extend the main System class
 * this allows the module to take on the system vars (to access the etomite functions)
 * actions can be called to the ActionServer.php file in a _GET format like this:
 * domain.com/manager/ActionServer.php?action=manageModule&mod=module_name&moduleAction=hello&param1=xxx&param2=xxx
 * this can be done with JS by using your own ajax calls, and then sending the response to Etomite.loadPane(response);
 * to load the content in the pane
 */

class mod_test_admin extends System {
    
    var $moduleConfig; // main var passed from module xml file for basic config info
    
    public function __construct($config=null) {
        $this->runStandalone();
        if (!empty($config) && $config != null) {
            $this->moduleConfig = $config;
        }
    }
    
    public function adminView() { // this is the default admin view page.
        include_once dirname(__FILE__).'/views/admin.phtml';
    }
    
    public function hello() {
        echo "hello";
    }
}

$action = $_REQUEST['moduleAction']; // defaults to adminView

$mod_test_admin = new mod_test_admin($mod_testConfig);

/*if(IN_ETOMITE_SYSTEM != "true" || !$mod_test_admin->userLoggedIn()) {
    die("you are not supposed to be here");
}*/

$mod_test_admin->$action();

?>
