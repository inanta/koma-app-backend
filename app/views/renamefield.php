<?php
use NS\UI\Widget;
?>
<div>
	<form method="post" action="<?php echo NS_BASE_URL . '/appbuilder/hasmany' ?>">
		<table>
			<tr>
				<td>
					Database Table Field
				</td>
			</tr>
		<?php foreach($fields as $key => $field): ?>
			<tr>
				<td><?php echo $key ?></td>
			</tr>
			<tr>
				<td>
					<?php echo new Widget\Text($key, $field) ?>
					<?php echo new Widget\ListBox($key . '_type', array(
						'Text' => 'Text Box',
						'TextArea' => 'Text Area (Multiline Text Box)',
						'Password' => 'Password Text Box',
						'FileUpload' => 'File Selector For Upload',
						'DatePicker' => 'Date Picker',
						'SwitchButton' => 'Bootstrap Switch Button (On / Off)',
						'SwitchButton-YesNo' => 'Bootstrap Switch Button (Yes / No)'
					)) ?>
				</td>
			</tr>
		<?php endforeach ?>
		<tr><td><?php echo new Widget\Button('submit', 'submit', Widget\Button::BUTTON_SUBMIT) ?></td></tr>
		</table>
	</form>
</div>