<?php echo '<?php use \Inationsoft\NS; use \Inationsoft\NS\UI\Widget; ?>' ?> 
<div class="form-wrapper">
<form method="post" action="<?php echo '<?php echo $app_base_url; ?>' ?>/dispatch">
	<div class="form-header">
		<div id="header-title">
			<?php echo $controller ?> 
		</div>
		<div id="header-button">
			<?php echo '<?php echo new Widget\Hidden(\'token\', $token); ?>' ?> 
			<ul>
				<li><?php echo '<?php echo new Widget\Button(\'dispatch\', \'insert\', Widget\Button::BUTTON_SUBMIT); ?>' ?></li>
				<li><?php echo '<?php echo new Widget\Button(\'dispatch\', \'delete\', Widget\Button::BUTTON_NORMAL, null, array(\'id\' => \'button-delete\')); ?>' ?></li>
			</ul>
		</div>	
		<div class="clear"></div>
	</div>
	<div id="page-limit">
		<?php echo 'Show: <?php echo new Widget\ListBox(\'page_limit\', array(\'5\' => \'5\', \'15\' => \'15\', \'100\' => \'100\', \'0\' => \'All\'), $page_limit); ?>&nbsp;<?php echo new Widget\Text(\'search_keyword\', $search_keyword) ?>&nbsp;<?php echo new Widget\Button(\'dispatch\', \'page_limit\', Widget\Button::BUTTON_SUBMIT, \'Search\'); ?>'; ?>
	</div>
	<table class="data-list">
		<tr>
			<th><?php echo '<?php echo new Widget\CheckBox(\'id\'); ?>' ?></th>
<?php foreach($columns as $column => $value): ?>
			<th class="text-left"><?php echo $value ?></th>
<?php endforeach; ?> 
			<th class="text-right">Action</th>
		<tr>
		<?php echo 
		'<?php $counter = 0; ?>
		<?php foreach($datas as $data): ?>
		<?php if($counter % 2 == 0): ?>
		<tr class="alternate-row">
		<?php else: ?>
		<tr>
		<?php endif; ?>
			<td><?php echo new Widget\CheckBox(\'' . $pk . '[]\', $data[\'' . $pk . '\'], false, null, array(\'class\' => \'' . $pk . '\')); ?></td>' ?>

<?php foreach($columns as $column => $value): ?> 
			<td class="text-left"><?php echo '<?php echo $data[\'' . $column . '\']; ?>' ?></td>
<?php endforeach; ?> 
			<td class="text-right">
				<div id="action-edit">[ <a href="<?php echo '<?php echo $app_base_url; ?>' ?>/edit/<?php echo '<?php echo $data[\'' . $pk . '\'] ?>' ?>">Edit</a> ]</div>
				<div id="action-detail">[ <a href="<?php echo '<?php echo $app_base_url; ?>' ?>/detail/<?php echo '<?php echo $data[\'' . $pk . '\'] ?>' ?>">Detail</a> ]</div>
				<div id="action-print">[ <a href="<?php echo '<?php echo $app_base_url; ?>' ?>/detail/<?php echo '<?php echo $data[\'' . $pk . '\'] ?>' ?>/true" target="_blank">Print</a> ]</div>
			</td>
		<?php echo
		'</tr>
		<?php ++$counter; ?>
		<?php endforeach; ?>'
		?> 
	</table>
	<div id="pager"><?php echo '<?php echo new Widget\Paginator(\'pager\', $data_count, $page, $page_limit, $app_base_url . \'/index/\' . $page_limit . \'/\'); ?>' ?></div>
</form>
</div>

<div id="popup-delete"></div>
<?php
echo '<?php NS\UI\ScriptManager::getInstance()->addScript(
	\'jQuery(document).ready(function() {
		jQuery("#button-delete").click(function() {
			if(jQuery(".' . $pk . ':checked").length == 0) {
				jQuery("#popup-delete").text("Please select item you want to delete").dialog({
					title: "Message",
					resizable: false,
					modal: true,
					buttons: {
						"OK": function() {
							jQuery(this).dialog( "close" );
						}	
					}
				});
			} else {
				jQuery("#popup-delete").text("Are you sure want to delete " + jQuery(".' . $pk . ':checked").length + " selected items?").dialog({
					title: "Confirmation",
					resizable: false,
					modal: true,
					buttons: {
						"Delete": function() {
							jQuery(this).dialog("close");
							jQuery("form").attr("action", "\' . $app_base_url. \'/delete").submit();
						},
						"Cancel": function() {
							jQuery(this).dialog( "close" );
						}	
					}
				});
			}
			
		});
	});\'
);
?>' ?>