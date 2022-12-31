<?php
use NS\Core\Model;

class AppRelatedTableModel extends NS\Core\Model {
	private $Column = null;

	function __construct() {
		parent::__construct('app_related_tables', 'app_related_table_id');

		$this->Column = Model::getInstance('AppRelatedTableColumnModel');
		$this->hasMany($this->Column, 'app_related_table_id');
	}

	function updateOrInsert($table) {
		$is_edit = $this->find(null,  ['app_id' => $table['app_id'], 'app_related_table_name' => $table['table']]);

		$this->populate([
			'app_id' => $table['app_id'],
			'app_related_table_name' => $table['table'],
			'app_related_table_join_column_1' => $table['join_column_1'],
			'app_related_table_join_column_2' => $table['join_column_2'],
		], false);

		if($app_related_table_id = $this->save()) {
			if ($is_edit) {
				$app_related_table_id = $this->app_related_table_id;
			}

			foreach ($table['columns'] as $column) {
				$column = [
					'app_related_table_id' => $app_related_table_id,
					'name' => $column
				];

				$this->Column->updateOrInsert($column);
			}
		}
	}

	// function saveData($field) {
	// 	echo "save related table";
	// 	$this->populate(array(
	// 		'app_id' => $field['app_id'],
	// 		'app_related_table_name' => $field['table'],
	// 		'app_related_table_join_column_1' => $field['join_column_1'],
	// 		'app_related_table_join_column_2' => $field['join_column_2'],
	// 	), false);

	// 	if($app_related_table_id = $this->save()) {
	// 		foreach ($field['columns'] as $column) {
	// 			$column = [
	// 				'app_related_table_id' => $app_related_table_id,
	// 				'name' => $column
	// 			];

	// 			$this->Column->saveData($column);
	// 		}
	// 	}


	// 	return true;
	// }

	function deleteColumns($app_related_table_id) {
		$this->Column->deleteAll(array('app_related_table_id' => $app_related_table_id));
	}
}
?>