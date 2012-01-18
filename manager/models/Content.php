<?php
if (!defined(CONFIG_LOADED)) {
    define("IN_ETOMITE_SYSTEM", true);
    include('includes/bootstrap.php');
}

class Content extends etomite {
    
    var $id;
    
    public function __construct($id = null) {
        if (!defined(CONFIG_LOADED)) {
            parent::__construct();
            $this->checkManagerLogin();
        }
    }
    
    public function saveTree($data=array(), $count=0) {
        if (is_array($data) && count($data) > 0) {
            // process the tree here
            /*$menuindex = $count;
            foreach($data['children'] as $child) {
                $id = str_replace("id_", "", $child['key']);
                $this->updIntTableRows(array('menuindex'=>$menuindex), 'site_content', 'id='.$id);
                $menuindex++;
                if (isset($child['children']) && count($child['children']) > 0) {
                    $menuindex = $this->saveTree($child, $menuindex);
                }
                if($count > 0) {
                    return $menuindex;
                }
                
            }*/
            for($i=0;$i < count($data);$i++) {
                $id = str_replace("id_", "", $data[$i]);
                if ($id > 0) {
                    $this->updIntTableRows(array('menuindex'=>$i), 'site_content', 'id='.$id);
                }
            }
            return true;
        }
        return false;
    }
    
    public function manageDocument($id=false, $reference=false) {
        if ($id && is_numeric($id)) {
            if(!$content = $this->getDocument($id)) {
                echo "<h2>That is not a valid document!</h2>";
            }
            // make sure to set the reference
            if($content['type']=='reference'){
                $reference = true;
            }
            // grab tvs for set template
            $result = $this->dbQuery(
                "SELECT t.* FROM ".$this->db."template_variables t" .
                " LEFT JOIN ".$this->db."template_variable_templates tvt" .
                " ON t.tv_id = tvt.tv_id" .
                " WHERE tvt.template_id = ".$content['template']
            );
            $templateVars = array();
            while($row = $this->fetchRow($result)) {
                $templateVars[] = $row;
            }
            // now grab template vars for the document
            $result = $this->getIntTableRows('tv_id,tv_value', 'site_content_tv_val', 'doc_id='.$content['id']);
            if (count($result) > 0) {
                foreach($result as $r){
                    $content['tvs'][$r['tv_id']] = $r['tv_value'];
                }
            } else {
                $content['tvs'] = array();
            }
        } else {
            $content = array();
        }
        
        include_once('views/document.phtml');
    }
    
    public function checkAlias($alias, $id=false) {
        if (empty($alias)) {
            return true;
        }
        if ($id) {
            $where = "id != " . $id . " AND alias = '" . $alias . "'";
        } else {
            $where = "alias = '" . $alias . "'";
        }
        
        if ($result = $this->getIntTableRows("*", 'site_content', $where)) {
            return false;
        } else {
            return true;
        }
    }
    
