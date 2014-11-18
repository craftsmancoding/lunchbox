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
          <a href="<?php print $data['site_url']; ?>/manager/?a=resource/update&id=<?php print $r['id'] ?>" class="button btn btn-mini btn-info">Edit</a>
            <a href="<?php print $data['site_url'] . $r['uri']; ?>" class="btn btn-mini" target="_blank">Preview</a>
             <a class="btn btn-mini btn-primary" onclick="javascript:launch_modal_parent(this);" href="<?php print $data['controller_url'] .'&method=parents'; ?>">Select Parent</a>
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
            'first' => '<span data-target="child_pages_modal" onclick="javascript:get_children2(this,'.$data['parent'].',[+offset+]);" class="linklike">&laquo; First</span>  ',
            'last' => ' <span data-target="child_pages_modal" onclick="javascript:get_children2(this,'.$data['parent'].',[+offset+]);" class="linklike">Last &raquo;</span>',
            'prev' => '<span data-target="child_pages_modal" onclick="javascript:get_children2(this,'.$data['parent'].',[+offset+]);" class="linklike">&lsaquo; Prev.</span> ',
            'next' => ' <span data-target="child_pages_modal" onclick="javascript:get_children2(this,'.$data['parent'].',[+offset+]);" class="linklike">Next &rsaquo;</span>',
            'current' => ' <span>[+page_number+]</span> ',
            'page' => ' <span data-target="child_pages_modal" onclick="javascript:get_children2(this,'.$data['parent'].',[+offset+]);" class="linklike">[+page_number+]</span> ',
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