<!-- Modal -->
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Select Parent</h4>
      </div>
      <div class="modal-body">
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
    <?php if($r['isfolder'] == 1) : ?>
      <div class="lunchbox_folder" onclick="javascript:drillDown('<?php print $r['id']; ?>');">&nbsp;</div>
    <?php else : ?>
      <div class="lunchbox_page"></div>
    <?php endif; ?>
    </td>
    <?php 
        // Configurable columns
        foreach($data['columns'] as $k => $v): ?>
            <td><?php print $r[$k]; ?></td>
        <?php endforeach; ?>
        <td>
             <a class="btn btn-mini btn-primary" onclick="javascript:launch_modal_parent(this);" href="<?php print $data['controller_url'] .'&method=parents'; ?>">Set as Parent</a>
         </td>
    </tr>
<?php endforeach; ?>
    </tbody>
</table>

<?php else: ?>

    <div class="danger">Sorry, no Child Pages found.</div>

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

<!-- Modal -->
<div class="modal fade" id="parent-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>

</div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>