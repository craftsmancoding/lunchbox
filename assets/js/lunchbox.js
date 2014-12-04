var queue = [];
var selected = 0;
function add_to_queue(obj) {
    var page_id = $(obj).data('id');
    var pagetitle = $(obj).data('pagetitle');
    var content = '<tr>'+
        '<td>'+page_id+'<input type="hidden" name="child[]" value="'+page_id+'"/></td>'+
        '<td>'+pagetitle+'</td>'+
        '<td><a href="#" class="btn btn-mini btn-remove" data-id="'+page_id+'" onclick="javascript:remove_q(this);">x</a></td>'+
        '</tr>';
    $('#q-body').append(content);
    queue.push(page_id);
    $(obj).parent('td').parent('tr').addClass('hide-row');
    console.log(queue);
}



function draw_modal_header(selected) {    
    var url = connector_url+"&class=page&method=modalheader&selected="+selected;
    $.ajax({ 
        type: "GET", 
        url: url, 
        success: function(response) {
            $('.selected-header').html(response);
        }   
    });
}


/**
 * Drill down into a folder
 *
 */
function drillDown(id) {
    get_children(id,0);
    setBreadcrumbs(id);
}

function drillDownModal(id) {
    get_parent_modal(id,0);
    get_children_modal(id,0);
    setBreadcrumbsModal(id);
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
 * display records for parent selection
 * @param integer offset
 * @param string sort column name
 * @param string dir ASC|DESC 
 */
function get_parent_modal(parent,offset,sort,dir) {
    parent = typeof parent !== "undefined" ? parent : 0;
    offset = typeof offset !== "undefined" ? offset : 0;
    sort = typeof sort !== "undefined" ? sort : 'menuindex';
    dir = typeof dir !== "undefined" ? dir : "ASC";
    var url = connector_url+"&class=page&method=parents&parent="+parent+"&offset="+offset+"&sort="+sort+"&dir="+dir+"&_nolayout=1&selected="+selected;

    console.log("[Lunchbox get_children()] requesting URL",url);

    jQuery.ajax({ 
        type: "GET", 
        url: url,
        success: function(response) {
            $("#set-parent-modal-content").html(response);
            setBreadcrumbsModal(parent);
        }   
    }); 
}

/**
 * display records for chidlren selection
 * @param integer offset
 * @param string sort column name
 * @param string dir ASC|DESC 
 */
function get_children_modal(parent,offset,sort,dir) {
    parent = typeof parent !== "undefined" ? parent : 0;
    offset = typeof offset !== "undefined" ? offset : 0;
    sort = typeof sort !== "undefined" ? sort : 'menuindex';
    dir = typeof dir !== "undefined" ? dir : "ASC";
    var url = connector_url+"&class=page&method=selectchildren&parent="+parent+"&offset="+offset+"&sort="+sort+"&dir="+dir+"&_nolayout=1&exclude="+JSON.stringify(queue);

    console.log("[Lunchbox get_children()] requesting URL",url);

    jQuery.ajax({ 
        type: "GET", 
        url: url,
        success: function(response) {
            $("#set-children-modal-content").html(response);
            setBreadcrumbsModal(parent);
        }   
    }); 
}

function get_children_on_queue(parent) {

    console.log('getting children for queue section...');
    $('#q-body').empty();
    var url = connector_url+"&class=page&method=records&parent="+parent;
    $.ajax({
        url: url,
        success: function( response )  
        {
            var data = $.parseJSON(response);
            var content = '';
            if(data.total !== 0) {
               for (var i = 0; i < data.total; i++) {
                    content += '<tr>'+
                        '<td>'+data.results[i].id+'<input type="hidden" name="child[]" value="'+data.results[i].id+'"/></td>'+
                        '<td>'+data.results[i].pagetitle+'</td>'+
                        '</tr>';

                     $('#q-body').append(content);
                }
            }
            console.log(data);            
        }
   });
}


/**
 * launch Modal to select parent
 * for the chosen page
 */
function launch_modal_parent(obj) { 
    $('.lunchbox_breadcrumbs_modal').remove();
    selected = $(obj).data('selected');     
    $.ajax({ 
        type: "GET", 
        url: $(obj).attr('href'), 
        success: function(response) { 
            draw_modal_header(selected);
            $('#parent-modal').modal('show');
            $('#set-parent-modal-content').html(response);
        }   
    }); 
    event.preventDefault();
}


/**
 * Launch modal to select children
 */
function launch_modal_children(obj) { 
    $('.lunchbox_breadcrumbs_modal').remove();
    selected = $(obj).data('selected');     
    $.ajax({ 
        type: "GET", 
        url: $(obj).attr('href'), 
        success: function(response) {
            get_children_on_queue(selected);
            draw_modal_header(selected);
            $('#children-modal').modal('show');
            $('#set-children-modal-content').html(response);
        }   
    }); 
    event.preventDefault();
}

function remove_q(obj) {
    var page_id = $(obj).data('id');
    $(obj).parent('td').parent('tr').remove();
    queue.splice( $.inArray(page_id,queue) ,1 );
    $('#children-select-tbody tr').filter('[data-id="'+page_id+'"]').removeClass('hide-row');
    console.log(queue);
    event.preventDefault();
}

function search_parent() {
        var search = $('#search_term').val();
         var parent = $('#parent').val();
        var url = connector_url+"&class=page&method=children&parent="+parent+"&search_term="+search;
        $.ajax({
            url: url,
            success: function( response )  
            {
               $("#child_pages").html(response);               
            }
       });
    
    event.preventDefault();
}


function search_parent_modal() {
        var search = $('#search_term_modal').val();
        var url = connector_url+"&class=page&method=parents&search_term="+search;
        $.ajax({
            url: url,
            success: function( response )  
            {
               $("#set-parent-modal-content").html(response);               
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
                data = $.parseJSON(response);
               // console.log(data);
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
                if($('.lunchbox_breadcrumbs_modal').length == 0) {
                    $('#set-parent-modal-content,#set-children-modal-content').after('<div class="lunchbox_breadcrumbs_modal">Testing</div>');
                }
                
                $('.lunchbox_breadcrumbs_modal').html(response);
            }   
        }); 
}

function show_all_child(parent){
   return get_children(parent);
}


function update_children(obj) {
     console.log('setting children [Lunchbox]');
    var form = $('#set-children-form');
    var values = form.serialize();
    var url = form.attr('action');   
     jQuery.ajax({ 
            type: "GET", 
            url: url,
            success: function(response) {
                console.log(response);
            }   
        }); 
    event.preventDefault();
}