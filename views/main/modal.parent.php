
      <div class="children-wrapper">

<?php if ($data['results']): ?>
<input type="hidden" name="lunchbox" value="1">
<table class="classy classy-custom">
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
    <tr class="<?php print $r['on_queue']; ?>">
        <?php 
        // Configurable columns
        //foreach($data['columns'] as $k => $v): ?>
           <!--  <td><?php //print $r[$k]; ?></td> -->
        <?php //endforeach; ?>
    <td>
    <?php if($r['has_children'] == 1) : ?>
       <div class="x-tree-node">
        <div class="lu-icon icon tree-folder <?php print $r['mgr_tree_icon']; ?>" data-id="<?php print  $r['id']; ?>" onclick="javascript:get_parent_modal('<?php print  $r['id'] ?>',0);">&nbsp;</div>
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
                <form action="<?php print $data['controller_url'] .'&method=setparent&class=page'; ?>" method="POST">
                    <input type="hidden" id="page_id" name="id" value="<?php print $data['selected']; ?>">
                    <input type="hidden" id="parent_id" name="parent" value="<?php print $r['id'] ?>">
                    <input type="submit" class="btn btn-mini btn-info" value="Set as Parent" onclick="javascript:set_parent(this);">
                </form>
           
         </td>
    </tr>
<?php endforeach; ?>
    </tbody>
</table>

<?php else: ?>

    <div class="lu-danger"><?php print $this->modx->lexicon('lunchbox.layout.noresult') ?></div>

<?php endif; ?>

<?php 
$results_per_page = (int) $this->modx->getOption('lunchbox.results_per_page','',$this->modx->getOption('default_per_page'));
print \Pagination\Pager::links($data['count'], $data['offset'], $results_per_page)
    ->setBaseUrl($data['baseurl'])
    ->setTpls(
        array(
            'first' => '<span onclick="javascript:get_parent_modal('.$data['parent'].',[+offset+]);" class="linklike">&laquo; First</span>  ',
            'last' => ' <span onclick="javascript:get_parent_modal('.$data['parent'].',[+offset+]);" class="linklike">Last &raquo;</span>',
            'prev' => '<span onclick="javascript:get_parent_modal('.$data['parent'].',[+offset+]);" class="linklike">&lsaquo; Prev.</span> ',
            'next' => ' <span onclick="javascript:get_parent_modal('.$data['parent'].',[+offset+]);" class="linklike">Next &rsaquo;</span>',
            'current' => ' <span>[+page_number+]</span> ',
            'page' => ' <span onclick="javascript:get_parent_modal('.$data['parent'].',[+offset+]);" class="linklike">[+page_number+]</span> ',
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