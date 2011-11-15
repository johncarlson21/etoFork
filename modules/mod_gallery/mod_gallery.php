<?php

class mod_test extends module {
    var $classParams = array();
    
    public function __construct($params=array()) {
        if (count($params) > 0) {
            $this->classParams = $params;
        }
    }
    
    // show the gallery
    public function index() {
        /*foreach ($this->classParams as $key=>$val) {
            echo "<p>param: " . $key . "=" . $val . "</p>";
        }*/
        
        $dir = absolute_base_path."assets/gallery/";
        $www = $etomite->config['www_base_path']."assets/gallery/";
        $orig = "original/"; // original image dir
        $thumbs = "thumbs/"; // thumbs dir
        $full = "full/"; // full size image dir
        $slides = "slides/"; // slides images
        $slide_height = 300; // slide height
        
        $loginId = 13; // id of the login document
        $manageId = 12; // id of the document that you put the manager snippet in
        $docId = $etomite->documentIdentifier;
        
        $columns = 3;
        $output = '';
        
        // check db for gallery images
        
        if($result = $etomite->getIntTableRows($fields="*",$from="831gallery",$where='docId='.$docId,$sort='gal_order',$dir='ASC')){
        	
        	$z=1;
        	$output .= "<table width='100%' cellpadding='5' class='gallery-table'>\n";
        		foreach($result as $item){
        			if($z==1){ $output .= "<tr>\n"; }
        			$output .= "<td valign='top' align='center'><a title='".htmlentities(stripslashes($item['title']),ENT_QUOTES)."<br />".htmlentities(stripslashes($item['description']),ENT_QUOTES)."' href='".$www.$full.$item['fn']."' rel='lightbox[group]'><img alt='".stripslashes($item['title'])."' src='".$www.$thumbs."th_".$item['fn']."' border='0' /></a><p style='text-align:center;'>".stripslashes($item['title'])."<br />".stripslashes($item['description'])."</p></td>\n";
        			if($z==$columns){ $output .= "</tr>\n"; $z=0; }
        			$z++;
        		}
        	
        	if($z>1){
        	for($i=$z;$i<=$columns;$i++){
        		$output .= "<td>&nbsp;</td>";
        	}
        	if($z<$columns){ $output .= "</tr>\n"; }
        	}
        	
        	$output .= "</table>\n";
        	
        }else{
        	$output .= "<p>Currently there are no gallery images!</p>";
        }
        if($_SESSION['validated']!=1 && $_SESSION['role']!=1){
        	$output .= "<p>&nbsp;</p><p>&nbsp;</p><p align='center'><a href='".$etomite->makeUrl($loginId,'',array())."'>Login to Manage Gallery</a></p>";
        }else{
        	$output .= "<p>&nbsp;</p><p>&nbsp;</p><div align='center'><p style='font-size:14px;'><a href='".$etomite->makeUrl($manageId,'',array('galId'=>$docId))."'>Manage Gallery</a></p>".'<form method="post" action="'.$etomite->makeUrl($loginId,'',array()).'">
              <input type="submit" value="Logout" name="logout"> [ admin ]
            </form>'."</div>";
        }
        
        
        return $output;
        
        
        
    }
}

$action = (isset($action) && !empty($action)) ? $action : 'index'; // defaults to adminView

$mod_test = new mod_test($moduleParams);

$mod_test->$action();
?>