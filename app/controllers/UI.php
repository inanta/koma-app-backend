<?php
/*
	Copyright (C) 2012 Inanta Martsanto 
	Inanta Martsanto (inanta@inationsoft.com)

	This file is part of Koma.

	Koma is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	Koma is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with Koma.  If not, see <http://www.gnu.org/licenses/>.
*/

namespace Inationsoft\Koma;

use NS\Core\Config;
use NS\Core\Controller;
use NS\Core\Model;
use NS\Core\View;
use NS\Net\Http\ClientRequest;
use NS\Database\Database;
use NS\Database\ActiveRecord;
use Koma\User;
use Koma\KomaTheme;

define('_NAMESPACE_', '\Inationsoft\Koma');

class UI extends Controller {
	private $_model, $_redirectBase, $_uh, $_cf;
	
	function _main() {
		$this->_cf = Config::getInstance();
		$this->_cf->Koma->ThemePath = NS_SYSTEM_PATH . '/theme/default';
		$this->_cf->Koma->ThemePublicURL = NS_PUBLIC_URL . '/koma/theme/default';
	}

	function index() {
	    $theme = KomaTheme::getInstance();
	    $theme->addHtmlHeadTag('script', ['type' => 'module', 'crossorigin' => null, 'src' => NS_ROOT_URL . '/public/application/app/ui-9b6bf6b1.js'], true);
	    $theme->addHtmlHeadTag('link', ['rel' => 'stylesheet', 'href' => NS_ROOT_URL . '/public/index-9c09f922.css']);
	    
		return new View('ui/index.php', array(
			// 'token' => md5(User::getInstance()->Token . get_class($this)),
		), array(
			$this->_cf->Koma->ThemePath . '/app'
		));
	}

