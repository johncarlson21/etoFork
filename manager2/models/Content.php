<?php
include_once('includes/bootstrap.php');

class Content extends etomiteExtender {
    
    var $id;
    
    public function __construct($id = null) {
        $this->runStandalone();
    }
    
    public function saveTree($data=array(), $count=0) {
        if (is_array($data) && count($data) > 0) {
            // process the tree here
            $menuindex = $count;
            foreach($data['children'] as $child) {
                $id = str_replace("id_", "", $child['key']);
                $this->updIntTableRows(array('menuindex'=>$menuindex), 'site_content', 'id='.$id);
                $menuindex++;
                if (isset($child['children']) && count($child['children']) > 0) {
                    /*foreach($child['children'] as $cc) {
                        $cid = str_replace("id_", "", $cc['key']);
                        $this->updIntTableRows(array('menuindex'=>$menuindex), 'site_content', 'id='.$cid);
                        $menuindex++;
                    }*/
                    $menuindex = $this->saveTree($child, $menuindex);
                }
                if($count > 0) {
                    return $menuindex;
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
                    } else {
                        $data['deleted'] = 0;
                        $data['deletedon'] = 0;
                        $data['deletedby'] = 0;
                        $data['editedby'] = $_SESSION['internalKey'];
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
}

?>