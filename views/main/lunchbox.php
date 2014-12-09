<?php include dirname(dirname(__FILE__)).'/includes/header.php';  ?>
<div class="lunchbox_canvas_inner clearfix" id="lunchbox_canvas_inner_head">
	<a class="btn pull-right" href="<?php print $data['site_url']; ?>manager/?id=<?php print $data['parent']; ?>&a=resource/create&class_key=modDocument&parent=<?php print $data['parent']; ?>&context_key=web"><?php print $this->modx->lexicon('lunchbox.action.addpage') ?></a>
	<form action="<?php print $data['controller_url'] .'&method=children&parent='.$data['parent']; ?>" id="search-parent">
	  <div class="pull-right lu-search-form">
	  	<label for="search_term"><?php print $this->modx->lexicon('lunchbox.form.search') ?> </label>
	  	<input type="hidden" name="parent" id="parent" value="<?php print $data['parent']; ?>">
	    <input type="text" name="search_term" id="search_term">
	    <input type="submit" class="btn btn-primary" onclick="javascript:search_parent();">
	  </div>
	</form>
</div>
<div id="child_pages_inner">
    <div class="children-wrapper">
<?php if ($data['results']): ?>
<input type="hidden" name="lunchbox" value="1">
<table class="classy">
    <thead>
        <tr>
            <?php 
            // Configurable columns
            //foreach($data['columns'] as $k => $v): ?>
               <!--  <th><?php //print $v; ?></th> -->
            <?php //endforeach; ?>
            <th>&nbsp;</th>
            <?php 
                // Configurable columns
                foreach($data['columns'] as $k => $v): ?>
                <th><?php print $v; ?></th>
            <?php endforeach; ?>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
<?php foreach ($data['results'] as $r) : ?>
    <tr>
        <?php 
        // Configurable columns
        //foreach($data['columns'] as $k => $v): ?>
           <!--  <td><?php //print $r[$k]; ?></td> -->
        <?php //endforeach; ?>
    <td>
 	<?php if($r['has_children'] == 1) : ?>
       <div class="x-tree-node">
        <div class="lu-icon icon tree-folder <?php print $r['mgr_tree_icon']; ?>" data-id="<?php print  $r['id']; ?>" onclick="javascript:get_children('<?php print  $r['id'] ?>',0);">&nbsp;</div>
      </div>
    <?php else : ?>
      <div class="x-tree-node">
        <div class="icon tree-resource <?php print $r['mgr_tree_icon']; ?>"></div>
      </div>
      
    <?php endif; ?>

    </td>
    <?php 
        // Configurable columns
        foreach($data['columns'] as $k => $v): ?>
            <td><?php print $r[$k]; ?></td>
        <?php endforeach; ?>
        <td>
            <a href="<?php print $data['site_url']; ?>/manager/?a=resource/update&id=<?php print $r['id'] ?>" class="button btn btn-mini btn-info"><?php print $this->modx->lexicon('lunchbox.action.edit') ?></a>
            <a href="<?php print $data['site_url'] . $r['uri']; ?>" class="btn btn-mini" target="_blank"><?php print $this->modx->lexicon('lunchbox.action.preview') ?></a>
            <a class="btn btn-mini btn-primary" onclick="javascript:launch_modal_parent(this);" data-selected="<?php print $r['id']; ?>"href="<?php print $data['controller_url'] .'&method=parents&selected=' . $r[id].'&parent=0&sort=menuindex&dir=ASC'; ?>"><?php print $this->modx->lexicon('lunchbox.action.selectparent') ?></a>    
             <a class="btn btn-mini btn-orange" onclick="javascript:launch_modal_children(this);" data-selected="<?php print $r['id']; ?>"href="<?php print $data['controller_url'] .'&method=selectchildren&selected=' . $r[id].'&parent=0&sort=menuindex&dir=ASC'; ?>"><?php print $this->modx->lexicon('lunchbox.action.selectchildren') ?></a>    
            <a class="btn btn-mini" href="<?php print $data['site_url']; ?>manager/?id=<?php print $data['parent']; ?>&a=resource/create&class_key=modDocument&parent=<?php print $r['id']; ?>&context_key=web"><?php print $this->modx->lexicon('lunchbox.action.addpage') ?></a>       
         </td>
    </tr>
