<?php
/*
 * IF YOU ARE OUTPUTTING HTML TO THE MANAGER FRAME
 * YOU SHOULD INCLUDE THE /manager/header.inc.php and
 * /manager/footer.inc.php files to setup the frame correctly
 * for layout. This can be done in a view/view_file.phtml file or
 * directly in the functions below.
 */

if(IN_ETOMITE_SYSTEM != "true" || !$etomite->userLoggedIn()) {
    die("you are not supposed to be here");
}

class mod_gallery_admin extends module {
    var $etomite;
    var $moduleConfig; // main var passed from module xml file for basic config info
    var $thumbnail_widths = array(120,207,800); // 120 = thumb, 212 = slide for front (height will be 266 and use the zc=C crop function), 640 = full image size
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
        if (!empty($config) && $config != null) {
            $this->moduleConfig = $config;
        }
        $this->dir = absolute_base_path . "assets/gallery/";
        $this->www = www_base_path . "assets/gallery/";
    }
    
    public function adminView() { // this is the default admin view page.
        include_once 'views/admin.phtml';
    }
    
    public function listGalleryItems() {
        $galId = $_REQUEST['galId'];
        $g = $this->etomite->getIntTableRows($fields="*", $from="831galleries",$where="id=".$galId);
        if (count($g)>0) {
            $gallery = $g[0];
            $items = $this->etomite->getIntTableRows($fields="*",$from="831gallery_items",$where="galId=".$galId,$sort='gal_order',$dir='ASC');
            include_once 'views/gallery_item_list.phtml';
        } else {
            echo "<h2>Sorry that is not a valid gallery!</h2>";
        }
    }
    
    public function listGalleries() {
        $galleries = $this->etomite->getIntTableRows($fields="*",$from="831galleries",$where="",$sort='name',$dir='ASC');
        include_once 'views/galleries_list.phtml';
    }
    
    public function createGallery() {
        $success = '';
        $error = '';
        if(isset($_POST['submit'])){
            // need to create a new gallery
            if(isset($_REQUEST['name']) && !empty($_REQUEST['name'])) {
                if($this->etomite->putIntTableRow(array('name'=>$_REQUEST['name'], 'description'=>$_REQUEST['description']), $into="831galleries")){
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
            $g = $this->etomite->getIntTableRows($fields="*", $from="831galleries",$where="id=".$galId);
            $gallery = $g[0];
            include_once 'views/upload_items.phtml';
        }
    }
    
}

$action = $_REQUEST['action']; // defaults to adminView

$mod_gallery_admin = new mod_gallery_admin($mod_galleryConfig);

$mod_gallery_admin->etomite = $etomite;

$mod_gallery_admin->$action();

?>
