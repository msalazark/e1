<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<?php
	$this->Html->tag = 'div';
	$this->Html->label($view['label']);
	$this->Html->attrs(['id' => 'widget_'.$view['params']['id'], 'class' => 'm-signature-pad']);
	$this->Html->content(
		'<div class="m-signature-pad--body">
			<canvas width="'.$view['width'].'" height="'.$view['height'].'" data-signature="1" style="'.$view['style'].'"></canvas>
		</div>
		<div class="m-signature-pad--footer">
			<button type="button" class="ui button compact tiny" data-action="clear">'.$view['clear']['content'].'</button>
		</div>
		<input type="hidden" name="'.$view['params']['name'].'" id="'.$view['params']['id'].'" value="" />'
	);
	
	echo $this->Html->field('field');