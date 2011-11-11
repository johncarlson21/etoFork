<?php
// save_role.processor.php
// Last Modified 2008-03-18 by Ralph

if(IN_ETOMITE_SYSTEM!="true")
{
  die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the Etomite Manager instead of accessing this file directly.");
}

if($_SESSION['permissions']['save_role']!=1 && $_REQUEST['a']==36)
{
  $e->setError(3);
  $e->dumpError();
}

extract($_POST);

if($name=='' || !isset($name))
{
  echo "Please enter a name for this role!";
  exit;
}

switch ($_POST['mode'])
{
  case '38':
    $sql = "INSERT INTO $dbase.".$table_prefix."user_roles(name, description, frames, home, view_document, new_document, save_document, delete_document, action_ok, logout, help, messages, new_user, edit_user, logs, edit_parser, save_parser, edit_template, settings, credits, new_template, save_template, delete_template, edit_snippet, new_snippet, save_snippet, delete_snippet, empty_cache, edit_document, change_password, error_dialog, about, file_manager, save_user, delete_user, save_password, edit_role, save_role, delete_role, new_role, access_permissions, new_chunk, save_chunk, edit_chunk, delete_chunk,
    export_html) VALUES('$name', '$description',$frames, $home, $view_document, $new_document, $save_document, $delete_document, $action_ok, $logout, $help, $messages, $new_user, $edit_user, $logs, 0, 0, $edit_template, $settings, $credits, $new_template, $save_template, $delete_template, $edit_snippet, $new_snippet, $save_snippet, $delete_snippet, $empty_cache, $edit_document, $change_password, $error_dialog, $about, $file_manager, $save_user, $delete_user, $save_password, $edit_role, $save_role, $delete_role, $new_role, $access_permissions, $new_chunk, $save_chunk, $edit_chunk, $delete_chunk, $export_html);";

    $rs = mysql_query($sql);

    if(!$rs)
    {
      echo "An error occured while attempting to save the new role.<p>";
      exit;
    }

    header("Location: index.php?a=75&r=2");
    break;

  case '35':
    $sql = "UPDATE $dbase.".$table_prefix."user_roles SET
      name='$name',
      description='$description',
      frames=$frames,
      home=$home,
      view_document=$view_document,
      new_document=$new_document,
      save_document=$save_document,
      delete_document=$delete_document,
      action_ok=$action_ok,
      logout=$logout, help=$help,
      messages=$messages,
      new_user=$new_user,
      edit_user=$edit_user,
      logs=$logs,
      edit_parser=0,
      save_parser=0,
      edit_template=$edit_template,
      settings=$settings,
      credits=$credits,
      new_template=$new_template,
      save_template=$save_template,
      delete_template=$delete_template,
      edit_snippet=$edit_snippet,
      new_snippet=$new_snippet,
      save_snippet=$save_snippet,
      delete_snippet=$delete_snippet,
      empty_cache=$empty_cache,
      edit_document=$edit_document,
      change_password=$change_password,
      error_dialog=$error_dialog,
      about=$about,
      file_manager=$file_manager,
      save_user=$save_user,
      delete_user=$delete_user,
      save_password=$save_password,
      edit_role=$edit_role,
      save_role=$save_role,
      delete_role=$delete_role,
      new_role=$new_role,
      access_permissions=$access_permissions,
      new_chunk=$new_chunk,
      save_chunk=$save_chunk,
      edit_chunk=$edit_chunk,
      delete_chunk=$delete_chunk,
      export_html=$export_html
      WHERE id=$id
    ";

    if(!$rs = mysql_query($sql))
    {
      echo "An error occured while attempting to update the role.";
      exit;
    }

    header("Location: index.php?a=75&r=2");
    break;

  default:
    echo "You supposed to be here now?";
    exit;
}

?>
