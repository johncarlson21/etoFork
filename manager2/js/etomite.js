
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
    editingDoc: false,
    movingDoc: false,
    movingDocId: '',
    usingEAEditor: false,
    goodResource: true,
    resourceTabSelected: 0,
    goodUsername: true,

    init: function() {
        /* login page */
        $('#loginBtn').click(function(){
            $('.error').text('').hide();
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
                        $('.error').text('Error: ' + json.message).show();
                    } else {
                        Etomite.notify('Logging you in. Please Wait');
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
        $('#loginPage #username').focus();
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
    
    loadPaneFromAction: function(act) {
        if (act != 'manageDocument') {
            Etomite.editingDoc = false;
        }
        if(act === null || act == '' || act == ' ') {
            return false;
        }
        spin();
        $.ajax({
            url: 'ActionServer.php',
            dataType: 'html',
            data: {
                action: act
            },
            success: function (content) {
                var p = $('#mainContent');
                p.html(content);
                Etomite.outerLayout.initContent('center');
                setTimeout(function() {
                    spin(false);
                }, 1000);
            }
        });
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
            debugLevel: 0,
            onClick: function(node, event) {
                if (Etomite.movingDoc) {
                    var pid = node.data.key.replace(/id_/, '');
                    Etomite.moveDocument(Etomite.movingDocId, pid);
                    return false;
                }
                if (Etomite.editingDoc && $('#parent').length > 0) {
                    var parentname = node.data.title;
                    var pid = node.data.key.replace(/id_/, '');
                    if (pid == '0') { parentname += " (0)"; }
                    $('#parent').val(pid);
                    $('#parentName').text(parentname);
                    return false;
                }
                // Close menu on click
                if( $(".contextMenu:visible").length > 0 ){
                    $(".contextMenu").hide();
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
                if (node.data.key == "id_0") {
                    bindRootContextMenu(node, span);
                } else {
                    bindContextMenu(node, span);
                }
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
        Etomite.editingDoc = true;
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
                    Etomite.editingDoc = false;
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
                    $('#deleteDocument').click(function(){
                        Etomite.updateDocProperty('deleted', '1', docId);
                    });
                    $('#cancelDocument').click(function(){
                        var content = '';
                        $.ajax({
                            url: 'ActionServer.php',
                            dataType: 'html',
                            data: {
                                action: 'loadWelcome'
                            },
                            success: function(html) {
                                $('#mainContent').html(html);
                            }
                        });
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
                                        $('#aliasUniqueMessage').html(json.message);
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
            if ($('#type').val() == 'reference') {
                var tinyContent = $('#taContent').val();
            } else {
                var ed = tinyMCE.get('taContent');
                var tinyContent = ed.getContent();
            }
            
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
                    Etomite.notify('Document Saved');
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
    
    moveDocumentDialog: function(docid) {
        $.ajax({
            url: 'ActionServer.php',
            dataType: 'html',
            data: {
                action: 'moveDocDialog',
                id: docid
            },
            success: function(html) {
                if(html === null) {
                    Etomite.errorDialog('You are not supposed to be here!', 'Error');
                } else {
                    Etomite.loadPane(html);
                }
            }
        });
    },
    
    moveDocument: function(docId, parentId) {
        // alert('moving document: '+docId+'; to parent: '+parentId);
        $('<div id="moveDocumentDialog"></div>').appendTo('#mainContent');
        $('#moveDocumentDialog').html('<p>Are you sure you want to move the document?</p>');
        $('#moveDocumentDialog').dialog({
            autoOpen: true,
            minWidth: 200,
            minHeight: 200,
            position: 'center',
            resizable: false,
            closeOnEscape: false,
            title: 'Move Document',
            modal: true,
            buttons: {
                Yes: function() {
                    //alert('parentid: '+parentId+"; document: "+docId); return false;
                    Etomite.updateDocProperty('parent', parentId, docId);
                    Etomite.movingDoc = false;
                    Etomite.movingDocId = '';
                    
                    Etomite.loadPaneFromAction('loadWelcome');
                    $(this).dialog('close');
                    $(this).dialog('destroy');
                    $('#moveDocumentDialog').remove();
                },
                No: function() {
                    $(this).dialog('close');
                    $(this).dialog('destroy');
                    $('#moveDocumentDialog').remove();
                    Etomite.movingDoc = false;
                    Etomite.movingDocId = '';
                    Etomite.loadPaneFromAction('loadWelcome');
                }
            }
        });
    },
    
    removeLocks: function() {
        $.ajax({
            url: 'ActionServer.php',
            dataType: 'json',
            data: {
                action: 'removeLocks'
            },
            success: function(json) {
                if(json === null || json.succeeded !== true) {
                    Etomite.errorDialog(json.message, 'Error');
                } else {
                    Etomite.notify(json.message);
                    Etomite.loadPaneFromAction('loadSystemInfo');
                }
            }
        });
    },
    
    syncSite: function() {
        $.ajax({
            url: 'ActionServer.php',
            dataType: 'html',
            data: {
                action: 'syncSite'
            },
            success: function(response) {
                if (response) {
                    Etomite.errorDialog(response, 'Clear Cache!');
                }
            }
        });
    },
    
    editResource: function(rtype, docId) {
        $.ajax({
            url: 'ActionServer.php',
            dataType: 'html',
            data: {
                action: 'editResource',
                type: rtype,
                id: docId
            },
            success: function(response) {
                if (response === null) {
                    Etomite.errorDialog('There was an error!', 'Error')
                } else {
                    Etomite.loadPane(response);
                }
                
                $('#name').keyup(function() {
                    Etomite.goodResource = true;
                    var rId = '';
                    if($('#editResource #id').length > 0){
                        rId = $('#id').val();
                    }
                    if ($('#name').val().length > 2) {
                        $.ajax({
                            url: 'ActionServer.php',
                            dataType: 'json',
                            data: { name: $('#name').val(), type: $('#type').val(), action: 'checkResourceName', id: rId },
                            success: function(json) {
                                if ((json === null) || (json.succeeded !== true)) {
                                    $('#uniqueResourceName').show();
                                    $('#uniqueResourceName').html(json.message);
                                    $('#uniqueResourceName').css('color','red');
                                    Etomite.goodResource = false;
                                } else {
                                    $('#uniqueResourceName').show();
                                    $('#uniqueResourceName').html(json.message);
                                    $('#uniqueResourceName').css('color','green');
                                    Etomite.goodResource = true;
                                }
                            }
                        });
                    } else {
                        $('#uniqueResourceName').hide();
                    }
                });
                
                $('#saveResource').click(function() {
                    Etomite.saveResource();
                });
                $('#deleteResource').click(function() {
                    Etomite.deleteResource();
                });
                $('#cancelResource').click(function() {
                    Etomite.loadPaneFromAction('loadResourcesView');
                });
            }
        });
    },
    
    saveResource: function() {
        if (Etomite.goodResource === false) {
            Etomite.errorDialog('The resource name you are trying to use, already exists or contains invalid characters!', 'Alert')
            return false;
        }
        var rId = $('#editResource #id').val();
        var rType = $('#type').val();
        var rName = $('#name').val();
        var rDesc = $('#description').val();
        var rSection = '';
        var rLocked = $('#locked').is(':checked') ? 1 : 0;
        if (rType != 'template') {
            rSection = $('#editResource #section').val();
        }
        var tArea = $('#editResource #resourc_editor').val();
        if (Etomite.usingEAEditor) {
            tArea = editAreaLoader.getValue('resource_editor');
        }
        var errors = '';
        if (rName === null || rName == '' || rName == ' ') {
            errors =+ "Name is required!<br />";
        }
        if (rType === null || rType == '' || rType == ' ') {
            Etomite.errorDialog('No Valid Resource Type!<br />Sending you back to Manage Resources', 'Error');
            setTimeout(function(){ Etomite.loadPaneFromAction('loadResourcesView');}, 1000);
        }
        if (errors != ''){
            Etomite.errorDialog(errors, 'errors');
        } else {
            spin();
            $.ajax({
                url: 'ActionServer.php',
                dataType: 'json',
                data: {
                    action: 'saveResource',
                    id: rId,
                    type: rType,
                    name: rName,
                    description: rDesc,
                    section: rSection,
                    content: tArea,
                    locked: rLocked
                },
                success: function(json) {
                    if (json === null || json.succeeded !== true) {
                        Etomite.errorDialog(json.message, 'Error');
                    } else {
                        setTimeout(function(){ spin(false); }, 1000);
                        if ($('#stay').is(':checked')) {
                            // only used for new insert
                            if(json.params !== null || json.params.id) {
                                $('#editResource #id').val(json.params.id);
                            }
                        } else {
                            Etomite.loadPaneFromAction('loadResourcesView');
                        }
                    }
                }
            });
        }
        
    },
    
    deleteResource: function() {
        var rType = $('#editResource #type').val();
        var rId = $('#editResource #id').val();
        if (rType == '' || rType == " " || rType === null || rId == '' || rId === null || rId == " ") {
            Etomite.errorDialog('That is not a valid Resource!', 'Error');
            return false;
        }
        var is_sure = window.confirm('Are you sure you want to delete this resource?');
        if (!is_sure) {
            return false;
        }
        spin();
        $.ajax({
            url: 'ActionServer.php',
            dataType: 'json',
            data: {
                action: 'deleteResource',
                id: rId,
                type: rType
            },
            success: function(json) {
                if (json === null || json.succeeded !== true) {
                    Etomite.errorDialog('There was an error deleting this resource!', 'Error');
                } else {
                    Etomite.notify(json.message);
                    Etomite.loadPaneFromAction('loadResourcesView');
                }
                spin(false);
            }
        });
    },
    
    createSection: function(rType) {
        if (rType ===null || rType == '') {
            return false;
        }
        $('<div id="sectionForm"></div>').appendTo('body');
        $('#sectionForm').html('<p><strong>Section Name:</strong> <input type="text" id="sectionName" /></p>' +
                '<p><strong>Description:</strong> <input type="text" maxchars="100" id="sectionDescription" /></p>');
        $('#sectionForm').dialog({
            autoOpen: true,
            title: 'Create New Resource Section',
            minWidth: 200,
            minHeight: 200,
            position: 'center',
            resizable: false,
            closeOnEscape: false,
            modal: true,
            buttons: {
                Create: function() {
                    var sectionName = $('#sectionName').val();
                    var sectionDescription = $('#sectionDescription').val();
                    $.ajax({
                        url: 'ActionServer.php',
                        dataType: 'json',
                        data: {
                            action: 'createSection',
                            name: sectionName,
                            description: sectionDescription,
                            section_type: rType
                        },
                        success: function(json) {
                            if(json === null || json.succeeded !== true) {
                                Etomite.errorDialog('Error creating resource section!', 'Error');
                            } else {
                                Etomite.notify('New Resource Section Created!');
                                Etomite.loadPaneFromAction('loadResourcesView');
                            }
                            $('#sectionForm').dialog('close');
                            $('#sectionForm').remove();
                        }
                    });
                },
                Cancel: function() {
                    $(this).dialog('close');
                    $(this).remove();
                }
            }
        });
    },
    
    editUser: function(uid) {
        $.ajax({
            url: 'ActionServer.php',
            dataType: 'html',
            data: {
                action: 'editUser',
                id: uid
            },
            success: function(response) {
                if(response === null) {
                    Etomite.errorDialog('There was an error!', 'Error');
                } else {
                    Etomite.loadPane(response);
                }
                
                $('#saveUser').click(function() {
                    Etomite.saveUser();
                });
                $('#deleteUser').click(function() {
                    Etomite.deleteUser(uid);
                });
                $('#cancelUser').click(function() {
                    Etomite.loadPaneFromAction('loadUsersView');
                });
                
                $('#editUser #username').keyup(function() {
                    Etomite.goodResource = true;
                    var uId = '';
                    if($('#editUser #id').length > 0){
                        uId = $('#id').val();
                    }
                    if ($('#username').val().length > 2) {
                        $.ajax({
                            url: 'ActionServer.php',
                            dataType: 'json',
                            data: { username: $('#editUser #username').val(), action: 'checkUsername', id: uId },
                            success: function(json) {
                                if ((json === null) || (json.succeeded !== true)) {
                                    $('#uniqueUsername').show();
                                    $('#uniqueUsername').html(json.message);
                                    $('#uniqueUsername').css('color','red');
                                    Etomite.goodUsername = false;
                                } else {
                                    $('#uniqueUsername').show();
                                    $('#uniqueUsername').html(json.message);
                                    $('#uniqueUsername').css('color','green');
                                    Etomite.goodUsername = true;
                                }
                            }
                        });
                    } else {
                        $('#uniqueUsername').hide();
                    }
                });
            }
        });
    },
    
    saveUser: function() {
        if (Etomite.goodUsername === false) {
            Etomite.errorDialog('The Username you are trying to use, already exists or contains invalid characters!', 'Alert')
            return false;
        }
        // build the user groups
        var user_groups = new Array();
        $.each($("input[name^='user_group']"), function(){
            if ($(this).is(':checked')) {
                //alert($(this).val());
                user_groups.push($(this).val());
            }
        });
        var uid = $('#editUser #id').val();
        var uname = $('#editUser #username').val();
        var newpw = $('#editUser #newpassword').val();
        var fname = $('#editUser #fullname').val();
        var em = $('#editUser #email').val();
        var ph = $('#editUser #phone').val();
        var mph = $('#editUser #mobilephone').val();
        var rl = $('#editUser #role').val();
        var bl = $('#editUser #blocked').is(':checked') ? 1 : 0;
        
        // need to add for permissions also
        
        var error = '';
        if (uname === null || uname == '') {
            error =+ "User Name is required!<br />";
        }
        
        if (fname === null || fname == '') {
            error =+ "Name is required!<br />";
        }
        
        if (em === null || em == '') {
            error =+ "Email is required!<br />";
        }
        
        if (newpw.length > 0) {
            if (newpw.length < 6) {
                error =+ "Password to short";
            }
        }
        
        if (error != '') {
            Etomite.errorDialog(error, 'Error');
        } else {
            $.ajax({
                url: 'ActionServer.php',
                dataType: 'json',
                data: {
                    action: 'saveUser',
                    id: uid,
                    username: uname,
                    password: newpw,
                    fullname: fname,
                    email: em,
                    phone: ph,
                    mobilephone: mph,
                    role: rl,
                    blocked: bl,
                    usergroups: user_groups
                },
                success: function(json) {
                    if (json === null || json.succeeded !== true) {
                        Etomite.errorDialog(json.message, 'Error');
                        return false;
                    } else {
                        if (uid > 0) {
                            Etomite.notify('User Updated!');
                        } else {
                            Etomite.notify('User Created!');
                        }
                        Etomite.loadPaneFromAction('loadUsersView');
                    }
                }
            });
        }
        
    },
    
    changePassword: function() {
        $('<div id="changePasswordDialog"></div>').appendTo('body');
        $('#changePasswordDialog').html('<p><strong>Password:</strong> <input type="text" name="newpassword" id="newpassword" /></p>' +
            '<p><strong>Confirm:</strong> <input type="text" name="confirm" id="confirm" /></p>' +
            '<p>Hint: Use the Generate button to create a password.</p>');
        $('#changePasswordDialog').dialog({
            autoOpen: true,
            title: 'Change Password',
            minWidth: 200,
            minHeight: 200,
            position: 'center',
            resizable: false,
            closeOnEscape: false,
            modal: true,
            buttons: {
                Save: function() {
                    var newpassword = $('#newpassword').val();
                    var confirm = $('#confirm').val();
                    
                    if (newpassword != confirm) {
                        Etomite.errorDialog('Password does not confirm', 'Alert!');
                        return false;
                    } else {
                        $.ajax({
                            url: 'ActionServer.php',
                            dataType: 'json',
                            data: {
                                action: 'changeUserPassword',
                                password: newpassword
                            },
                            success: function(json) {
                                if (json === null || json.succeeded !== true) {
                                    Etomite.errorDialog(json.message, 'Error');
                                } else {
                                    $(this).dialog('close');
                                    $(this).dialog('destroy');
                                    $('#changePasswordDialog').remove();
                                    Etomite.notify('Password Changed!');
                                }
                            }
                        });
                    }
                },
                Cancel: function() {
                    $(this).dialog('close');
                    $(this).dialog('destroy');
                    $('#changePasswordDialog').remove();
                },
                Generate: function() {
                    Etomite.generateMyPassword();
                }
            }
        });
    },
    
    deleteUser: function(uid) {
        if (uid === null || uid == "") {
            Etomite.errorDialog('That is not a valid user!', 'Error');
            return false;
        }
        var is_sure = window.confirm('Are you sure you want to delete this user?');
        if (!is_sure) {
            return false;
        }
        $.ajax({
            url: 'ActionServer.php',
            dataType: 'json',
            data: {
                action: 'deleteuser',
                id: uid
            },
            success: function(json) {
                if (json === null || json.succeeded !== true) {
                    Etomite.errorDialog(json.message, 'Error');
                    return false;
                } else {
                    Etomite.notify('User Deleted!');
                    Etomite.loadPaneFromAction('loadUsersView');
                }
            }
        });
    },
    
    generateMyPassword: function() {
        var pw = getPassword();
        $('#newpassword').val(pw);
        $('#confirm').val(pw);
    },
    
    generatePassword: function() {
        var pw = getPassword();
        $('#newpassword').val(pw);
    },
    
    resetUserFailed: function(uid) {
        if (uid) {
            $.ajax({
                url: 'ActionServer.php',
                dataType: 'json',
                data: {
                    action: 'resetUserFailed',
                    id: uid
                },
                success: function(json) {
                    if (json === null || json.succeeded !== true) {
                        return false;
                    } else {
                        $('#editUser #failed').text('0');
                        $('#editUser #blocked-msg').hide();
                        $('#editUser #blocked').removeAttr('checked');
                        Etomite.notify('Failed Login Count Reset!<br />User no longer blocked!');
                    }
                }
            });
        }
    },
    
    editRole: function(rid) {
        $.ajax({
            url: 'ActionServer.php',
            dataType: 'html',
            data: {
                action: 'editRole',
                id: rid
            },
            success: function(response) {
                if (response === null) {
                    Etomite.errorDialog('There was an error opening the role');
                    return false;
                } else {
                    Etomite.loadPane(response);
                }
                $('#saveRole').click(function() {
                    Etomite.saveRole();
                });
                $('#deleteRole').click(function() {
                    Etomite.deleteRole(rid);
                });
                $('#cancelRole').click(function() {
                    Etomite.loadPaneFromAction('loadUsersView');
                });
            }
        });
    },
    
    saveRole: function() {
        var params = $('#editRoleForm').serialize() + "&action=saveRole";
        $.ajax({
            url: 'ActionServer.php',
            data: params,
            dataType: 'json',
            success: function(json) {
                if (json === null || json.succeeded !== true) {
                    Etomite.errorDialog(json.message, 'Error');
                } else {
                    Etomite.notify('Role Updated');
                    Etomite.loadPaneFromAction('loadUsersView');
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
            autoOpen: true,
            minWidth: 300,
            minHeight: 300,
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
