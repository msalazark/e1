<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<?php
	if(!empty($function['values'])){
		foreach($function['values'] as $value){
			$value['value'] = $this->Parser->parse($value['value']);
			
			if($value['type'] == 'var'){
				$this->set($value['name'], $value['value']);
			}else if($value['type'] == 'data'){
				$this->data($value['name'], $value['value'], true);
			}else if($value['type'] == 'session'){
				\GApp::session()->set($value['name'], $value['value']);
			}
		}
	}