    /*
	function apps() {
		$request = new ClientRequest();
		$app_model = Model::getInstance('AppModel');
		$apps = $app_model->getAll();

		$mapping = [
			'app_id' => 'id',
			'app_name' => 'name',
			'app_slug' => 'slug',
			'app_info' => 'info',
			'app_table' => 'table',
			'app_table_pk' => 'pk',
			'app_is_read_only' => 'is_read_only',
			'app_tags' => 'tags',
			'app_feature_export' => 'export',
			'app_feature_import' => 'import',
			'app_webhook_insert_url' => 'webhook_insert',
			'app_webhook_retrieve_url' => 'webhook_retrieve',
			'app_webhook_update_url' => 'webhook_update',
		];

		$response = [
			'success' => true,
			'headers' => [
				[ label  => "", value  => "id", searchable  => false, sortable  => false, visible  => true ],
				[ label  => "Name", value => "name", searchable => true, sortable => true, visible => true ],
				[ label  => "Slug", value  => "slug", searchable  => true, sortable  => true, visible  => true  ],
				[ label  => "Tags", value  => "tags", searchable  => false, sortable  => false, visible  => true  ],
				[ label  => "Description", value  => "info", searchable  => false, sortable  => false, visible  => true  ],
				[ label  => "Action",  value  => "action",  searchable => false,  sortable => false,  visible => true]
			],
			'items' => [],
			'app_id_column' => 'id'
		];

		foreach ($apps as $app) {
			$current_app = [
				'settings' => [
				    'features' => [
				        'label' => 'Features',
						'value' => []
				    ],
					'webhooks' => [
						'label' => 'Webhooks',
						'value' => []
					]
				]
			];

			foreach ($mapping as $key => $value) {
				if ($value == 'is_read_only') {
					$app[$key] = $app[$key] === '1' ? true : false;
				} else if ($value == 'tags') {
					$tags = [];

					foreach ($app[$key] as $tag) {
						$tags[] = $tag['app_tag_label'];
					}

					$app[$key] = $tags;
				} else if ($value == 'export' || $value == 'import') {
					$features_name_mapping = [
						'export' => [
							'label' => 'Export',
							'value' => 'export'
							
						],
						'import' => [
							'label' => 'Import',
							'value' => 'import'
							
						]
					];

					$current_app['settings']['features']['value'][$features_name_mapping[$value]['value']] = [
						'label' => $features_name_mapping[$value]['label'],
						'value' => filter_var($app[$key], FILTER_VALIDATE_BOOLEAN)
					];

					$app[$key] = $current_app['settings'];
					$value = 'settings';
				} else if ($value == 'webhook_insert' || $value == 'webhook_update' || $value == 'webhook_retrieve') {
					$webhook_name_mapping = [
						'webhook_insert' => [
							'label' => 'Insert',
							'value' => 'insert'
							
						],
						'webhook_update' => [
							'label' => 'Update',
							'value' => 'update'
							
						],
						'webhook_retrieve' => [
							'label' => 'Retrieve',
							'value' => 'retrieve'
							
						]
					];

					$current_app['settings']['webhooks']['value'][$webhook_name_mapping[$value]['value']] = [
						'label' => $webhook_name_mapping[$value]['label'],
						'value' => $app[$key]
					];

					$app[$key] = $current_app['settings'];
					$value = 'settings';
				}

				$current_app[$value] = $app[$key];
			}

			$response['items'][] = $current_app;
		}

		return new Response(json_encode($response), 200, ['content-type' => ['application/json']]);
	}

	function columns($table) {
		$db = \NS\Database\Database::getInstance();
		$columns = $db->getColumns($table);

		return new Response(json_encode($columns), 200, ['content-type' => ['application/json']]);
	}

	function data($table) {
		$request = new ClientRequest();
		$columns = explode('/', $request->Get->value('columns'));
		$columns = array_map('trim', $columns);

		$ar = new ActiveRecord($table);
		$ar->findAll($columns);
		$records = $ar->toArray();
		
		return new Response(json_encode(array(
			'success' => true, 
			'records' => $records,
		)), 200, ['content-type' => ['application/json']]);
	}

	function delete() {
		$request = new ClientRequest();
		$data = $request->JSON->value();
		$apps = [];

		$app_model = Model::getInstance('AppModel');

		foreach ($data as $slug) {
			$apps[$slug] = $app_model->deleteApp($slug);
		}

		return new Response(json_encode(array('success' => true, 'apps' => $apps)), 200, ['content-type' => ['application/json']]);
	}

	function deleterecords($slug) {
		$request = new ClientRequest();
		$data = $request->JSON->value();

		$app_model = Model::getInstance('AppModel');
		$app = $app_model->get(null, ['app_slug' => $slug]);


		$ar = new ActiveRecord($app['app_table'], $app['app_table_pk']);

		foreach ($data['items'] as $item) {
			$record[$app['app_table_pk']] = $item;

			$ar->deleteAll($record);
		}
		
		return new Response(json_encode(array('success' => true)), 200, ['content-type' => ['application/json']]);
	}

	function records($slug) {
		$request = new ClientRequest();
		$data = $request->JSON->value();

		$app_model = Model::getInstance('AppModel');
		$app = $app_model->get(null, ['app_slug' => $slug]);

		$ar = new ActiveRecord($app['app_table'], $app['app_table_pk']);
		$ar_related = [];

		foreach($app['app_related_tables'] as $related_table) {
			$ar_related[] =  new ActiveRecord($related_table['app_related_table_name']);

			$ar->hasOne(end($ar_related), $related_table['app_related_table_join_column_1'], $related_table['app_related_table_join_column_2']);
		}
		
		$ar->findAll();

		$headers = [];
		$items = [];
		$records = $ar->toArray();

		if ($app['app_is_read_only'] === '0') {
			$headers[] = [
				'align' => $field['app_field_header_align'],
				'format' => $field['app_field_header_format'],
				'hideable' => false,
				'label' => $app['app_table_pk'],
				'searchable' => false,
				'sortable' => false,
				'value' => $app['app_table_pk'],
				'visible' => true
			];
		}

		$has_related_tables = (isset($app['app_related_tables'])) ? true : false;

		foreach ($app['app_fields'] as $field) {
			if ($has_related_tables) {
				$attribute_items = $this->_getAttribute($field, 'items');

				if ($attribute_items && $attribute_items['source'] ==  'db') {
					$field['app_field_name'] = $attribute_items['label_column'];
				}
			}

			$headers[] = [
			    'align' => $field['app_field_header_align'],
				'format' => $field['app_field_header_format'],
				'label' => $field['app_field_label'],
				'searchable' => true,
				'sortable' => true,
				'value' => $field['app_field_name'],
				'visible' => filter_var($field['app_field_header_visible'], FILTER_VALIDATE_BOOLEAN)
			];

			
		}

		if ($app['app_is_read_only'] === '0') {
			$headers[] = [
				'align' => $field['app_field_header_align'],
				'format' => $field['app_field_header_format'],
				'hideable' => false,
				'label' => 'Action',
				'searchable' => false,
				'sortable' => false,
				'value' => 'action',
				'visible' => true
			];
		}

		foreach ($records as $record) {
			$current_record = [];

			foreach ($headers as $header) {
				$current_record[$header['value']] = $record[$header['value']];
			}

			$current_record['action'] = $record[$app['app_table_pk']];
			$items[] = $current_record;
		}
		
		$settings = [
		    'features' => [
		        'export' => filter_var($app['app_feature_export'], FILTER_VALIDATE_BOOLEAN),
		        'import' => filter_var($app['app_feature_import'], FILTER_VALIDATE_BOOLEAN)
		    ]
		];
		
		return new Response(json_encode(array(
			'success' => true, 
			'headers' => $headers,
			'items' => $items, 
			'app' => [
				'name' => $app['app_name'],
				'pk' => $app['app_table_pk'],
				'is_read_only' => $app['app_is_read_only'] === '1' ? true: false,
				'settings' => $settings
			]
		)), 200, ['content-type' => ['application/json']]);
	}

	function record($slug, $id) {
		$request = new ClientRequest();
		$data = $request->JSON->value();

		$app_model = Model::getInstance('AppModel');
		$app = $app_model->get(null, ['app_slug' => $slug]);

		$ar = new ActiveRecord($app['app_table'], $app['app_table_pk']);
		$ar->findByPK($id);

		$record = current($ar->toArray());
		$filtered_record = [];

		foreach ($app['app_fields'] as $field) {
			$filtered_record[$field['app_field_name']] = $record[$field['app_field_name']];
		}

		return new Response(json_encode(array(
			'success' => true, 
			'record' => $filtered_record
		)), 200, ['content-type' => ['application/json']]);
	}

	function save() {
		// TODO Move config somewhere else
		$table_relations = [
			'select' => 'one',
			'grid-input' => 'many',
		];

		$request = new ClientRequest();
		$app_model = Model::getInstance('AppModel');

		$app = $request->JSON->value('app');

		$db = \NS\Database\Database::getInstance();
		$columns = $db->getColumns($app['table']);

		$related_tables = [];
		$attributes = [];

		foreach ($app['columns'] as $index => $column) {
			foreach ($column as $key => $value) {
				if (isset($value['source']) && $value['source'] == 'db') {
					$attributes[]  = $key;
					$attributes[]  = $value;

					$element = 
						isset($app['columns'][$index][$key]['value'][0]['element']) ?
							$app['columns'][$index][$key]['value'][0]['element'] : 'option';

					$app['columns'][$index][$key]['value'] = [
						[
							'element' => $element
						]
					];

					if (in_array($app['columns'][$index]['name'], $columns)) {
						$app['related_tables'][] = [
							'table' => $app['columns'][$index][$key]['table'],
							'join_column_1' => $app['columns'][$index]['name'],
							'join_column_2' => $app['columns'][$index][$key]['value_column'],
							'columns' => [$app['columns'][$index][$key]['label_column']],

							'relation' => $table_relations[$app['columns'][$index]['element']]
						];
					}
				}
			}

			// if (isset($app['columns'][$index]['items']['source']) && $app['columns'][$index]['items']['source'] == 'db') {
			// 	$element = 
			// 		isset($app['columns'][$index]['items']['value'][0]['element']) ?
			// 			$app['columns'][$index]['items']['value'][0]['element'] : 'option';

			// 	$app['columns'][$index]['items']['value'] = [
			// 		[
			// 			'element' => $element
			// 		]
			// 	];

			// 	$app['related_tables'][] = [
			// 		'table' => $app['columns'][$index]['items']['table'],
			// 		'join_column_1' => $app['columns'][$index]['name'],
			// 		'join_column_2' => $app['columns'][$index]['items']['value_column'],
			// 		'columns' => [$app['columns'][$index]['items']['label_column']],
			// 	];
			// }
		}

		$app_model->saveData($app);
		
		return new Response(
			json_encode(
				array(
					'success' => true,
					'attributes' => $app,
					'columns' => $columns
				)
			), 200, [ 'content-type' => ['application/json'] ]
		);
	}

	function saverecord($slug) {
		$request = new ClientRequest();
		$data = $request->JSON->value();

		$app_model = Model::getInstance('AppModel');
		$app = $app_model->get(null, ['app_slug' => $slug]);

		$ar = new ActiveRecord($app['app_table'], $app['app_table_pk']);
		$ar->populate($data, false);

		$result = $ar->save();
		
		return new Response(json_encode(array('success' => $result)), 200, ['content-type' => ['application/json']]);
	}

	function settings($slug) {
		$request = new ClientRequest();
		$data = $request->JSON->value();

		$app_model = Model::getInstance('AppModel');
		$app_model->updateSettings($slug, $data);


		return new Response(json_encode(array('success' => true)), 200, ['content-type' => ['application/json']]);
	}

	function tables($table = '') {
		$db = \NS\Database\Database::getInstance();
		$tables = $db->getTables();

		if ($table) {
			if (in_array($table, $tables)) {
				$tables = [$table];
			} else {
				$tables = [];
			}
		}

		return new Response(json_encode($tables), 200, ['content-type' => ['application/json']]);
	}

	function updaterecord($slug) {
		$request = new ClientRequest();
		$data = $request->JSON->value();

		$app_model = Model::getInstance('AppModel');
		$app = $app_model->get(null, ['app_slug' => $slug]);

		$pk = $data[$app['app_table_pk']];
		unset($data[$app['app_table_pk']]);

		$ar = new ActiveRecord($app['app_table'], $app['app_table_pk']);
		$ar->findByPK($pk);
		$ar->populate($data, false);

		$result = $ar->save();
		
		return new Response(json_encode(array('success' => $result)), 200, ['content-type' => ['application/json']]);
	}

	private function _getAttribute($field, $selected_attribute) {
		foreach ($field['app_field_attributes'] as $attribute) {
			if ($attribute['app_field_attribute_name'] == $selected_attribute) {
				$value =  $attribute['app_field_attribute_value'];

				if ((substr($value, 0, 1) == '{' && substr($value, -1) == '}') || (substr($value, 0, 1) == '[' && substr($value, -1) == ']')) {
					$value = json_decode($value, true);
				}

				return $value;
			}

			
		}

		return false;
	}
	*/
}
?>