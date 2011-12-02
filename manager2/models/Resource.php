<?php
define("IN_ETOMITE_SYSTEM", true);
include('includes/bootstrap.php');

class Resource extends etomiteExtender {
    public $lastId = false;
    
    public function loadResourcesView() {
        include_once('views/resources.phtml');
    }
    
    public function checkResourceName($name, $type, $id=false) {
        if (empty($name) || empty($type)) {
            return true;
        }
        $nField = 'name';
        switch ($type) {
            case 'template':
                $type_table = 'site_templates';
                $nField = 'templatename';
            break;
            case 'snippet':
                $type_table = 'site_snippets';
            break;
            case 'chunk':
                $type_table = 'site_htmlsnippets';
            break;
        }
        
        if ($id) {
            $where = "id != " . $id . " AND ".$nField." = '" . $name . "'";
        } else {
            $where = $nField." = '" . $name . "'";
        }
        
        if ($result = $this->getIntTableRows("*", $type_table, $where)) {
            return false;
        } else {
            return true;
        }
    }
    
    public function editResource($type=false) {
        $id = (isset($_REQUEST['id']) && !empty($_REQUEST['id']) && is_numeric($_REQUEST['id'])) ? (int)$_REQUEST['id'] : false;
        if(!$type) {
            return false;
        }
        $type_table = '';
        $header_text = '';
        switch ($type) {
            case 'template':
                $type_table = 'site_templates';
                $header_text = $this->_lang['template_title'];
                $syntax = 'html';
            break;
            case 'snippet':
                $type_table = 'site_snippets';
                $header_text = $this->_lang['snippet_title'];
                $syntax = 'php';
            break;
            case 'chunk':
                $type_table = 'site_htmlsnippets';
                $header_text = $this->_lang['htmlsnippet_title'];
                $syntax = 'html';
            break;
        }
        // need a switch here for the type to choose the correct db
        
        if ($id) {
            if ($result = $this->getIntTableRows("*", $type_table, 'id='.$id)) {
                $resource = $result[0];
                if ($type == 'template') {
                    $header_text .= "&nbsp; ".$resource['templatename'];
                    $resource['name'] = $resource['templatename'];
                } else {
                    $header_text .= "&nbsp;-&nbsp;".$resource['name'];
                }
            } else {
                return "<h2>That is not a valid resource!</h2>";
            }
        } else {
            $resource = array();
        }
        
        include_once('views/edit_resource.phtml');
    }
    
    public function saveResource($type=false) {
        $id = (isset($_REQUEST['id']) && !empty($_REQUEST['id']) && is_numeric($_REQUEST['id'])) ? (int)$_REQUEST['id'] : false;
        if(!$type) {
            return false;
        }
        $name = (isset($_REQUEST['name']) && !empty($_REQUEST['name'])) ? mysql_escape_string($_REQUEST['name']) : 'unsaved';
        $description = (isset($_REQUEST['description']) && !empty($_REQUEST['description'])) ? mysql_escape_string($_REQUEST['description']) : '';
        $section = (isset($_REQUEST['section']) && !empty($_REQUEST['section'])) ? (int)$_REQUEST['section'] : 1;
        $content = (isset($_REQUEST['content']) && !empty($_REQUEST['content'])) ? mysql_escape_string($_REQUEST['content']) : '';
        $locked = (isset($_REQUEST['locked']) && $_REQUEST['locked']) ? 1 : 0;
        $data = array();
        $type_table = '';
        $nField = 'name';
        $cField = 'snippet';
        $data = array();
        switch ($type) {
            case 'template':
                $type_table = 'site_templates';
                $nField = 'templatename';
                $cField = 'content';
            break;
            case 'snippet':
                $type_table = 'site_snippets';
                $data['section'] = $section;
            break;
            case 'chunk':
                $type_table = 'site_htmlsnippets';
                $data['section'] = $section;
            break;
        }
        $data[$nField] = $name;
        $data[$cField] = $content;
        $data['description'] = $description;
        $data['locked'] = $locked;
        if ($id) {
            $res = $this->getIntTableRows("*", $type_table, 'id='.$id);
            $orig = $res[0];
            if ($this->updIntTableRows($data, $type_table, 'id='.$id)) {
                if ($type != 'template') {
                    // save version
                    if ($type == 'chunk') {
                        $orig["htmlsnippet_id"] = $id;
                    } else {
                        $orig["snippet_id"] = $id;
                    }
                    $orig['date_mod'] = date('Y-m-d h:i:s');
                    unset($orig['id']);
                    $this->putIntTableRow($orig, $type_table."_versions");
                }
                return true;
            }
            return false;
        } else {
            if ($this->putIntTableRow($data, $type_table)) {
                $this->lastId = $this->insertId();
                return true;
            }
            return false;
        }
    }
    
    public function deleteResource($id=false, $type=false) {
        if(!$id || !$type) {
            return false;
        }
        switch ($type) {
            case 'template':
                $type_table = 'site_templates';
            break;
            case 'snippet':
                $type_table = 'site_snippets';
            break;
            case 'chunk':
                $type_table = 'site_htmlsnippets';
            break;
        }
        // delete the resource
        if ($this->dbQuery("DELETE FROM ".$this->db.$type_table." WHERE id=".$id." LIMIT 1")) {
            return true;
        } else {
            return false;
        }
    }
    
    public function createSection($name, $description, $type) {
        if ($this->putIntTableRow(array('name'=>$name,'description'=>$description,'section_type'=>$type), 'site_section')){
            return true;
        }
        return false;
    }
    
}

?>