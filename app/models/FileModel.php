<?php
use NS\Core\Model;

class FileModel extends NS\Core\Model {
	function __construct() {
		parent::__construct('files', 'file_id');
	}

	function saveData($file) {
		$is_edit = $this->find(null, array('file_id' => $file['file_id']));

		$this->populate(array(
			'filename' => $file['filename'],
			'file_path' => $file['file_path'],
			'file_url' => $file['file_url']
		), false);

		if($file_id = $this->save()) {
			if ($is_edit) {
				$file_id = $this->file_id;
			}

			return $file_id;
		}

		return false;
	}
}
?>