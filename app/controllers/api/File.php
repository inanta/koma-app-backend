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

use NS\Core\Controller;
use NS\Core\Model;
use NS\Net\Http\Response;
use NS\Net\Http\ClientRequest;
use NS\Database\Database;
use NS\Database\ActiveRecord;
use NS\Utility\Image;

define('_NAMESPACE_', '\Inationsoft\Koma');

class File extends Controller {
	private $_model;

	function _main() {
		$this->_model = Model::getInstance('FileModel');
	}

	function upload() {
		$response = ['error' => false];

		foreach ($_FILES['files']['error'] as $key => $error) {
			if ($error == UPLOAD_ERR_OK) {
				$tmp_name = $_FILES['files']['tmp_name'][$key];

				$filename_part = explode(".", basename($_FILES['files']['name'][$key]));
				$file_ext = array_pop($filename_part);

				$name = $this->_slugify(implode(".", $filename_part));
				$saved_name = $name . '_' . md5(time()) . '.' . $file_ext;
				$original_name = $name . '.' . $file_ext;
				
				if(move_uploaded_file($tmp_name, 'public/application/app/file/uploads/' . $saved_name)) {
					if (in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
						$image = new Image('public/application/app/file/uploads/' . $saved_name);
						$image->resizeToWidth(150);
						$image->save('public/application/app/file/uploads/icons/' . $saved_name);
					}

					$this->_model->saveData([
						'filename' => $original_name,
						'file_path' => 'public/application/app/file/uploads/' . $saved_name,
						'file_icon' => 'public/application/app/file/uploads/icons/' . $saved_name,
						'file_url' => NS_BASE_URL  . 'public/application/app/file/uploads/' . $saved_name,
						'file_icon_url' => NS_BASE_URL  . 'public/application/app/file/uploads/icons/' . $saved_name,
					]);

					$response['files'][] = [
						'filename' => $original_name,
						'file_path' => 'public/application/app/file/uploads/' . $saved_name
					];
				} else {
					$response['error'] = true;	
				}
			} else {
				$response['error'] = true;
			}
		}

		return new Response(json_encode($response), 200, ['content-type' => ['application/json']]);
	}

	function list($page_limit = 15, $page = 1) {
		$response = ['error' => false];

		$offset = ($page * $page_limit) - $page_limit;
		$files = $this->_model->getAll(['filename', 'file_path'], null , ['file_id' => Model::ORDER_DESC], $offset, $page_limit);

		$response['files'] = $files;

		return new Response(json_encode($response), 200, ['content-type' => ['application/json']]);
	}

	private function _slugify($text, string $divider = '-') {
	  // replace non letter or digits by divider
	  $text = preg_replace('~[^\pL\d]+~u', $divider, $text);

	  // transliterate
	  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

	  // remove unwanted characters
	  $text = preg_replace('~[^-\w]+~', '', $text);

	  // trim
	  $text = trim($text, $divider);

	  // remove duplicate divider
	  $text = preg_replace('~-+~', $divider, $text);

	  // lowercase
	  $text = strtolower($text);

	  if (empty($text)) {
	    return 'n-a';
	  }

	  return $text;
	}
}
?>