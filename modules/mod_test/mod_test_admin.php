<?php
/*
 * IF YOU ARE OUTPUTTING HTML TO THE MANAGER FRAME
 * YOU SHOULD INCLUDE THE /manager/header.inc.php and
 * /manager/footer.inc.php files to setup the frame correctly
 * for layout. This can be done in a view/view_file.phtml file or
 * directly in the functions below.
 */

if(IN_ETOMITE_SYSTEM != "true" || !$etomite->userLoggedIn()) {
    die("you are not supposed to be here");
}

class mod_test_admin extends module {
    
    var $moduleConfig; // main var passed from module xml file for basic config info
    
    public function __construct($config=null) {
        if (!empty($config) && $config != null) {
            $this->moduleConfig = $config;
        }
    }
    
    public function adminView() { // this is the default admin view page.
        include_once 'views/admin.phtml';
    }
    
    public function hello() {
        echo "hello";
    }
}

$action = $_REQUEST['action']; // defaults to adminView

$mod_test_admin = new mod_test_admin($mod_testConfig);

$mod_test_admin->$action();

?>
