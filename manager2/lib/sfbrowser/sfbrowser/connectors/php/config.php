<?php
define("SFB_PATH",			"lib/sfbrowser/sfbrowser/");		// path of sfbrowser (relative to the page it is run from)
define("SFB_BASE",			$_SERVER['DOCUMENT_ROOT']."/assets/"); //../../../../assets/");		// upload folder (relative to sfbpath)
define("SFB_PREVIEWPATH",            "/assets/");

define("SFB_LANG",			"en_US");				// the language ISO code
define("PREVIEW_BYTES",		600);				// ASCII preview ammount
define("SFB_DENY",			"");	// forbidden file extensions

define("FILETIME",			"j-n-Y H:i");		// file time display

define("SFB_ERROR_RETURN",	"<html><head><meta http-equiv=\"Refresh\" content=\"0;URL=http:/\" /></head></html>");

define("SFB_PLUGINS",		"imageresize");

define("SFB_DEBUG",			false);
?>