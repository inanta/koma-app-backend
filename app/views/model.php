<?php echo '<?php' ?>

<?php if(isset($namespace)): ?>
namespace <?php echo $namespace; ?>;
<?php endif; ?>

use NS\Core\Model;

class <?php echo $model ?> extends Model {
<?php if(!empty($has_many_ar_var)): ?>
	public <?php echo implode(', ', $has_many_ar_var) ?>;
<?php endif; ?>

	function __construct() {
		parent::__construct('<?php echo $table ?>', '<?php echo $pk ?>');
<?php if(!empty($has_many_ar_var)): ?>

<?php foreach($has_many_ar_var as $table => $var): ?>
<?php if(isset($namespace)): ?>
		$this-><?php echo str_replace('$', '', $var) ?> = Model::getInstance('<?php echo $namespace ?>\<?php echo $hasmany_save[$table]['model'] ?>'); 
<?php else: ?>
		$this-><?php echo str_replace('$', '', $var) ?> = Model::getInstance('<?php echo $hasmany_save[$table]['model'] ?>'); 
<?php endif; ?>
<?php endforeach; ?>
<?php endif;?>
<?php if(!empty($has_many_ar_var)): ?>

<?php foreach($has_many_ar_var as $table => $var): ?>
<?php if($pk == $fk[$table]): ?>
		$this->hasMany($this-><?php echo str_replace('$', '', $var) ?>, '<?php echo $fk[$table] ?>');
<?php else: ?>
		$this->hasMany($this-><?php echo str_replace('$', '', $var) ?>, '<?php echo $fk[$table] ?>', '<?php echo $pk ?>');
<?php endif; ?>
<?php endforeach; ?>
<?php endif;?>
	}

	function saveData($data, $is_edit = false) {
		if($is_edit && isset($data[$this->PrimaryKey])) {
			if(!$this->find(null, array($this->PrimaryKey => $data[$this->PrimaryKey]), false)) return false;
		}

		$this->populate($data, false);

		if(($id = $this->save()) === true) {
			$id = $this->{$this->PrimaryKey};
		}

		return $id;
	}

	function deleteSelected($ids) {
		if(empty($ids)) return false;
    
		$criteria = $this->createFilterCriteria();
		$criteria->in($this->PrimaryKey, $ids);

		return $this->deleteAll($criteria);
	}
}
<?php echo '?>' ?>