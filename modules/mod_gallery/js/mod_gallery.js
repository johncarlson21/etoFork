/* mod_gallery js functions */

function editItem(id, galId) {
    $('#editDialog').dialog({
        autoOpen: true,
        title: 'Edit Gallery Item',
        modal: true,
        width: 500,
        position: 'top',
        resizable: false,
        open: function() {
            $('#editDialog').load('index.php', {
                a: 'module',
                mod: 'mod_gallery',
                action: 'editItem',
                itemId: id
            },
            function(response, status, xhr){
                if (status == 'error') {
                    var msg = 'Sorry but there was an error: ';
                    msg =+ xhr.status + " " + xhr.statusText;
                    alert(msg);
                }
                
                $('#editItemBtn').click(function(){
                    var item_title = $('#item_title').val();
                    var item_description = $('#item_description').val();
                    var item_slide = $('#item_slide').is(':checked') ? 1 : 0;
                    if(item_title==""){ alert("Title must not be empty"); return;}
                    $.ajax({
                        url: 'index.php',
                        dataType: 'json',
                        data: {
                            a: 'module',
                            mod: 'mod_gallery',
                            action: 'editItem',
                            itemId: id,
                            edit: true,
                            title: item_title,
                            description: item_description,
                            slide: item_slide
                        },
                        success: function(json){
                            if ((json === null) || (json.succeeded !== true)) {
                                alert(json.message);
                                return;
                            }
                            alert("Item saved!");
                            window.location="index.php?a=module&mod=mod_gallery&action=listGalleryItems&galId=" + galId;
                        }
                    });
                });
                
            });
        }
    });
}

function deleteItem(id, galId) {
    var is_sure = confirm("Are you sure that you want to delete this item?");
    if(is_sure) {
        $.ajax({
            url: 'ActionServer.php',
            dataType: 'json',
            data: {
                action: 'manageModule',
                mod: 'mod_gallery',
                moduleAction: 'deleteItem',
                itemId: id
            },
            success: function(json){
                if (json === null || json.succeeded !== true) {
                    alert(json.message);
                    return false;
                }
                alert("Item removed!");
                Etomite.moduleActionCall('', "action=manageModule&mod=mod_gallery&moduleAction=listGalleryItems&galId=" + galId);
            }
        });
    } else {
        return false;
    }
}