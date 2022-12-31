<?php echo '<?php use \Inationsoft\NS; use \Inationsoft\NS\UI\Widget; ?>' ?>
<div class="form-wrapper">
<form method="post" action="<?php echo '<?php echo $app_base_url; ?>' ?>/save">
	<div class="form-header">
		<div id="header-title">
			<?php echo $controller; ?>
		</div>
		<div id="header-button">
			<ul>
				<li><?php echo '<?php echo new Widget\Button(\'dispatch\', \'save\', Widget\Button::BUTTON_SUBMIT); ?>' ?></li>
				<li><?php echo '<?php echo new Widget\Button(\'dispatch\', \'cancel\', Widget\Button::BUTTON_SUBMIT); ?>' ?></li>
			</ul>
		</div>	
		<div class="clear"></div>
	</div>
	<?php echo '<?php echo new Widget\Hidden(\'token\', $token); ?>' ?> 
	<?php echo '<?php echo new Widget\Hidden(\'' . $pk . '\', ' . '$datas[\'' . $pk . '\']' . '); ?>' ?> 
	<table class="form-input">
<?php foreach($columns as $column => $value): ?>
		<tr>
			<td><?php echo $value ?></td>
			<td>:</td>
			<td>
				<?php if($column_types[$column] == 'Text'): ?> 
				<?php echo '<?php echo new Widget\\' . $column_types[$column] . '(\'' . $column . '\', ' . '$datas[\'' . $column . '\']' . '); ?>' ?> 
				<?php elseif($column_types[$column] == 'TextArea'): ?> 
				<?php echo '<?php echo new Widget\\' . $column_types[$column] . '(\'' . $column . '\', ' . '$datas[\'' . $column . '\']' . '); ?>' ?> 
				<?php elseif($column_types[$column] == 'DatePicker'): ?> 
				<?php echo '<?php echo new Widget\\' . $column_types[$column] . '(\'' . $column . '\', ' . '$datas[\'' . $column . '\']' . ', null, Widget\DatePicker::DATEPICKER_TEXTBOX); ?>' ?> 
				<?php endif; ?>
			</td>
		</tr>
<?php endforeach; ?>
	</table>
</form>
</div>