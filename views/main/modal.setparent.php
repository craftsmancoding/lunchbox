
<!-- Modal -->
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Select Parent</h4>
      </div>
      <div class="modal-body">
      <form action="<?php print $data['controller_url'] .'&method=records&in_modal=1'; ?>" id="search-parent" data-target="child_pages_modal">
        <label for="search_term">Search Parent: </label>
        <input type="text" name="search_term" id="search_term">
        <input type="submit" class="btn btn-primary" onclick="javascript:search_parent();">
      </form>
      <div id="child_pages_modal">
       <?php print $data['records_layout']; ?>
      </div><!--e#child-pages-->
      </div>

    </div>
  </div>