<?php

class AppFieldAttributeModel extends NS\Core\Model {
	function __construct() {
		parent::__construct('app_field_attributes', 'app_field_attribute_id');
	}

	function updateOrInsert($attribute) {
		$skipped_attributes = [
			'element',
			'label',
			'name',
			'app_id',
			'container',
			'row',
			'column'
		];

		if (!in_array($attribute['name'], $skipped_attributes)) {
			$value = $attribute['value'];

			if (is_object($value) || is_array($value)) {
				$value = json_encode($value);
			}

			$is_edit = $this->find(null,  ['app_field_id' => $attribute['app_field_id'], 'app_field_attribute_name' => $attribute['name']]);

			// var_dump($is_edit);
			// var_dump($attribute);

			$this->populate([
				'app_field_id' => $attribute['app_field_id'],
				'app_field_attribute_name' => $attribute['name'],
				'app_field_attribute_value' => $value,
			], false);

			return $this->save();
		}
	}

	// function saveData($attribute) {
	// 	$skipped_attributes = array(
	// 		'element',
	// 		'label',
	// 		'name',
	// 		'app_id',
	// 		'container',
	// 		'row',
	// 		'column'
	// 	);

	// 	if (!in_array($attribute['name'], $skipped_attributes)) {
	// 		$value = $attribute['value'];

	// 		if (is_object($value) || is_array($value)) {
	// 			$value = json_encode($value);
	// 		}

	// 		$this->populate(array(
	// 			'app_field_id' => $attribute['app_field_id'],
	// 			'app_field_attribute_name' => $attribute['name'],
	// 			'app_field_attribute_value' => $value,
	// 		), false);

	// 		return $this->save();
	// 	}

	// 	return null;
	// }
}
?>