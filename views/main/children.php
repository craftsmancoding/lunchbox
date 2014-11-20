<?php include dirname(dirname(__FILE__)).'/includes/header.php';  ?>

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
    

<?php print $data['records_layout']; ?>
</div>
<!-- Modal -->
<div class="modal fade" id="parent-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
<?php include dirname(dirname(__FILE__)).'/includes/footer.php';  ?>


