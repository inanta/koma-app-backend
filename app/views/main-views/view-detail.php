<?php echo '<?php use \Inationsoft\NS; use \Inationsoft\NS\UI\Widget; ?>' ?>
<div class="form-wrapper">
<form method="post" action="<?php echo '<?php echo $app_base_url ?>'; ?>">
	<div class="form-header">
		<div id="header-title">
			<?php echo $controller ?>
		</div>
		<div id="header-button">
			<ul>
				<li><?php echo '<?php echo new Widget\Button(\'dispatch\', \'back\', Widget\Button::BUTTON_SUBMIT); ?>' ?></li>
			</ul>
		</div>	
		<div class="clear"></div>
	</div>
	<table class="form-input">
<?php $counter = 0; ?>
<?php foreach($columns as $column => $value): ?> 
<?php if($counter % 2 == 0): ?>
		<tr class="alternate-row">
<?php else: ?>
		<tr>
<?php endif; ?> 
			<td><?php echo $value; ?></td>
			<td>:</td>
			<td>
				<?php echo '<?php echo $datas[\'' . $column . '\']; ?>' ?> 
			</td>
		</tr>
<?php ++$counter; ?>
<?php endforeach; ?>
	</table>
</form>
</div>