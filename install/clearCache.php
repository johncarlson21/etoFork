<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
  "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <title>etoFork - Installation</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style type="text/css">
      @import url('../assets/site/style.css');
      .ok
      {
        color:green;
        font-weight: bold;
      }
      .notok
      {
        color:red;
        font-weight: bold;
      }
      .labelHolder
      {
        width : 180px;
        float : left;
        font-weight: bold;
      }
    </style>
  <script type="text/javascript" src="extLinks.js"> </script>
</head>

<body>
<table border="0" cellpadding="0" cellspacing="0" class="mainTable">
  <tr class="fancyRow">
    <td><span class="headers">&nbsp;<img src="../manager/images/misc/dot.gif" alt="" style="margin-top: 1px;" />&nbsp;etoFork <?php echo $code_name." v".$release; ?></span></td>
    <td align="right"><span class="headers">Etomite 1.x Upgrade - Refresh Site Cache</span></td>
  </tr>
  <tr class="fancyRow2">
    <td colspan="2" class="border-top-bottom smallText" align="right">&nbsp;</td>
  </tr>
  <tr align="left" valign="top">
    <td colspan="2"><table width="100%"  border="0" cellspacing="0" cellpadding="1">
      <tr align="left" valign="top">
        <td class="pad" id="content" colspan="2">
        		<?php
					if (!isset($_POST['submit']) || !isset($_POST['clearCache']) || $_POST['clearCache'] != 1) {
						// did not get here from the upgrade
						echo "<p><span class='notok'>Failed!</span> - You did not get here from the upgrade process!</p>";
					} else {
						// clear cach
						// refresh site cache
						  include("../manager/includes/config.inc.php");
						  include_once('../manager/models/etomite.class.php');
						  $etomite = new etomite;
						  $etomite->syncsite();
						  echo "<p><span class='ok'>OK!</span> - Site Cache Successfully Cleared!</p>";
						  echo '<p>Once logged in, please re-check your configuration. Administration -> Configuration</p>
      <p>Please login to the <a href="../manager">manager</a>.</p>';
					}
				?>
            </td>
      </tr>
    </table></td>
  </tr>
  <tr class="fancyRow2">
    <td class="border-top-bottom smallText">&nbsp;</td>
    <td class="border-top-bottom smallText" align="right">&nbsp;</td>
  </tr>
</table>
</body>
</html>