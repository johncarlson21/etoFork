<?php
if (!defined(CONFIG_LOADED)) {
    define("IN_ETOMITE_SYSTEM", true);
    include('includes/bootstrap.php');
}

class User extends etomite {
    public $lastId = false;
    public $errors;
    
    public function __construct() {
        if (!defined(CONFIG_LOADED)) {
            parent::__construct();
            $this->checkManagerLogin();
        }
    }
    
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
        $mailmessages = (isset($_REQUEST['mailmessages']) && !empty($_REQUEST['mailmessages'])) ? (int)$_REQUEST['mailmessages'] : 0;
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
            'blocked' => $blocked,
            'mailmessages' => $mailmessages
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
    
    public function changeUserPassword($password) {
        if ($user = $this->userLoggedIn()) {
            if ($this->updIntTableRows(array('password'=>md5($password)), 'manager_users', 'id='.$user['id'])) {
                return true;
            }
            return false;
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
    
    public function saveRole() {
        $id = (isset($_REQUEST['id']) && !empty($_REQUEST['id']) && is_numeric($_REQUEST['id'])) ? (int)$_REQUEST['id'] : false;
        $reqFields = $_REQUEST;
        unset($reqFields['id'], $reqFields['action']);
        $resF = $this->dbQuery('SHOW COLUMNS FROM '.$this->db.'user_roles WHERE Field != "id" AND Field != "name" AND Field != "description"');
        $fields = array(); // list of table fields to use
        while($row = $this->fetchRow($resF)) {
            $fields[] = $row['Field'];
        }
        $data = array();
        $data['name'] = $reqFields['name'];
        $data['description'] = $reqFields['description'];
        unset($reqFields['name'], $reqFields['description']);
        foreach($fields as $f) {
            $data[$f] = (isset($reqFields[$f]) && ($reqFields[$f] == 1 || $reqFields[$f] == 'on') ) ? 1 : 0;
        }
        if ($id) {
            if ($this->updIntTableRows($data, 'user_roles', 'id='.$id)) {
                return true;
            }
        } else {
            if ($this->putIntTableRow($data, 'user_roles')) {
                return true;
            }
        }
        return false;
    }
    
    public function myMessages() {
        include_once('views/messages.phtml');
    }
    
    public function sendMyMessage() {
        $db = $this->db;
        $sendto = $_REQUEST['sendto'];
        $userid = $_REQUEST['user'];
        $groupid = $_REQUEST['group'];
        $subject = addslashes($_REQUEST['messagesubject']);
        if($subject == "") $subject = "(no subject)";
        $message = addslashes($_REQUEST['messagebody']);
        if($message == "") $message = "(no message)";
        $postdate = time();
        
        $footerInfo = "<hr /><p>&nbsp;</p><p>Login to the <a href='".MANAGER_URL."'>MANAGER</a> to view this message</p>" .
            "<p>If the link above doesn't work, copy and past this link: ".MANAGER_URL." into your browser!</p>" .
        	"<p align='center'><strong><em>Do not reply to this message, it will go nowhere!</em></strong></p>";
        
        if($sendto == 'u') {
          if($userid == 0) {
            return false;
          }
          $sql = "INSERT INTO ".$db."user_messages SET
            type = 'Message',
            subject = '$subject',
            message = '$message',
            sender = ". $_SESSION['internalKey'].",
            recipient = $userid,
            private = 1,
            postdate = $postdate,
            messageread = 0;
          ";
          $rs = $this->dbQuery($sql);
          // put in sent message
          $rs = $this->dbQuery("INSERT INTO ".$db."user_messages SET
			type = 'Message Sent',
			subject = '$subject',
			message = '$message',
			sender = ".$_SESSION['internalKey'].",
			recipient = $userid,
			private = 1,
			postdate = $postdate,
			messageread = 1;
          ");
          // send mail if nessesary
          $curUser = $this->getUser($_SESSION['internalKey']);
          $user = $this->getUser($userid);
          if($user['mailmessages'] == 1) {
              $message = "<p><strong>User:</strong> ".$curUser['fullname']." has sent you a message</p>".
                  "<p><strong>Subject:</strong> ".$subject."</p><p><strong>Message:</strong></p>".$message.$footerInfo;
              $to = array(array('email'=>$user['email'], 'name'=>$user['fullname']));
              $this->sendMessageToUser($to, $message);
          }
          return true;
        }
        
        if($sendto == 'g') {
          if($groupid == 0) {
            return false;
          }
          $sql = "SELECT internalKey FROM ".$db."user_attributes WHERE ".$db."user_attributes.role=$groupid;";
          $rs = $this->dbQuery($sql);
          $limit = $this->recordCount($rs);
          $curUser = $this->getUser($_SESSION['internalKey']);
          for( $i=0; $i<$limit; $i++ ){
            $row=$this->fetchRow($rs);
            if($row['internalKey']!=$_SESSION['internalKey']) {
              $sql2 = "INSERT INTO ".$db."user_messages SET
                type = 'Message',
                subject = '$subject',
                message = '$message',
                sender = ".$_SESSION['internalKey'].",
                recipient = ".$row['internalKey'].",
                private = 0,
                postdate = $postdate,
                messageread = 0;
              ";
              $rs2 =  $this->dbQuery($sql2);
              // send mail if nessesary
              $user = $this->getUser($row['internalKey']);
              if($user['mailmessages'] == 1) {
                  $sendmessage = "<p><strong>User:</strong> ".$curUser['fullname']." has sent you a message</p>".
                      "<p><strong>Subject:</strong> ".$subject."</p><p><strong>Message:</strong></p>".$message.$footerInfo;
                  $to = array(array('email'=>$user['email'], 'name'=>$user['fullname']));
                  $this->sendMessageToUser($to, $sendmessage);
              }
            }
            
          }
          // put in sent message
          $rs = $this->dbQuery("INSERT INTO ".$db."user_messages SET
			type = 'Message Sent; group|$groupid',
			subject = '$subject',
			message = '$message',
			sender = ".$_SESSION['internalKey'].",
			recipient = 0,
			private = 0,
			postdate = $postdate,
			messageread = 1;
          ");
          return true;
        }
        
        if($sendto == 'a') {
          $sql = "SELECT id FROM ".$db."manager_users;";
          $rs = $this->dbQuery($sql);
          $limit = $this->recordCount($rs);
          $curUser = $this->getUser($_SESSION['internalKey']);
          for( $i=0; $i<$limit; $i++ ){
            $row=$this->fetchRow($rs);
            if($row['id'] != $_SESSION['internalKey']) {
              $sql2 = "INSERT INTO ".$db."user_messages SET
                type = 'Message',
                subject = '$subject',
                message = '$message',
                sender = ".$_SESSION['internalKey'].",
                recipient = ".$row['id'].",
                private = 0,
                postdate = $postdate,
                messageread = 0;
              ";
              $rs2 =  $this->dbQuery($sql2);
              // send mail if nessesary
              $user = $this->getUser($row['id']);
              if($user['mailmessages'] == 1) {
                  $sendmessage = "<p><strong>User:</strong> ".$curUser['fullname']." has sent you a message</p>".
                      "<p><strong>Subject:</strong> ".$subject."</p><p><strong>Message:</strong></p>".$message.$footerInfo;
                  $to = array(array('email'=>$user['email'], 'name'=>$user['fullname']));
                  $this->sendMessageToUser($to, $sendmessage);
              }
            }
          }
          // put in sent message
          $rs = $this->dbQuery("INSERT INTO ".$db."user_messages SET
			type = 'Message Sent; everyone',
			subject = '$subject',
			message = '$message',
			sender = ".$_SESSION['internalKey'].",
			recipient = 0,
			private = 0,
			postdate = $postdate,
			messageread = 1;
          ");
          return true;
        }
        return false;
    }
    
    public function deleteMyMessage($id, $userid) {
        if (!empty($id) && is_numeric($id) && !empty($userid) && is_numeric($userid)) {
            $this->dbQuery("DELETE FROM ".$this->db."user_messages WHERE id = '".$id."' AND (recipient = '".$userid."' OR sender = '".$userid."')");
            return true;
        }
        return false;
    }
    
}

?>