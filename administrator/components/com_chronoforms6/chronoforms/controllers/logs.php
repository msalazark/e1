<?php
/**
* COMPONENT FILE HEADER
**/
namespace G2\A\E\Chronoforms\C;
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
class Logs extends \G2\A\E\Chronoforms\App {
	use \G2\A\C\T\DataOps;
	
	var $models = array(
		'\G2\A\E\Chronoforms\M\Connection',
		'\G2\A\E\Chronoforms\M\Datalog',
		'\G2\A\M\Group'
	);
	var $helpers= array(
		'\G2\A\E\Chronofc\H\Field',
		'\G2\A\E\Chronofc\H\Parser',
	);
	
	function index(){
		if(!empty($this->data['form_id'])){
			$connection = $this->Connection->where('id', $this->data('form_id'))->select('first', ['json' => ['params', 'events', 'sections', 'views', 'functions', 'models', 'locales', 'rules']]);
			$this->set('connection', $connection);
			
			$this->Datalog->where('form_id', $this->data['form_id']);
			
			$this->Search($this->Datalog, ['user_id', 'ipaddress', 'created', 'data']);
			
			$this->Paginate($this->Datalog);
			$this->Order($this->Datalog, ['record_id' => 'Datalog.aid', 'record_created' => 'Datalog.created', 'record_approved' => 'Datalog.approved']);
			
			$this->Datalog->belongsTo('\G2\A\M\User', 'User', 'user_id');
			
			$records = $this->Datalog->select('all');
			$this->set('records', $records);
		}else{
			
		}
	}
	
	function view(){
		$this->Datalog->belongsTo('\G2\A\M\User', 'User', 'user_id');
		
		$record = $this->Datalog->where('aid', $this->data['aid'])->select('first');
		$this->set('record', $record);
	}
	
	function delete(){
		return $this->deleteRecord($this->Datalog);
	}
	
	function csv(){
		$connection = $this->Connection->where('id', $this->data('form_id'))->select('first', ['json' => ['params', 'events', 'sections', 'views', 'functions', 'models', 'locales', 'rules']]);
		
		if(!empty($this->data('gcb'))){
			$this->Datalog->where('aid', $this->data('gcb'), 'in');
		}
		
		$records = $this->Datalog->where('form_id', $this->data['form_id'])->select('all');
		
		$titles = [];
		$rows = [];
		if(!empty($records)){
			foreach($records as $k => $record){
				//$record['Datalog']['data'] = json_decode($record['Datalog']['data'], true);
				foreach($record['Datalog'] as $dk => $dv){
					if($dk != 'data'){
						$rows[$k][$dk] = $dv;
						if(!in_array($dk, $titles)){
							$titles[] = $dk;
						}
					}else{
						$dv = json_decode($dv, true);
						foreach($dv as $dvk => $dvv){
							if(!in_array($dvk, $titles)){
								$titles[] = $dvk;
							}
							$rows[$k][$dvk] = is_array($dvv) ? json_encode($dvv, JSON_UNESCAPED_UNICODE) : $dvv;
						}
					}
				}
				//$titles = array_unique(array_merge($titles, array_keys($data)));
			}
		}
		
		foreach($rows as $k => $row){
			foreach($titles as $title){
				if(!isset($row[$title])){
					$rows[$k][$title] = '';
				}
			}
		}
		
		$this->set('data', $rows);
		$functions_path = \G2\Globals::ext_path('chronofc', 'admin').'functions'.DS.'csv'.DS.'csv'.'_output.php';
		$view = new \G2\L\View($this);
		$result = $view->view($functions_path, ['function' => ['action' => 'download', 'delimiter' => ',', 'data_provider' => '{var:data}', 'file_name' => $connection['Connection']['alias'].'.csv']], true);
	}
}
?>