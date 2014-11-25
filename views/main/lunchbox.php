<?php include dirname(dirname(__FILE__)).'/includes/header.php';  ?>
<script id="children-tpl" type="text/x-handlebars-template">
 <tr>
    <td>
	    {{#ifCond isfolder '==' 1}}
		  <div class="lunchbox_folder" onclick="javascript:get_children({{id}});">&nbsp;</div>
		{{else}}
		  <div class="lunchbox_page"></div>
		{{/ifCond}}
      
    </td>
    	
    	<td>{{id}}</td>
    	<td>{{pagetitle}}</td>
    
        <td>
            <a href="#" class="btn btn-mini" target="_blank">Preview</a>
            <a href="#" class="button btn btn-mini btn-info">Edit</a>
            <a class="btn btn-mini btn-primary" onclick="javascript:launch_modal_parent(this);" href="{{Lunchbox.controller_url}}&method=parents&selected={{id}}">Select Parent</a>
        	<a href="{{Lunchbox.site_url}}{{id}}" class="button btn btn-mini" target="_blank">Add Page</a>

         </td>
    </tr>
</script>

<script id="children-header-tpl" type="text/x-handlebars-template">
	{{#each}}
	<th>{{this}}</th>
	{{/each}}
	}
</script>



<script>

	// Initialized Lunchbox
	function lunchbox_init() {
		console.log('[Lunchbox Init]');	
		ifCond();
		get_children(Lunchbox.parent);
	}

	/**
	 * get child pages
	 * @param integer parent		
	 * @param integer offset
	 * @param string sort column name
	 * @param string dir ASC|DESC 
	 */
	function get_children(parent,offset,sort,dir) {
	    console.log("[Lunchbox get_children()] requesting URL TEST");
	    parent = typeof parent !== "undefined" ? parent : 0;
	    offset = typeof offset !== "undefined" ? offset : 0;
	    sort = typeof sort !== "undefined" ? sort : Lunchbox.sort_col;
	    dir = typeof dir !== "undefined" ? dir : "ASC";
	    var url = Lunchbox.connector_url+"&class=page&method=records&parent="+parent+"&offset="+offset+"&sort="+sort+"&dir="+dir+"&_nolayout=1";

	    jQuery.ajax({ 
	        type: "GET", 
	        url: url,
	        success: function(response) {
	        	data = $.parseJSON(response);
	        	var children_tpl = Handlebars.compile( $('#children-tpl').html() );
	        	var children_header_tpl = Handlebars.compile( $('#children-header-tpl').html() );
	        	var arrayLength = data.results.length;
	        	console.log(data.results);
			    for (var i = 0; i < arrayLength; i++) {
			        var Page = data.results[i];
			        $('#child-target').append( children_tpl(Page) );
			    }
		
			    $('#children-header').append( children_header_tpl(data.cols) );
			    
	        }   
	    }); 
	}

	function launch_modal_parent() {    
	    console.log("[Lunchbox get_children()] requesting URL TEST");
	    var url = Lunchbox.connector_url+"&class=page&method=records&parent=0&_nolayout=1";

	    jQuery.ajax({ 
	        type: "GET", 
	        url: url,
	        success: function(response) {
	        	data = $.parseJSON(response);
	        	var children_tpl = Handlebars.compile( $('#children-tpl').html() );
	        	$('#parent-modal').modal('show');
	    		$('#child_pages_modal').html( children_tpl(data) );
	        }   
	    }); 
	    event.preventDefault();

	}


	function ifCond() {
		Handlebars.registerHelper('ifCond', function (v1, operator, v2, options) {
	    switch (operator) {
	        case '==':
	            return (v1 == v2) ? options.fn(this) : options.inverse(this);
	        case '===':
	            return (v1 === v2) ? options.fn(this) : options.inverse(this);
	        case '<':
	            return (v1 < v2) ? options.fn(this) : options.inverse(this);
	        case '<=':
	            return (v1 <= v2) ? options.fn(this) : options.inverse(this);
	        case '>':
	            return (v1 > v2) ? options.fn(this) : options.inverse(this);
	        case '>=':
	            return (v1 >= v2) ? options.fn(this) : options.inverse(this);
	        case '&&':
	            return (v1 && v2) ? options.fn(this) : options.inverse(this);
	        case '||':
	            return (v1 || v2) ? options.fn(this) : options.inverse(this);
	        default:
	            return options.inverse(this);
	    }
	});
	}

	jQuery(document).ready(function() {
        lunchbox_init();
    });

</script>
<div class="lu-msg"></div>

<div class="lunchbox_canvas_inner clearfix" id="lunchbox_canvas_inner_head">
	<form action="<?php print $data['controller_url'] .'&method=records'; ?>" id="search-parent" data-target="child_pages_modal">
    <div class="lb-form-search-wrap pull-right">
        <label for="search_term">Search </label>
        <input type="text" name="search_term" id="search_term">
        <input type="submit" class="btn btn-primary" onclick="javascript:search_parent();">
    </div>
       
      </form>

</div>

	<div class="children-wrapper">
	<input type="hidden" name="lunchbox" value="1">
	<table class="classy">
	    <thead>
	        <tr id="children-header">
	        </tr>
	    </thead>
	    <tbody id="child-target">

	    </tbody>
	</table>
	</div>

<!-- Modal -->
<div class="modal fade" id="parent-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<!-- Modal -->
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Select Parent for Test Title (100)</h4>
      </div>
      <div class="modal-body">
      <div id="child_pages_modal"></div><!--e#child-pages-->
      </div>

    </div>
  </div>
</div>
<?php include dirname(dirname(__FILE__)).'/includes/footer.php';  ?>


