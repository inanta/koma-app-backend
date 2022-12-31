<?php echo '<?php use \Inationsoft\NS; use \Inationsoft\NS\UI\Widget; ?>' ?>
<form method="post" action="<?php echo '<?php echo $app_base_url; ?>' ?>/dispatch">
<div class="breadcrumbwidget">
	<ul class="breadcrumb">
		<li><a href="<?php echo NS_ROOT_URL ?>">Home</a> <span class="divider">/</span></li>
		<li class="active"><?php echo $controller ?></li>
	</ul>
</div><!--breadcrumbs-->
<div class="pagetitle">
	<div class="row-fluid">
		<div class="span6"><h1><?php echo $controller ?></h1></div>
		<div class="span6">
			<div id="header-button">
				<?php echo '<?php echo new Widget\Hidden(\'token\', $token); ?>' ?>
				<?php echo '<?php echo new Widget\Hidden(\'last_search_keyword\', $search_keyword); ?>' ?>
				<?php echo '<?php echo new Widget\Hidden(\'page\', $page); ?>' ?> 
				<ul>
					<li><?php echo '<?php echo new Widget\Bootstrap\Button(\'dispatch\', \'insert\', Widget\Button::BUTTON_SUBMIT, \'<span class="icon-pencil"></span> Insert\'); ?>' ?></li>
					<li><?php echo '<?php echo new Widget\Bootstrap\Button(\'dispatch\', \'delete\', Widget\Button::BUTTON_NORMAL, \'<span class="icon-trash"></span> Delete\', array(\'id\' => \'button-delete\')); ?>' ?></li>
				</ul>
			</div>	
		</div>
	</div>
</div><!--pagetitle-->

<div class="maincontent">
	<div class="contentinner">
		<div class="form-wrapper">
			<table class="data-list table table-bordered">
				<thead>
					<tr class="table-pre">
						<td colspan="<?php echo (count($columns) + 2); ?>">
							<?php
								$search_column = null;
								foreach($columns as $column => $value) {
									if($search_column == null) {
										$search_column = '\'' . $column . '\' => \'' . $value . '\'';
									} else {
										$search_column .= ', \'' . $column . '\' => \'' . $value . '\'';
									}
								}
							?>
							<?php echo 'Show: <?php echo new Widget\ListBox(\'page_limit\', array(\'5\' => \'5\', \'15\' => \'15\', \'100\' => \'100\', \'0\' => \'All\'), $page_limit); ?>&nbsp;<?php echo new Widget\ListBox(\'search_column\', array(' . $search_column . ')) ?>&nbsp;<?php echo new Widget\Text(\'search_keyword\', $search_keyword) ?>&nbsp;<?php echo new Widget\Bootstrap\Button(\'dispatch\', \'page_limit\', Widget\Button::BUTTON_SUBMIT, \'<span class="icon-search"></span> Search\'); ?>'; ?>
						</td>
					</tr>
					<tr>
						<th class="align-center">
							<?php echo '<?php echo new Widget\CheckBox(\'id\', null, false, null, array(\'class\' => \'checkall\')); ?>' ?>
						</th>
			<?php foreach($columns as $column => $value): ?>
						<th><?php echo $value ?></th>
			<?php endforeach; ?> 
						<th class="align-center">Action</th>
					<tr>
				</thead>
				<tbody>
					<?php echo 
					'<?php $counter = 0; ?>
					<?php foreach($datas as $data): ?>
					<?php if($counter % 2 == 0): ?>
					<tr class="alternate-row">
					<?php else: ?>
					<tr>
					<?php endif; ?>
						<td class="align-center">
							<?php echo new Widget\CheckBox(\'' . $pk . '[]\', $data[\'' . $pk . '\'], false, null, array(\'class\' => \'' . $pk . '\')); ?>
						</td>' ?>
			
<?php foreach($columns as $column => $value): ?> 
						<td><?php echo '<?php echo $data[\'' . $column . '\']; ?>' ?></td>
<?php endforeach; ?> 
						<td class="align-center action-button">
							<?php echo '<?php echo new Widget\Bootstrap\DropdownButton(\'actions\', array(\'<span class="icon-tasks"></span> Detail\' => $app_base_url . \'/detail/\' . $data[\'' . $pk . '\'], \'<span class="icon-edit"></span> Edit\' => $app_base_url . \'/edit/\' . $data[\'' . $pk . '\'], new Widget\Hyperlink(null, $app_base_url . \'/detail/\' . $data[\'' . $pk . '\'] . \'/true\', \'<span class="icon-print"></span> Print\', array(\'target\' => \'_blank\'))), true) ?>' ?>
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
							Showing <?php echo '<?php echo ((($page * $page_limit) - $page_limit) + 1) ?> to <?php echo (($page * $page_limit) > $data_count ? $data_count : ($page * $page_limit)) ?> of <?php echo $data_count ?> entries' ?>
						</td>
						<td colspan="<?php echo ceil((count($columns) + 2) / 2); ?>" class="align-right">
							<?php echo '<?php echo new Widget\Paginator(\'pager\', $data_count, $page, $page_limit, $app_base_url . \'/index/\' . $page_limit . \'/\'); ?>' ?>
						</td>
					</tr>
				</tfoot>
			</table>
		</div>

		<div id="popup-delete"></div>
		<?php
		echo '<?php NS\UI\ScriptManager::getInstance()->addScript(\'jQuery(document).ready(function() { jQuery("#button-delete").click(function() { confirmDelete("Please select item you want to delete", "Are you sure want to delete {0} item(s)?", "\' . $app_base_url . \'/delete"); }); });\'); ?>';
		?>
	</div><!--contentinner-->
</div><!--maincontent-->
</form>