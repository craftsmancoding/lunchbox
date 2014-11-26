/**
 * Drill down into a folder
 *
 */
function drillDown(id) {
    get_children(id,0);
    setBreadcrumbs(id);
}

function drillDownModal(id) {
    get_children_modal(id,0);
    setBreadcrumbsModal(id);
}

/**
 * See http://www.sencha.com/forum/showthread.php?21756-How-do-I-add-plain-text-to-a-Panel 
 * And http://www.sencha.com/forum/showthread.php?38841-Using-Extjs-to-change-div-content
 */
function setBreadcrumbs(page_id) {
    jQuery.ajax({ 
            type: "GET", 
            url: connector_url+'&class=page&method=breadcrumbs&page_id='+page_id,
            success: function(response) {
                if($('#lunchbox_breadcrumbs').length == 0) {
                    $('#child_pages').after('<div id="lunchbox_breadcrumbs"></div>');
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
            url: connector_url+'&class=page&method=breadcrumbsmodal&page_id='+page_id,
            success: function(response) {
                if($('#lunchbox_breadcrumbs_modal').length == 0) {
                    $('#child_pages_modal').after('<div id="lunchbox_breadcrumbs_modal">Testing</div>');
                }
                
                $('#lunchbox_breadcrumbs_modal').html(response);
            }   
        }); 
}


/**
 * @param integer offset
 * @param string sort column name
 * @param string dir ASC|DESC 
 */
function get_children(parent,offset,sort,dir) {
    parent = typeof parent !== "undefined" ? parent : 0;
    offset = typeof offset !== "undefined" ? offset : 0;
    sort = typeof sort !== "undefined" ? sort : sort_col;
    dir = typeof dir !== "undefined" ? dir : "ASC";
    var url = connector_url+"&class=page&method=children&parent="+parent+"&offset="+offset+"&sort="+sort+"&dir="+dir+"&_nolayout=1";

    console.log("[Lunchbox get_children()] requesting URL",url);

    jQuery.ajax({ 
        type: "GET", 
        url: url,
        success: function(response) {
            $("#child_pages").html(response);
            setBreadcrumbs(parent);
        }   
    }); 
}


/**
 * @param integer offset
 * @param string sort column name
 * @param string dir ASC|DESC 
 */
function get_children_modal(parent,offset,sort,dir) {
    parent = typeof parent !== "undefined" ? parent : 0;
    offset = typeof offset !== "undefined" ? offset : 0;
    sort = typeof sort !== "undefined" ? sort : sort_col;
    dir = typeof dir !== "undefined" ? dir : "ASC";
    var url = connector_url+"&class=page&method=parents&parent="+parent+"&offset="+offset+"&sort="+sort+"&dir="+dir+"&_nolayout=1";

    console.log("[Lunchbox get_children()] requesting URL",url);

    jQuery.ajax({ 
        type: "GET", 
        url: url,
        success: function(response) {
            $("#child_pages_modal").html(response);
            setBreadcrumbsModal(parent);
        }   
    }); 
}

function show_all_child(parent){
   return get_children(parent);
}

function launch_modal_parent(obj) {      
    $.ajax({ 
        type: "GET", 
        url: $(obj).attr('href'), 
        success: function(response) { 
            $('#parent-modal').modal('show');
            $('#child_pages_modal').html(response);
        }   
    }); 
    event.preventDefault();
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
