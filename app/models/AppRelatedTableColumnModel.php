<?php

class AppRelatedTableColumnModel extends NS\Core\Model {
	function __construct() {
		parent::__construct('app_related_table_columns', 'app_related_table_column_id');
	}

	function updateOrInsert($column) {
		$is_edit = $this->find(null,  ['app_related_table_id' => $column['app_related_table_id'], 'app_related_table_column_name' => $column['name']]);

		$this->populate(array(
			'app_related_table_id' => $column['app_related_table_id'],
			'app_related_table_column_name' => $column['name'],
		), false);

		return $this->save();
	}

	// function saveData($column) {
	// 	$this->populate(array(
	// 		'app_related_table_id' => $column['app_related_table_id'],
	// 		'app_related_table_column_name' => $column['name'],
	// 	), false);

	// 	return $this->save();
	// }
}
?>