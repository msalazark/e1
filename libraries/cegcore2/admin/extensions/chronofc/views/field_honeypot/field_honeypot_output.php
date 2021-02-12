<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<?php
	$view['container']['class'] = 'field hidden';
	$field_class = $this->Field->setup('text', $view);
	
	echo $this->Html->input('text')->field($field_class);
	
	$this->Parser->add_security($view, [
		'type' => 'check_honeypot',
		'field_name' => $view['params']['name'],
		'failed_error' => !empty($view['failed_error']) ? $view['failed_error'] : '',
	]);