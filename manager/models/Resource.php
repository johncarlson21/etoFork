<?php
if (!defined('CONFIG_LOADED')) {
    define("IN_ETOMITE_SYSTEM", true);
    include('includes/bootstrap.php');
}

define('DS', DIRECTORY_SEPARATOR);


class Resource extends etomite {
    public $lastId = false;
    
    public function __construct() {
        if (!defined(CONFIG_LOADED)) {
            parent::__construct();
            $this->checkManagerLogin();
        }
    }
    
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
        $content = (isset($_REQUEST['content']) && !empty($_REQUEST['content'])) ? stripslashes($_REQUEST['content']) : '';
        $content = mysql_real_escape_string($content);
        
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
                    $orig['snippet'] = mysql_real_escape_string($orig['snippet']);
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
    
    public function sectionExists($name, $type) {
        $section = $this->getIntTableRows('*', 'site_section', 'name="'.$name.'" AND section_type="'.$type.'"');
        if(count($section) > 0) {
            return true;
        }
        return false;
    }
    
    public function getSection($name, $type) {
        $section = $this->getIntTableRows('*', 'site_section', 'name="'.$name.'" AND section_type="'.$type.'"');
        if(count($section) > 0) {
            return $section[0];
        }
        return false;
    }
    
    public function createSection($name, $description, $type) {
        if ($this->putIntTableRow(array('name'=>$name,'description'=>$description,'section_type'=>$type), 'site_section')){
            return true;
        }
        return false;
    }
    
    public function getTVFromFieldName($name) {
        if (!empty($name)) {
            $result = $this->getIntTableRows("*",'template_variables','field_name="'.$name.'"');
            if(count($result) > 0) {
                return $result[0];
            }
        }
    }
    
    public function setTVS2Doc($templateVars, $docId) {
        if(empty($docId) || count($templateVars) < 0) { return; }
        // delete all vars first
        $this->dbQuery("DELETE FROM ".$this->db."site_content_tv_val WHERE doc_id=".$docId);
        foreach($templateVars as $t) {
            if ($tv = $this->getTVFromFieldName($t['name'])) {
                $result = $this->putIntTableRow(array('doc_id'=>$docId,'tv_id'=>$tv['tv_id'],'tv_value'=>$t['value']), 'site_content_tv_val');
            }
        }
    }
    
    public function editTV() {
        $tvTypes = array('text','textarea','select','checkbox','radio','file');
        $tvOTypes = array('text','image','link','date');
        $templates = $this->getIntTableRows('*','site_templates');
        $id = (isset($_REQUEST['id']) && !empty($_REQUEST['id']) && is_numeric($_REQUEST['id'])) ? (int)$_REQUEST['id'] : false;
        
        if ($id) {
            if ($result = $this->getIntTableRows("*", 'template_variables', 'tv_id='.$id)) {
                $tv = $result[0];
                // get templates for tv
                $tvTpls = $this->getIntTableRows("*", 'template_variable_templates', 'tv_id='.$id);
                foreach($tvTpls as $tt) {
                    $tv['templates'][] = $tt['template_id'];
                }
                unset($tvTpls);
            } else {
                return "<h2>That is not a valid resource!</h2>";
            }
        } else {
            $tv = array();
        }
        
        include_once('views/edit_tv.phtml');
    }
    
    public function saveTV() {
        $id = (isset($_REQUEST['tv_id']) && !empty($_REQUEST['tv_id']) && is_numeric($_REQUEST['tv_id'])) ? (int)$_REQUEST['tv_id'] : false;
        // build data
        $data = array(
            'name' => isset($_REQUEST['name']) ? mysql_real_escape_string($_REQUEST['name']):'',
            'field_name' => isset($_REQUEST['field_name']) ? mysql_real_escape_string($_REQUEST['field_name']):'',
            'description' => isset($_REQUEST['description']) ? mysql_real_escape_string($_REQUEST['description']):'',
            'type' => isset($_REQUEST['type']) ? mysql_real_escape_string($_REQUEST['type']):'text',
            'output_type' => isset($_REQUEST['output_type']) ? mysql_real_escape_string($_REQUEST['output_type']):'text',
            'opts' => isset($_REQUEST['opts']) ? mysql_real_escape_string($_REQUEST['opts']):'',
            'field_size' => isset($_REQUEST['field_size']) ? (int)$_REQUEST['field_size']:'',
            'field_max_size' => isset($_REQUEST['field_max_size']) ? (int)$_REQUEST['field_max_size']:'',
            'tv_order' => isset($_REQUEST['tv_order']) ? (int)$_REQUEST['tv_order']:'0',
            'default_val' => isset($_REQUEST['default_val']) ? mysql_real_escape_string($_REQUEST['default_val']):'',
            'required' => isset($_REQUEST['required']) && $_REQUEST['required'] == 1 ? 1:0
        );
        
        $templates = isset($_REQUEST['templates']) ? $_REQUEST['templates']:''; // need to add this part.
        
        if ($id) {
            if ($this->updIntTableRows($data, 'template_variables', 'tv_id='.$id)) {
                if(!empty($templates) && count($templates) > 0) {
                    // delete all of them first
                    $delR = $this->dbQuery('DELETE FROM '.$this->db.'template_variable_templates WHERE tv_id='.$id);
                    foreach ($templates as $tpl) {
                        $this->putIntTableRow(array('template_id'=>$tpl['id'],'tv_id'=>$id),'template_variable_templates');
                    }
                }
                return true;
            }
        } else {
            if ($this->putIntTableRow($data, 'template_variables')) {
                $tv_id = $this->insertId();
                if(!empty($templates) && count($templates) > 0) {
                    foreach ($templates as $tpl) {
                        $this->putIntTableRow(array('template_id'=>$tpl['id'],'tv_id'=>$tv_id),'template_variable_templates');
                    }
                }
                return true;
            }
        }
        return false;
    }
    
    public function deleteTV($tv_id) {
        if (empty($tv_id)) { return false; }
        // remove template variable
        if ($result = $this->dbQuery("DELETE FROM ".$this->db."template_variables WHERE tv_id = ".$tv_id)) {
            if ($result = $this->dbQuery("DELETE FROM ".$this->db."template_variable_templates WHERE tv_id = ".$tv_id)) {
                if ($result = $this->dbQuery("DELETE FROM ".$this->db."site_content_tv_val WHERE tv_id = ".$tv_id)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
        return false;
    }
    
    // removes files and non-empty directories
    public function rrmdir($dir) {
      if (is_dir($dir)) {
        $files = scandir($dir);
        foreach ($files as $file)
        if ($file != "." && $file != "..") $this->rrmdir($dir."/".$file);
        rmdir($dir);
      }
      else if (file_exists($dir)) unlink($dir);
    }
    
    // copies files and non-empty directories
    public function rcopy($src, $dst) {
      //if (file_exists($dst)) rrmdir($dst);
      if (is_dir($src)) {
        mkdir($dst);
        $files = scandir($src);
        foreach ($files as $file)
        if ($file != "." && $file != "..") $this->rcopy($src."/".$file, $dst."/".$file);
      }
      else if (file_exists($src)) copy($src, $dst);
    }
    
}

?>