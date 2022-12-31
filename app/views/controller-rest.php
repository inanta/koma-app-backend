<?php
use NS\String;
?>
<?php echo '<?php' ?>

<?php if(isset($namespace)): ?>
namespace <?php echo $namespace; ?>;
<?php endif; ?>

use NS\Core\Config;
use NS\Core\RESTController;
use NS\Core\Model;
use NS\Exception\HttpRequestException;
use NS\Utility\JQuery\DataTables;
use Koma\User;

<?php if(isset($namespace)): ?>
define('_NAMESPACE_', '<?php echo $namespace; ?>');
<?php endif; ?>
define('DEFAULT_PAGE_LIMIT', 15);

class <?php echo $controller ?> extends RESTController {
	private $_model, $_uh, $_cf, $_redirectBase;

	function _main() {
<?php if(isset($namespace)): ?>
		$this->_model = Model::getInstance('<?php echo $namespace; ?>\<?php echo $model ?>');
<?php else: ?>
		$this->_model = Model::getInstance('<?php echo $model ?>');
<?php endif; ?>
		$this->_uh = User::getInstance();
		$this->_cf = Config::getInstance();
		$this->_redirectBase = '<?php echo $folder; ?>/<?php echo ($app == $controller ? '' : String::toLowerCase("'$app/' . ")) ?>service/<?php echo  String::toLowerCase($controller) ?>';
	}

	function index() {
		$this->_model->PrimaryKey = null;

		$data_tables = new DataTables($this->_model);
		
		$this->Response = $data_tables->getResult();
	}

	function delete() {
		if($this->Request->Post->value('token') != md5($this->_uh->Token . get_class($this))) $this->error(400);

		$this->_model->deleteSelected($this->Request->Post->value($this->_model->PrimaryKey));

		$this->Response = array(
			'result' => true
		);
	}

	function detail($id) {
		if(!$datas = $this->_model->getByPK($id)) {
			$this->error(400);
		}

		$this->Response = array(
			'result' => $datas
		);
	}

	function save() {
		if($this->Request->Post->value('token') != md5($this->_uh->Token . get_class($this))) $this->error(400);
		if($this->Request->Post->value('edit_token') && $this->Request->Post->value('edit_token') != md5($this->_uh->Token . $this->Request->Post->value($this->_model->PrimaryKey) . get_class($this))) $this->error(400);

		$is_edit = ($this->Request->Post->value('edit_token') ? true : false);

		if($this->_model->saveData($this->Request->Post->value(), $is_edit)) {
			$this->Response = array(
				'result' => true
			);
		} else {
			$this->error(400);
		}
	}

	private function error($code, $message) {
		throw new HttpRequestException($code, $message);
	}
}
<?php echo '?>' ?>