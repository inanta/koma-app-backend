<?php echo '<?php' ?> 
use NS\UI\Widget;
<?php echo '?>' ?> 
?> 
<div class="breadcrumbwidget">
	<ul class="breadcrumb">
		<li><a href="<?php echo '<?php echo NS_BASE_URL; ?>' ?>"><?php echo '<?php echo _(\'Home\'); ?>' ?></a> <span class="divider">/</span></li>
		<li><a href="<?php echo '<?php echo $app_base_url; ?>' ?>"><?php echo '<?php echo _(\'', $controller, '\'); ?>' ?></a> <span class="divider">/</span></li>
		<li class="active"><?php echo '<?php echo _(\'Add Has Many\'); ?>' ?></li>
	</ul>
</div><!--breadcrumbs-->
<div class="pagetitle">
	<div class="row-fluid">
		<div class="span6"><h1><?php echo '<?php echo _(\'', $controller, '\'); ?>' ?></h1><span><?php echo '<?php echo _(\'Add has many data\'); ?>' ?></span></div>
		<div class="span6">
			<div id="header-button">
				<ul>
					<li><?php echo '<?php echo new Widget\Bootstrap\HyperlinkButton(null, $app_base_url . \'/index\', \'<span class="icon-ok"></span> \' . _(\'Save\')); ?>' ?></li>
				</ul>
			</div>
		</div>
	</div>
</div><!--pagetitle-->

<div class="maincontent">
	<div class="contentinner">
		<div class="form-wrapper">
			<h4 class="widgettitle shadowed"><?php echo '<?php echo _(\'List Of All Data\'); ?>' ?></h4>
			<div class="widgetcontent">
				<table class="data-list table table-bordered">
					<thead>
						<tr class="table-pre">
							<td colspan="<?php echo (count($columns) + 2) ?>">
<?php
$search_column = null;
foreach($columns as $column => $value) {
	if($value == '') continue;

	if($search_column == null) {
		$search_column = '\'' . $column . '\' => _(\'' . $value . '\')';
	} else {
		$search_column .= ', \'' . $column . '\' => _(\'' . $value . '\')';
	}
}
?>
								<?php echo '<?php echo _(\'Show\'); ?>: <?php echo new Widget\ListBox(\'page_limit\', array(\'5\' => \'5\', \'15\' => \'15\', \'100\' => \'100\', \'0\' => _(\'All\')), $page_limit); ?>&nbsp;<?php echo new Widget\ListBox(\'search_column\', array(' . $search_column . ')) ?>&nbsp;'; ?> 
								<?php echo '<?php echo new Widget\Text(\'search_keyword\', $search_keyword) ?>&nbsp;<?php echo new Widget\Bootstrap\Button(\'dispatch\', \'page_limit\', Widget\Button::BUTTON_SUBMIT, \'<span class="icon-search"></span> \' . _(\'Search\')); ?>'; ?> 
							</td>
						</tr>
						<tr>
<?php foreach($columns as $column => $value): ?>
<?php if($value == '') continue; ?>
							<th><?php echo '<?php echo _(\'' . $value . '\'); ?>'; ?></th>
<?php endforeach; ?>
							<th class="align-center"><?php echo '<?php echo _(\'Action\'); ?>' ?></th>
						</tr>
					</thead>
					<tbody>
						<?php echo 
						'<?php $counter = 0; ?>
						<?php foreach($datas as $data): ?>
						<?php if($counter % 2 == 0): ?>
						<tr class="alternate-row">
						<?php else: ?>
						<tr>
						<?php endif; ?>' ?> 
<?php foreach($columns as $column => $value): ?>
<?php if($value == '') continue; ?>
							<td><?php echo '<?php echo $data[\'' . $column . '\']; ?>'; ?></td>
<?php endforeach; ?>
							<td class="align-center action-button">
								<form action="<?php echo $app_base_url, '/savehasmany/', $reservation['reservation_id']; ?>" id="savehasmany-<?php echo '<?php echo $counter; ?>' ?>" method="post">
									<?php echo '<?php echo new Widget\Hidden(\'food_menu_id\', $data[\'food_menu_id\']); ?>' ?>
									<?php echo '<?php echo new Widget\Bootstrap\Button(\'savehasmany\', \'savehasmany\', Widget\Bootstrap\Button::BUTTON_SUBMIT, \'<span class="icon-plus"></span> \' . _(\'Add\')); ?>' ?>
								</form>
							</td>
						<?php echo
						'</tr>
						<?php ++$counter; ?>
						<?php endforeach; ?>'
						?> 
					</tbody>
					<tfoot>
						<tr class="table-pos">
							<td colspan="<?php echo floor((count($columns) + 2) / 2); ?>">
								<?php echo '<?php echo ($data_count == 0 ? _(\'Data not found\') : ($page_limit == 0 ? sprintf(_(\'Showing 1 to %s of %s entries\'), $data_count, $data_count) : sprintf(_(\'Showing %s to %s of %s entries\'), ((($page * $page_limit) - $page_limit) + 1), (($page * $page_limit) > $data_count ? $data_count : ($page * $page_limit)), $data_count))) ?>'; ?> 
							</td>
							<td colspan="<?php echo ceil((count($columns) + 2) / 2); ?>" class="align-right">
								<?php echo '<?php echo new Widget\Paginator(\'pager\', $data_count, $page, $page_limit, $app_base_url . \'/index/\' . $page_limit . \'/\'); ?>' ?> 
							</td>
						</tr>
					</tfoot>
				</table>
			</div>

			<h4 class="widgettitle shadowed"><?php echo '<?php echo _(\'List Of Selected Data\'); ?>' ?></h4>
			<div class="widgetcontent">
				<table class="data-list table table-bordered">
					<thead>
						<tr>
<?php foreach($columns as $column => $value): ?>
							<th><?php echo '<?php echo _(\'' . $value . '\'); ?>'; ?></th>
<?php endforeach; ?>
							<th class="align-center"><?php echo '<?php echo _(\'Action\'); ?>' ?></th>
						</tr>
					</thead>
					<tbody>
						<?php echo 
						'<?php $counter = 0; ?>
						<?php foreach($reservation[\'ht_reservation_food_menus\'] as $data): ?>
						<?php if($counter % 2 == 0): ?>
						<tr class="alternate-row">
						<?php else: ?>
						<tr>
						<?php endif; ?>' ?> 
<?php foreach($columns as $column => $value): ?>
							<td><?php echo '<?php echo $data[\'' . $column . '\']; ?>'; ?></td>
<?php endforeach; ?>
							<td class="align-center action-button">
								<?php echo '<?php echo new Widget\Bootstrap\HyperlinkButton(null, $app_base_url . \'/removehasmany/\' . $data[\'reservation_id\'] . \'/\' . $data[\'food_menu_id\'] . \'/\' . $data[\'order_time\'], \'<span class="icon-trash"></span> \' . _(\'Delete\')); ?>' ?>
							</td>
						<?php echo
						'</tr>
						<?php ++$counter; ?>
						<?php endforeach; ?>'
						?> 
					</tbody>
					<tfoot>
						<tr class="table-pos">
							<td colspan="6">
								<?php echo '<?php echo (count($reservation[\'ht_reservation_food_menus\']) == 0 ? _(\'Data not found\') : sprintf(_(\'Showing %s entries\'), count($reservation[\'ht_reservation_food_menus\']))) ?>'; ?> 
							</td>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div><!--contentinner-->
</div><!--maincontent-->