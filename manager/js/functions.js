/*function setupTiny(el) {
    tinyMCE.init({
      auto_reset_designmode : true,
      document_base_url : Etomite.db_url,
      mode : "exact",
      elements : el,
      theme : "advanced",
      relative_urls : Etomite.rel,
      remove_script_host : false,
      convert_urls : true,
      //plugins : "table, advhr, advimage, advlink, emotions, iespell, insertdatetime, preview, searchreplace, print, contextmenu, paste, directionality, fullscreen",
      plugins : "autolink,lists,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,noneditable,visualchars,nonbreaking,xhtmlxtras,template,fullscreen",
      theme_advanced_buttons1_add_before : "newdocument, separator",
      theme_advanced_buttons1_add : "fontselect, fontsizeselect",
      theme_advanced_buttons2_add : "separator, insertdate, inserttime, preview, separator, forecolor, backcolor",
      theme_advanced_buttons2_add_before: "cut, copy, paste, pastetext, pasteword, separator, search, replace, separator",
      theme_advanced_buttons3_add_before : "tablecontrols, separator",
      theme_advanced_buttons3_add : "emotions, iespell, media, advhr, separator, print, separator, ltr, rtl, separator, fullscreen",
      theme_advanced_toolbar_location : "top",
      theme_advanced_toolbar_align : "left",
      theme_advanced_path_location : "bottom",
      content_css : Etomite.cssPath,
      plugin_insertdate_dateFormat : Etomite.dateFormat,
      plugin_insertdate_timeFormat : Etomite.timeFormat,
      extended_valid_elements : "hr[class|width|size|noshade], font[face|size|color|style] ,span[class|align|style],module",
      //external_link_list_url : "example_data/example_link_list.js",
      //external_image_list_url : "example_data/example_image_list.js",
      //flash_external_list_url : "example_data/example_flash_list.js",
      file_browser_callback : 'elFinderBrowser',
      theme_advanced_resize_horizontal : false,
      theme_advanced_resizing : true,
      //init_instance_callback : resizeEditorBox,
      skin: "o2k7",
      skin_variant: 'silver'
    });
}*/

function elFinderBrowser (field_name, url, type, win) {
    var elfinder_url = Etomite.db_url + 'manager/lib/elfinder2_0/elfinder.html';    // use an absolute path!
    tinyMCE.activeEditor.windowManager.open({
      file: elfinder_url,
      title: 'File Manager',
      width: 900,  
      height: 450,
      resizable: 'yes',
      inline: 'yes',    // This parameter only has an effect if you use the inlinepopups plugin!
      popup_css: false, // Disable TinyMCE's default popup CSS
      close_previous: 'no'
        }, {
          window: win,
          input: field_name
        });
    return false;
}

function bindContextMenu(node, span) {
    // Add context menu to this node:
    $(span).contextMenu({menu: "myMenu"}, function(action, el, pos) {
        var id = node.data.key.replace(/id_/, '');
        switch( action ) {
            case "preview":
                window.open(node.data.docUrl);
                break;
            case "move":
                Etomite.moveDocumentDialog(id);
                Etomite.movingDoc = true;
                Etomite.movingDocId = id;
                break;
            case "edit":
                if (node.data.weblink) {
                    Etomite.editDocument(id, '', 'true');
                } else {
                    Etomite.editDocument(id);
                }
                break;
			case "duplicate":
				if (node.data.weblink) {
                    Etomite.duplicateDocument(id, 'true');
                } else {
                    Etomite.duplicateDocument(id);
                }
				break;
            case "createDoc":
                Etomite.editDocument('', id);
                break;
            case "createLink":
                Etomite.editDocument('', id, 'true');
                break;
            case "publish":
                Etomite.updateDocProperty('published', '1', id);
                break;
            case "unpublish":
                Etomite.updateDocProperty('published', '0', id);
                break;
            case "delete":
                $('<div id="confirm"></div>').appendTo('body').html("<p>Are you sure you want to delete this document and all child documents?</p>");
                $('#confirm').dialog({
                    autoOpen: true,
                    minWidth: 200,
                    minHeight: 200,
                    position: 'center',
                    resizable: false,
                    closeOnEscape: false,
                    title: 'Alert: Delete Document',
                    modal: true,
                    buttons: {
                        Yes: function() {
                            Etomite.updateDocProperty('deleted', '1', id);
                            $(this).dialog('close');
                            $(this).dialog('destroy');
                            $('#confirm').remove();
                        },
                        No: function() {
                            $(this).dialog('close');
                            $(this).dialog('destroy');
                            $('#confirm').remove();
                        }
                    }
                });
                break;
            case "undelete":
                $('<div id="confirm"></div>').appendTo('body').html("<p>Are you sure you want to un-delete this document and all child documents?</p>");
                $('#confirm').dialog({
                    autoOpen: true,
                    minWidth: 200,
                    minHeight: 200,
                    position: 'center',
                    resizable: false,
                    closeOnEscape: false,
                    title: 'Alert: Un-delete Document',
                    modal: true,
                    buttons: {
                        Yes: function() {
                            Etomite.updateDocProperty('deleted', '0', id);
                            $(this).dialog('close');
                            $(this).dialog('destroy');
                            $('#confirm').remove();
                        },
                        No: function() {
                            $(this).dialog('close');
                            $(this).dialog('destroy');
                            $('#confirm').remove();
                        }
                    }
                });
                break;
            case "showinmenu":
                Etomite.updateDocProperty('showinmenu', '1', id);
                break;
            case "hideinmenu":
                Etomite.updateDocProperty('showinmenu', '0', id);
                break;
        }
    });
}

