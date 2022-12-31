<?php echo '<?php' ?> 
use NS\UI\Widget;
<?php echo '?>' ?> 
<form class="stdform stdform2" method="post" action="<?php echo '<?php echo $app_base_url; ?>' ?>/dispatch">
<div class="pagetitle">
	<div class="row-fluid">
		<div class="span6"><h1><?php echo $controller ?></h1><span><?php echo $controller ?></span></div>
	</div>
</div><!--pagetitle-->

<div class="maincontent">
	<div class="contentinner">
		<h4 class="widgettitle nomargin shadowed">Cetak <?php echo $controller ?></h4>
		<div class="widgetcontent bordered shadowed nopadding">
<?php foreach($columns as $column => $value): ?>
<?php if($column == $pk) continue; ?>
			<div class="field-section">
				<label class="field-label"><?php echo $value; ?></label>
				<div class="field">
					<?php echo '<?php echo $datas[\'' . $column . '\']; ?>' ?> 
				</div>
			</div>
<?php endforeach; ?> 
		</div>
	</div><!--contentinner-->
</div><!--maincontent-->
</form>