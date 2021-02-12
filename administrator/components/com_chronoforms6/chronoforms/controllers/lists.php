<?php
/**
* COMPONENT FILE HEADER
**/
namespace G2\A\E\Chronoforms\C;
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
class Blocks extends \G2\A\E\Chronoforms\App {
	use \G2\A\C\T\DataOps;
	
	var $models = array(
		'\G2\A\E\Chronoforms\M\Block',
		'\G2\A\M\Group'
	);
	var $helpers= array(
		'\G2\A\E\Chronofc\H\Field',
		'\G2\A\E\Chronofc\H\Parser',
	);
	
	function index(){
		/*
		$this->Search($this->Block, ['title', 'desc']);
		
		$this->Paginate($this->Block);
		
		$this->Order($this->Block, ['block_title' => 'Block.title', 'block_id' => 'Block.id', 'block_published' => 'Block.published']);
		
		$blocks = $this->Block->select('all', ['json' => ['content']]);
		$this->set('blocks', $blocks);
		*/
		if(!empty($this->data['form_id'])){
			$connection = $this->Connection->where('id', $this->data('form_id'))->select('first', ['json' => ['params', 'events', 'sections', 'views', 'functions', 'models', 'locales', 'rules']]);
			$this->set('connection', $connection);
			
			$Table = new \G2\L\Model(['name' => 'Table', 'table' => $tablename, 'dbo' => $dbo]);
		}else{
			
		}
	}
	
}
?>