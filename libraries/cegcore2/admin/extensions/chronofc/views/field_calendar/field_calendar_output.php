<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<?php
	$field_class = $this->Field->setup('calendar', $view);
	
	echo $this->Html->input('calendar')->field($field_class);