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
	<table class="form-input">
<?php foreach($columns as $column => $value): ?>
<?php if($column == $pk) continue; ?>
		<tr>
			<td><?php echo $value; ?></td>
			<td>:</td>
			<td>
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
						echo '<?php echo new Widget\\Bootstrap\\' . $column_types[$column] . '(\'' . $column . '\', ' . '$datas[\'' . $column . '\']' . '); ?>';
						break;
				}
				?>
			</td>
		</tr>
<?php endforeach; ?>
	</table>
</form>
</div>