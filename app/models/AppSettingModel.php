<?php
use NS\Core\Model;

class AppSettingModel extends NS\Core\Model {
	function __construct() {
		parent::__construct('app_settings', 'app_id');
	}
}
?>