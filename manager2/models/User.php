<?php
define("IN_ETOMITE_SYSTEM", true);
include('includes/bootstrap.php');

class User extends etomiteExtender {
    
    public function loadUsersView(){
        include_once('views/users.phtml');
    }
}

?>