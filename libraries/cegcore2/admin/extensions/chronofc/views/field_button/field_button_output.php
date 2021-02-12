<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<?php
	if($view['params']['type'] == 'repeater_add'){
		$view['params']['type'] = 'button';
		$view['class'] .= ' multiply';
	}else if($view['params']['type'] == 'repeater_remove'){
		$view['params']['type'] = 'button';
		$view['class'] .= ' remove';
	}else if($view['params']['type'] == 'partitions_forward'){
		$view['params']['type'] = 'button';
		$view['class'] .= ' forward';
	}else if($view['params']['type'] == 'partitions_backward'){
		$view['params']['type'] = 'button';
		$view['class'] .= ' backward';
	}else if($view['params']['type'] == 'partitions_finish'){
		$view['params']['type'] = 'button';
		$view['class'] .= ' finish';
	}
	
	$field_class = $this->Field->setup('button', $view);
	
	echo $this->Html->input('button')->field($field_class);