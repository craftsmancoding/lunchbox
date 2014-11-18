/**
 * Drill down into a folder
 *
 */
function drillDown(id) {
    get_children(id,0);
    // update the breadcrumbs
    setBreadcrumbs(id);
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
                    $('#child_pages').after('<div id="lunchbox_breadcrumbs">Testing</div>');
                }
                
                $('#lunchbox_breadcrumbs').html(response);
            }   
        }); 
}


/**
 * @param integer offset
 * @param string sort column name
 * @param string dir ASC|DESC 
 */
function get_children(parent,offset,sort,dir) {
    console.log(sort_col);
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
        }   
    }); 
}


/**
 * @param integer offset
 * @param string sort column name
 * @param string dir ASC|DESC 
 */
function get_children2(obj,parent,offset,sort,dir) {
    var target = $(obj).data('target');
    parent = typeof parent !== "undefined" ? parent : 0;
    offset = typeof offset !== "undefined" ? offset : 0;
    sort = typeof sort !== "undefined" ? sort : sort_col;
    dir = typeof dir !== "undefined" ? dir : "ASC";
    var url = connector_url+"&class=page&method=records&parent="+parent+"&offset="+offset+"&sort="+sort+"&dir="+dir+"&_nolayout=1";

    console.log("[Lunchbox get_children()2] requesting URL TEST");

    jQuery.ajax({ 
        type: "GET", 
        url: url,
        success: function(response) {
            $("#"+target).html(response);
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
            $('#parent-modal').html(response);
        }   
    }); 
    event.preventDefault();
}