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

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
  "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <title>Etomite &raquo; Install</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <style type="text/css">
    @import url('../assets/site/style.css');
  </style>
  <script type="text/javascript" src="extLinks.js"> </script>
</head>

<body>
<table border="0" cellpadding="0" cellspacing="0" class="mainTable">
  <tr class="fancyRow">
    <td><span class="headers">&nbsp;<img src="../manager/images/misc/dot.gif" alt="" style="margin-top: 1px;" />&nbsp;Etomite <?php echo $code_name." v".$release; ?> ( Updated Code from John Carlson )</span></td>
    <td align="right"><span class="headers">Installation</span></td>
  </tr>
  <tr class="fancyRow2">
    <td colspan="2" class="border-top-bottom smallText" align="right">&nbsp;</td>
  </tr>
  <tr align="left" valign="top">
    <td class="pad" id="content" colspan="2" style="padding:1em 2em;">

      <h1><b>Reminder and Installation Selection</b></h1>

      <p>If you have not already done so, please take the time to read the <a class="external" href="README.html" title="Click to read this file now.">README</a> file. There is also a text version located in the root directory where this package was installed. Doing so could eliminate the possibility of encountering un-needed problems that may occur during your new (or upgrade) installation. If you have any questions about which of the options listed below best suits your needs, please visit the <a class="external" href="http://www.etomite.com/forums" title="Get Help Now" target="_blank">Etomite Forums</a> now. It is much easier to help you <i>before</i> you get into trouble.</p>

      <p><b>NOTE:</b> If you will be performing a <b>New installation</b> you must first rename <u>or</u> copy <span class='mono'>manager/includes/config.php</span> to <span class='mono'>manager/includes/config.inc.php</span> and change permissions to writable before proceeding.</p>

      <p><b>Please choose your installation type:</b></p>

      <p><a href="installStart.php?installationType=full" title="Perform Full Install Now">New installation (Full)</a> - Includes an assortment of sample resources to demonstrate how Etomite can be implemented.</p>
	  <p>This installation has updates/extensions to the core to allow for zend framework like urls, versioning ( documents, snippets and chunks), meta information on documents, sections for snippets and chunks to organize them, and Template Variables.</p>
      
      <p><a href="repairConfig.php" title="Repair site configuration file">Repair Configuration File</a> - repair your config file after moving your site to a new server!</p>

    </td>
  </tr>
  <tr class="fancyRow2">
    <td class="border-top-bottom smallText">&nbsp;</td>
    <td class="border-top-bottom smallText" align="right">&nbsp;</td>
  </tr>
</table>
</body>
</html>
