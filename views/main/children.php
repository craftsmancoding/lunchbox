<?php include dirname(dirname(__FILE__)).'/includes/header.php';  ?>

<div class="lu-msg"></div>

<div class="lunchbox_canvas_inner clearfix" id="lunchbox_canvas_inner_head">
	<a class="btn btn-primary pull-right" href="<?php print $data['site_url']; ?>manager/?id=<?php print $data['parent']; ?>&a=resource/create&class_key=modDocument&parent=<?php print $data['parent']; ?>&context_key=web">Add Page</a>
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
    <?php if($r['isfolder'] == 1 && $r['parent'] > 0) : ?>
      <div class="lunchbox_folder" data-id="<?php print  $r['id']; ?>" data-in_modal="<?php print  $data['in_modal']; ?>" data-target="<?php  print $data['target']; ?>" onclick="javascript:get_children2(this,'<?php print  $r['id'] ?>',0);">&nbsp;</div>
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
            <?php if($data['in_modal']) :?>
                <form action="<?php print $data['controller_url'] .'&method=setparent&class=page'; ?>" method="POST">
                    <input type="hidden" id="page_id" name="id" value="<?php print $data['selected']; ?>">
                    <input type="hidden" id="parent_id" name="parent" value="<?php print $r['id'] ?>">
                    <input type="submit" class="btn btn-mini btn-info" value="Set as Parent" onclick="javascript:set_parent(this);">
                </form>
            <?php else : ?>
                 <a href="<?php print $data['site_url']; ?>/manager/?a=resource/update&id=<?php print $r['id'] ?>" class="button btn btn-mini btn-info">Edit</a>
                <a href="<?php print $data['site_url'] . $r['uri']; ?>" class="btn btn-mini" target="_blank">Preview</a>
                <a class="btn btn-mini btn-primary" onclick="javascript:launch_modal_parent(this);" href="<?php print $data['controller_url'] .'&method=parents&in_modal=1&selected=' . $r[id]; ?>">Select Parent</a>
            <?php endif; ?>
           
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
            'first' => '<span data-in_modal="'.$data['in_modal'].'" data-target="'.$data['target'].'" onclick="javascript:get_children2(this,'.$data['parent'].',[+offset+]);" class="linklike">&laquo; First</span>  ',
            'last' => ' <span data-in_modal="'.$data['in_modal'].'" data-target="'.$data['target'].'" onclick="javascript:get_children2(this,'.$data['parent'].',[+offset+]);" class="linklike">Last &raquo;</span>',
            'prev' => '<span data-in_modal="'.$data['in_modal'].'" data-target="'.$data['target'].'" onclick="javascript:get_children2(this,'.$data['parent'].',[+offset+]);" class="linklike">&lsaquo; Prev.</span> ',
            'next' => ' <span data-in_modal="'.$data['in_modal'].'" data-target="'.$data['target'].'" onclick="javascript:get_children2(this,'.$data['parent'].',[+offset+]);" class="linklike">Next &rsaquo;</span>',
            'current' => ' <span>[+page_number+]</span> ',
            'page' => ' <span data-in_modal="'.$data['in_modal'].'" data-target="'.$data['target'].'" onclick="javascript:get_children2(this,'.$data['parent'].',[+offset+]);" class="linklike">[+page_number+]</span> ',
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
<div class="modal fade" id="parent-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
<?php include dirname(dirname(__FILE__)).'/includes/footer.php';  ?>


