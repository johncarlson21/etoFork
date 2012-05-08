<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
  "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <title>Etomite &raquo; Install</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <style type="text/css">
    @import url('../assets/site/style.css');
    ul li { margin-top: 7px; }
    .ok { color : green; font-weight: bold; }
    .notok { color : red; font-weight: bold; }
    .labelHolder {
      width : 180px;
      float : left;
      font-weight: bold;
    }
  </style>
</head>

<body>
<table border="0" cellpadding="0" cellspacing="0" class="mainTable">
  <tr class="fancyRow">
    <td><span class="headers">&nbsp;<img src="../manager/images/misc/dot.gif" alt="" style="margin-top: 1px;" />&nbsp;Etomite</span></td>
    <td align="right"><span class="headers">Repair Config File</span></td>
  </tr>
  <tr class="fancyRow2">
    <td colspan="2" class="border-top-bottom smallText" align="right">&nbsp;</td>
  </tr>
  <tr align="left" valign="top">
    <td colspan="2"><table width="100%"  border="0" cellspacing="0" cellpadding="1">
      <tr align="left" valign="top">
        <td class="pad" id="content" colspan="2">

<?php
include_once("../manager/includes/config.inc.php"); // include old install to grab the original db info
if (isset($_POST['submit']) && $_POST['repair'] == 1){

    // attempt to write the manager/includes/config.inc.php file
    
    echo "<p>Writing configuration file: ";
    // read in the config.inc.php template
    $filename = "./config.inc.php";
    $handle = fopen($filename, "r");
    $contents = fread($handle, filesize($filename));
    fclose($handle);
    
    // perform global search and replace of tags in the SQL
    $self = str_replace("/install/", "", $_SERVER["PHP_SELF"]);
    $self = str_replace(basename(__FILE__), "", $self);
    $urlPieces = explode("/", $self);
    
    $www = $_SERVER['HTTP_HOST'];
    $www_url = $www . implode("/", $urlPieces);
    $relative_path = implode("/", $urlPieces);
    
    // update db info from form else keep original value
    $database_server = isset($_POST['databasehost']) && !empty($_POST['databasehost']) ? $_POST['databasehost'] : $database_server;
    $database_user = isset($_POST['databaseloginname']) && !empty($_POST['databaseloginname']) ? $_POST['databaseloginname'] : $database_user;
    $database_password = isset($_POST['databaseloginpassword']) && !empty($_POST['databaseloginpassword']) ? $_POST['databaseloginpassword'] : $database_password;
    $dbase = isset($_POST['databasename']) && !empty($_POST['databasename']) ? $_POST['databasename'] : $dbase;
    $table_prefix = isset($_POST['tableprefix']) && !empty($_POST['tableprefix']) ? $_POST['tableprefix'] : $table_prefix;
    
    $search = array('{HOST}','{USER}','{PASS}','{DBASE}','{PREFIX}','{ABSOLUTE_PATH}','{WWW_PATH}','{RELATIVE_PATH}');
    $replace = array($database_server,$database_user,$database_password,str_replace("`", "", $dbase),$table_prefix,dirname(dirname(__FILE__)),$www_url,);
    $configString = str_replace($search,$replace,$contents);
    
    // open config.inc.php
    $filename = '../manager/includes/config.inc.php';
    $configFileFailed = false;
    if (@!$handle = fopen($filename, 'w'))
    {
      $configFileFailed = true;
    }
    
    // write $configString to our opened file.
    if(@fwrite($handle, $configString) === FALSE)
    {
      $configFileFailed = true;
    }
    @fclose($handle);
    
    if ($configFileFailed) {
        ?>
            <p><span class='notok'>Failed!</span></p>
            <p>There was an error trying to write to your config.inc.php file! Make sure you have set write permissions to the file! (chmod 777)</p>
            <p>Try Again?</p>
            <script type="text/javascript">
  function validate() {
    var f = document.myForm;
    if(f.databasename.value=="") {
      alert('You need to enter a value for database name!');
      return false;
    }
    if(f.databasehost.value=="") {
      alert('You need to enter a value for database host!');
      return false;
    }
    if(f.databaseloginname.value=="") {
      alert('You need to enter your database login name!');
      return false;
    }
    if(f.cmsadmin.value=="") {
      alert('You need to enter a username for the Etomite admin account!');
      return false;
    }
    if(f.cmspassword.value=="") {
      alert('You need to a password for the Etomite admin account!');
      return false;
    }
    if(f.cmspassword.value!=f.cmspasswordconfirm.value) {
      alert('The administrator password and the confirmation don\'t match!');
      return false;
    }
    return true;
  }
</script>
                <form action="repairConfig.php" method="post" name="myForm" onsubmit="return validate()">
                <p>Below are your default values for your MySQL configuration. Please update them if your mysql information has changed.</p>
                <p><span class="labelHolder"><label for="databasename">Database name:</label></span><input type="text" id="databasename" name="databasename" style="width:200px" value="<?php echo $_POST['databasename'];?>" /><br />
                <span class="labelHolder"><label for="tableprefix">Table prefix:</label></span><input type="text" id="tableprefix" name="tableprefix" style="width:200px" value="<?php echo $_POST['table_prefix']; ?>" /></p>
                <p>Now please enter the login data for your database.</p>
                <p><span class="labelHolder"><label for="databasehost">Database host:</label></span><input type="text" id="databasehost" name="databasehost" value="<?php echo $_POST['databasehost']; ?>" style="width:200px" /><br />
                <span class="labelHolder"><label for="databaseloginname">Database login name:</label></span><input type="text" id="databaseloginname" name="databaseloginname" style="width:200px" value="<?php echo $_POST['databaseloginname']; ?>" /><br />
                <span class="labelHolder"><label for="databaseloginpassword">Database password:</label></span><input type="password" id="databaseloginpassword" name="databaseloginpassword" style="width:200px" value="<?php echo $_POST['databaseloginpassword']; ?>" /></p>
                
            <input type="hidden" name="repair" value="1" />
            <p><input type="submit" name="submit" value="REPAIR" /></p>
        </form>
        <?php
    } else {
        ?>
        <p><span class='ok'>OK!</span></p>
        <p>The New Configuration File has been created! You can now log into the <a href="../manager/"><b><u>Etomite manager</u></b></a>. </p><p>Please make sure you CHMOD the config.inc.php file so it is not writeable by anyone other than yourself... Also, don't forget to remove the installer folder, as it is no longer needed.</p>
        <?php
    }
} else {
    // show form
    
    ?>
        <p>This script will recreate your config.inc.php file. Use this script if you have moved your site to another server!</p>
        <script type="text/javascript">
  function validate() {
    var f = document.myForm;
    if(f.databasename.value=="") {
      alert('You need to enter a value for database name!');
      return false;
    }
    if(f.databasehost.value=="") {
      alert('You need to enter a value for database host!');
      return false;
    }
    if(f.databaseloginname.value=="") {
      alert('You need to enter your database login name!');
      return false;
    }
    if(f.cmsadmin.value=="") {
      alert('You need to enter a username for the Etomite admin account!');
      return false;
    }
    if(f.cmspassword.value=="") {
      alert('You need to a password for the Etomite admin account!');
      return false;
    }
    if(f.cmspassword.value!=f.cmspasswordconfirm.value) {
      alert('The administrator password and the confirmation don\'t match!');
      return false;
    }
    return true;
  }
</script>
                <form action="repairConfig.php" method="post" name="myForm" onsubmit="return validate()">
                <p>Below are your default values for your MySQL configuration. Please update them if your mysql information has changed.</p>
                <p><span class="labelHolder"><label for="databasename">Database name:</label></span><input type="text" id="databasename" name="databasename" style="width:200px" value="<?php echo str_replace("`", "", $dbase);?>" /><br />
                <span class="labelHolder"><label for="tableprefix">Table prefix:</label></span><input type="text" id="tableprefix" name="tableprefix" style="width:200px" value="<?php echo $table_prefix; ?>" /></p>
                <p>Now please enter the login data for your database.</p>
                <p><span class="labelHolder"><label for="databasehost">Database host:</label></span><input type="text" id="databasehost" name="databasehost" value="<?php echo $database_server; ?>" style="width:200px" /><br />
                <span class="labelHolder"><label for="databaseloginname">Database login name:</label></span><input type="text" id="databaseloginname" name="databaseloginname" style="width:200px" value="<?php echo $database_user; ?>" /><br />
                <span class="labelHolder"><label for="databaseloginpassword">Database password:</label></span><input type="password" id="databaseloginpassword" name="databaseloginpassword" style="width:200px" value="<?php echo $database_password; ?>" /></p>
                
            <input type="hidden" name="repair" value="1" />
            <p><input type="submit" name="submit" value="REPAIR" /></p>
        </form>
    <?php
}
?>

</td>
      </tr>
     </table>
    </td>
  </tr>
  <tr class="fancyRow2">
    <td class="border-top-bottom smallText">&nbsp;</td>
    <td class="border-top-bottom smallText" align="right">&nbsp;</td>
  </tr>
</table>
</body>
</html>