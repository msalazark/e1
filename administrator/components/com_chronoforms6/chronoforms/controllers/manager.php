<?php
/**
* COMPONENT FILE HEADER
**/
namespace G2\A\E\Chronoforms\C;
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
class Manager extends \G2\L\Controller {
	var $models = array(
		'\G2\A\E\Chronoforms\M\Connection',
		'\G2\A\E\Chronoforms\M\Block',
	);
	var $helpers= array(
		'\G2\H\Html',
		'\G2\A\E\Chronofc\H\Parser',
		'\G2\A\E\Chronofc\H\Field',
		'\G2\H\Paginator',
		'\G2\H\Sorter',
	);
	
	function _initialize(){
		//$this->layout('default');
		$this->fparams = \GApp::extension('chronoforms')->settings();
	}
	
	function index(){
		$conn = $this->get('chronoform', $this->data('chronoform'));
		
		if(empty($conn)){
			return ['error' => rl('Error, form does not exist.')];
		}
		
		$connection = $this->Connection->where('alias', $conn, '= BINARY')->where('published', 1)->order(['id' => 'desc'])->select('first', ['json' => ['events', 'sections', 'views', 'functions', 'models', 'locales', 'rules', 'params']]);
		
		if(empty($connection)){
			return ['error' => rl('Error, form does not exist or is not published.')];
		}
		
		if(empty($connection['Connection']['public']) AND $this->site == 'front'){
			return ['error' => rl('Error, form is not available for frontend users.')];
		}
		
		if(!empty($connection['Connection']['rules']['access'])){
			$rules = array_filter($connection['Connection']['rules']['access']);
			if(!empty($rules) AND \GApp::access($connection['Connection']['rules'], 'access') !== true){
				if(empty($connection['Connection']['events']['403'])){
					return ['error' => rl('You do not have enough permissions to access this resource.')];
				}else{
					$this->set('event', '403');
				}
			}
		}
		
		$this->set('__connection', $connection['Connection']);
		
		//find if blocks are needed
		$view_blocks = \G2\L\Arr::getVal($connection, 'Connection.views.[n].type', []);
		$fn_blocks = \G2\L\Arr::getVal($connection, 'Connection.functions.[n].type', []);
		$blocks_ids = [];
		
		foreach($view_blocks as $k => $view_block){
			if($view_block == 'stored_block'){
				if(!empty($connection['Connection']['views'][$k]['id'])){
					$blocks_ids[] = $connection['Connection']['views'][$k]['id'];
				}else if(!empty($connection['Connection']['views'][$k]['block_id'])){
					$blocks_ids[] = $connection['Connection']['views'][$k]['block_id'];
				}
			}
		}
		
		foreach($fn_blocks as $k => $fn_block){
			if($fn_block == 'stored_block'){
				if(!empty($connection['Connection']['functions'][$k]['id'])){
					$blocks_ids[] = $connection['Connection']['functions'][$k]['id'];
				}else if(!empty($connection['Connection']['functions'][$k]['block_id'])){
					$blocks_ids[] = $connection['Connection']['functions'][$k]['block_id'];
				}
			}
		}
		
		$blocks_ids = array_unique($blocks_ids);
		
		$blocks = [];
		if(!empty($blocks_ids)){
			$blocks = $this->Block
			->where('id', $blocks_ids, 'in')
			->where('OR')
			->where('block_id', $blocks_ids, 'in')
			->select('all', ['json' => ['content']]);
		}
		
		$this->set('__blocks', $blocks);
		
		//get event
		$event = $this->get('event', $this->data('event'));
		//get default event
		if(!empty($connection['Connection']['params']['default_event'])){
			$default_event = $connection['Connection']['params']['default_event'];
		}else if(!empty($connection['Connection']['params']['events_ordered'])){
			foreach($connection['Connection']['events'] as $event_name => $event_data){
				if(empty($event_data['type'])){
					$default_event = $event_name;
					break;
				}
			}
			
			if(empty($default_event)){
				$default_event = array_keys($connection['Connection']['events'])[0];
			}
		}else{
			$default_event = 'load';
		}
		
		$this->set('__default_event', $default_event);
		
		if(empty($event)){
			$event = $default_event;
		}
		
		if(!isset($connection['Connection']['events'][$event])){
			if(!empty($connection['Connection']['params']['404_default'])){
				$event = $default_event;
			}else{
				$event = '404';
			}
		}
		
		$this->set('__event', $event);
		
		if(\GApp::instance()->site == 'front'){
			$good = (\GApp::extension('chronoforms')->valid() OR !isset($connection['Connection']['params']['limited_edition']));
			if(!$good){
				$this->set('__viewslimit', 15);
			}
		}
		
		$this->view = [
			'views' => [
				'site' => 'admin',
				'cont' => 'manager',
				'act' => 'event',
			]
		];
	}
	
	function _finalize(){
		if(empty($this->tvout) AND \GApp::extension('chronoforms')->valid() == false){
			if(\G2\Globals::get('app') != 'wordpress'){
				echo '<a href="http://www.chronoengine.com/" target="_blank" class="chronoforms6_credits">Form by ChronoForms - ChronoEngine.com</a>';
			}else{
				echo '<h3>Form by ChronoForms - ChronoEngine.com</h3>';
			}
		}
	}
}
?>