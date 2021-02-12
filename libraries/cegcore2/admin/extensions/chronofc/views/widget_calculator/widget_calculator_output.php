<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<?php
	echo '<div class="'.$view['class'].'" id="'.$view['id'].'">';
	
	$this->Html->attr('name', $view['params']['name']);
	$this->Html->attr('id', $view['params']['id']);
	$this->Html->attr('value', $view['value']);
	$this->Html->attr('data-display', $view['id'].'_value');
	
	echo $this->Html->input('hidden')->build();
	
	echo '<div class="value">'.(!empty($view['before_value']) ? ' '.$view['before_value'] : '').'<span id="'.$view['id'].'_value">'.$view['value'].'</span></div>';
	echo '<div class="label">'.$view['label'].'</div>';
	
	echo '</div>';
	
	$this->Html->reset();