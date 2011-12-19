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
    
    public function syncSite() {
        $sql = "UPDATE ".$this->db."site_content SET published=1 WHERE ".$this->db."site_content.pub_date < ".time()." AND ".$this->db."site_content.pub_date!=0";
        $rs = $this->dbQuery($sql);
        $num_rows_pub = $this->affectedRows($etomiteDBConn);
        
        $sql = "UPDATE ".$this->db."site_content SET published=0 WHERE ".$this->db."site_content.unpub_date < ".time()." AND ".$this->db."site_content.unpub_date!=0";
        $rs = $this->dbQuery($sql);
        $num_rows_unpub = $this->affectedRows($etomiteDBConn);
        
        printf("<p>".$this->_lang["refresh_published"]."</p>", $num_rows_pub);
        printf("<p>".$this->_lang["refresh_unpublished"]."</p>", $num_rows_unpub);
        
        include_once(MANAGER_PATH."includes/cache_sync.class.processor.php");
        $sync = new synccache($this, $this->_lang);
        $sync->setCachepath(absolute_base_path."assets/cache/");
        $sync->setReport(true);
        $sync->emptyCache();
    }
    
    public function showSiteSchedule() {
        include('views/site_schedule.phtml');
    }
    
}

?>