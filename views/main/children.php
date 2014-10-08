<?php include dirname(dirname(__FILE__)).'/includes/header.php';  ?>

<div class="lunchbox_canvas_inner">
	<h2 class="lunchbox_cmp_heading" id="lunchbox_pagetitle">Welcome to Lunchbox</h2>
</div>

<div class="children-wrapper">
<?php if ($data['results']): ?>
<table class="classy products-tbl">
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

    <div class="danger">Sorry, no products were found.</div>

<?php endif; ?>
</div>
<?php include dirname(dirname(__FILE__)).'/includes/footer.php';  ?>


