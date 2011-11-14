<?php

class mod_test extends module {
    var $classParams = array();
    
    public function __construct($params=array()) {
        if (count($params) > 0) {
            $this->classParams = $params;
        }
    }
    
    public function index() {
        echo "hello there from mod_test module";
        foreach ($this->classParams as $key=>$val) {
            echo "<p>param: " . $key . "=" . $val . "</p>";
        }
    }
}

$action = (isset($action) && !empty($action)) ? $action : 'index'; // defaults to adminView

$mod_test = new mod_test($moduleParams);

$mod_test->$action();
?>