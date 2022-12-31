<?php use NS\String ?>
<?php echo '<?php' ?> 
use NS\UI\Widget;
<?php echo '?>' ?> 
<div class="breadcrumbwidget">
	<ul class="breadcrumb">
		<li><a href="<?php echo '<?php echo NS_BASE_URL; ?>' ?>"><?php echo '<?php echo _(\'Home\'); ?>' ?></a> <span class="divider">/</span></li>
		<li><a href="<?php echo '<?php echo $app_base_url; ?>' ?>"><?php echo '<?php echo _(\'', $controller, '\'); ?>' ?></a> <span class="divider">/</span></li>
		<li class="active"><?php echo '<?php echo _(\'Insert\'); ?>' ?></li>
	</ul>
</div><!--breadcrumbs-->
<div class="pagetitle">
	<div class="row-fluid">
		<div class="span6"><h1><?php echo '<?php echo _(\'', $controller, '\'); ?>' ?></h1><span><?php echo '<?php echo _(\'Insert new data\'); ?>' ?></span></div>
		<div class="span6">
			<div id="header-button">
				<ul>
					<li><?php echo '<?php echo new Widget\Bootstrap\Button(\'button-save\', \'save\', Widget\Button::BUTTON_NORMAL, \'<span class="icon-ok"></span> \' . _(\'Save\')); ?>' ?></li>
					<li><?php echo '<?php echo new Widget\Bootstrap\HyperlinkButton(\'button-back\', $app_base_url . \'/index\', \'<span class="icon-arrow-left"></span> \' . _(\'Back\')); ?>' ?></li>
				</ul>
			</div>
		</div>
	</div>
</div><!--pagetitle-->

<div class="maincontent">
	<div class="contentinner">
		<div>
			<?php echo '<?php echo new Widget\Bootstrap\Alert(null, \'\', false, null, array(\'style\' => \'display: none;\')); ?>' ?> 
		</div>
		<form action="<?php echo '<?php echo $service_base_url; ?>' ?>/save" class="stdform stdform2" id="<?php echo String::toLowerCase($controller) ?>" method="post">
			<?php echo '<?php echo new Widget\Hidden(\'token\', $token); ?>' ?> 
			<h4 class="widgettitle nomargin shadowed"><?php echo '<?php echo _(\'Insert ', $controller, '\'); ?>' ?></h4>
			<div class="widgetcontent bordered shadowed nopadding">
<?php foreach($columns as $column => $value): ?> 
<?php if($column == $pk) continue; ?> 
				<div class="field-section">
					<label class="field-label"><?php echo '<?php echo _(\'' . $value . '\'); ?>'; ?></label>
					<div class="field">
						<?php
						switch($column_types[$column]) {
							case 'Text':
							case 'TextArea':
							case 'Password':
								echo '<?php echo new Widget\\' . $column_types[$column] . '(\'' . $column . '\', ' . '$datas[\'' . $column . '\']' . '); ?>';
								break;
							case 'FileUpload':
								echo '<?php echo new Widget\\' . $column_types[$column] . '(\'' . $column . '\'); ?>';
								break;
							case 'DatePicker':
								echo '<?php echo new Widget\\' . $column_types[$column] . '(\'' . $column . '\', ' . '$datas[\'' . $column . '\']' . '); ?>';
								break;
							case 'SwitchButton':
								echo '<?php echo new Widget\\Bootstrap\\' . $column_types[$column] . '(\'' . $column . '\', ' . '1' . ', empty($datas[\'' . $column . '\']) ? false : true); ?>';
								break;
							case 'SwitchButton-YesNo':
								echo '<?php echo new Widget\\Bootstrap\\' . current(explode('-', $column_types[$column])) . '(\'' . $column . '\', ' . '1' . ', empty($datas[\'' . $column . '\']) ? false : true, array(\'data-on-label\' => \'YES\', \'data-off-label\' => \'NO\')); ?>';
								break;
						}
						?> 
					</div>
				</div>
<?php endforeach; ?> 
			</div>
		</form>
	</div><!--contentinner-->
</div><!--maincontent-->