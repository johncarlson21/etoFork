<?php
define("IN_ETOMITE_SYSTEM", true);
include('includes/bootstrap.php');

class User extends etomiteExtender {
    public $lastId = false;
    public $errors;
    
    public function loadUsersView(){
        include_once('views/users.phtml');
    }
    
    public function editUser() {
        $id = (isset($_REQUEST['id']) && !empty($_REQUEST['id']) && is_numeric($_REQUEST['id'])) ? (int)$_REQUEST['id'] : false;
        if ($id) {
            $userdata = $this->getUser($id);
        } else {
            $userdata = array();
        }
        include('views/edit_user.phtml');
    }
    
    public function checkUsername($username, $id=false) {
        if (empty($username)) {
            return true;
        }
        if ($id) {
            $where = 'username = "' . $username . '" AND id != ' . $id;
        } else {
            $where = 'username = "' . $username . '"';
        }
        if ($result = $this->getIntTableRows("*", 'manager_users', $where)) {
            return false;
        } else {
            return true;
        }
    }
    
    public function saveUser() {
        $id = (isset($_REQUEST['id']) && !empty($_REQUEST['id']) && is_numeric($_REQUEST['id'])) ? (int)$_REQUEST['id'] : false;
        $username = (isset($_REQUEST['username']) && !empty($_REQUEST['username'])) ? mysql_escape_string($_REQUEST['username']) : '';
        $fullname = (isset($_REQUEST['fullname']) && !empty($_REQUEST['fullname'])) ? mysql_escape_string($_REQUEST['fullname']) : '';
        $email = (isset($_REQUEST['email']) && !empty($_REQUEST['email'])) ? mysql_escape_string($_REQUEST['email']) : '';
        $phone = (isset($_REQUEST['phone']) && !empty($_REQUEST['phone'])) ? mysql_escape_string($_REQUEST['phone']) : '';
        $mobilephone = (isset($_REQUEST['mobilephone']) && !empty($_REQUEST['mobilephone'])) ? mysql_escape_string($_REQUEST['mobilephone']) : '';
        $role = (isset($_REQUEST['role']) && !empty($_REQUEST['role'])) ? (int)$_REQUEST['role'] : '';
        $blocked = (isset($_REQUEST['blocked']) && !empty($_REQUEST['blocked'])) ? (int)$_REQUEST['blocked'] : 0;
        $password = (isset($_REQUEST['password']) && !empty($_REQUEST['password'])) ? mysql_escape_string($_REQUEST['password']) : '';
        $usergroups = (isset($_REQUEST['usergroups']) && !empty($_REQUEST['usergroups'])) ? $_REQUEST['usergroups'] : array();
        $mu_data = array(
            'username' => $username
        );
        $ua_data = array(
            'fullname' => $fullname,
            'email' => $email,
            'phone' => $phone,
            'mobilephone' => $mobilephone,
            'role' => $role,
            'blocked' => $blocked
        );
        
        if (isset($password) && !empty($password)) {
            $mu_data['password'] = md5($password);
        }
        
        if ($id) {
            if ($oldUser = $this->getUser($id)) {
                if ($blocked == 1 && $oldUser['blocked'] != 1) {
                    $ua_data['blockeduntil'] = (time()-1);
                } elseif ($blocked == 0) {
                    $ua_data['blockeduntil'] = 0;
                }
                
                if ($result = $this->updIntTableRows($mu_data, 'manager_users', 'id='.$id)) {
                    $res2 = $this->updIntTableRows($ua_data, 'user_attributes', 'internalKey='.$id);
                    // first remove groups for member and then re-add
                    $this->dbQuery("DELETE FROM ".$this->db."member_groups WHERE member=".$id);
                    // add new groups if needed
                    if(count($usergroups)>0) {
                        foreach($usergroups as $ug) {
                            $rs = $this->putIntTableRow(array('user_group'=>$ug,'member'=>$id), 'member_groups');
                        }
                    }
                    return true;
                }
            }
            return false;
        } else {
            if ($result = $this->putIntTableRow($mu_data, 'manager_users')) {
                $insId = $this->insertId();
                $this->lastId = $insId;
                $ua_data['internalKey'] = $insId;
                $res2 = $this->putIntTableRow($ua_data, 'user_attributes');
                // first remove groups for member and then re-add
                $this->dbQuery("DELETE FROM ".$this->db."member_groups WHERE member=".$insId);
                // add new groups if needed
                if(count($usergroups)>0) {
                    foreach($usergroups as $ug) {
                        $rs = $this->putIntTableRow(array('user_group'=>$ug,'member'=>$insId), 'member_groups');
                    }
                }
                return true;
            }
            return false;
        }
    }
    
    public function deleteUser($id=false) {
        if ($id) {
            // remove user from manager_users
            $this->dbQuery("DELETE FROM ".$this->db."manager_users WHERE id=".$id." LIMIT 1");
            $this->dbQuery("DELETE FROM ".$this->db."user_attributes WHERE internalKey=".$id." LIMIT 1");
            $this->dbQuery("DELETE FROM ".$this->db."member_groups WHERE member=".$id);
            // reset users created documents to the admin
            if ($userdocs = $this->getIntTableRows("*", 'site_content', 'createdby='.$id)) {
                if (count($userdocs) > 0) {
                    foreach($userdocs as $ud) {
                        $this->updIntTableRows(array('createdby'=>1), 'site_content', 'id='.$ud['id']);
                    }
                }
            }
            return true;
        }
        return false;
    }
    
    public function resetUserFailed() {
        $id = (isset($_REQUEST['id']) && !empty($_REQUEST['id']) && is_numeric($_REQUEST['id'])) ? (int)$_REQUEST['id'] : false;
        if ($user = $this->getUser($id)) {
            $this->updIntTableRows(array('blocked'=>'0', 'blockeduntil'=>'0', 'failedlogincount'=>'0'), 'user_attributes', 'id='.$id);
            return true;
        }
        return false;
    }
    
    /* ######### ROLE MANAGEMENT ############# */
    
    public function editRole() {
        $id = (isset($_REQUEST['id']) && !empty($_REQUEST['id']) && is_numeric($_REQUEST['id'])) ? (int)$_REQUEST['id'] : false;
        if ($id) {
            $r = $this->getIntTableRows('*', 'user_roles', 'id='.$id);
            $roledata = $r[0];
        } else {
            $roledata = array();
        }
        include('views/edit_role.phtml');
    }
    
}

?>