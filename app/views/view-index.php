<?php 
use NS\String; 
?>
<?php echo '<?php' ?> 
use NS\UI\Widget;
<?php echo '?>' ?> 
<div class="breadcrumbwidget">
	<ul class="breadcrumb">
		<li><a href="<?php echo '<?php echo NS_BASE_URL; ?>' ?>"><?php echo '<?php echo _(\'Home\'); ?>' ?></a> <span class="divider">/</span></li>
		<li class="active"><?php echo '<?php echo _(\'', $controller, '\'); ?>' ?></li>
	</ul>
</div><!--breadcrumbs-->
<div class="pagetitle">
	<div class="row-fluid">
		<div class="span6"><h1><?php echo '<?php echo _(\'', $controller, '\'); ?>' ?></h1><span><?php echo '<?php echo _(\'View all data\'); ?>' ?></span></div>
		<div class="span6">
			<div id="header-button">
				<ul>
					<li><?php echo '<?php echo new Widget\Bootstrap\HyperlinkButton(null, $app_base_url . \'/insert\', \'<span class="icon-pencil"></span> \' . _(\'Insert\')); ?>'; ?></li>
					<li><?php echo '<?php echo new Widget\Bootstrap\Button(\'dispatch\', \'delete\', Widget\Button::BUTTON_NORMAL, \'<span class="icon-trash"></span> \' . _(\'Delete\'), array(\'id\' => \'button-delete\')); ?>'; ?></li>
				</ul>
			</div>	
		</div>
	</div>
</div><!--pagetitle-->

<div class="maincontent">
	<div class="contentinner">
		<form method="post" action="<?php echo '<?php echo $service_base_url; ?>' ?>/delete">
			<?php echo '<?php echo new Widget\Hidden(\'token\', $token); ?>'; ?> 
			<div class="form-wrapper">
			    <table class="data-list table table-bordered" id="<?php echo String::toLowerCase($controller);  ?>-table" data-url="<?php echo '<?php echo $app_base_url; ?>' ?>" data-service-url="<?php echo '<?php echo $service_base_url; ?>' ?>/index">
					<thead>
						<tr>
							<th class="align-center">
								<?php echo '<?php echo new Widget\CheckBox(\'id\', null, false, null, array(\'class\' => \'checkall\')); ?>'; ?> 
							</th>
<?php foreach($columns as $column => $value): ?>
							<th><?php echo '<?php echo _(\'' . $value . '\'); ?>'; ?></th>
<?php endforeach; ?>
							<th class="align-center"><?php echo '<?php echo _(\'Action\'); ?>' ?></th>
						</tr>
					</thead>
				</table>
			</div>

			<div id="popup-delete" data-message-select="<?php echo '<?php echo _(\'Please select item(s) you want to delete\'); ?>' ?>" data-message-delete="<?php echo '<?php echo _(\'Are you sure want to delete {0} item(s)?\'); ?>' ?>"></div>
		</form>
	</div><!--contentinner-->
</div><!--maincontent-->
</form>