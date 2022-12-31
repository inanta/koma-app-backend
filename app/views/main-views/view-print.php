<?php echo '<?php use \Inationsoft\NS; use \Inationsoft\NS\UI\Widget; ?>' ?>
<div class="form-wrapper">
	<div class="form-header">
		<div id="header-title">
			<?php echo $controller ?>
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
</div>
<script type="text/javascript">
	window.print();
	window.close();
</script>