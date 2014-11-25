/**
 * Drill down into a folder
 *
 */
function drillDown(id,in_modal) {
    get_children(id,0);
    // update the breadcrumbs
    if(in_modal == 1) {
        setBreadcrumbsModal(id);
    } else {
        setBreadcrumbs(id);
    }
    
}

/**
 * See http://www.sencha.com/forum/showthread.php?21756-How-do-I-add-plain-text-to-a-Panel 
 * And http://www.sencha.com/forum/showthread.php?38841-Using-Extjs-to-change-div-content
 */
function setBreadcrumbs(page_id) {
    jQuery.ajax({ 
            type: "GET", 
            url: Lunchbox.connector_url+'&class=page&method=breadcrumbs&page_id='+page_id+'&in_modal=0',
            success: function(response) {
                if($('#lunchbox_breadcrumbs').length == 0) {
                    $('#child_pages').after('<div id="lunchbox_breadcrumbs">Testing</div>');
                }
                
                $('#lunchbox_breadcrumbs').html(response);
            }   
        }); 
}


/**
 * See http://www.sencha.com/forum/showthread.php?21756-How-do-I-add-plain-text-to-a-Panel 
 * And http://www.sencha.com/forum/showthread.php?38841-Using-Extjs-to-change-div-content
 */
function setBreadcrumbsModal(page_id) {
    jQuery.ajax({ 
            type: "GET", 
            url: Lunchbox.connector_url+'&class=page&method=breadcrumbs&page_id='+page_id+'&in_modal=1',
            success: function(response) {
                if($('#lunchbox_breadcrumbs_modal').length == 0) {
                    $('#child_pages_modal').after('<div id="lunchbox_breadcrumbs_modal">Testing</div>');
                }
                
                $('#lunchbox_breadcrumbs_modal').html(response);
            }   
        }); 
}


/**
 * display main lunchbox layout
 */
function display_lunchbox() {
   
    var url = Lunchbox.connector_url+"&class=page&method=children&_nolayout=1";

    console.log("[Lunchbox get_children()] requesting URL",url);

    jQuery.ajax({ 
        type: "GET", 
        url: url,
        success: function(response) {
            $("#child_pages").html(response);
        }   
    }); 
}


function search_parent() {
    console.log('Searching [Lunchbox]');
    var form = $('#search-parent');
    var target = form.data('target');
    console.log(target);
    var values = form.serialize();
    var url = form.attr('action');    
        $.ajax({
            url: url,  
            data: values,  
            success: function( response )  
            {
                 $("#"+target).html(response);               
            }
       });
    
    event.preventDefault();
}


function set_parent(obj) {
    console.log('setting parent [Lunchbox]');
    var form = $(obj).parent();
    var values = form.serialize();
    var url = form.attr('action');   
        $.ajax({
            type: "POST",
            url: url,  
            data: values,  
            success: function( response )  
            {
                $('#parent-modal').modal('hide');
                data = $.parseJSON(response);

                if(data.success == true) {
                    $('.lu-msg').html('<div class="success">'+data.msg+'</div>')
                    .delay(2000).fadeOut(function() {
                       location.reload();
                    });

                } else{
                    $('.lu-msg').html('<div class="danger">'+data.msg+'</div>')
                    .delay(2000).fadeOut();
                }          
            }
       });
    event.preventDefault();
}
