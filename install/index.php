<?php
// index.php
// startup file for the install/upgrade process
// Modified 2008-04-08 [v1.0] by Ralph Dahlgren
// Modified 2008-05-08 [v1.1] by Ralph Dahlgren

// start the PHP session
session_start();
$_SESSION['session_test'] = 1;
include("../manager/includes/version.inc.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>etoFork - Installation</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta name="robots" content="noindex, nofollow" />
<link type="text/css" href="../manager/css/smoothness/jquery-ui-1.8.16.custom.css" rel="stylesheet" />
<link href="../manager/css/manager_style.css" media="screen" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>
<script type="text/javascript" src="../manager/js/etomite.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    Etomite.inAdmin = false;
    Etomite.init();
});
</script>
<style type="text/css">
.error {
    font-size: 11px;
    color: #F00;
    text-align: center;
    font-weight: bold;
}
</style>
<style type="text/css">
.ok { color:green; }
.notok { color:red; }
.labelHolder {
    width : 180px;
    display : inline-block;
    font-weight: bold;
}
</style>
<script type="text/javascript" src="extLinks.js"> </script>
</head>
<body id="installPage">
    <div class="header">etoFork - Installation</div>
    <div class="wrapper" id="mainContent">
        <div class="login-box" style="margin: 20px 0;">
            <div class="login-info">
                <div class="eto-logo"><img src='../manager/images/misc/etofork_logo.png' alt='<?php echo $release; ?>' title='<?php echo $release; ?>' /></div>
                <div class="eto-login-msg"></div>
                <div style="clear:both"></div>
            </div>
            <div class="eto-install-msg">
            	<h2>Reminder and Installation Selection</h2>

                <p>If you have not already done so, please take the time to read the <a class="external" href="README.html" title="Click to read this file now.">README</a> file. There is also a text version located in the root directory where this package was installed. Doing so could eliminate the possibility of encountering un-needed problems that may occur during your new (or upgrade) installation. If you have any questions about which of the options listed below best suits your needs, please visit the <a class="external" href="http://www.etomite.com/forums" title="Get Help Now" target="_blank">Etomite Forums</a> now. It is much easier to help you <i>before</i> you get into trouble.</p>
                
                <p><b>NOTE:</b> If you will be performing a <b>New installation</b> you must first rename <u>or</u> copy <span class='mono'>manager/includes/config.php</span> to <span class='mono'>manager/includes/config.inc.php</span> and change permissions to writable before proceeding.</p>
                
                <p><b>Please choose your installation type:</b></p>
                
                <p><a class="new-install-btn button" href="installStart.php?installationType=full" title="Perform Full Install Now">New installation (Full)</a></p><blockquote><p>Includes an assortment of sample resources to demonstrate how Etomite can be implemented.</p>
                <p>This installation has updates/extensions to the core to allow for zend framework like urls, versioning ( documents, snippets and chunks), meta information on documents, sections for snippets and chunks to organize them, and Template Variables.</p></blockquote>
                
                <p><a class="repair-btn button" href="repairConfig.php" title="Repair site configuration file">Repair Configuration File</a></p><blockquote><p>Repair your config file after moving your site to a new server!</p></blockquote>

            </div>
        </div>
    </div>
</body>
</html>