<?php
if (!defined(CONFIG_LOADED)) {
    define("IN_ETOMITE_SYSTEM", true);
    include('includes/bootstrap.php');
}

class System extends etomiteExtender {
    var $settingsFields;
    
    public function __construct() {
        if (!defined(CONFIG_LOADED)) {
            parent::__construct();
            $this->checkManagerLogin();
        }
        $this->getSettingsFields();
    }
    
    public function getSettingsFields() {
        if ($fields = $this->getIntTableRows("setting_name", 'system_settings')) {
            if (count($fields) > 0) {
                $arr = array();
                foreach($fields as $field) {
                    
                    $arr[] = $field['setting_name'];
                }
                $this->settingsFields = $arr;
            }
        }
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
        $num_rows_pub = $this->affectedRows($rs);
        
        $sql = "UPDATE ".$this->db."site_content SET published=0 WHERE ".$this->db."site_content.unpub_date < ".time()." AND ".$this->db."site_content.unpub_date!=0";
        $rs = $this->dbQuery($sql);
        $num_rows_unpub = $this->affectedRows($rs);
        
        printf("<p>".$this->_lang["refresh_published"]."</p>", $num_rows_pub);
        printf("<p>".$this->_lang["refresh_unpublished"]."</p>", $num_rows_unpub);
        
        include_once(MANAGER_PATH . "includes/cache_sync.class.processor.php");
        $sync = new synccache($this, $this->_lang);
        $sync->setCachepath(absolute_base_path."assets/cache/");
        $sync->setReport(true);
        $sync->emptyCache();
    }
    
    public function syncEtoCache() { // silent cache sync
        include_once(MANAGER_PATH . "includes/cache_sync.class.processor.php");
        $sync = new synccache($this, $this->_lang);
        $sync->setCachepath(absolute_base_path."assets/cache/");
        $sync->setReport(false);
        $sync->emptyCache();
    }
    
    public function showSiteSchedule() {
        include_once('views/site_schedule.phtml');
    }
    
    public function showSiteSettings() {
        include_once('views/system_settings.phtml');
    }
    
    public function saveSiteSettings() {
        $output = '';
        if (count($this->settingsFields) > 0) {
            foreach($this->settingsFields as $field) {
                $value = '';
                if (isset($_GET[$field])) {
                    $value = is_numeric($_GET[$field]) ? (int) $_GET[$field] : mysql_real_escape_string($_GET[$field]);
                    if (!$this->updIntTableRows(array('setting_value'=>$value), 'system_settings', 'setting_name="'.$field.'"')) {
                        return false; // there was an error
                    }
                    $output .= "saving: ".$field." = ".$value."\n";
                }
            }
            $this->syncEtoCache();
            return true;
        }
        return false;
    }
    
    public function manageModule() {
        require_once(absolute_base_path . 'modules/module.php');
        $moduleClass = new module; // start the module for basic function
        
        if(isset($_REQUEST['mod']) && !empty($_REQUEST['mod']) && file_exists(absolute_base_path . "modules/" . $_REQUEST['mod'])){
            if(!isset($_REQUEST['moduleAction'])) {
                $_REQUEST['moduleAction'] = 'adminView';
            }
            $module = $_REQUEST['mod'];
            // load module config
            $xmlUrl = "../modules/" . $module . "/" . $module . ".xml"; // XML feed file/URL
            $xmlStr = file_get_contents($xmlUrl);
            $xmlObj = simplexml_load_string($xmlStr);
            $arrXml = objectsIntoArray($xmlObj);
            $var_name = $module . "Config";
            ${$var_name} = $arrXml; //simplexml_load_string($xmlStr);
            // load module
            require_once (absolute_base_path . 'modules/' . $module . "/" . $module . "_admin.php");
        }
    }
    
    public function showVisitorStats() {
        include_once('views/visitors_stats.phtml');
    }
    
    public function showOnlineVisitors() {
        include_once('views/visitors_online.phtml');
    }
    
}

?>