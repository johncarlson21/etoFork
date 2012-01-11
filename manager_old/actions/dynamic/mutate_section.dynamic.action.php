<?php
if(IN_ETOMITE_SYSTEM != "true")
{
  die($_lang["include_ordering_error"]);
}
if($_SESSION['permissions']['edit_snippet'] != 1 && $_REQUEST['a'] == 22)
{
  $e->setError(3);
  $e->dumpError();
}

if($_SESSION['permissions']['new_snippet'] != 1 && $_REQUEST['a'] == 23)
{
  $e->setError(3);
  $e->dumpError();
}
$flag=false;
if(isset($_REQUEST['id'])){
	// we are editing get seciton information
	$flag=true;
	$sqlS = "select * from $dbase.".$table_prefix."site_section where id=".$_REQUEST['id']." LIMIT 1";
	$rsS = mysql_query($sqlS);
	$limitS = mysql_num_rows($rsS);
	if($limitS>0){
		$row = mysql_fetch_assoc($rsS);
		$id = $_REQUEST['id'];
		$section_type = $row['section_type'];
		$name = $row['name'];
		$descripton = $row['description'];
	}else{
		die("That is not a valid SECTION");
	}
	
}

// get section type
if(!$flag){ // check for section because this is new
	$section_type = isset($_REQUEST['section_type']) ? $_REQUEST['section_type']:'snippet';
	$name = '';
	$description = '';
	$id = '';
}

?>
<div class="subTitle">
 <span class="floatRight">
    <img src="media/images/_tx_.gif" width="1" height="5"><br />
    <?php echo $site_name ;?> - Manage Section
  </span>
</div>
<div class="sectionHeader">
  <img src='media/images/misc/dot.gif' alt="." />&nbsp;Create/Edit Section
  </div>

<div class="sectionBody">
<p>Use this form to create/edit a section! Sections are a way to organize your snippets and chunks.</p>
<form name="mutate" method="post" action="index.php?a=221">
  <input type="hidden" name="id" value="<?php echo $id;?>" />
  <input type="hidden" name="section_type" value="<?php echo $section_type; ?>" />
  <strong>Section Name:</strong> <input type="text" name="name" value="<?php echo $name; ?>" size="35"/><br /><br />
  <strong>Description:</strong> <input type="text" name="description" value="<?php echo $description; ?>" size="50" /><br /><br />
  <input type="submit" name="submit" value="Save Section" />
</form>
</div>