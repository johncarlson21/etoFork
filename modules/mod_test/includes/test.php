<?php

// to use the etomite class just include it.
define("IN_ETOMITE_SYSTEM", true);
// just put the path to the bootstrap file
require_once('../../../manager/includes/bootstrap.php');

// start the etomite class
$etomite = new etomite();
// if this is a manager file be sure to use the checkManagerLogin() function
$etomite->checkManagerLogin();
// your set to go..

print_r($etomite->config);

echo "Hello there this is an included file that uses an iframe."

?>