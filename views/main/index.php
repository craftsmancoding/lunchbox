<?php include dirname(dirname(__FILE__)).'/includes/header.php';  ?>

<div class="lunchbox_canvas_inner">
	<h2 class="lunchbox_cmp_heading" id="lunchbox_pagetitle"><?php print $this->modx->lexicon('lunchbox.layout.title') ?></h2>
</div>

<div class="x-panel-body panel-desc x-panel-body-noheader x-panel-body-noborder">
<p><?php print $this->modx->lexicon('lunchbox.layout.subtitle') ?></p>
</div>

<div class="x-panel-body">


	<?php
	$License = new \Lunchbox\License($this->modx);
	$status = $License->check($this->modx->getOption('lunchbox.license_key'));
	if ($status == 'valid'):
	?>
		<div class="success">
			<h4><?php print $this->modx->lexicon('setting_lunchbox.license_key') ?>: <?php print $this->modx->getOption('lunchbox.license_key'); ?></h4>
			<strong><?php print $this->modx->lexicon('status') ?></strong>: <?php print $this->modx->lexicon('valid') ?>
		</div>
	<?php else: ?>
		<div class="error">
			<h4><?php print $this->modx->lexicon('setting_lunchbox.license_key') ?>: <?php print $this->modx->getOption('lunchbox.license_key'); ?></h4>
			<strong><?php print $this->modx->lexicon('status') ?></strong>: <?php print $this->modx->lexicon($status); ?>
		</div>
	<?php endif; ?>
</div>


<?php include dirname(dirname(__FILE__)).'/includes/footer.php';  ?>


