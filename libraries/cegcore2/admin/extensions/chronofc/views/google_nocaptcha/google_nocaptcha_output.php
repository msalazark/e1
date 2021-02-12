<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<?php
	$lang = $this->Parser->parse($view['lang']);
	
	if(0){
		echo '<button class="g-recaptcha ui button blue" data-sitekey="'.$view['site_key'].'" data-callback="">Submit</button>';
	}else{
		$this->Html->tag = 'div';
		
		if(!empty($view['label'])){
			$this->Html->label($view['label']);
		}
		
		$this->Html->attrs(['id' => $view['name']]);
		$this->Html->content(
			'<div class="g-recaptcha" data-sitekey="'.$view['site_key'].'" data-theme="'.$view['theme'].'"></div>'
		);
		
		echo $this->Html->field('field required');
		
		//echo '<div class="g-recaptcha" data-sitekey="'.$view['site_key'].'" data-theme="'.$view['theme'].'"></div>';
		
		$this->Parser->add_security($view, [
			'type' => $view['type'],
			'secret_key' => !empty($view['secret_key']) ? $view['secret_key'] : '',
			'failed_error' => !empty($view['failed_error']) ? $view['failed_error'] : '',
		]);
	}
	
	if(empty(\GApp::instance()->tvout)){
		\GApp::document()->addJsFile('https://www.google.com/recaptcha/api.js?hl='.$lang);
	}else{
		echo '<script src="https://www.google.com/recaptcha/api.js?hl='.$lang.'"></script>';
	}