<?php
use NS\UI\Widget;
?>
<div>
	<form method="post" action="<?php echo NS_BASE_URL . '/appbuilder/renamefield' ?>">
		<table>
			<tr>
				<td>
					Database Table Field
				</td>
			</tr>
		<?php foreach($fields as $field): ?>
			<tr>
				<td><?php echo new Widget\CheckBox('fields[]', $field, !(NS\String::endsWith($field, 'id') || NS\String::startsWith($field, 'id')), $field) ?></td>
			</tr>
			
		<?php endforeach ?>
			<tr>
				<td>
					<p>Select Auto Icrement Primary Key Column</p>
				</td>
				<td>
					<?php echo new Widget\ListBox('pk', $fields) ?>
				</td>
			</tr>
			<!--
			<tr>
				<td>
					<p>Select Column For Searching</p>
				</td>
				<td>
					<?php echo new Widget\ListBox('search', $fields) ?>
				</td>
			</tr>
			-->
			<tr>
				<td><?php echo new Widget\Button('submit', 'submit', Widget\Button::BUTTON_SUBMIT) ?></td>
			</tr>
		</table>
	</form>
</div>