<?php endforeach; ?>
    </tbody>
</table>

<?php else: ?>

    <div class="danger"><?php print $this->modx->lexicon('lunchbox.layout.noresult') ?></div>

<?php endif; ?>

<?php 
$results_per_page = (int) $this->modx->getOption('lunchbox.results_per_page','',$this->modx->getOption('default_per_page'));
print \Pagination\Pager::links($data['count'], $data['offset'], $results_per_page)
    ->setBaseUrl($data['baseurl'])
    ->setTpls(
        array(
            'first' => '<span onclick="javascript:get_children('.$data['parent'].',[+offset+]);" class="linklike">&laquo; First</span>  ',
            'last' => ' <span onclick="javascript:get_children('.$data['parent'].',[+offset+]);" class="linklike">Last &raquo;</span>',
            'prev' => '<span onclick="javascript:get_children('.$data['parent'].',[+offset+]);" class="linklike">&lsaquo; Prev.</span> ',
            'next' => ' <span onclick="javascript:get_children('.$data['parent'].',[+offset+]);" class="linklike">Next &rsaquo;</span>',
            'current' => ' <span>[+page_number+]</span> ',
            'page' => ' <span onclick="javascript:get_children('.$data['parent'].',[+offset+]);" class="linklike">[+page_number+]</span> ',
            'outer' => '
                <style>
                    span.linklike { cursor: pointer; }
                    span.linklike:hover { color:blue; text-decoration:underline; }
                </style>
                <div id="pagination">[+content+]<br/>
            <div class="page-count">Page [+current_page+] of [+page_count+]</div>
            <div class="displaying-page">Displaying records [+first_record+] thru [+last_record+] of [+record_count+]</div>
          </div>',
      )
    );
?>

</div>
</div>
<!-- Modal -->
<div class="modal fade" id="parent-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<!-- Modal -->
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="selected-header"></div>
      <div class="modal-body">
      <form action="<?php print $data['controller_url'] .'&method=parents'; ?>" id="search-parent">
	      <div class="pull-right">
	      	<label for="search_term_modal"><?php print $this->modx->lexicon('lunchbox.form.search') ?> </label>
	        <input type="text" name="search_term" id="search_term_modal">
	        <input type="submit" class="btn btn-primary" onclick="javascript:search_parent_modal();">
	      </div>
	      <div class="clear">&nbsp;</div>
      </form>
      <div class="lu-msg"></div>
      <div id="set-parent-modal-content"></div><!--e#child-pages-->
      </div>

    </div>
  </div>
</div>


<div class="modal fade" id="children-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<!-- Modal -->
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="selected-header"></div>

        <form action="<?php print $data['controller_url'] .'&method=parents'; ?>" id="search-children-form">

          <div class="pull-right">
            <label for="search_term_children_modal"><?php print $this->modx->lexicon('lunchbox.form.search') ?> </label>
            <input type="text" name="search_term" id="search_term_children_modal">
            <input type="submit" class="btn btn-primary" onclick="javascript:search_children_modal();">
          </div>
          <div class="clear">&nbsp;</div>
        </form>

 <form action="<?php print $data['controller_url'] .'&method=setchildren&class=page'; ?>" id="set-children-form" method="POST">
 <input type="hidden" id="children_parent" name="parent" value="">
      <div class="modal-body">

	      <div class="clearfix">
        <div class="lu-msg"></div>
	      	<div id="set-children-modal-content"></div><!--e#set-children-modal-content-->
      		<div id="queue-children">
      			<h4>Child Pages</h4>
      			<table class="classy classy2">
      <thead>
        <tr>
          <th>ID</th>
          <th>Pagetitle</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody id="q-body">
      </tbody>
    </table>

      		</div>
	      </div>
      		
      </div>
          <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php print $this->modx->lexicon('lunchbox.action.cancel') ?></button>
        <button type="button" class="btn btn-primary"  onclick="javascript:update_children();"><?php print $this->modx->lexicon('lunchbox.action.updatechildren') ?></button>
      </div>

      </form>

    </div>
  </div>
</div>

<?php include dirname(dirname(__FILE__)).'/includes/footer.php';  ?>


