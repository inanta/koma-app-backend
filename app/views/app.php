<?php
use NS\UI\Widget;
?>
<div>
	<form method="post" action="<?php echo NS_BASE_URL . '/appbuilder/build' ?>">
		<table>
			<tr>
				<td>
					<p>Application Name</p>
				</td>
				<td>
					<?php echo new Widget\Text('app', $app) ?>
				</td>
			</tr>
			<tr>
				<td>
					<p>Controller Name</p>
				</td>
				<td>
					<?php echo new Widget\Text('controller', $controller) ?>
				</td>
			</tr>
			<tr>
				<td>
					<p>Model Name</p>
				</td>
				<td>
					<?php echo new Widget\Text('model', $model) ?>
				</td>
			</tr>
			<tr>
				<td colspan="2"><?php echo new Widget\Button('submit', 'submit', Widget\Button::BUTTON_SUBMIT) ?></td>
			</tr>
		</table>
		<hr />
		<table>
			<?php foreach($hasmany_tables as $table => $model): ?>
			<tr>
				<td>
					<p>Has Many Model Name</p>
				</td>
				<td>
					<?php echo new Widget\Text('hasmany_models[' . $table . ']', $model) ?>
					<?php $save_column = array(); ?>
					<?php
						foreach($hasmany_table_columns[$table] as $column => $new_column) {
							$save_column[$column] = $column;
						}
					?>
					Save Column: <?php echo new Widget\ListBox('hasmany_save_column[' . $table . ']', $save_column); ?>
					PK: <?php echo new Widget\ListBox('hasmany_pks[' . $table . ']', $save_column); ?>
				</td>
			</tr>
			<tr>
				<td>
					<p>Has Many Save Model Name</p>
				</td>
				<td>
					<?php echo new Widget\Text('hasmany_save_models[' . $hasmany_save_tables[$table]['table'] . ']', $hasmany_save_tables[$table]['model']) ?>
					<?php echo new Widget\ListBox('fk[' . $table . ']', $parent_columns); ?>
				</td>
			</tr>
			<tr>
				<td>
					<p>Columns Rename</p>
					<table>
						<?php foreach($hasmany_table_columns[$table] as $column => $new_column): ?>
						<tr>
							<td>
								<?php echo $column ?>
							</td>
							<td>
								<?php echo new Widget\Text('hasmany_columns[' . $table . '][' . $column . ']', $new_column) ?>
							</td>
						</tr>
						<?php endforeach; ?>
					</table>
				</td>
			</tr>
			<?php endforeach; ?>
		</table>
	</form>
</div>
