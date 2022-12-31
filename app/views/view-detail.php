<?php echo '<?php' ?> 
use NS\UI\Widget;
<?php echo '?>' ?> 
<div class="breadcrumbwidget">
	<ul class="breadcrumb">
		<li><a href="<?php echo '<?php echo NS_BASE_URL; ?>' ?>"><?php echo '<?php echo _(\'Home\'); ?>' ?></a> <span class="divider">/</span></li>
		<li><a href="<?php echo '<?php echo $app_base_url; ?>' ?>"><?php echo '<?php echo _(\'', $controller, '\'); ?>' ?></a> <span class="divider">/</span></li>
		<li class="active"><?php echo '<?php echo _(\'Detail\'); ?>' ?></li>
	</ul>
</div><!--breadcrumbs-->
<div class="pagetitle">
	<div class="row-fluid">
		<div class="span6"><h1><?php echo '<?php echo _(\'', $controller, '\'); ?>' ?></h1><span><?php echo '<?php echo _(\'View detail\'); ?>' ?></span></div>
		<div class="span6">
			<div id="header-button">
				<?php echo '<?php echo new Widget\Hidden(\'token\', $token); ?>' ?> 
				<?php echo '<?php echo new Widget\Hidden(\'' . $pk . '\', ' . '$datas[\'' . $pk . '\']' . '); ?>' ?> 
				<ul>
					<li><?php echo '<?php echo new Widget\Bootstrap\HyperlinkButton(null, $app_base_url . \'/detail/\' . $datas[\'' . $pk . '\'] . \'/true\', \'<span class="icon-print"></span> \' . _(\'Print\'), array(\'target\' => \'_blank\')); ?>'; ?></li>
					<li><?php echo '<?php echo new Widget\Bootstrap\HyperlinkButton(null, $app_base_url . \'/index\', \'<span class="icon-arrow-left"></span> \' . _(\'Back\')); ?>'; ?></li>
				</ul>
			</div>
		</div>
	</div>
</div><!--pagetitle-->

<div class="maincontent">
	<div class="contentinner">
		<h4 class="widgettitle nomargin shadowed"><?php echo '<?php echo _(\'Detail ' . $controller . '\'); ?>' ?></h4>
		<div class="widgetcontent bordered shadowed nopadding stdform stdform2">
<?php foreach($columns as $column => $value): ?>
<?php if($column == $pk) continue; ?>
			<div class="field-section">
				<label class="field-label"><?php echo '<?php echo _(\'' . $value . '\'); ?>'; ?></label>
				<div class="field">
					<?php echo '<?php echo $datas[\'' . $column . '\']; ?>' ?> 
				</div>
			</div>
<?php endforeach; ?> 
		</div>
	</div><!--contentinner-->
</div><!--maincontent-->