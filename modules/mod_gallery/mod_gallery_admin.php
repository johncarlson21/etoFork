<?php
/*
 * IF YOU ARE OUTPUTTING HTML TO THE MANAGER FRAME
 * YOU SHOULD INCLUDE THE /manager/header.inc.php and
 * /manager/footer.inc.php files to setup the frame correctly
 * for layout. This can be done in a view/view_file.phtml file or
 * directly in the functions below.
 */
define("IN_ETOMITE_SYSTEM", true);

class mod_gallery_admin extends etomite {
    var $moduleDir;
    var $ajaxClass;
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
    
    // set all items to slide
    var $slideFlag = true;
    
    public function __construct($config=null) {
        parent::__construct();
        if (!empty($config) && $config != null) {
            $this->moduleConfig = $config;
        }
        $this->moduleDir = dirname(__FILE__);
        $this->dir = absolute_base_path . "assets/gallery/";
        $this->www = www_base_path . "assets/gallery/";
        $this->ajaxClass = new Ajax(); // setup the ajax functions to use
        $this->checkManagerLogin();
    }
    
    public function adminView() { // this is the default admin view page.
        include_once $this->moduleDir.'/views/admin.phtml';
    }
    
    public function listGalleryItems() {
        // update order
        if(isset($_POST['submit']) && $_POST['submit']=="Save Order" && !empty($_SESSION['gItems'])){
			$ids = $_SESSION['gItems'];
			foreach($ids as $id){
				$order = 0;
				if(isset($_POST['item_'.$id.'_order']) && !empty($_POST['item_'.$id.'_order'])){
					$order = $_POST['item_'.$id.'_order'];
				}
				$result = $this->updIntTableRows($fields=array('gal_order'=>$order),$into="831gallery_items",$where='id='.$id);
			}
		
		}
        $galId = $_REQUEST['galId'];
        $g = $this->getIntTableRows($fields="*", $from="831galleries",$where="id=".$galId);
        $output = '';
        if (count($g)>0) {
            $gallery = $g[0];
            $items = $this->getIntTableRows($fields="*",$from="831gallery_items",$where="galId=".$galId,$sort='gal_order',$dir='ASC');
            include_once $this->moduleDir.'/views/gallery_item_list.phtml';
        } else {
            echo "<h2>Sorry that is not a valid gallery!</h2>";
        }
    }
    
    public function listGalleries() {
        $galleries = $this->getIntTableRows($fields="*",$from="831galleries",$where="",$sort='name',$dir='ASC');
        include_once $this->moduleDir.'/views/galleries_list.phtml';
    }
    
    public function createGallery() {
        $success = '';
        $error = '';
        if(isset($_POST['submit'])){
            // need to create a new gallery
            if(isset($_REQUEST['name']) && !empty($_REQUEST['name'])) {
                if($this->putIntTableRow(array('name'=>$_REQUEST['name'], 'description'=>$_REQUEST['description']), $into="831galleries")){
                    $success = "<div class='success'>Gallery Created</div>";
                } else {
                    $error = "<div class='error'>There was an error creating your gallery</div>";
                }
            }
        }
        include('views/add_gallery.phtml');
    }
    
    public function uploadGalleryItems() {
        $galId = (int)$_REQUEST['galId'];
        if (empty($galId)){
            echo "<h2>Sorry that is not a valid gallery<h2>";
        } else {
            $g = $this->getIntTableRows($fields="*", $from="831galleries",$where="id=".$galId);
            $gallery = $g[0];
            include_once 'views/upload_items.phtml';
        }
    }
    
    public function checkForItemsToProcess() {
        $galId = (int)$_REQUEST['galId'];
        $dir = $this->dir . "original";
        $found = false;
        if (is_dir($dir)) {
            if ($handle = opendir($dir)) {
                while (false !== ($file = readdir($handle))) {
                    $pos = strpos($file, "gal_".$galId);
                    if ($file != "." && $file != ".." && $pos !== false) {
                        $found = true;
                    }
                }
                closedir($handle);
            }
            if ($found) {
                echo "<div style='color: #FF0000; padding: 5px; border: #000 solid 1px; margin: 10px 0; font-weight: bold; font-style: italic;'>";
                echo "<p>You have images waiting to be processed for this gallery! Click Process Images below!</p>";
                echo "</div>";
            }
        }
    }
    
