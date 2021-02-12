<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<?php
	$field_class = $this->Field->setup('password', $view);
	
	echo $this->Html->input('password')->field($field_class);