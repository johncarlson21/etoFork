<?php
// resources.dynamic.action.php
// Modified 2008-04-04 [v1.0] by Ralph Dahlgren
// Modified 2008-05-10 [v1.1] by Ralph Dahlgren
// - HTML markup improvements

if(IN_ETOMITE_SYSTEM!="true") die($_lang["include_ordering_error"]);
?>

<!-- Header -->
<div class="subTitle">
  <span class="floatRight">
    <img src="media/images/_tx_.gif" width="1" height="5"><br />
    <?php echo $site_name ;?> - <?php echo $_lang['resource_management']; ?>
  </span>
</div>

<script type="text/javascript">
function checkIM() {
  im_on = document.settings.im_plugin[0].checked; // check if im_plugin is on
  if(im_on==true) {
    showHide(/imRow/, 1);
  }
}

function showHide(what, onoff){

  var all = document.getElementsByTagName( "*" );
  var l = all.length;
  var buttonRe = what;
  var id, el, stylevar;

  if(onoff==1) {
    stylevar = "<?php echo $displayStyle; ?>";
  } else {
    stylevar = "none";
  }

  for ( var i = 0; i < l; i++ ) {
    el = all[i]
    id = el.id;
    if ( id == "" ) continue;
    if (buttonRe.test(id)) {
      el.style.display = stylevar;
    }
  }
}
</script>

<!--<link type="text/css" rel="stylesheet" href="media/style/tabs.css" />-->
<script type="text/javascript" src="media/script/tabpane.js"></script>

<script type="text/javascript" src="frames/scriptaculous/prototype.js" ></script>
<script type="text/javascript" src="frames/scriptaculous/scriptaculous.js" ></script>
<script type="text/javascript" src="frames/scriptaculous/effects.js" ></script>


<div class="tab-pane" id="resourcesPane">

<script type="text/javascript">
  tpSettings = new WebFXTabPane(document.getElementById("resourcesPane"));
</script>

<!-- Template Management -->
<div class="tab-page" id="tabPage1">
  <div class="tab">
    <img src='media/images/misc/dot.gif' alt="." />&nbsp;<?php echo $_lang['manage_templates']; ?>
  </div>
  <script type="text/javascript">
    tpSettings.addTabPage(document.getElementById("tabPage1"));
  </script>
  <div class="sectionBody">
    <div class="menuHeader"><?php echo $_lang['template_management_msg']; ?></div>
      <br />
      <table>
        <tr>
          <td><img src="media/images/misc/li.gif"></td>
          <td>&nbsp;</td>
          <td><a href="index.php?a=19"><?php echo $_lang['new_template']; ?></a></td>
        </tr>
        <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>

<?php
    $sql = "select templatename, id, description, locked from $dbase.".$table_prefix."site_templates order by templatename";
    $rs = mysql_query($sql);
    $limit = mysql_num_rows($rs);
    if($limit<1){
      echo "<tr><td colspan=\"3\">{$_lang['no_results']}</td></tr>";
    }
    for($i=0; $i<$limit; $i++) {
      $row = mysql_fetch_assoc($rs);
      $id = strval($row['id']);
?>

      <tr style="padding:0; height:auto; margin:0; padding:0; border:none; ">
        <td style="vertical-align:top;">
          <img src="media/images/misc/li.gif">
        </td>
        <td style="text-align:right; width:auto; vertical-align:top; margin:0; padding:0 4px 0 4px; border:none;">
          <?php echo $row['id']; ?>
        </td>
        <td>
          <a href="index.php?id=<?php echo $row['id']; ?>&a=16"><?php echo $row['templatename']; ?></a><?php echo $row['description']!='' ? ' - '.$row['description'] : '' ; ?><?php echo $row['locked']==1 ? ' <i><small>('.$_lang['template_locked_message'].')</small></i>' : "" ; ?>
        </td>
      </tr>

<?php
    }
?>

    </table>

  </div>
</div>

<!-- Snippet Management -->
<div class="tab-page" id="tabPage2">
  <div class="tab">
    <img src='media/images/misc/dot.gif' alt="." />&nbsp;<?php echo $_lang['manage_snippets']; ?>
  </div>
  <script type="text/javascript">
    tpSettings.addTabPage(document.getElementById("tabPage2"));
  </script>

  <div class="sectionBody">
  <p class="menuHeader"><?php echo $_lang['snippet_management_msg']; ?></p>
  <br />
  <img src="media/images/misc/li.gif"> &nbsp; <a href="index.php?a=23"><?php echo $_lang['new_snippet']; ?></a> &nbsp;&nbsp;&nbsp; <img src="media/images/misc/li.gif"> &nbsp;<a href="index.php?a=220&section_type=snippet">New Section</a>
  <br /><br />