    public function processItems(){
        $galId = (int) $_REQUEST['galId'];
        $g = $this->getIntTableRows($fields="*", $from="831galleries",$where="id=".$galId);
        $gallery = $g[0];
        $dir = $this->dir;
        $orig = $this->orig;
        $thumbnail_widths = $this->thumbnail_widths;
        $thumbs = $this->thumbs;
        $slides = $this->slides;
        $full = $this->full;
        $www = $this->www;
        $slide_height = $this->slide_height;
        $slideFlag = $this->slideFlag;
        
        $output = '';
        // CREATE GALLERY ITEMS
        
        require_once(str_replace("manager/", "", absolute_base_path).'assets/phpThumb/phpthumb.class.php');
        
        // create phpThumb object
        $phpThumb = new phpThumb();
        
        
        // get the files
        $imgs = array();
        if ($handle = opendir($dir.$orig)) {
            /* This is the correct way to loop over the directory. */
            while (false !== ($file = readdir($handle))) {
                if($file != "." && $file != ".." && !is_dir($file)){
                    $imgs[] = $file; // put the file names into an array to go over later
                }// end if for files
            }
            closedir($handle);
        }
        if (count($imgs)>0) {
            // loop through the files
            
            $output .= "<p>There are ".count($imgs)." images to add to the gallery</p>";
            $added = array();
            foreach($imgs as $im){
                // run them through the php thumb
                $file_name = '';
                $file_name = explode(".",$im); // 0 = name, 1 = ext
                $z = 1;
                foreach($thumbnail_widths as $thumbnail_width){
                    $www_filename = '';
                    $output_filename = '';
                    $fn = '';
            
                    $phpThumb->resetObject();
                    $phpThumb->setSourceFilename($dir.$orig.$im);
                    $phpThumb->setParameter('w', $thumbnail_width);
                    $phpThumb->setParameter('config_output_format', 'jpg');
                    //$phpThumb->setParameter('config_imagemagick_path', '/usr/local/bin/convert');
                    if($thumbnail_width == 120){
                        $output_filename = $dir.$thumbs."th_".$file_name[0].".".$phpThumb->config_output_format;
                        $www_filename = $www.$thumbs."th_".$file_name[0].".".$phpThumb->config_output_format;
                        $fn = $file_name[0].".".$phpThumb->config_output_format;
            
                    }elseif($thumbnail_width == 207){
                        $phpThumb->setParameter('h', $slide_height); // slide height
                        $phpThumb->setParameter('zc', 'C'); // crop
                        $output_filename = $dir.$slides.$file_name[0].".".$phpThumb->config_output_format;
                                    $www_filename = $www.$slides.$file_name[0].".".$phpThumb->config_output_format;
                                    $fn = $file_name[0].".".$phpThumb->config_output_format;
                    }else{
                        $output_filename = $dir.$full.$file_name[0].".".$phpThumb->config_output_format;
                        $www_filename = $www.$full.$file_name[0].".".$phpThumb->config_output_format;
                        $fn = $file_name[0].".".$phpThumb->config_output_format;
                    }
                    
                    if ($phpThumb->GenerateThumbnail()) {
                        $output_size_x = ImageSX($phpThumb->gdimg_output);
                        $output_size_y = ImageSY($phpThumb->gdimg_output);
                        if ($output_filename || $this->capture_raw_data) {
                            if ($this->capture_raw_data && $phpThumb->RenderOutput()) {
                            // RenderOutput renders the thumbnail data to $phpThumb->outputImageData, not to a file or the browser
                            } elseif ($phpThumb->RenderToFile($output_filename)) {
                            // do something on success
                                //echo 'Successfully rendered:<br><img src="'.$www_filename.'">';
                                $output .= "File: ".$output_filename." was created!<br />";
                                if($z==1){
                                // check for slide flag
                                if($slideFlag){ $sf = '1'; }else{ $sf = '0'; }
                                if($result = $this->putIntTableRow($fields=array('fn'=>$fn,'slide'=>$sf,'galId'=>$galId),$into="831gallery_items")){
                                    // added to db
                                    // not used right now
                                    $insId = $this->insertId();
                                    if(!in_array($insId,$added)){
                                        $added[] = $insId;
                                    }
                                }
                                }
                            } else {
                            // do something with debug/error messages
                                //echo 'Failed (size='.$thumbnail_width.'):<pre>'.implode("\n\n", $phpThumb->debugmessages).'</pre>';
                                $output .= "There was an error creating file: ".$output_filename."<br />";
                            }
                        } else {
                            $phpThumb->OutputThumbnail();
                        }
                    }// end if for generate thumb
                    else {
                        // do something with debug/error messages
                        // echo 'Failed (size='.$thumbnail_width.').<br>';
                        // echo '<div style="background-color:#FFEEDD; font-weight: bold; padding: 10px;">'.$phpThumb->fatalerror.'</div>';
                        // echo '<form><textarea rows="10" cols="60" wrap="off">'.htmlentities(implode("\n* ", $phpThumb->debugmessages)).'</textarea></form><hr>';
                    }
                    $z++;
                }// end foreach on widths
                
                @unlink($dir.$orig.$im); // delete the uploaded image.
            
            }// end foreach for imgs
        } else {
            $output = "<h2>There are no images to process!</h2>";
        }
        include_once 'views/process_items.phtml';
    
        // END CREATE GALLERY IMAGES

    }
    
