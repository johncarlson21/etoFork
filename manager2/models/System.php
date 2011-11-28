<?php
include_once('includes/bootstrap.php');

class System extends etomiteExtender {
    
    public function __construct() {
        $this->runStandalone();
    }
    
    public function removeLocks() {
        $sql = "TRUNCATE ".$this->db."active_users";
        $rs = $this->dbQuery($sql);
        if(!$rs){
          return false;
        }
        return true;
    }
    
}

?>