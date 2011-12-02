<?php
define("IN_ETOMITE_SYSTEM", true);
require_once('./includes/bootstrap.php');
require_once('./models/Ajax.php');

class ActionServer extends Ajax {
    
    public function loadWelcome() {
        $etomite = new etomiteExtender();
        $_lang = $this->_lang;
        include('views/welcome.phtml');
    }
    
    public function loginRequest() {
        $this->validateRequest(array(
            'username',
            'password'
        ));
        $etomite = new etomiteExtender();
        $username = $_REQUEST['username']; // this gets escaped in the function
        $password = $_REQUEST['password']; // this gets escaped in the function
        
        if ($etomite->userLogin($username,$password)) {
            $this->respond(true,"User Logged In");
        } else {
            $this->respond(false, "Username and Password did not match!");
        }
        
    }
    
    public function getDocTree() {
        $etomite = new etomiteExtender();
        $documents = $etomite->generateDocTree();
        echo json_encode($documents);
        exit(0);
    }
    
    public function saveTree() {
        if (!isset($_REQUEST['tree']) || count($_REQUEST['tree']) < 1) {
            $this->respond(true,'');
        }
        $Content = new Content();
        if ($Content->saveTree($_REQUEST['tree'])) {
            $this->respond(true, 'Tree Saved');
        } else {
            $this->respond(false, 'There was an error saving the tree');
        }
    }
    
    public function checkAlias() {
        $Content = new Content();
        $good = $Content->checkAlias($_REQUEST['alias'], (isset($_REQUEST['id']) && !empty($_REQUEST['id'])) ? (int)$_REQUEST['id'] : false);
        if ($good) {
            $this->respond(true, 'That is a good alias');
        } else {
            $this->respond(false, 'Alias exists');
        }
    }
    
