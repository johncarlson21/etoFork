<?php

function __autoload($class_name) {
    if(!class_exists($class_name)){
        if(file_exists(MANAGER_PATH.'/models/'.$class_name . '.php')){
            require_once(MANAGER_PATH.'/models/'.$class_name . '.php');
        }elseif(file_exists(MANAGER_PATH.'/includes/'.$class_name . '.php')){
            require_once(MANAGER_PATH.'/includes/'.$class_name . '.php');
        }elseif(file_exists(MANAGER_PATH.'/includes/'.$class_name . '.class.php')){
            require_once(MANAGER_PATH.'/includes/'.$class_name . '.class.php');
        }
    }
}

// added to take an xml object and turn it into an array
function objectsIntoArray($arrObjData, $arrSkipIndices = array())
{
    $arrData = array();
   
    // if input is object, convert into array
    if (is_object($arrObjData)) {
        $arrObjData = get_object_vars($arrObjData);
    }
   
    if (is_array($arrObjData)) {
        foreach ($arrObjData as $index => $value) {
            if (is_object($value) || is_array($value)) {
                $value = objectsIntoArray($value, $arrSkipIndices); // recursive call
            }
            if (in_array($index, $arrSkipIndices)) {
                continue;
            }
            $arrData[$index] = $value;
        }
    }
    return $arrData;
}

function nicesize($size)
{
  $a = array("B", "KB", "MB", "GB", "TB", "PB");

  $pos = 0;
  while ($size >= 1024)
  {
    $size /= 1024;
    $pos++;
  }
  if($size == 0)
  {
    return "-";
  }
  else
  {
    return round($size,2)." ".$a[$pos];
  }
}


?>