<?php
use NS\String;
?>
<?php echo '<?php' ?>

<?php if(isset($namespace)): ?>
namespace <?php echo $namespace; ?>;
<?php endif; ?>

use NS\Core\Config;
use NS\Core\Controller;
use NS\Core\Model;
use NS\Net\Http\Cookie;
use NS\String;
use NS\UI\ScriptManager;
use NS\UI\StyleManager;
use Koma\User;

<?php if(isset($namespace)): ?>
define('_NAMESPACE_', '<?php echo $namespace; ?>');
<?php endif; ?>
define('DEFAULT_PAGE_LIMIT', 15);

class <?php echo $controller ?> extends Controller {
	private $_model, $_uh, $_cf, $_redirectBase;

	function _main() {
<?php if(isset($namespace)): ?>
		$this->_model = Model::getInstance('<?php echo $namespace; ?>\<?php echo $model ?>');
<?php else: ?>
		$this->_model = Model::getInstance('<?php echo $model ?>');
<?php endif; ?>
		$this->_uh = User::getInstance();
		$this->_cf = Config::getInstance();
		$this->_redirectBase = '<?php echo $folder; ?>/<?php echo ($app == $controller ? '' : String::toLowerCase("'$app/' . ")) ?><?php echo  String::toLowerCase($controller) ?>';

		if(!$this->_uh->isUser()) $this->redirect('system/auth');

		$js = ScriptManager::getInstance();
		$js->addSource(NS_JQUERY_PATH);
		$js->addSource(NS_JQUERY_UI_PATH);
		$js->addSource($this->Path . '/assets/js/<?php echo String::toLowerCase($app) ?>.js');
		StyleManager::getInstance()->addSource(NS_JQUERY_UI_STYLE_PATH);

		$this->View->assign(array(
			'app_base_url' => NS_BASE_URL . '/' . $this->_redirectBase,
			'service_base_url' => NS_BASE_URL . '/' . '<?php echo $folder; ?>/service/<?php echo ($app == $controller ? '' : String::toLowerCase("'$app/' . ")) ?><?php echo  String::toLowerCase($controller) ?>'
		));
	}

	function index() {
		$this->View->File = '<?php echo String::toLowerCase($app); ?>/index.php';
		$this->View->assign(array(
			'token' => md5(User::getInstance()->Token . get_class($this)),
		));
	}

	function insert() {
		$datas = $this->_model->getColumns();

		foreach($datas as $key => $value) {
			$datas[$key] = $this->Session->get($key);
		}

		$this->View->File = '<?php echo String::toLowerCase($app); ?>/insert.php';
		$this->form($datas);
	}

	function edit($id) {
		if(!$datas = $this->_model->getByPK($id)) {
			$this->redirect($this->_redirectBase . '/index');
		}

		$this->View->File = '<?php echo String::toLowerCase($app); ?>/edit.php';
		$this->View->assign('edit_token', md5($this->_uh->Token . $id . get_class($this)));
		$this->form($datas, true);
	}

	function detail($id, $print = false) {
		if(!$datas = $this->_model->getByPK($id)) {
			$this->redirect($this->_redirectBase . '/index');
		}

		if($print) {
			$this->_cf->getInstance()->Koma->ThemeMainTemplate = 'theme-print.php';
			$this->View->File = '<?php echo String::toLowerCase($app); ?>/print.php';
		} else {
			$this->View->File = '<?php echo String::toLowerCase($app); ?>/detail.php';
		}

		$this->form($datas, true);
	}
	
<?php foreach($hasmany as $table => $data): ?>
	function <?php echo String::toLowerCase(str_replace('Model', '', $data['model'])); ?>() {
		$this->View->File = '<?php echo String::toLowerCase($app); ?>/hasmany_<?php echo String::toLowerCase(str_replace('Model', '', $data['model'])); ?>.php';
	}
<?php endforeach; ?>

	private function form($datas, $is_edit = false) {
		$this->View->assign(array(
			'token' => md5($this->_uh->Token . get_class($this)),
			'datas' => $datas
		));
	}
}
<?php echo '?>' ?>