    public function manageDocument() {
        $reference = false;
        if (isset($_REQUEST['reference']) && $_REQUEST['reference']=='true') {
            $reference = true;
        }
        $id = (isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])) ? (int)$_REQUEST['id'] : '';
        $Content = new Content();
        if (empty($id)) {
            $Content->manageDocument(null, $reference);
        } else {
            $Content->manageDocument($id, $reference);
        }
    }
    
    public function saveDocument() {
        $this->validateRequest(array(
            'type',
            'contentType',
            'pagetitle',
            'longtitle',
            'description',
            'alias',
            'published',
            'createdon',
            'pub_date',
            'unpub_date',
            'menuindex',
            'parent',
            'isfolder',
            'content',
            'richtext',
            'template',
            'searchable',
            'cacheable',
            'authenticate',
            'showinmenu',
            'meta_title',
            'meta_description',
            'meta_keywords'
        ));
        $data = array(
            'id' => (isset($_REQUEST['id']) && !empty($_REQUEST['id'])) ? (int)$_REQUEST['id'] : '',
            'type' => $_REQUEST['type'],
            'contentType' => $_REQUEST['contentType'],
            'pagetitle' => (isset($_REQUEST['pagetitle']) && !empty($_REQUEST['pagetitle'])) ? addslashes($_REQUEST['pagetitle']) : 'Un saved',
            'longtitle' => addslashes($_REQUEST['longtitle']),
            'description' => addslashes($_REQUEST['description']),
            'alias' => $_REQUEST['alias'],
            'published' => (isset($_REQUEST['published']) && $_REQUEST['published'] == 1) ? 1 : 0,
            'createdon' => '',
            'pub_date' => '',
            'unpub_date' => '',
            'menuindex' => (int)$_REQUEST['menuindex'],
            'parent' => (int)$_REQUEST['parent'],
            'isfolder' => (isset($_REQUEST['isfolder']) && $_REQUEST['isfolder'] == 1) ? 1 : 0,
            'content' => addslashes($_REQUEST['content']),
            'richtext' => (isset($_REQUEST['richtext']) && $_REQUEST['richtext'] == 1) ? 1 : 0,
            'template' => (int)$_REQUEST['template'],
            'searchable' => (isset($_REQUEST['searchable']) && $_REQUEST['searchable'] == 1) ? 1 : 0,
            'cacheable' => (isset($_REQUEST['cacheable']) && $_REQUEST['cacheable'] == 1) ? 1 : 0,
            'syncsite' => (isset($_REQUEST['syncsite']) && $_REQUEST['syncsite'] == 1) ? 1 : 0,
            'authenticate' => (isset($_REQUEST['authenticate']) && $_REQUEST['authenticate'] == 1) ? 1 : 0,
            'showinmenu' => (isset($_REQUEST['showinmenu']) && $_REQUEST['showinmenu'] == 1) ? 1 : 0,
            'meta_title' => addslashes($_REQUEST['meta_title']),
            'meta_description' => addslashes($_REQUEST['meta_description']),
            'meta_keywords' => addslashes($_REQUEST['meta_description'])
        );
        $Content = new Content();
        if($Content->saveDocument($data)) {
            $this->respond(true, 'Document Saved!');
        } else {
            $this->respond(false, 'Document not saved!');
        }
    }
    
    public function updateDocProperty() {
        $this->validateRequest(array('propName', 'propVal', 'id'));
        $Content = new Content();
        if ($Content->updateDocProperty($_REQUEST['id'], $_REQUEST['propName'], $_REQUEST['propVal'])) {
            $this->respond(true, 'Property updated');
        } else {
            $this->respond(false, 'Property not updated');
        }
    }
    
    public function moveDocDialog() {
        $id = isset($_REQUEST['id']) && is_numeric($_REQUEST['id']) ? (int)$_REQUEST['id'] : '';
        $Content = new Content();
        $Content->moveDocDialog();
    }
    
    public function loadSystemInfo() { // need to fix
        $etomite = new etomiteExtender();
        include('views/system_info.phtml');
    }
    
    public function removeLocks() {
        $System = new System();
        if ($System->removeLocks()) {
            $this->respond(true, 'Locks removed!');
        } else {
            $this->respond(false, 'There was an error removing locks!');
        }
    }
    
    public function loadAuditTrail() {
        include_once('views/audit_trail.phtml');
    }
    
    public function manageFiles() {
        include_once('views/manage_files.phtml');
    }
    
    public function loadHelp() { // need to fix
        $etomite = new etomiteExtender();
        include_once('views/help.phtml');
    }
    
    public function loadAbout() { // need to fix
        $etomite = new etomiteExtender();
        include_once('views/about.phtml');
    }
    
    public function loadResourcesView() {
        $Resource = new Resource();
        $Resource->loadResourcesView();
    }
    
    public function editResource() {
        $this->validateRequest(array('type'));
        $type = (isset($_REQUEST['type']) && !empty($_REQUEST['type'])) ? $_REQUEST['type'] : false;
        $Resource = new Resource();
        $Resource->editResource($type);
    }
    
    public function saveResource() {
        $this->validateRequest(array('type'));
        $type = (isset($_REQUEST['type']) && !empty($_REQUEST['type'])) ? $_REQUEST['type'] : false;
        $Resource = new Resource();
        if ($Resource->saveResource($type)) {
            if ($Resource->lastId && $Resource->lastId > 0) {
                $params = array('id'=>$Resource->lastId);
            }
            $this->respond(true, 'Resource Saved!', $params);
        } else {
            $this->respond(false, 'Resource not saved!');
        }
    }
    
    public function deleteResource() {
        $this->validateRequest(array('id','type'));
        $Resource = new Resource();
        if ($Resource->deleteResource((int)$_REQUEST['id'], $_REQUEST['type'])) {
            $this->respond(true, 'Resource Deleted');
        } else {
            $this->respond(false, 'Resource not deleted!');
        }
    }
    
    public function checkResourceName() {
        $Resource = new Resource();
        // todo no contain
        $against = array(" ","'",'"',"&","@","!","#","$","%","^","*","(",")","+","=");
        foreach($against as $a) {
            if (strpos($_REQUEST['name'], $a) !== false) {
                $this->respond(false, 'Resource name cannot contain &quot;'.$a.'&quot;');
            }
        }
        $good = $Resource->checkResourceName($_REQUEST['name'], $_REQUEST['type'], (isset($_REQUEST['id']) && !empty($_REQUEST['id'])) ? (int)$_REQUEST['id'] : false);
        if ($good) {
            $this->respond(true, 'That is a good resource name!');
        } else {
            $this->respond(false, 'Resource Name already exists!');
        }
    }
    
    public function createSection() {
        $this->validateRequest('name','description','section_type');
        $Resource = new Resource();
        if ($Resource->createSection($_REQUEST['name'], $_REQUEST['descriptoin'], $_REQUEST['section_type'])) {
            $this->respond(true, 'created');
        } else {
            $this->respond(false, 'not created');
        }
    }
    
    public function loadUsersView() {
        $User = new User();
        $User->loadUsersView();
    }

}

$action_server = new ActionServer();
$action_server->validateRequest(array('action'));

$action = $_REQUEST['action'];
$action_server->$action();

?>