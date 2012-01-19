<?php

class mod_gallery extends etomite {
    var $classParams = array();
    var $moduleConfig; // main var passed from module xml file for basic config info
    var $thumbnail_widths = array(120,207,800); // 120 = thumb, 212 = slide for front (height will be 266 and use the zc=C crop function), 640 = full image size
    var $slideWidth = 207; // should be the same as the second thumbnail_width size
    var $capture_raw_data = false;
    
    var $dir;
    var $www;
    var $orig = "original/"; // original image dir
    var $thumbs = "thumbs/"; // thumbs dir
    var $full = "full/"; // full size image dir
    var $slides = "slides/"; // slides images
    var $slide_height = 300; // slide height
    
    public function __construct($params=array()) {
        if (count($params) > 0) {
            $this->classParams = $params;
        }
        $this->dir = absolute_base_path."assets/gallery/";
        $this->www = www_base_path."assets/gallery/";
    }
    
    // show the gallery
    public function index() {
        $this->setJSCSS('<script type="text/javascript" src="/manager/frames/scriptaculous/prototype.js" ></script>');
        $this->setJSCSS('<script type="text/javascript" src="/manager/frames/scriptaculous/scriptaculous.js" ></script>');
        $this->setJSCSS("<link rel='stylesheet' href='/assets/js/lightbox/lightbox.css' type='text/css' media='screen' />");
        $this->setJSCSS("<script type='text/javascript' src='/assets/js/lightbox/lightbox-pt-1.6-compat.js'></script>");
        $dir = $this->dir;
        $www = $this->www;
        $orig = "original/"; // original image dir
        $thumbs = "thumbs/"; // thumbs dir
        $full = "full/"; // full size image dir
        $slides = "slides/"; // slides images
        $slide_height = 300; // slide height
        
        $columns = 3;
        $output = '';
        
        $galId = $this->classParams['galId'];
        
        // check db for gallery images
        
        if($result = $this->getIntTableRows($fields="*",$from="831gallery_items",$where='galId='.$galId,$sort='gal_order',$dir='ASC')){
        	
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

        include_once 'views/front_gallery_item_list.phtml';

    }
}

$action = (isset($action) && !empty($action)) ? $action : 'index'; // defaults to adminView

$mod_gallery = new mod_gallery($moduleParams);

$mod_gallery->etomite = $etomite;

$mod_gallery->$action();
?>