    public function saveDocument($data=array()) {
        if(empty($data) || count($data) < 1) {
            return false;
        }
        $id = (isset($data['id']) && is_numeric($data['id']) && !empty($data['id'])) ? $data['id'] : false;
        $templateVars = isset($data['templateVars']) && !empty($data['templateVars']) ? $data['templateVars']:false;
        unset($data['templateVars']);
        unset($data['id']);
        $syncsite = $data['syncsite'];
        unset($data['syncsite']);
        
        if (!isset($data['createdon']) || empty($data['createdon'])) {
            $data['createdon'] = time();
        } else {
            $data['createdon'] = strtotime($data['createdon']);
        }
        if (!isset($data['pub_date']) || empty($data['pub_date'])) {
            $data['pub_date'] = time();
        } else {
            $data['pub_date'] = strtotime($data['pub_date']);
        }
        if (!isset($data['unpub_date']) || empty($data['unpub_date'])) {
            $data['unpub_date'] = 0;
        } else {
            $data['unpub_date'] = strtotime($data['unpub_date']);
        }
        
        // set parent to a folde if not already
        if ($data['parent'] > 0) {
            $parent = $this->getDocument($data['parent']);
            if ($parent['isfolder'] != 1) {
                $result = $this->updIntTableRows(array('isfolder'=>1), 'site_content', 'id='.$parent['id']);
            }
        }
        if ($id) { // update document
            $orig_doc = $this->getDocument($id);
            if ($result = $this->updIntTableRows($data, 'site_content', 'id=' . $id)) {
                // save version
                $orig_doc['orig_id'] = $id;
                $orig_doc['versionedon'] = time();
                unset($orig_doc['id']);
                $result = $this->putIntTableRow($orig_doc, 'site_content_versions');
                if ($templateVars) {
                    // set template vars
                    $Resource = new Resource();
                    $Resource->setTVS2Doc($templateVars, $id);
                }
                $System = new System();
                $System->syncEtoCache();
                return true;
            } else {
                return false;
            }
        } else { // save new document
            if ($result = $this->putIntTableRow($data, 'site_content')) {
                $data['orig_id'] = $this->insertId();
                $data['versionedon'] = time();
                unset($data['id']);
                $result = $this->putIntTableRow($data, 'site_content_versions');
                $id = $data['orig_id'];
                if ($templateVars) {
                    // set template vars
                    $Resource = new Resource();
                    $Resource->setTVS2Doc($templateVars, $id);
                }
                $System = new System();
                $System->syncEtoCache();
                return true;
            } else {
                return false;
            }
        }
        return false;
    }
    
    public function updateDocProperty($id=false, $property='', $propertyVal='') {
        if (!$id || empty($id) || empty($property) || !isset($property)) {
            return false;
        }
        
        // grab document
        if ($doc = $this->getDocument($id)) {
            $data = array(
                $property => $propertyVal,
            );
            switch($property) {
                case "deleted":
                    if ($propertyVal == '1') {
                        $data['deleted'] = 1;
                        $data['deletedon'] = time();
                        $data['deletedby'] = $_SESSION['internalKey'];
                        $data['editedby'] = $_SESSION['internalKey'];
                        $children = $this->getAllChildren($id, 'menuindex', 'ASC', $fields='id, deleted', '', true);
                        foreach ($children as $ch) {
                            if ($ch['deleted'] != 1) {
                                $this->updateDocProperty($ch['id'], 'deleted', '1');
                            }
                        }
                    } else {
                        $data['deleted'] = 0;
                        $data['deletedon'] = 0;
                        $data['deletedby'] = 0;
                        $data['editedby'] = $_SESSION['internalKey'];
                        $children = $this->getAllChildren($id, 'menuindex', 'ASC', $fields='id, deleted', '', true);
                        foreach ($children as $ch) {
                            if ($ch['deleted'] == 1) {
                                $this->updateDocProperty($ch['id'], 'deleted', '0');
                            }
                        }
                    }
                break;
                case "published":
                    if ($propertyVal == '1') {
                        $data['published'] = 1;
                        $data['pub_date'] = time();
                        $data['unpub_date'] = 0;
                        $data['editedby'] = $_SESSION['internalKey'];
                    } else {
                        $data['published'] = 0;
                        $data['pub_date'] = 0;
                        $data['unpub_date'] = time();
                        $data['editedby'] = $_SESSION['internalKey'];
                    }
                break;
                case "parent":
                    $parent = $this->getDocument($propertyVal);
                    if ($parent['isfolder'] != 1 && $parent['id'] > 0) {
                        $this->updIntTableRows(array('isfolder'=>1), 'site_content', 'id=' . $parent['id']);
                    }
                    $data[$property] = $propertyVal;
                    $data['editedby'] = $_SESSION['internalKey'];
                    break;
                default:
                    $data[$property] = $propertyVal;
                    $data['editedby'] = $_SESSION['internalKey'];
                break;
            }
            if($result = $this->updIntTableRows($data, 'site_content', 'id='.$id)) {
                return true;
            }
        }
        return false;
    }
    
    public function moveDocDialog() {
        include('views/move_doc.phtml');
    }
    
    public function purgeDocuments() {
        // delete all documents where deleted = 0;
        if ($result = $this->dbQuery("DELETE FROM ".$this->db."site_content WHERE deleted=1")) {
            return true;
        }
        return false;
    }
    

}

?>