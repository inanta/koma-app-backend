<?php echo '<?php use \Inationsoft\NS; use \Inationsoft\NS\UI\Widget; ?>' ?>
<form class="stdform stdform2" method="post" action="<?php echo '<?php echo $app_base_url; ?>' ?>/save">
<div class="breadcrumbwidget">
	<ul class="breadcrumb">
		<li><a href="<?php echo NS_ROOT_URL ?>">Home</a> <span class="divider">/</span></li>
		<li><a href="<?php echo '<?php echo $app_base_url; ?>' ?>"><?php echo $controller ?></a> <span class="divider">/</span></li>
		<li class="active">Edit</li>
	</ul>
</div><!--breadcrumbs-->
<div class="pagetitle">
	<div class="row-fluid">
		<div class="span6"><h1><?php echo $controller ?></h1></div>
		<div class="span6">
			<div id="header-button">
				<?php echo '<?php echo new Widget\Hidden(\'token\', $token); ?>' ?>
				<?php echo '<?php echo new Widget\Hidden(\'edit_token\', $edit_token); ?>' ?>
				<?php echo '<?php echo new Widget\Hidden(\'' . $pk . '\', ' . '$datas[\'' . $pk . '\']' . '); ?>' ?> 
				<ul>
					<li><?php echo '<?php echo new Widget\Bootstrap\Button(\'dispatch\', \'save\', Widget\Button::BUTTON_SUBMIT, \'<span class="icon-ok"></span> Save\'); ?>' ?></li>
					<li><?php echo '<?php echo new Widget\Bootstrap\Button(\'dispatch\', \'cancel\', Widget\Button::BUTTON_SUBMIT, \'<span class="icon-remove"></span> Cancel\'); ?>' ?></li>
				</ul>
			</div>
		</div>
	</div>
</div><!--pagetitle-->

<div class="maincontent">
	<div class="contentinner">
		<h4 class="widgettitle nomargin shadowed">Edit <?php echo $controller ?></h4>
		<div class="widgetcontent bordered shadowed nopadding">
			<?php foreach($columns as $column => $value): ?>
			<?php if($column == $pk) continue; ?> 
			<div class="field-section">
				<label class="field-label"><?php echo $value; ?></label>
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
							echo '<?php echo new Widget\\' . $column_types[$column] . '(\'' . $column . '\', ' . '$datas[\'' . $column . '\']' . ', null, Widget\DatePicker::DATEPICKER_TEXTBOX); ?>';
							break;
						case 'SwitchButton':
							echo '<?php echo new Widget\\Bootstrap\\' . $column_types[$column] . '(\'' . $column . '\', ' . '$datas[\'' . $column . '\']' . ', empty($datas[\'' . $column . '\']) ? false : true); ?>';
							break;
					}
					?> 
				</div>
			</p>
			<?php endforeach; ?> 
		</div>
	</div><!--contentinner-->
</div><!--maincontent-->
</form>