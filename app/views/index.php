<?php
use NS\UI\Widget;
?>
<div>
	<form method="post" action="<?php echo NS_BASE_URL . '/appbuilder/fieldselect' ?>">
		<table>
			<!--
			<tr>
				<td>
					<?php echo new Widget\Text('Username', null, 'Database Username') ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo new Widget\Text('Password', null, 'Database Password') ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo new Widget\Text('Database', null, 'Database Name') ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo new Widget\Text('table', null, 'Table Name') ?>
				</td>
			</tr>
			-->
			<tr>
				<td>
					<?php echo new Widget\ListBox('table', $tables) ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo new Widget\Button('submit', 'submit', Widget\Button::BUTTON_SUBMIT) ?>
				</td>
			</tr>
		</table>
	</form>
</div>
