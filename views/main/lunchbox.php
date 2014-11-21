<?php include dirname(dirname(__FILE__)).'/includes/header.php';  ?>
<script id="template" type="text/x-handlebars-template">
	{{#each this}}
		<li>
			<h2>{{author}}</h2>
			<p>{{tweet}}</p>
		</li>
	{{/each}}
</script>

<script>
	
	function lunchbox_init() {
		console.log('[Lunchbox Init]');	
		var data = [
	        {
	            author: 'Daniel Doe',
	            tweet : 'Test Handle Bar'
	        },
	        {
	            author: 'jane Doe',
	            tweet : 'Test Handle Bar 2.0'
	        }
	    ];

	    var template = Handlebars.compile( $('#template').html() );
	    console.log(template);
	    $('ul.tweets').append( template(data) );
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
<div id="child_pages_inner">
	<ul class="tweets"></ul>
</div>
<!-- Modal -->
<div class="modal fade" id="parent-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
<?php include dirname(dirname(__FILE__)).'/includes/footer.php';  ?>


