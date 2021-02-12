<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<?php
	$field_class = $this->Field->setup('checkboxes', $view);
	
	echo $this->Html->input('checkbox', (isset($view['style']) ? $view['style'] : ''))->fields([], $field_class);