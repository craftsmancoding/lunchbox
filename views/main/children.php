<?php include dirname(dirname(__FILE__)).'/includes/header.php';  ?>

<div class="lu-msg"></div>

<div class="lunchbox_canvas_inner clearfix" id="lunchbox_canvas_inner_head">
	<a class="btn btn-primary pull-right" href="<?php print $data['site_url']; ?>manager/?id=<?php print $data['parent']; ?>&a=resource/create&class_key=modDocument&parent=<?php print $data['parent']; ?>&context_key=web">Add Page</a>
</div>

<?php print $data['records_layout']; ?>
<!-- Modal -->
<div class="modal fade" id="parent-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
<?php include dirname(dirname(__FILE__)).'/includes/footer.php';  ?>


