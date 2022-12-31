<?php
use NS\String;
?>
<?php echo '<?php' ?>

use NS\Core\Controller;
use NS\Core\Model;
use NS\Net\Http\Cookie;
use NS\String;
use NS\UI\ScriptManager;
use NS\UI\StyleManager;
use Inationsoft\Koma;
use Inationsoft\Koma\User;

define('DEFAULT_PAGE_LIMIT', 15);

class <?php echo $controller ?> extends Controller {
	private $_model, $_redirectBase;

	function _main() {
		$this->_model = Model::getInstance('<?php echo $model ?>');
		$this->_redirectBase = <?php echo ($app == $controller ? '' : String::toLowerCase("'$app/' . ")) ?>String::toLowerCase(get_class($this));

		$js = ScriptManager::getInstance();
		$js->addSource(NS_JQUERY_PATH);
		$js->addSource(NS_JQUERY_UI_PATH);
		$js->addSource($this->Path . '/assets/<?php echo NS\String::toLowerCase($app) ?>.js');
		StyleManager::getInstance()->addSource(NS_JQUERY_UI_STYLE_PATH);

		$this->View->assign('app_base_url', NS_BASE_URL . '/' . $this->_redirectBase);
	}

	function index($page_limit = DEFAULT_PAGE_LIMIT, $page = 1) {
		$condition = null;
		$search_keyword = null;
		$search_column = null;
		$cookie = Cookie::getInstance();

		if($this->Request->Post->value('token')) {
			$search_keyword = $this->Request->Post->value('search_keyword') ? $this->Request->Post->value('search_keyword') : null;
			$search_column = $this->Request->Post->value('search_column');
			$page_limit = $this->Request->Post->value('page_limit');
		} else {
			if($cookie->get('search_token') === md5($cookie->get('search_keyword') . get_class($this) . User::getInstance()->Token)) {
				$search_keyword = $cookie->get('search_keyword');
				$search_column = $cookie->get('search_column');
			}
		}

		if(!empty($search_keyword)) {
			$condition = $this->_model->createFilterCriteria();
			$condition->contains($search_column, $search_keyword);

			$cookie->set('search_keyword', $search_keyword);
			$cookie->set('search_column', $search_column);
			$cookie->set('search_token', md5($search_keyword . get_class($this) . User::getInstance()->Token));
		} else {
			$cookie->delete('search_keyword');
			$cookie->delete('search_column');
			$cookie->delete('search_token');
		}

		$cookie->set('page', $page);
		$cookie->set('page_limit', $page_limit);

		$this->View->File = 'index.php';
		$this->View->assign(array(
			'token' => md5(User::getInstance()->Token . get_class($this)),
			'datas' => $this->_model->getAll(null, $condition, array(($search_column == '' ? $this->_model->PrimaryKey : $search_column) => Model::ORDER_ASC), ($page * $page_limit) - $page_limit, $page_limit),
			'data_count' => $this->_model->count($condition),
			'page' => $page,
			'page_limit' => $page_limit,
			'search_keyword' => $search_keyword
		));
	}

	function insert() {
		$datas = $this->_model->getColumns();

		foreach($datas as $key => $value) {
			$datas[$key] = $this->Session->get($key);
		}

		$this->View->File = 'insert.php';
		$this->form($datas);
	}

	function edit($id) {
		if(!$datas = $this->_model->get($id)) {
			$this->redirect($this->_redirectBase . '/index');
		}

		$this->View->File = 'edit.php';
		$this->View->assign('edit_token', md5(User::getInstance()->Token . $id . get_class($this)));
		$this->form($datas, true);
	}

	function delete($ids) {
		if($this->Request->Post->value('token') != md5(User::getInstance()->Token . get_class($this) )) $this->redirect($this->_redirectBase . '/index');

		$this->_model->deleteSelected($this->Request->Post->value($this->_model->PrimaryKey));
		$this->redirect($this->_redirectBase . '/index');
	}

	function detail($id, $print = false) {
		if(!$datas = $this->_model->get($id)) {
			$this->redirect($this->_redirectBase . '/index');
		}

		if($print) {
			$this->Render = false;
			$this->View->File = 'print.php';
		} else {
			$this->View->File = 'detail.php';
		}

		$this->form($datas, true);
	}

	function save() {
		if($this->Request->Post->value('dispatch') != 'save') $this->redirect($this->_redirectBase . '/index');
		if($this->Request->Post->value('token') != md5(User::getInstance()->Token . get_class($this) )) $this->redirect($this->_redirectBase . '/index');
		if($this->Request->Post->value('edit_token') && $this->Request->Post->value('edit_token') != md5(User::getInstance()->Token . $this->Request->Post->value($this->_model->PrimaryKey) . get_class($this))) $this->redirect($this->_redirectBase . '/index');

		$is_edit = ($this->Request->Post->value('edit_token') ? true : false);
		$cookie = Cookie::getInstance();

		if($this->_model->saveData($this->Request->Post->value(), $is_edit)) {
			$page = (!$is_edit ? ceil($this->_model->count() / ($cookie->get('page_limit') ? $cookie->get('page_limit') : DEFAULT_PAGE_LIMIT)) : (Cookie::getInstance()->get('page') ? Cookie::getInstance()->get('page') : 1));
			$page_limit =  ($cookie->get('page_limit') ? $cookie->get('page_limit') : DEFAULT_PAGE_LIMIT);
			$this->redirect($this->_redirectBase . '/index/' . $page_limit . '/' . $page, 'Success saving data...');
		}
		else $this->redirect($this->_redirectBase . '/index', 'Failed when saving data...');
	}

	private function form($datas, $is_edit = false) {
		$this->View->assign('token', md5(User::getInstance()->Token . get_class($this)));
		$this->View->assign('datas', $datas);
	}
}
<?php echo '?>' ?>