<?php
define("IN_ETOMITE_SYSTEM", true);
require_once('./includes/bootstrap.php');
require_once('./models/Ajax.php');

class ActionServer extends Ajax {
    
    public function __construct() {
        if ((!isset($_SESSION['validated']) || $_SESSION['validated'] != 1) && $_REQUEST['action'] != 'loginRequest') {
            echo "<script>window.top.location.href='".MANAGER_URL."';</script>";
            exit(0);
        }
    }
    
    public function loadWelcome() {
        $etomite = new etomite();
        $_lang = $etomite->_lang;
        include('views/welcome.phtml');
    }
    
    public function loginRequest() {
        $this->validateRequest(array(
            'username',
            'password'
        ));
        $etomite = new etomite();
        $username = $_REQUEST['username']; // this gets escaped in the function
        $password = $_REQUEST['password']; // this gets escaped in the function
        
        if ($etomite->userLogin($username,$password)) {
            $this->respond(true,$etomite->_lang['user_logged_in']);
        } else {
            $this->respond(false, $etomite->_lang['username_password_no_match']);
        }
        
    }
    
    public function getDocTree() {
        $etomite = new etomite();
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
            $this->respond(true, $Content->_lang['save_tree']);
        } else {
            $this->respond(false, $Content->_lang['save_tree_error']);
        }
    }
    
    public function reloadModuleNav() {
        $etomite = new etomite();
        echo $etomite->buildAdminModuleMenu();
        exit(0);
    }
    
    public function checkAlias() {
        $Content = new Content();
        $against = array(" ","'",'"',"&","@","!","#","$","%","^","*","(",")","+","=");
        foreach($against as $a) {
            if (strpos($_REQUEST['alias'], $a) !== false) {
                $this->respond(false, $etomite->_lang['alias_error_start'].$a.$etomite->_lang['alias_error_end']);
            }
        }
        $good = $Content->checkAlias($_REQUEST['alias'], (isset($_REQUEST['id']) && !empty($_REQUEST['id'])) ? (int)$_REQUEST['id'] : false);
        if ($good) {
            $this->respond(true, $Content->_lang['alias_good']);
        } else {
            $this->respond(false, $Content->_lang['alias_exists']);
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
	
	public function duplicateDocument() {
        $reference = false;
        if (isset($_REQUEST['reference']) && $_REQUEST['reference']=='true') {
            $reference = true;
        }
        $id = (isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])) ? (int)$_REQUEST['id'] : '';
        $Content = new Content();
        if (!empty($id)) {
            $Content->duplicateDocument($id, $reference);
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
            'meta_keywords' => addslashes($_REQUEST['meta_keywords']),
            'templateVars' => isset($_REQUEST['templateVars']) ? $_REQUEST['templateVars']:'',
			'groups' => isset($_REQUEST['groups']) && !empty($_REQUEST['groups']) ? $_REQUEST['groups'] : ''
        );
        $Content = new Content();
        if($Content->saveDocument($data)) {
            $this->respond(true, $Content->_lang['document_saved']);
        } else {
            $this->respond(false, $Content->_lang['document_not_saved']);
        }
    }
    
    public function updateDocProperty() {
        $this->validateRequest(array('propName', 'propVal', 'id'));
        $Content = new Content();
        if ($Content->updateDocProperty($_REQUEST['id'], $_REQUEST['propName'], $_REQUEST['propVal'])) {
            $this->respond(true, $Content->_lang['property_updated']);
        } else {
            $this->respond(false, $Content->_lang['property_not_updated']);
        }
    }
    
    public function moveDocDialog() {
        $id = isset($_REQUEST['id']) && is_numeric($_REQUEST['id']) ? (int)$_REQUEST['id'] : '';
        $Content = new Content();
        $Content->moveDocDialog();
    }
    
    public function purgeDocuments() {
        $Content = new Content();
        if ($Content->purgeDocuments()) {
            $this->respond(true, $Content->_lang['documents_purged']);
        } else {
            $this->respond(false, $Content->_lang['documents_purged_error']);
        }
    }
    
    public function loadSystemInfo() { // need to fix
        $etomite = new etomite();
        include('views/system_info.phtml');
    }
    
    public function removeLocks() {
        $System = new System();
        if ($System->removeLocks()) {
            $this->respond(true, $System->_lang['locks_removed']);
        } else {
            $this->respond(false, $System->_lang['locks_removed_error']);
        }
    }
    
    public function loadAuditTrail() {
        include_once('views/audit_trail.phtml');
    }
    
    public function manageFiles() {
        include_once('views/manage_files.phtml');
    }
    
    public function loadHelp() { // need to fix
        $etomite = new etomite();
        include_once('views/help.phtml');
    }
    
    public function loadAbout() { // need to fix
        $etomite = new etomite();
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
        $params = array();
        $Resource = new Resource();
        if ($Resource->saveResource($type)) {
            if ($Resource->lastId && $Resource->lastId > 0) {
                $params = array('id'=>$Resource->lastId);
            }
            $this->respond(true, $Resource->_lang['resource_saved'], $params);
        } else {
            $this->respond(false, $Resource->_lang['resource_not_saved']);
        }
    }
    
    public function deleteResource() {
        $this->validateRequest(array('id','type'));
        $Resource = new Resource();
        if ($Resource->deleteResource((int)$_REQUEST['id'], $_REQUEST['type'])) {
            $this->respond(true, $Resource->_lang['resource_deleted']);
        } else {
            $this->respond(false, $Resource->_lang['resource_not_deleted']);
        }
    }
    
    public function checkResourceName() {
        $Resource = new Resource();
        // todo no contain
        $against = array(" ","'",'"',"&","@","!","#","$","%","^","*","(",")","+","=");
        foreach($against as $a) {
            if (strpos($_REQUEST['name'], $a) !== false) {
                $this->respond(false, $Resource->_lang['resource_name_error_start'].$a.$Resource->_lang['resource_name_error_end']);
            }
        }
        $good = $Resource->checkResourceName($_REQUEST['name'], $_REQUEST['type'], (isset($_REQUEST['id']) && !empty($_REQUEST['id'])) ? (int)$_REQUEST['id'] : false);
        if ($good) {
            $this->respond(true, $Resource->_lang["resource_name_good"]);
        } else {
            $this->respond(false, $Resource->_lang["resource_name_exists"]);
        }
    }
    
    public function createSection() {
        $this->validateRequest('name','description');
        $Resource = new Resource();
        if ($Resource->createSection($_REQUEST['name'], $_REQUEST['description'])) {
            $this->respond(true, $Resource->_lang['created']);
        } else {
            $this->respond(false, $Resource->_lang["not_created"]);
        }
    }
    
    public function editTV() {
        $Resource = new Resource();
        $Resource->editTV();
    }
    
    public function saveTV() {
        $this->validateRequest('name','field_name','templates');
        $Resource = new Resource();
        if ($Resource->saveTV()) {
            $this->respond(true, $Resource->_lang["tv_saved"]);
        } else {
            $this->respond(false, $Resource->_lang['tv_not_saved']);
        }
    }
    
    public function deleteTV() {
        $this->validateRequest('tv_id');
        $Resource = new Resource();
        if ($Resource->deleteTV($_REQUEST['tv_id'])) {
            $this->respond(true, $Resource->_lang['tv_removed_msg']);
        } else {
            $this->respond(false, $Resource->_lang['tv_not_removed_msg']);
        }
    }
    
    public function loadUsersView() {
        $User = new User();
        $User->loadUsersView();
    }
    
    public function editUser() {
        $User = new User();
        $User->editUser();
    }
    
    public function checkUsername() {
        $User = new User();
        // todo no contain
        $against = array(" ","'",'"',"&","@","!","#","$","%","^","*","(",")","+","=");
        foreach($against as $a) {
            if (strpos($_REQUEST['username'], $a) !== false) {
                $this->respond(false, $User->_lang['username_error_start'].$a.$User->_lang['username_error_start']);
            }
        }
        $good = $User->checkUsername($_REQUEST['username'], (isset($_REQUEST['id']) && !empty($_REQUEST['id'])) ? (int)$_REQUEST['id'] : false);
        if ($good) {
            $this->respond(true, $User->_lang['username_good']);
        } else {
            $this->respond(false, $User->_lang['username_exists']);
        }
    }
    
    public function saveUser() {
        $this->validateRequest(array(
            'username',
            'fullname',
            'email'
        ));
        $params = array();
        
        $User = new User();
        if ($User->saveUser()) {
            if ($User->lastId && $User->lastId > 0) {
                $params = array('id'=>$User->lastId);
            }
            $this->respond(true, $User->_lang['created'], $params);
        } else {
            $this->respond(false, $User->_lang['not_created']);
        }
        
    }
    
    public function deleteUser() {
        $this->validateRequest(array('id'));
        $User = new User();
        if ($User->deleteUser((int)$_REQUEST['id'])) {
            $this->respond(true, $User->_lang['user_deleted']);
        } else {
            $this->respond(false, $User->_lang['user_not_deleted']);
        }
    }
    
    public function resetUserFailed() {
        $this->validateRequest(array('id'));
        $User = new User();
        if ($User->resetUserFailed((int)$_REQUEST['id'])) {
            $this->respond(true, $User->_lang["failed_login_reset"]);
        } else {
            $this->respond(false, $User->_lang["failed_login_not_reset"]);
        }
    }
    
    public function changeUserPassword() {
        $this->validateRequest(array('password'));
        $User = new User();
        if ($User->changeUserPassword($_REQUEST['password'])) {
            $this->respond(true, $User->_lang["password_changed"]);
        } else {
            $this->respond(false, $User->_lang["password_not_changed"]);
        }
    }
    
    public function editRole() {
        $User = new User();
        $User->editRole();
    }
    
    public function saveRole() {
        $this->validateRequest(array('name')); // name is required
        $User = new User();
        if ($User->saveRole()) {
            $this->respond(true, $User->_lang["roled_saved"]);
        } else {
            $this->respond(false, $User->_lang["roled_not_saved"]);
        }
    }
	
	public function loadGroupsView() {
        $User = new User();
        $User->loadGroupsView();
    }
	
	public function createGroup() {
		$this->validateRequest(array('name'));
		$User = new User();
		if ($User->editGroup(false, $_REQUEST['name'])) {
			$this->respond(true, '');
		}
		$this->respond(false, '');
	}
	
	public function editGroup() {
		$this->validateRequest(array('name','id'));
		$User = new User();
		if ($User->editGroup((int)$_REQUEST['id'], $_REQUEST['name'])) {
			$this->respond(true, '');
		}
		$this->respond(false, '');
	}
	
	public function deleteGroup() {
		$this->validateRequest(array('id'));
		$User = new User();
		if ($User->deleteGroup((int)$_REQUEST['id'])) {
			$this->respond(true, '');
		}
		$this->respond(false, '');
	}
    
    public function syncSite() {
        $System = new System();
        $System->syncSite();
    }
    
    public function showSiteSchedule() {
        $System = new System();
        $System->showSiteSchedule();
    }
    
    public function showSiteSettings() {
        $System = new System();
        $System->showSiteSettings();
    }
    
    public function saveSiteSettings() {
        $System = new System();
        if ($System->saveSiteSettings()) {
            $this->respond(true, $System->_lang["settings_saved"]);
        } else {
            $this->respond(false, $System->_lang["settings_not_saved"]);
        }
    }
    
    public function showVisitorStats() {
        $System = new System();
        $System->showVisitorStats();
    }
    
    public function showOnlineVisitors() {
        $System = new System();
        $System->showOnlineVisitors();
    }
    
    public function myMessages() {
        $User = new User();
        $User->myMessages();
    }
    
    public function sendMyMessage() {
        $this->validateRequest(array('sendto','user'));
        $User = new User();
        if ($User->sendMyMessage()) {
            $this->respond(true, $User->_lang['msg_sent']);
        } else {
            $this->respond(false, $User->_lang['msg_sent_error']);
        }
    }
    
    public function deleteMyMessage() {
        $this->validateRequest('id');
        $User = new User();
        if ($User->deleteMyMessage($_REQUEST['id'], $_SESSION['internalKey'])) {
            $this->respond(true, $User->_lang["msg_deleted"]);
        } else {
            $this->respond(false, $User->_lang["msg_not_deleted"]);
        }
    }

}

$action_server = new ActionServer();
$action_server->validateRequest(array('action'));

$action = $_REQUEST['action'];
$action_server->$action();

?>