function bindRootContextMenu(node, span) {
    // Add context menu to this node:
    $(span).contextMenu({menu: "myRootMenu"}, function(action, el, pos) {
        var id = node.data.key.replace(/id_/, '');
        switch( action ) {
            case "createDoc":
                Etomite.editDocument('', id);
                break;
            case "createLink":
                Etomite.editDocument('', id, 'true');
                break;
            case "purgeDocs":
                $('<div id="confirm"></div>').appendTo('body').html("<p>Are you sure you want to delete this document and all child documents?</p><p>THERE IS NO GOING BACK!</p>");
                $('#confirm').dialog({
                    autoOpen: true,
                    minWidth: 200,
                    minHeight: 200,
                    position: 'center',
                    resizable: false,
                    closeOnEscape: false,
                    title: 'Alert: Purge Documents',
                    modal: true,
                    buttons: {
                        Yes: function() {
                            Etomite.purgeDocuments();
                            $(this).dialog('close');
                            $(this).dialog('destroy');
                            $('#confirm').remove();
                        },
                        No: function() {
                            $(this).dialog('close');
                            $(this).dialog('destroy');
                            $('#confirm').remove();
                        }
                    }
                });
                break;
            case "refreshTree":
                $("#docTree").dynatree("getTree").reload();
                break;
        }
    });
}

var spinner = null;

function spin(stop) {
    if(stop !== false) {
        $('#spinner').show().center();
        spinner = $('#spinner').spin({
            lines: 12, // The number of lines to draw
            length: 7, // The length of each line
            width: 4, // The line thickness
            radius: 10, // The radius of the inner circle
            color: '#000', // #rgb or #rrggbb
            speed: 1, // Rounds per second
            trail: 60, // Afterglow percentage
            shadow: false // Whether to render a shadow
        });
    } else {
        spinner.spin(false);
    }
}

function revert(){
    var is_sure = window.confirm("Are you sure you want to revert the document back to this version? You will not be able to revert back to the current version!");
    if(is_sure==true){
        return true;
    }else{ return false; }
}

//<!-- password geneator -->
//<!-- Original:  ataxx@visto.com -->

//<!-- This script and many more are available free online at -->
//<!-- The JavaScript Source!! http://javascript.internet.com -->

//<!-- Begin
function getRandomNum(lbound, ubound) {
    return (Math.floor(Math.random() * (ubound - lbound)) + lbound);
}
function getRandomChar(number, lower, upper, other, extra) {
    var numberChars = "0123456789";
    var lowerChars = "abcdefghijklmnopqrstuvwxyz";
    var upperChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    var otherChars = "~!@#$%^&*()-_=+[{]}|;:'\",<.>?";
    var charSet = extra;
    if (number == true)
        charSet += numberChars;
    if (lower == true)
        charSet += lowerChars;
    if (upper == true)
        charSet += upperChars;
    if (other == true)
        charSet += otherChars;
    return charSet.charAt(getRandomNum(0, charSet.length));
}
function getPassword(length, extraChars, firstNumber, firstLower, firstUpper, firstOther,
    latterNumber, latterLower, latterUpper, latterOther) {
    length = 10;
    var rc = "";
    if (length > 0){
        rc = rc + getRandomChar(true, true, true, true, '');
        for (var idx = 1; idx < length; ++idx) {
            rc = rc + getRandomChar(true, true, true, true, '');
        }
    }
    return rc;
}
