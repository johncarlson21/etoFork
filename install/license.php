<?php
session_start();
foreach($_POST as $key => $val) {
	$_SESSION[$key] = $val;
}
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
    width : 210px;
    display : inline-block;
    font-weight: bold;
}
ul li { margin-top: 7px; }
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

<p>
Usage of this software is subject to the GPL license. To help you understand what the GPL licence is and how it affects your ability to use the software, we have provided the following summary:
</p>

<p>
	<b>The GNU General Public License is a Free Software license. </b>
	<br />
	Like any Free Software license, it grants to you the four following freedoms:<br />
	<ul style="text-align: justify;">
	<li>The freedom to run the program for any purpose. </li>
	<li>The freedom to study how the program works and adapt it to your needs. </li>
	<li>The freedom to redistribute copies so you can help your neighbor. </li>
	<li>The freedom to improve the program and release your improvements to the public, so that the whole community benefits. </li>
	</ul>
	<br />

	You may exercise the freedoms specified here provided that you comply with the express conditions of this license. The principal conditions are:<br />
	<ul style="text-align: justify;">
	<li>You must conspicuously and appropriately publish on each copy distributed an appropriate copyright notice and disclaimer of warranty and keep intact all the notices that refer to this License and to the absence of any warranty; and give any other recipients of the Program a copy of the GNU General Public License along with the Program. Any translation of the GNU General Public License must be accompanied by the GNU General Public License. </li>
	<li>If you modify your copy or copies of the program or any portion of it, or develop a program based upon it, you may distribute the resulting work provided you do so under the GNU General Public License. Any translation of the GNU General Public License must be accompanied by the GNU General Public License. </li>
	<li>If you copy or distribute the program, you must accompany it with the complete corresponding machine-readable source code or with a written offer, valid for at least three years, to furnish the complete corresponding machine-readable source code. </li>
	<li>Any of these conditions can be waived if you get permission from the copyright holder.</li>
	<li>Your fair use and other rights are in no way affected by the above.</li>
	</ul>
	<br />
	The above is a summary of the  GNU General Public License. By proceeding, you are agreeing to the GNU General Public Licence, not the above. The above is simply a summary of the GNU General Public Licence, and it's accuracy is not guaranteed. It is strongly recommended you read the <a href="http://www.gnu.org/copyleft/gpl.html" target="_blank">GNU General Public License</a> in full before proceeding, which can also be found in the LICENCE file distributed with Etomite.
</p>
<script>
function ableButton() {
	check = document.getElementById("licenseOK");
	button = document.getElementById("submit");

	if(check.checked==true) {
		button.disabled = false;
	} else {
		button.disabled = true;
	}

}
</script>
            <div>
            <form action="installNewDB.php" method="post">
                <input type="checkbox" id="licenseOK" name="licenseOK" onClick="ableButton()" /><label for="licenseOK">I agree to the terms set out in this license.</label>
                <input type="submit" id="submit" value="Proceed" disabled="disabled" class="button" style="float: none;" />
            </form>
            </div>
            </div>
        </div>
    </div>
</body>
</html>