
var Etomite = {
    inAdmin: true,
    outerLayout: null,
    db_url: '',
    rel: '',
    EtoRoot: '',
    cssPath: '',
    dateFormat: '',
    timeFormat: '',
    goodAlias: true,
    
    init: function() {
        /* login page */
        $('#loginBtn').click(function(){
            var uname = $('#username').val();
            var pword = $('#password').val();
            var terms = ($('#licenseOK').is(':checked')) ? true : false;
            var error = '';
            if(uname == null || uname == "" || uname == " "){
                error += "Username cannot be empty!\n";
            }
            if(pword == null || pword == "" || pword == " "){
                error += "Password cannot be empty!\n";
            }
            if(terms == false){
                error += "You must aggree to Etomite GPL License";
            }
            if(error != ""){
                alert(error);
                return;
            }
            $.ajax({
                url: 'ActionServer.php',
                type:"POST",
                dataType: 'json',
                data: {
                    action: 'loginRequest',
                    username: uname,
                    password: pword
                },
                success: function(json) {
                    if ((json === null) || (json.succeeded !== true)) {
                        Etomite.errorDialog(json.message, 'ERROR');
                    } else {
                        Etomite.errorDialog('Logging you in. Please Wait');
                        setTimeout(function(){window.location.reload();},1000);
                    }
                }
            });
        });
        
        $('#loginBtn').keypress(function(e) {
            if(e.keyCode == 13) {
                $('#loginBtn').click();
            }
        });

        $('#loginPage #username').keypress(function(e) {
            if(e.keyCode == 13) {
                $('#loginBtn').click();
            }
        });
        $('#loginPage #password').keypress(function(e) {
            if(e.keyCode == 13) {
                $('#loginBtn').click();
            }
        });
        /* end login page */
        
        if (Etomite.inAdmin == true) {
            Etomite.loadDocTree();
        }
        
    },
    
    loadPane: function(content) {
        var p = $('#mainContent');
        p.html(content);
        Etomite.outerLayout.initContent('center');
    },
    
    loadDocTree: function() {
        
        $("#docTree").dynatree({
            initAjax: {
                url: 'ActionServer.php',
                data: {
                    action: 'getDocTree'
                }
            },
            imagePath: 'lib/dynatree/skin-vista/',
            persist: true,
            onClick: function(node, event) {
                // Close menu on click
                if( $(".contextMenu:visible").length > 0 ){
                    $(".contextMenu").hide();
//                  return false;
                }
            },
            
            /*Bind context menu for every node when it's DOM element is created.
              We do it here, so we can also bind to lazy nodes, which do not
              exist at load-time. (abeautifulsite.net menu control does not
              support event delegation)*/
            onCreate: function(node, span){
                var nsp = $(span).find("a.dynatree-title");
                if (node.data.published == '0') {
                    nsp.addClass('unpublishedNode');
                }
                if (node.data.showinmenu == '0') {
                    nsp.addClass('hiddenNode');
                }
                if (node.data.deleted == '1') {
                    nsp.addClass('deletedNode');
                }
                bindContextMenu(node, span);
            },
        /* D'n'd, just to show it's compatible with a context menu.
           See http://code.google.com/p/dynatree/issues/detail?id=174 */
        dnd: {
            preventVoidMoves: true, // Prevent dropping nodes 'before self', etc.
            onDragStart: function(node) {
                return true;
            },
            onDragEnter: function(node, sourceNode) {
                if(node.parent !== sourceNode.parent)
                    return false;
                return ["before", "after"];
            },
            onDrop: function(node, sourceNode, hitMode, ui, draggable) {
                sourceNode.move(node, hitMode);
                $.ajax({
                    url: 'ActionServer.php',
                    dataType: 'json',
                    data: {
                        action: 'saveTree',
                        tree: $("#docTree").dynatree("getTree").toDict()
                    },
                    success: function(json) {
                        if(json === null && json.succeed !== true) {
                            Etomite.errorDialog('Error moving document', 'Error');
                        }
                    }
                });
            }
        }
        });
    },
    
    /* CONTENT */
    editDocument: function(docId, parentId, weblink) {
        spin();
        if (weblink != 'true' || weblink == null || weblink == '') {
            weblink = false;
        }
        
        $.ajax({
            url: 'ActionServer.php',
            dataType: 'html',
            data: {
                action: 'manageDocument',
                reference: weblink,
                id: docId,
                pid: parentId
            },
            success: function(response) {
                if (response === null) {
                    Etomite.errorDialog('There was an error', 'ERROR');
                } else {
                    Etomite.loadPane(response);
                    setTimeout(function() {
                        spin(false);
                        if(weblink == false){
                            setupTiny("taContent");
                        }
                    }, 1000);
                    
                    $('#saveDocument').click(function(){
                        Etomite.saveDocument();
                    });
                    $('#alias').keyup(function() {
                        Etomite.goodAlias = true;
                        var docId = '';
                        if($('#docId').length > 0){
                            docId = $('#docId').val();
                        }
                        if ($('#alias').val().length > 2) {
                            $.ajax({
                                url: 'ActionServer.php',
                                dataType: 'json',
                                data: { alias: $('#alias').val(), action: 'checkAlias', id: docId },
                                success: function(json) {
                                    if ((json === null) || (json.succeeded !== true)) {
                                        $('#aliasUniqueMessage').show();
                                        $('#aliasUniqueMessageResponse').hide();
                                        Etomite.goodAlias = false;
                                    } else {
                                        $('#aliasUniqueMessageResponse').html(json.message);
                                        $('#aliasUniqueMessageResponse').show();
                                        $('#aliasUniqueMessage').hide();
                                        Etomite.goodAlias = true;
                                    }
                                }
                            });
                        } else {
                            $('#aliasUniqueMessageResponse').hide();
                            $('#aliasUniqueMessage').hide();
                        }
                    });
                }
            }
        });
    },
    
    saveDocument: function() {
        if(Etomite.goodAlias == true) {
            spin();
            // get the vars
            var docId = '';
            if($('#docId').length > 0){
                docId = $('#docId').val();
            }
            var ed = tinyMCE.get('taContent');
            var tinyContent = ed.getContent();
            $.ajax({
                url: 'ActionServer.php',
                dataType: 'json',
                data: {
                    action: 'saveDocument',
                    id: docId,
                    type: $('#type').val(),
                    contentType: $('#contentType').val(),
                    pagetitle: $('#pagetitle').val(),
                    longtitle: $('#setitle').val(),
                    description: $('#description').val(),
                    alias: $('#alias').val(),
                    published: ($('#published').is(':checked')) ? 1 : 0,
                    createdon: $('#createdon').val(),
                    pub_date: $('#pub_date').val(),
                    unpub_date: $('#unpub_date').val(),
                    menuindex: $('#menuindex').val(),
                    parent: $('#parent').val(),
                    isfolder: ($('#isfolder').is(':checked')) ? 1 : 0,
                    content: tinyContent,
                    richtext: ($('#richtext').is(':checked')) ? 1 : 0,
                    template: $('#template').val(),
                    searchable: ($('#searchable').is(':checked')) ? 1 : 0,
                    cacheable: ($('#cacheable').is(':checked')) ? 1 : 0,
                    syncsite: ($('#syncsite').is(':checked')) ? 1 : 0,
                    authenticate: ($('#authenticate').is(':checked')) ? 1 : 0,
                    showinmenu: ($('#showinmenu').is(':checked')) ? 1 : 0,
                    meta_title: $('#meta_title').val(),
                    meta_description: $('#meta_description').val(),
                    meta_keywords: $('#meta_keywords').val()
                },
                success: function() {
                    setTimeout(function() {
                        spin(false);
                    }, 1500);
                    $("#docTree").dynatree("getTree").reload();
                }
            });
        } else {
            Etomite.errorDialog('That alias already exists!', 'Error')
        }
        
    },
    
    updateDocProperty: function(prop, pval, docId) {
        $.ajax({
            url: 'ActionServer.php',
            dataType: 'json',
            data: {
                action: 'updateDocProperty',
                propName: prop,
                propVal: pval,
                id: docId
            },
            success: function(json) {
                if(json === null || json.succeeded !== true) {
                    Etomite.notify('Document was not updated!');
                } else {
                    Etomite.notify('Document Updated!');
                    $("#docTree").dynatree("getTree").reload();
                }
            }
        });
    },
    
    notify: function(message) {
        $('<div id="notification">' + message + '</div>').appendTo('body');
        $('#notification').center(false);
        $('#notification').css({zIndex:3000, backgroundImage: 'url(images/popup_background.png)'});
        setTimeout(function() {
            $('#notification').fadeOut('slow', function() {
                $('#notification').remove();
            });
        }, 2000);
    },
    
    errorDialog: function(message, dtitle) {
        $('<div id="errorDialog"></div>').appendTo('#mainContent');
        $('#errorDialog').html('<p>' + message + '</p>');
        $('#errorDialog').dialog({
            autoOpen: false,
            minWidth: 200,
            minHeight: 200,
            position: 'top',
            resizable: false,
            closeOnEscape: false,
            title: dtitle,
            buttons: {
                Ok: function() {
                    $( this ).dialog( "close" );
                }
            }
        });
        
        $('#errorDialog').dialog('open');
    }
    
}// end etomite var

/* extend jquery to add the spin (spinner function) */
$.fn.spin = function(opts) {
    this.each(function() {
      var $this = $(this),
          data = $this.data();

      if (data.spinner) {
        data.spinner.stop();
        delete data.spinner;
      }
      if (opts !== false) {
        data.spinner = new Spinner($.extend({color: $this.css('color')}, opts)).spin(this);
      }
    });
    return this;
  };
  
$.fn.center = function (opt) {
      this.css("position","absolute");
      this.css({zIndex:3000});
      if (opt !== false) {
          this.css("top", (($(window).height() - this.outerHeight()) / 2) + $(window).scrollTop() + "px");
      } else {
          this.css("top", "0px");
      }
      this.css("left", (($(window).width() - this.outerWidth()) / 2) + $(window).scrollLeft() + "px");
      return this;
  }
