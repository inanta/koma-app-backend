<?php
use NS\Core\Model;

class AppModel extends NS\Core\Model {
	private $Field = null, $RelatedTable = null, $Tag = null, $Setting = null;

	function __construct() {
		parent::__construct('apps', 'app_id');

		$this->Field = Model::getInstance('AppFieldModel');
		$this->RelatedTable =  Model::getInstance('AppRelatedTableModel');
		$this->Tag =  Model::getInstance('AppTagModel');
		$this->Setting =  Model::getInstance('AppSettingModel');

		//$this->hasOne(new NS\ActiveRecord('products_image'), 'image_id');
		$this->hasMany($this->Field, 'app_id');
		$this->hasMany($this->RelatedTable, 'app_id');
		$this->hasMany($this->Tag, 'app_id');
		$this->hasMany($this->Setting, 'app_id');
	}

	function saveData($app) {
		$is_edit = $this->find(null, array('app_slug' => $app['slug']));

		$this->populate(array(
			'app_name' => $app['name'],
			'app_slug' => $app['slug'],
			'app_table' => $app['table'],
			'app_table_pk' => $app['pk'],
		), false);

		if($app_id = $this->save()) {
			if ($is_edit) {
				$app_id = $this->app_id;
			}

			// $fields = $this->Field->getAll(null, array('app_id' => $app_id));
			// $this->Field->deleteAll(array('app_id' => $app_id));

			// foreach ($fields as $field) {
			// 	$this->Field->deleteAttributes($field['app_field_id']);
			// }

			$fields = [];

			foreach ($app['columns'] as $column) {
				$fields[] = $column['name'];
				$column['app_id'] = $app_id;

				// $this->Field->saveData($column);
				$this->Field->updateOrInsert($column);
			}

			$criteria = $this->Field->createFilterCriteria();
			$criteria->equals('app_id', $app_id);
			$criteria->notIn('app_field_name', $fields);

			$deleted_fields = $this->Field->getAll(null, $criteria);

			foreach ($deleted_fields as $deleted_field) {
				$this->Field->deleteFieldAttributes($deleted_field['app_field_id']);
				$this->Field->delete(['app_field_id' => $deleted_field['app_field_id']]);
			}

			/* Delete And Save Related Table */
			// $tables = $this->RelatedTable->getAll(null, array('app_id' => $app_id));
			// $this->RelatedTable->deleteAll(array('app_id' => $app_id));

			// foreach ($tables as $table) {
			// 	$this->RelatedTable->deleteColumns($table['app_related_table_id']);
			// }

			foreach ($app['related_tables'] as $related_table) {
				$related_table['app_id'] = $app_id;

				// $this->RelatedTable->saveData($related_table);
				$this->RelatedTable->updateOrInsert($related_table);
			}
		}
	}

	function deleteApp($slug) {
		$app = $this->get(null, array('app_slug' => $slug));

		foreach ($app['app_fields'] as $field) {
			$this->Field->deleteAttributes($field['app_field_id']);
		}

		foreach ($app['app_related_tables'] as $table) {
			$this->RelatedTable->deleteColumns($table['app_related_table_id']);
		}

		return $this->deleteAll([
			'app_id' => $app['app_id'],
			'app_slug' => $slug,
		], null, true);
	}

	function updateSettings($slug, $data) {
		$app = $this->get(null, array('app_slug' => $slug));
		$updated_app = [];

		$mapping = [
			'slug' => 'app_slug',
			'is_read_only' => 'app_is_read_only',
			'features' => [
				'export' => 'app_feature_export',
				'import' => 'app_feature_import'
			],
			'webhooks' => [
				'insert' => 'app_webhook_insert_url',
				'retrieve' => 'app_webhook_retrieve_url',
				'update' => 'app_webhook_update_url'
			]
		];

		if (isset($data['headers'])) {
			foreach ($data['headers'] as $field) {
				$this->Field->updateHeader($app['app_id'], $field);
			}
		}

		foreach ($mapping as $key => $value) {
			if(isset($data[$key])) {
				if (is_array($value)) {
					foreach ($value as $sub_key => $sub_value) {
						$updated_app[$sub_value] = $data[$key][$sub_key];	
					}
				} else {
					$updated_app[$value] = $data[$key];
				}
				
			}
		}

		if (count($updated_app) > 0) {
			return $this->update($updated_app, [
				'app_slug' => $slug
			]);
		}

		return false;
	}
}
?>