<?php
	// modified 2010-04-22 by John Carlson to add section function and organizaion of snippets
	$sqlS = "select * from $dbase.".$table_prefix."site_section where section_type='snippet' order by sort_order DESC,name ASC";
	$rsS = mysql_query($sqlS);
	$limitS = mysql_num_rows($rsS);
	if($limitS<1){
		echo $_lang['no_results'];
	}
	for($z=0;$z<$limitS;$z++){
		$rowS = mysql_fetch_assoc($rsS);
		
		// build box
	?>
		<div class="section-<?php echo $rowS['id']; ?>" style="margin:10px;border:#3399FF solid 1px;">
			<div class="subTitle"><div style="float:left;font-weight:bold;font-size:14px;padding:2px 5px;"><?php echo $rowS['name']." - ".$rowS['description']; ?></div><div style="float:right;font-size:14px;font-weight:bold;padding:2px 5px;"><a style="color:#FFF;" onclick="new Effect.toggle('section-<?php echo $rowS['id']; ?>-content','blind'); return false;" href="#">Minimize/Show</div><div style="clear:both;"></div></div>
			<div id="section-<?php echo $rowS['id']; ?>-content" style="padding:10px 20px;">
	<?
	// start snippets
		  $sql = "select name, id, description, locked from $dbase.".$table_prefix."site_snippets where section=".$rowS['id']." order by name";
		  $rs = mysql_query($sql);
		  $limit = mysql_num_rows($rs);
		  if($limit<1){
			echo $_lang['no_results'];
		  }
		  for($i=0; $i<$limit; $i++) {
			$row = mysql_fetch_assoc($rs);
		?>
		
			<div>
			  <img src="media/images/misc/li.gif"> &nbsp;
			  <a href="index.php?id=<?php echo $row['id']; ?>&a=22">
				<?php echo $row['name']; ?>
			  </a>
			  <?php echo $row['description']!='' ? ' - '.$row['description'] : '' ; ?>
			  <?php echo $row['locked']==1 ? ' <i><small>('.$_lang['snippet_locked_message'].')</small></i>' : "" ; ?>
			</div>

<?php
	
			} // end for for snippets
			
			// end snippets
?>
		</div><!-- end section content box -->
	</div><!-- end box for section -->
<?php
  }// end for for sections
?>

  </div>
</div>

<!-- Chunk Management -->
<div class="tab-page" id="tabPage3">
  <div class="tab">
    <img src='media/images/misc/dot.gif' alt="." />&nbsp;<?php echo $_lang['manage_htmlsnippets']; ?>
  </div>
  <script type="text/javascript">
    tpSettings.addTabPage(document.getElementById("tabPage3"));
  </script>

  <div class="sectionBody">
    <p class="menuHeader"><?php echo $_lang['htmlsnippet_management_msg']; ?></p>
    <br />
    <img src="media/images/misc/li.gif"> &nbsp; <a href="index.php?a=78"><?php echo $_lang['new_htmlsnippet']; ?></a> &nbsp;&nbsp;&nbsp; <img src="media/images/misc/li.gif"> &nbsp;<a href="index.php?a=220&section_type=chunk">New Section</a>
    <br /><br />

<?php
	// modified 2010-04-22 by John Carlson to add section function and organizaion of snippets
	$sqlS = "select * from $dbase.".$table_prefix."site_section where section_type='chunk' order by sort_order DESC,name ASC";
	$rsS = mysql_query($sqlS);
	$limitS = mysql_num_rows($rsS);
	if($limitS<1){
		echo $_lang['no_results'];
	}
	for($z=0;$z<$limitS;$z++){
		$rowS = mysql_fetch_assoc($rsS);
		
		// build box
	?>
		<div class="section-<?php echo $rowS['id']; ?>" style="margin:10px;border:#3399FF solid 1px;">
			<div class="subTitle"><div style="float:left;font-weight:bold;font-size:14px;padding:2px 5px;"><?php echo $rowS['name']." - ".$rowS['description']; ?></div><div style="float:right;font-size:14px;font-weight:bold;padding:2px 5px;"><a style="color:#FFF;" onclick="new Effect.toggle('section-<?php echo $rowS['id']; ?>-content','blind'); return false;" href="#">Minimize/Show</div><div style="clear:both;"></div></div>
			<div id="section-<?php echo $rowS['id']; ?>-content" style="padding:10px 20px;">

<?php
	// start chunks
    $sql = "select name, id, description, locked from $dbase.".$table_prefix."site_htmlsnippets where section=".$rowS['id']." order by name";
    $rs = mysql_query($sql);
    $limit = mysql_num_rows($rs);
    if($limit<1){
      echo $_lang['no_results'];
    }
    for($i=0; $i<$limit; $i++) {
      $row = mysql_fetch_assoc($rs);
?>

      <img src="media/images/misc/li.gif"> &nbsp; <span style="width:200px"><a href="index.php?id=<?php echo $row['id']; ?>&a=77"><?php echo $row['name']; ?></a></span><?php echo $row['description']!='' ? ' - '.$row['description'] : '' ; ?><?php echo $row['locked']==1 ? ' <i><small>('.$_lang['snippet_locked_message'].')</small></i>' : "" ; ?><br />

<?php
    } // end for for chunks
			// end snippets
?>
		</div><!-- end section content box -->
	</div><!-- end box for section -->
<?php
  }// end for for sections
?>

  </div>
</div>

<!-- Keywords Management -->
<div class="tab-page" id="tabPage4">
  <div class="tab">
    <img src='media/images/misc/dot.gif' alt="." />&nbsp;<?php echo $_lang['keywords']; ?>
  </div>
  <script type="text/javascript">
    tpSettings.addTabPage(document.getElementById("tabPage4"));
  </script>
  <div class="sectionBody">
    <span class="menuHeader"><?php echo $_lang['keywords_message']; ?></span>
    <br /><br />
    <?php include('manage_keywords.dynamic.action.php'); ?>

  </div>
</div>

</div> <!-- resourcesPane -->