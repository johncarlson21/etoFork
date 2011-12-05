<?php
define("IN_ETOMITE_SYSTEM", true);
include('includes/bootstrap.php');

class User extends etomiteExtender {
    
    public function loadUsersView(){
        include_once('views/users.phtml');
    }
    
    public function editUser() {
        $id = (isset($_REQUEST['id']) && !empty($_REQUEST['id']) && is_numeric($_REQUEST['id'])) ? (int)$_REQUEST['id'] : false;
        if ($id ) {
            $userdata = $this->getUser($id);
        } else {
            $userdata = array();
        }
        include('views/edit_user.phtml');
    }
}

?>