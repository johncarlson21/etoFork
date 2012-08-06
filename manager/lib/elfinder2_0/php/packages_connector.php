<?php
/* added for using as an etomite file manager */
define("IN_ETOMITE_SYSTEM", true);
include_once("../../../includes/bootstrap.php");

$etomite = new etomite();
$etomite->checkManagerLogin();

/* end etomite check */

set_time_limit(0); // just in case it too long, not recommended for production
error_reporting(E_ALL | E_STRICT); // Set E_ALL for debuging
ini_set('max_file_uploads', 50);   // allow uploading up to 50 files at once
ini_set('upload_max_filesize', '8M'); // allow uploading up to 8MB

// needed for case insensitive search to work, due to broken UTF-8 support in PHP
ini_set('mbstring.internal_encoding', 'UTF-8');
ini_set('mbstring.func_overload', 2);

include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderConnector.class.php';
include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinder.class.php';
include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderVolumeDriver.class.php';
include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderVolumeLocalFileSystem.class.php';
// Required for MySQL storage connector
// include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderVolumeMySQL.class.php';
// Required for FTP connector support
// include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderVolumeFTP.class.php';


/**
 * Simple function to demonstrate how to control file access using "accessControl" callback.
 * This method will disable accessing files/folders starting from  '.' (dot)
 *
 * @param  string  $attr  attribute name (read|write|locked|hidden)
 * @param  string  $path  file path relative to volume root directory started with directory separator
 * @return bool|null
 **/
function access($attr, $path, $data, $volume) {
    return strpos(basename($path), '.') === 0       // if file/folder begins with '.' (dot)
        ? !($attr == 'read' || $attr == 'write')    // set read+write to false, other (locked+hidden) set to true
        :  null;                                    // else elFinder decide it itself
}

$opts = array(
    // 'debug' => true,
    'roots' => array(
        array(
            'driver'        => 'LocalFileSystem',   // driver for accessing file system (REQUIRED)
            'path'          => absolute_base_path . 'tmp/packages/',         // path to files (REQUIRED)
            'URL'           => relative_base_path . 'tmp/packages/', // URL to files (REQUIRED)
            'accessControl' => 'access'             // disable and hide dot starting files (OPTIONAL)
        )
    )
);

// run elFinder
$connector = new elFinderConnector(new elFinder($opts));
$connector->run();

