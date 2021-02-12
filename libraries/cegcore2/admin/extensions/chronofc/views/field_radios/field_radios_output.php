<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<?php
	$field_class = $this->Field->setup('radios', $view);
	
	echo $this->Html->input('radio', (isset($view['style']) ? $view['style'] : 'radio'))->fields([], $field_class);