/**
 * See http://www.sencha.com/forum/showthread.php?21756-How-do-I-add-plain-text-to-a-Panel 
 * And http://www.sencha.com/forum/showthread.php?38841-Using-Extjs-to-change-div-content
 */
function setBreadcrumbs(page_id) {

   /* Ext.Ajax.request({
        url: connector_url+'&class=page&method=breadcrumbs&page_id='+page_id,
        params: {},
        async:false,
        success: function(response){
            Ext.fly('lunchbox_breadcrumbs').update(response.responseText);
        }
    });*/
}


/**
 * @param integer offset
 * @param string sort column name
 * @param string dir ASC|DESC 
 */
function get_children(parent,offset,sort,dir) {
    parent = typeof parent !== "undefined" ? parent : 0;
    offset = typeof offset !== "undefined" ? offset : 0;
    sort = typeof sort !== "undefined" ? sort : "id";
    dir = typeof dir !== "undefined" ? dir : "ASC";
    var url = connector_url+"&class=page&method=children&parent="+parent+"&offset="+offset+"&sort="+sort+"&dir="+dir+"&_nolayout=1";

    console.log("[Lunchbox get_children()] requesting URL",url);
	Ext.Ajax.request({
        url: url,
        params: {},
        async:false,
        success: function(response){
            console.log("Success: Data received from "+url);
            Ext.fly("child_pages").update(response.responseText);
        },
        failure: function(response){
            console.error("The request to "+url+" failed.", response);
        }
    });                
}

function show_all_child(parent){
   return get_children(parent);
}