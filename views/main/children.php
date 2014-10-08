<?php include dirname(dirname(__FILE__)).'/includes/header.php';  ?>

<div class="lunchbox_canvas_inner">
	<h2 class="lunchbox_cmp_heading" id="lunchbox_pagetitle">Welcome to Lunchbox</h2><br>
</div>

<div class="children-wrapper">
<?php if ($data['results']): ?>
<table class="classy">
    <thead>
        <tr>
            <?php 
            // Configurable columns
            //foreach($data['columns'] as $k => $v): ?>
               <!--  <th><?php //print $v; ?></th> -->
            <?php //endforeach; ?>
            <th>&nbsp;</th>
            <th>Pagetitle</th>
            <th>ID</th>
            <th>Description</th>
            <th>Published</th>
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
		<td>&nbsp;</td>
		<td><?php print $r['pagetitle']; ?></td>
		<td><?php print $r['id']; ?></td>
		<td><?php print $r['description']; ?></td>
		<td><?php print $r['published']; ?></td>
        <td>
            <!--span class="button btn" onclick="javascript:paint('productedit',{product_id:<?php print $r['product_id']; ?>});">Edit</span-->
             <a href="<?php //print static::page('productedit',array('product_id'=>$r['product_id'])); ?>" class="button btn btn-mini btn-info">Edit</a>
             <a href="<?php //print static::page('productpreview',array('product_id'=>$r['product_id'])); ?>" class="btn btn-mini" target="_blank">Preview</a>
         </td>
    </tr>
<?php endforeach; ?>
    </tbody>
</table>

<?php else: ?>

    <div class="danger">Sorry, no Child Pages found.</div>

<?php endif; ?>

<?php 

$results_per_page = (int) $this->modx->getOption('default_per_page');
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
<?php include dirname(dirname(__FILE__)).'/includes/footer.php';  ?>


