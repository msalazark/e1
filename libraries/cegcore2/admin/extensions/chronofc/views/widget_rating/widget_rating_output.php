<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<?php
	$this->Html->tag = 'div';
	
	if(!empty($view['label'])){
		$this->Html->label($view['label']);
	}
	
	$max = !empty($view['max']) ? $this->Parser->parse($view['max']) : 5;
	
	$value = !empty($view['value']) ? $this->Parser->parse($view['value']) : '';
	
	$this->Html->attrs(['id' => 'rating_'.$view['params']['id'], 'class' => 'rating-widget']);
	$this->Html->content(
		'<div class="ui '.$view['size'].' '.$view['icon'].' rating" data-rating="'.$value.'" data-max-rating="'.$max.'" data-id="#'.$view['params']['id'].'" data-interactive="'.$view['interactive'].'" data-clearable="'.$view['clearable'].'"></div>
		<input type="hidden" name="'.$view['params']['name'].'" id="'.$view['params']['id'].'" value="'.$value.'" />'
	);
	
	echo $this->Html->field('field');