<?php
use NS\Core\Model;

class AppFieldModel extends NS\Core\Model {
	private $Attribute = null;

	function __construct() {
		parent::__construct('app_fields', 'app_field_id');

		$this->Attribute = Model::getInstance('AppFieldAttributeModel');
		$this->hasMany($this->Attribute, 'app_field_id');
	}

	function updateOrInsert($field) {
		$is_edit = $this->find(null,  ['app_id' => $field['app_id'], 'app_field_name' => $field['name']]);

		$this->populate([
			'app_id' => $field['app_id'],
			'app_field_name' => $field['name'],
			'app_field_label' => $field['label'],
			'app_field_container' => $field['container'],
			'app_field_row' => $field['row'],
			'app_field_column' => $field['column'],
			'app_field_element' => $field['element'],
		], false);

		if($app_field_id = $this->save()) {
			if ($is_edit) {
				$app_field_id = $this->app_field_id;
			}

			$attributes = [];

			foreach ($field as $key => $value) {
				$attributes[] = $key;

				$this->Attribute->updateOrInsert([
					'app_field_id' => $app_field_id,
					'name' => $key,
					'value' => $value
				]);
			}

			if (count($attributes) > 0) {
				$criteria = $this->Attribute->createFilterCriteria();
				$criteria->equals('app_field_id', $app_field_id);
				$criteria->notIn('app_field_attribute_name', $attributes);

				$attrs = $this->Attribute->delete($criteria);
			}

			

			// die($criteria);
		}
	}

	function deleteFieldAttributes($app_field_id) {
		$this->Attribute->deleteAll(['app_field_id' => $app_field_id]);
	}

	// function saveData($field) {
	// 	$this->populate(array(
	// 		'app_id' => $field['app_id'],
	// 		'app_field_name' => $field['name'],
	// 		'app_field_label' => $field['label'],
	// 		'app_field_container' => $field['container'],
	// 		'app_field_row' => $field['row'],
	// 		'app_field_column' => $field['column'],
	// 		'app_field_element' => $field['element'],
	// 	), false);


	// 	if($app_field_id = $this->save()) {
	// 		foreach ($field as $key => $value) {
	// 			// $field['app_field_id'] = $app_field_id;

	// 			$this->Attribute->saveData(array(
	// 				'app_field_id' => $app_field_id,
	// 				'name' => $key,
	// 				'value' => $value
	// 			));
	// 		}
	// 	}
	// }

	function deleteAttributes($app_field_id) {
		$this->Attribute->deleteAll(array('app_field_id' => $app_field_id));
	}

	function updateHeader($app_id, $field) {
		if ($this->find(null, array('app_id' => $app_id, 'app_field_name' => $field['value']))) {
			$this->populate(array(
			    'app_field_header_align' => $field['align'],
				'app_field_header_format' => $field['format'],
				'app_field_header_visible' => $field['visible']
			));

			$this->save();
		}
	}
}
?>