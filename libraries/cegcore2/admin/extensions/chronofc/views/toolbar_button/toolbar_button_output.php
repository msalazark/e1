<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<?php
	$params = [];
	
	if(strpos($view['parameters'], 'http') === 0 OR strpos($view['parameters'], '{url:') === 0){
		$url = $this->Parser->parse($view['parameters']);
	}else{
		$url = $this->Parser->url('_self');
		
		$view['parameters'] = $this->Parser->parse($view['parameters']);
		parse_str($view['parameters'], $params);
	}
	
	if(!empty($view['event'])){
		$params['event'] = $this->Parser->parse($view['event']);
	}
	
	$url = \G2\L\Url::build($url, $params);
	
	$tag = 'a';
	$attr = 'href';
	if(!empty($view['submit_data'])){
		$tag = 'button';
		$attr = 'data-url';
	}
	
	if(!empty($view['selections']['required'])){
		$this->Html->attr('data-selections', 1);
		$this->Html->attr('data-message', $view['selections']['message']);
	}
	
	echo $this->Html->attr($attr, r2($url))
	->addClass('toolbar-button')
	->addClass($view['class'])
	->attr('data-form', '#'.\G2\L\Str::slug($view['form_name']))
	->content($this->Parser->parse($view['content']))
	->tag($tag);
	
	$this->Html->reset();
?>
	