    public function editItem() {
        $itemId = (int) $_REQUEST['itemId'];
        if(isset($_REQUEST['edit']) && $_REQUEST['edit']=='true') {
            // lets try to edit the item
            $this->ajaxClass->validateRequest(array('title','itemId'));
            $title = isset($_REQUEST['title']) ? $_REQUEST['title'] : '';
            $description = isset($_REQUEST['description']) ? $_REQUEST['description'] : '';
            $slide = (isset($_REQUEST['slide']) && $_REQUEST['slide'] == 1) ? 1 : 0;
            if($this->updIntTableRows(array('title'=>$title,'description'=>$description,'slide'=>$slide), "831gallery_items", "id=".$itemId)) {
                $this->ajaxClass->respond(true,"Item Saved");
            } else {
                $this->ajaxClass->respond(false,"Item not saved!");
            }
        }
        // get item info
        $gi = $this->getIntTableRows("*", "831gallery_items", "id=".$itemId);
        $item = $gi[0];
        $slide_checked = '';
        if ($item['slide'] == 1) {
            $slide_checked = 'checked="checked"';
        }
        include_once 'views/edit_item.phtml';
    }
    
    public function deleteItem() {
        $this->ajaxClass->validateRequest(array('itemId'));
        $itemId = (int) $_REQUEST['itemId'];
        if($gi = $this->getIntTableRows("*", "831gallery_items", "id=".$itemId)) {
            if(count($gi)>0) {
                $item = $gi[0];
                if(file_exists($this->dir.$this->full.$item['fn'])) {
                    @unlink($this->dir.$this->full.$item['fn']);
                }
                if(file_exists($this->dir.$this->slides.$item['fn'])) {
                    @unlink($this->dir.$this->slides.$item['fn']);
                }
                if(file_exists($this->dir.$this->thumbs."th_".$item['fn'])) {
                    @unlink($this->dir.$this->thumbs."th_".$item['fn']);
                }
                // now remove the item from the db
                if($this->dbQuery("DELETE FROM ".$this->db."831gallery_items WHERE id=".$item['id']." LIMIT 1")){
                    $this->ajaxClass->respond(true, "Item removed");
                } else {
                    $this->ajaxClass->respond(false, "Item not removed!");
                }
                
            } else {
                $this->ajaxClass->respond(false, "That is not a valid item!");
            }
        } else {
            $this->ajaxClass->respond(false, "That is not a valid item!");
        }
        
    }
    
}

$action = $_REQUEST['moduleAction']; // defaults to adminView

$mod_gallery_admin = new mod_gallery_admin($mod_galleryConfig);

$mod_gallery_admin->$action();

?>
