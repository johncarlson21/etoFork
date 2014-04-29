
var Etomite = {
    inAdmin: true,
    isMobile: false,
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
            var error = '';
            if(uname == null || uname == "" || uname == " "){
                error += "Username cannot be empty!\n";
            }
            if(pword == null || pword == "" || pword == " "){
                error += "Password cannot be empty!\n";
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
						$('.error').html('<i class="fa fa-ban"></i>' + json.message).show();
                    } else {
                        if (Etomite.isMobile) {
                            $('.success').css({color:'#62A624'});
                            $('.success').text('Logging you in!');
                        } else {
                            Etomite.notify('Logging you in. Please Wait');
                        }
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
        
        $.ajaxSetup({ type: 'POST' });
        
        if (Etomite.inAdmin == true) {
			Etomite.loadDocTree();
        }
        
    },
    
    checkLogin: function() {
        $.ajax({
            url: 'ActionServer.php',
            dataType: 'html',
            cache: false,
            data: {
                action: 'checkLogin'
            },
            success: function(response) {
                $(response).appendTo('body');
                return true;
            }
        });
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
                spin(false);
            }
        });
    },
    
    loadPane: function(content) {
        spin();
        var p = $('#mainContent');
        p.html(content);
        spin(false);
    },
    
    loadDocTree: function() {
		$.contextMenu('destroy', '#docTree .jqtree-tree');
		$('#docTree').tree('destroy');
		
		// create root context
		$('#rootNode').contextMenu({
			selector: 'span',
			autoHide: true,
			callback: function(key, options) {
				if (Etomite.editingDoc == true || Etomite.movingDoc == true) {
					alert('You are editing or moving a document! Please save or cancel first!');
					return;
				}
				var id = $(this).attr('data-id');
				switch( key ) {
					case "create":
						Etomite.editDocument('', id);
						break;
					case "createw":
						Etomite.editDocument('', id, 'true');
						break;
					case "purge":
						$('<div id="confirm"></div>').appendTo('body').html("<p>Are you sure you want to delete this document and all child documents?</p><p>THERE IS NO GOING BACK!</p>");
						$('#confirm').dialog({
							dialogClass: "no-close",
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
					case "refresh":
						Etomite.loadDocTree();
						break;
				}
			},
			items: {
				"create": {name: "Create Document Here", icon: "add"},
				"createw": {name: "Create Weblink Here", icon: "addw"},
				"sep1": "---------",
				"purge": {name: "Purge Documents", icon: "purge"},
				"refresh": {name: "Refresh Documents", icon: "refresh"}
			},
			events: {
				show: function(opt) {
					
				}
			}
		});
				
		$('#docTree').tree({
			dataUrl: 'ActionServer.php?action=getDocTree',
			dragAndDrop: true,
			autoOpen: false,
			onCreateLi: function(node, $li) {
				$li.find('.jqtree-element').first().attr('data-id', node.id);
				$li.find('.jqtree-element').first().attr('data-docurl', node.docUrl);
				$li.find('.jqtree-element').first().attr('data-weblink', node.weblink);
				if (node.is_folder == 1 && node.weblink == 1) {
					$li.find('.jqtree-title').before('<i class="fa fa-globe"></i> &nbsp;');
				} else if (node.is_folder == 1) {
					$li.find('.jqtree-title').before('<i class="fa fa-folder-o"></i> &nbsp;');	
				} else if (node.weblink == 1) {
					$li.find('.jqtree-title').before('<i class="fa fa-globe"></i> &nbsp;');
				} else {
					$li.find('.jqtree-title').before('<i class="fa fa-file-o"></i> &nbsp;');	
				}
				if (node.published == '0') {
					$li.find('.jqtree-title').addClass('unpublishedNode');
					$li.find('.fa').addClass('unpublishedNode');
				}
				if (node.showinmenu == '0') {
					$li.find('.jqtree-title').addClass('hiddenNode');
				}
				if (node.deleted == '1') {
					$li.find('.jqtree-title').addClass('deletedNode');
				}
				
			}
		});
				
		$('#docTree').bind(
			'tree.move',
			function(event) {
				var prevparent = event.move_info.previous_parent.id;
				var node = event.move_info.moved_node;
				event.preventDefault();
				event.move_info.do_move();
				var parent = node.parent.id;
				if (prevparent != parent) {
					// need to move this document
					if (parent == null || parent == 'undefined' || parent < 1) {
						parent = 0;
					}
					Etomite.updateDocProperty('parent', parent, node.id);
				}
				
				$.ajax({
					url: 'ActionServer.php',
					dataType: 'json',
					data: {
						action: 'saveTree',
						tree: $('#docTree').tree('toJson')
					},
					success: function(response) {
						if(response === null || response.succeeded !== true) {
                            Etomite.errorDialog('Error moving document', 'Error');
                        }
						Etomite.loadDocTree();
					} // end function
				});
			}
		);
		
		$('#docTree').bind(
			'tree.click',
			function(event) {
				var node = event.node;
				//event.preventDefault();
				if (Etomite.movingDoc) {
					var pid = node.id;
					Etomite.moveDocument(Etomite.movingDocId, pid);
				}
				if (Etomite.editingDoc && $('#parent').length > 0) {
					var parentname = node.name;
					var pid = node.id;
					if (pid == '0') { parentname += " (0)"; }
					$('#parent').val(pid);
					$('#parentName').text(parentname);
				}
			}
		);
		
		$('#docTree').bind(
			'tree.init',
			function() {
				$('#docTree .jqtree-tree').contextMenu({
					selector: 'li div',
					className: 'data-title',
					autoHide: true,
					zIndex: 9999,
					//determinePosition: {my: "center top", at: "center bottom", of: this, collision: "fit fit"},
					callback: function(key, options) {
						if (Etomite.editingDoc == true || Etomite.movingDoc == true) {
							alert('You are editing or moving a document! Please save or cancel first!');
							return;
						}
						// key is the action
						var id = $(this).attr('data-id');
						var docurl = $(this).attr('data-docurl');
						var weblink = $(this).attr('data-weblink');
						switch( key ) {
							case "preview":
								window.open(docurl);
								break;
							case "edit":
								if (weblink == 'true') {
									Etomite.editDocument(id, '', 'true');
								} else {
									Etomite.editDocument(id);
								}
								break;
							case "copy":
								if (weblink == 'true') {
									Etomite.duplicateDocument(id, 'true');
								} else {
									Etomite.duplicateDocument(id);
								}
								break;
							case "move":
								Etomite.moveDocumentDialog(id);
								Etomite.movingDoc = true;
								Etomite.movingDocId = id;
								break;
							case "create":
								Etomite.editDocument('', id);
								break;
							case "createw":
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
									dialogClass: "no-close",
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
									dialogClass: "no-close",
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
							case "showmenu":
								Etomite.updateDocProperty('showinmenu', '1', id);
								break;
							case "hidemenu":
								Etomite.updateDocProperty('showinmenu', '0', id);
								break;
							case "convdoc":
								Etomite.updateDocProperty('type', 'document', id);
								break;
							case "convlink":
								Etomite.updateDocProperty('type', 'reference', id);
								break;
						} // end switch
					},
					items: {
						"preview": {name: "Preview", icon: "preview"},
						"edit": {name: "Edit", icon: "edit"},
						"copy": {name: "Duplicate", icon: "copy"},
						"move": {name: "Change Parent Document", icon: "move"},
						"convdoc": {name: "Convert to Document", icon: "convert"},
						"convlink": {name: "Convert to Weblink", icon: "convert"},
						"sep1": "---------",
						"create": {name: "Create Document Here", icon: "add"},
						"createw": {name: "Create Weblink Here", icon: "addw"},
						"sep2": "---------",
						"publish": {name: "Publish Document", icon: "publish"},
						"unpublish": {name: "Un-publish Document", icon: "unpublish"},
						"sep3": "---------",
						"delete": {name: "Delete", icon: "delete"},
						"undelete": {name: "Undelete", icon: "undelete"},
						"sep4": "---------",
						"showmenu": {name: "Show in Menus", icon: "showmenu"},
						"hidemenu": {name: "Hide in Menus", icon: "hidemenu"}
					},
					events: {
						show: function(opt) {
							var name = $(this).find('.jqtree-title').first().text();
							$('.data-title').attr('data-menutitle', '');
							$('.data-title').attr('data-menutitle', name);
						}
					}
				});
			}
		);
    },
    
    /* CONTENT */
    editDocument: function(docId, parentId, weblink) {
        Etomite.editingDoc = true;
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
					if ($('#groupsWindow').hasClass('ui-dialog-content')) {
						$('#groupsWindow').dialog('destroy');
						$('#groupsWindow').dialog('remove');
					}
					
					Etomite.loadPane(response);
					
					$("#saveDocument").unbind( "click" );
					$("#saveCloseDocument").unbind( "click" );
					$("#deleteDocument").unbind( "click" );
					$("#cancelDocument").unbind( "click" );
                    
                    $('#saveDocument').click(saveDocHandler); /*function(){
                        Etomite.saveDocument();
                    });*/
					$('#saveCloseDocument').click(function() {
						Etomite.saveDocument(true);
					});
                    $('#deleteDocument').click(function(){
                        Etomite.updateDocProperty('deleted', '1', docId);
                    });
                    $('#cancelDocument').click(function(){
						Etomite.editingDoc = false;
						Etomite.movingDoc = false;
                        var content = '';
                        $.ajax({
                            url: 'ActionServer.php',
                            dataType: 'html',
                            data: {
                                action: 'loadWelcome'
                            },
                            success: function(html) {
                                $('#mainContent').html(html);
								activateCollapse();
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
    
    saveDocument: function(saveclose) {
        if(Etomite.goodAlias == true) {
            spin();
            // get the vars
            var docId = '';
            if($('#docId').length > 0){
                docId = $('#docId').val();
            } else {
                docId = '';
            }
            if ($('#type').val() == 'reference') {
                var tinyContent = $('#taContent').val();
            } else {
                /*var ed = tinyMCE.get('taContent');
                var tinyContent = ed.getContent();*/
                var tinyContent = $('#taContent').val();
            }
            
            // get tvs
            var tvs = $('#templateVars form').serializeArray();
			
			// build the user groups
			var document_groups = new Array();
			$.each($("input[name^='document_groups']"), function(){
				if ($(this).is(':checked')) {
					document_groups.push($(this).val());
				}
			});
            
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
                    meta_keywords: $('#meta_keywords').val(),
                    templateVars: tvs,
					groups: document_groups
                },
                success: function() {
                    spin(false);
                    Etomite.notify('Document Saved');
                    Etomite.loadDocTree();
                    if ($('#docId').length > 0 && saveclose !== true) {
                        if ($('#type').val() == 'reference') {
                            Etomite.editDocument(docId, '', 'true');
                        } else {
                            Etomite.editDocument(docId);
                        }
                    } else {
                        Etomite.loadPaneFromAction('loadWelcome');
						activateCollapse();
						Etomite.editingDoc = false;
                    }
                }
            });
        } else {
            Etomite.errorDialog(eLang.alias_exists, 'Error'); //'That alias already exists!' 
        }
        
    },
    
    updateDocProperty: function(prop, pval, docId) {
		Etomite.editingDoc = false;
		Etomite.movingDoc = false;
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
					Etomite.loadDocTree();
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
        $('<div id="moveDocumentDialog"></div>').appendTo('#mainContent');
        $('#moveDocumentDialog').html('<p>Are you sure you want to move the document?</p>');
        $('#moveDocumentDialog').dialog({
			dialogClass: "no-close",
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
					activateCollapse();
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
					activateCollapse();
                }
            }
        });
    },
	
	duplicateDocument: function(docId, weblink) {
		Etomite.editingDoc = true;
        if (weblink != 'true' || weblink == null || weblink == '') {
            weblink = false;
        }
        
        $.ajax({
            url: 'ActionServer.php',
            dataType: 'html',
            data: {
                action: 'duplicateDocument',
                reference: weblink,
                id: docId
            },
            success: function(response) {
                if (response === null) {
                    Etomite.errorDialog('There was an error', 'ERROR');
                    Etomite.editingDoc = false;
                } else {
					if ($('#groupsWindow').length > 0) {
						$('#groupsWindow').dialog('destroy');
						$('#groupsWindow').dialog('remove');
					}
					
					Etomite.loadPane(response);
                    
					$("#saveDocument").unbind( "click" );
					$("#saveCloseDocument").unbind( "click" );
					$("#deleteDocument").unbind( "click" );
					$("#cancelDocument").unbind( "click" );
					
                    $('#saveDocument').click(saveDocHandler); /*function(){
                        Etomite.saveDocument();
                    });*/
					$('#saveCloseDocument').click(function() {
						Etomite.saveDocument(true);
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
								activateCollapse();
								Etomite.editingDoc = false;
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
    
    purgeDocuments: function() {
        $.ajax({
            url: 'ActionServer.php',
            dataType: 'json',
            data: { action: 'purgeDocuments' },
            success: function(json) {
                if(json === null || json.succeeded !== true) {
                    Etomite.errorDialog(json.message, 'Error');
                } else {
                    Etomite.loadDocTree();
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
					setResourceTab();
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
        var tArea = $('#editResource #resource_editor').val();
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
                        spin(false);
						Etomite.notify('Resource Saved!');
                        if ($('#stay').is(':checked')) {
                            // only used for new insert
                            if((json.params !== null || json.params.id) && rId == '') {
                                $('#editResource #id').val(json.params.id);
                            }
                        } else {
                            Etomite.loadPaneFromAction('loadResourcesView');
							setResourceTab();
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
					setResourceTab();
                }
                spin(false);
            }
        });
    },
    
    createSection: function() {
        $('<div id="sectionForm"></div>').appendTo('body');
        $('#sectionForm').html('<p><strong>Category Name:</strong> <input type="text" id="sectionName" /></p>' +
                '<p><strong>Description:</strong> <input type="text" maxchars="100" id="sectionDescription" /></p>');
        $('#sectionForm').dialog({
            autoOpen: true,
            title: 'Create New Resource Category',
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
                            description: sectionDescription
                        },
                        success: function(json) {
                            if(json === null || json.succeeded !== true) {
                                Etomite.errorDialog('Error creating resource category!', 'Error');
                            } else {
                                Etomite.notify('New Resource Category Created!');
                                Etomite.loadPaneFromAction('loadResourcesView');
								setResourceTab('section');
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
    
    editTV: function(tv_id) {
        $.ajax({
            url: 'ActionServer.php',
            dataType: 'html',
            data: {
                action: 'editTV',
                id: tv_id
            },
            success: function(response) {
                if(response === null) {
                    Etomite.errorDialog('There was an error!', 'Error!');
                    return false;
                } else {
                    Etomite.loadPane(response);
                }
                
                // might need to add field name check here
                
                $('#saveTV').click(function() {
                    Etomite.saveTV();
                });
                $('#deleteTV').click(function() {
                    Etomite.deleteTV();
                });
                $('#cancelTV').click(function() {
                    Etomite.loadPaneFromAction('loadResourcesView');
					setResourceTab('tv');
                });
            }
        });
    },
    
    saveTV: function() {
        var id = $('#editTV #tv_id').val();
        var tName = $('#editTV #name').val();
        var tFName = $('#editTV #field_name').val();
        var tDescription = $('#editTV #description').val();
        var tDefaultVal = $('#editTV #default_val').val();
        var tType = $('#editTV #type option:selected').val();
        var tOType = $('#editTV #output_type option:selected').val();
        var tOpts = $('#editTV #opts').val();
        var tFSize = $('#editTV #field_size').val();
        var tFMSize = $('#editTV #field_max_size').val();
        var tOrder = $('#editTV #tv_order').val();
        var tRequired = $('#editTV #required').is(':checked') ? 1 : 0;
        var tTemplates = $('#editTV #templates').val();
        var errors = '';
        if (tName === null || tName == '' || tName == ' ') {
            errors += "TV Name is required!<br />";
        }
        if (tFName === null || tFName == '' || tFName == ' ') {
            errors += "TV Field Name is required!"
        }
        if (errors != '') {
            Etomite.errorDialog(errors, 'Alert!');
            return false;
        }
        
        $.ajax({
            url: 'ActionServer.php',
            dataType: 'json',
            data: {
                action: 'saveTV',
                tv_id: id,
                name: tName,
                field_name: tFName,
                description: tDescription,
                default_val: tDefaultVal,
                type: tType,
                output_type: tOType,
                opts: tOpts,
                field_size: tFSize,
                field_max_size: tFMSize,
                tv_order: tOrder,
                required: tRequired,
                templates: tTemplates
            },
            success: function(json) {
                if (json === null || json.succeeded !== true) {
                    Etomite.errorDialog(json.message, 'Error');
                } else {
                    Etomite.notify(json.message);
                    Etomite.loadPaneFromAction('loadResourcesView');
					setResourceTab('tv');
                }
            }
        });
        
    },
    
    deleteTV: function() {
        var id = $('#editTV #tv_id').val();
        var is_sure = window.confirm("Are you sure you want to remove this Template Variable?");
        if(is_sure) {
            $.ajax({
                url: 'ActionServer.php',
                dataType: 'json',
                data: {
                    action: 'deleteTV',
                    tv_id: id
                },
                success: function(json) {
                    if (json === null || json.succeeded !== true) {
                        Etomite.errorDialog(json.message, 'Error!');
                    } else {
                        Etomite.notify('Template Variable Removed!');
                        Etomite.loadPaneFromAction('loadResourcesView');
						setResourceTab('tv');
                    }
                }
            });
        }
    },
    
    elFinderWindow: function(element) {
        $('<div id="elfinder"></div>').appendTo('#mainContent');
        $('#elfinder').dialog({
            autoOpen: true,
            width: 800,
            height: 440,
            position: 'center',
            resizable: false,
            closeOnEscape: false,
            title: "File Manager",
            close: function() {
                $(this).dialog('destroy');
                $('#elfinder').remove();
            }
        });
        var elf = $('#elfinder').elfinder({
            // set your elFinder options here
            url: 'lib/elfinder2_0/php/connector.php',  // connector URL
            height: 430,
            contextmenu : {
                // navbarfolder menu
                navbar :
                  ['open', '|', 'copy', 'cut', 'paste', 'duplicate', '|', 'rm', '|', 'info'],
                // current directory menu
                cwd    :
                  ['reload', 'back', '|', 'upload', 'mkdir', 'mkfile', 'paste', '|', 'info'],
                // current directory file menu
                files  :
                  ['getfile', '|','open', 'quicklook', '|', 'download', '|', 'copy', 'cut', 'paste', 'duplicate', '|', 'rm', '|', 'edit', 'rename', 'resize', '|', 'archive', 'extract', '|', 'info']
                },
            customData : {token : '427820038402jfkals89802', inManager : 'true'},
            commands : [
                       'open', 'reload', 'home', 'up', 'back', 'forward', 'getfile', 'quicklook',
                       'download', 'rm', 'duplicate', 'rename', 'mkdir', 'mkfile', 'upload', 'copy',
                       'cut', 'paste', 'edit', 'resize', 'extract', 'archive', 'search', 'info', 'view', 'help'
                   ],
            getFileCallback: function(url) { // editor callback
              path = url;
              $('#'+element).val(path); // pass selected file path to element
              $('#elfinder').dialog('close');
              $('#elfinder').dialog('destroy');
              $('#elfinder').remove();
            }
          }).elfinder('instance');
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
        var sm = $('#editUser #sendmessages').is(':checked') ? 1 : 0;
        
        // need to add for permissions also
        
        var error = '';
        if (uname === null || uname == '') {
            error += "User Name is required!<br />";
        }
        
        if (fname === null || fname == '') {
            error += "Name is required!<br />";
        }
        
        if (em === null || em == '') {
            error += "Email is required!<br />";
        }
        
        if (newpw.length > 0) {
            if (newpw.length < 6) {
                error += "Password to short";
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
                    mailmessages: sm,
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
	
	editGroup: function(gid, gval) {
        $('<div id="groupForm"></div>').appendTo('body');
        $('#groupForm').html('<p><strong>Group Name:</strong> <input type="text" id="groupName" value="' + gval + '" /></p>');
        $('#groupForm').dialog({
            autoOpen: true,
            title: 'Edit User Group',
            minWidth: 200,
            minHeight: 200,
            position: 'center',
            resizable: false,
            closeOnEscape: false,
            modal: true,
            buttons: {
                Update: function() {
                    var groupName = $('#groupName').val();
					var groupId = gid;
                    $.ajax({
                        url: 'ActionServer.php',
                        dataType: 'json',
                        data: {
                            action: 'editGroup',
                            name: groupName,
							id: groupId
                        },
                        success: function(json) {
                            if(json === null || json.succeeded !== true) {
                                Etomite.errorDialog('Error updating user group!', 'Error');
                            } else {
                                Etomite.notify('User Group Updated!');
                                Etomite.loadPaneFromAction('loadGroupsView');
                            }
                            $('#groupForm').dialog('close');
                            $('#groupForm').remove();
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
	
	createGroup: function() {
        $('<div id="groupForm"></div>').appendTo('body');
        $('#groupForm').html('<p><strong>Group Name:</strong> <input type="text" id="groupName" /></p>');
        $('#groupForm').dialog({
            autoOpen: true,
            title: 'Create New User Group',
            minWidth: 200,
            minHeight: 200,
            position: 'center',
            resizable: false,
            closeOnEscape: false,
            modal: true,
            buttons: {
                Create: function() {
                    var groupName = $('#groupName').val();
                    $.ajax({
                        url: 'ActionServer.php',
                        dataType: 'json',
                        data: {
                            action: 'createGroup',
                            name: groupName
                        },
                        success: function(json) {
                            if(json === null || json.succeeded !== true) {
                                Etomite.errorDialog('Error creating user group!', 'Error');
                            } else {
                                Etomite.notify('New User Group Created!');
                                Etomite.loadPaneFromAction('loadGroupsView');
                            }
                            $('#groupForm').dialog('close');
                            $('#groupForm').remove();
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
	
	deleteGroup: function(gid) {
		if (gid === null || gid == '') {
            return false;
        }
        var is_sure = window.confirm('Are you sure you want to delete this group?\nThis will remove all User and Document connections to this group!');
        if (is_sure) {
            $.ajax({
                url: 'ActionServer.php',
                data: {
                    action: 'deleteGroup',
                    id: gid
                },
                dataType: 'json',
                success: function(json) {
                    if (json === null || json.succeeded !== true) {
                        Etomite.errorDialog('There was a problem, the group did not get deleted!');
                        return false;
                    } else {
                        Etomite.notify('User Group Deleted');
                        Etomite.loadPaneFromAction('loadGroupsView');
                    }
                }
            });
        }
	},
    
    manageModule: function(module) {
        if (module === null || module == '') {
            return false;
        }
        $.ajax({
            url: '../modules/module.php',
            dataType: 'html',
            data: {
                action: 'manageModule',
                mod: module
            },
            success: function(response) {
                if (response !== null) {
                    Etomite.loadPane(response);
                } else {
                    Etomite.errorDialog('There was an error loading that module', 'Error');
                }
            }
        });
    },
    
    moduleActionCall: function(url, params, container) {
        if (params === null || params == '') {
            return false;
        }
        $.ajax({
            url: (url.length > 0) ? url : '../modules/module.php',
            data: params,
            dataType: 'html',
            success: function(response) {
                if (container == null) {
                    Etomite.loadPane(response);
                } else {
                    spin();
                    $(container).html(response);
                    spin(false);
                }
            }
        });
    },
    
    installModule: function() {
        $.ajax({
            url: '../modules/module.php',
            dataType: 'html',
            data: {
                action: 'installModule'
            },
            success: function(response) {
                if (response === null) {
                    Etomite.errorDialog('There was an error trying to start install process!', 'Error!');
                } else {
                    Etomite.loadPane(response);
					
                    $('.install-module-btn').click(function() {
                        if ($('#module_name').val() === null || $('#module_name').val() == '') {
                            alert("Module name must not be empty!");
                            return false;
                        }
                        Etomite.runModuleInstall($('#module_name').val());
                    });
                    // load the file manager up to the modules directory
                    var elf = $('#fileManager').elfinder({
                        lang: 'en',             // language (OPTIONAL)
                        url : './lib/elfinder2_0/php/packages_connector.php',  // connector URL (REQUIRED)
                        height: 500,
                        docked: true,
                        contextmenu : {
                         // navbarfolder menu
                         navbar :
                           ['open', '|', 'copy', 'cut', 'paste', 'duplicate', '|', 'rm', '|', 'info'],
                         // current directory menu
                         cwd    :
                           ['reload', 'back', '|', 'upload', 'mkdir', 'mkfile', 'paste', '|', 'info'],
                         // current directory file menu
                         files  :
                           ['getfile', '|','open', 'quicklook', '|', 'download', '|', 'copy', 'cut', 'paste', 'duplicate', '|', 'rm', '|', 'edit', 'rename', 'resize', '|', 'archive', 'extract', '|', 'info']
                         },
                         customData : {token : '427820038402jfkals89802', inManager : 'true'},
                         commands : [
                                    'open', 'reload', 'home', 'up', 'back', 'forward', 'getfile', 'quicklook',
                                    'download', 'rm', 'duplicate', 'rename', 'mkdir', 'mkfile', 'upload', 'copy',
                                    'cut', 'paste', 'edit', 'resize', 'extract', 'archive', 'search', 'info', 'view', 'help'
                                ]
                    }).elfinder('instance');
                }
            }
        });
    },
    
    runModuleInstall: function(module) {
        if (module === null || module == '') {
            alert('Module name must not be empty!');
            return false;
        }
        $.ajax({
            url: '../modules/module.php',
            dataType: 'json',
            data: {
                action: 'runModuleInstall',
                module: module
            },
            success: function(json) {
                if(json === null || json.succeeded !== true) {
                    Etomite.errorDialog(json.message, 'Error!');
                    return false;
                } else {
                    Etomite.notify(json.message); // need to also maybe do a re-direct to module list
                    $('#moduleNav').click();
                    Etomite.reloadModuleNav();
					setTimeout(function() {
                        window.location.reload(true);
                    }, 1000);
                }
            }
        });
    },
    
    reloadModuleNav: function() {
        $.ajax({
            url: 'ActionServer.php',
            dataType: 'html',
            data: { action: 'reloadModuleNav'},
            success: function(response) {
                if (response !== null) {
                    $('#module-nav-list ul').remove();
                    $('#module-nav-list').append(response);
                }
            }
        });
    },
    
    showVisitorStats: function(params) { // params = url params: scope=total&start=xxxxxx
        $.ajax({
            url: 'ActionServer.php?action=showVisitorStats&' + params,
            dataType: 'html',
            success: function(response) {
                if (response !== null) {
                    Etomite.loadPane(response);
                } else {
                    return false;
                }
            }
        });
    },
    
    myMessages: function(params) {
        $.ajax({
            url: 'ActionServer.php?action=myMessages&' + params,
            dataType: 'html',
            success: function(response) {
                if(response !== null) {
                    Etomite.loadPane(response);
                } else {
                    return false;
                }
            }
        });
    },
    
    sendMyMessage: function() {
        var sendto = $('#messagefrm input:radio[name=sendto]:checked').val();
        var user = $('#messagefrm [name=user] option:selected').val();
        var group = $('#messagefrm [name=group] option:selected').val();
        var message = $('#messagefrm [name=messagebody]').val();
        if (sendto == 'u' && (user === null || user == '')) {
            Etomite.errorDialog('You are trying to send to a specific user!<br />You need to select a user!', 'ALERT!');
            return false;
        }
        if (sendto == 'g' && (group === null || group == '')) {
            Etomite.errorDialog('You are trying to send to a specific group!<br />You need to select a group!', 'ALERT!');
            return false;
        }
        if (message === null || message == '' || message == ' ') {
            Etomite.errorDialog('Message Body must not be empty!', 'ALERT!');
            return false;
        }
        var params = $('#messagefrm').serialize() + "&action=sendMyMessage";
        $.ajax({
            url: 'ActionServer.php',
            data: params,
            dataType: 'json',
            success: function(json) {
                if (json === null || json.succeeded !== true) {
                    Etomite.errorDialog(json.message, 'Error:');
                } else {
                    Etomite.notify(json.message);
                    Etomite.myMessages();
                }
            }
        });
    },
    
    deleteMyMessage: function(messageid) {
        if (messageid === null || messageid == '') {
            return false;
        }
        var is_sure = window.confirm('Are you sure you want to delete this message?');
        if (is_sure) {
            $.ajax({
                url: 'ActionServer.php',
                data: {
                    action: 'deleteMyMessage',
                    id: messageid
                },
                dataType: 'json',
                success: function(json) {
                    if (json === null || json.succeeded !== true) {
                        Etomite.errorDialog(json.message);
                        return false;
                    } else {
                        Etomite.notify('Message Deleted');
                        Etomite.myMessages();
                    }
                }
            });
        }
    },
    
    saveSiteSettings: function() {
        $.ajax({
            url: 'ActionServer.php',
            data: 'action=saveSiteSettings&' + $('#system-settings-form').serialize(),
            dataType: 'json',
            success: function(json) {
                if (json === null || json.succeeded !== true) {
                    Etomite.errorDialog(json.message, 'Error!');
                } else {
                    Etomite.notify(json.message + '<br />Refreshing Window!');
                    setTimeout(function() {
                        window.location.reload(true);
                    }, 1000);
                }
            }
        });
    },
    
    notify: function(message) {
        $('<div id="notification" class="alert alert-success"><i class="fa fa-check"></i> ' + message + '</div>').appendTo('body');
        $('#notification').css({zIndex:30000,position:'absolute'});
		$('#notification').position({my:'top',at:'top',of:window}); 
		$('#notification').css({marginTop:'20px'});
        setTimeout(function() {
            $('#notification').fadeOut('slow', function() {
            });
            $('#notification').remove();
        }, 2000);
    },
    
    errorDialog: function(message, dtitle) {
        $('<div id="errorDialog" style="margin-top:20px;"></div>').appendTo('#mainContent');
        $('#errorDialog').html('<p>' + message + '</p>');
        $('#errorDialog').dialog({
			dialogClass: "no-close",
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
    },
    
    showDebugWindow: function() {
        $('#debug-output').dialog({
            title: "DEBUG",
            autoOpen: true,
            width: 600,
            height: 400,
            resizable: true,
            position: 'top',
            modal: false
        });
    }
    
}// end etomite var

var saveDocHandler = function() {
    Etomite.saveDocument();
};
