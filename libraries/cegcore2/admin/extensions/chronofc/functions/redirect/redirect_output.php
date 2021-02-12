<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<?php
	if(empty($function['url']) AND empty($function['parameters']) AND empty($function['event'])){
		return;
	}
	
	if(empty($function['url'])){
		$url = $this->Parser->_url();
	}else{
		$url = $this->Parser->parse($function['url']);
	}
	
	$params = [];
	if(strpos($function['parameters'], "\n") !== false OR strpos($function['parameters'], "&") === false){
		$params = array_replace($params, $this->Parser->rparams($function['parameters']));
	}else{
		$function['parameters'] = $this->Parser->parse($function['parameters']);
		parse_str($function['parameters'], $params);
	}
	
	if(!empty($function['event'])){
		$params['event'] = $this->Parser->parse($function['event']);
	}
	
	$url = \G2\L\Url::build($url, $params);
	
	$this->Parser->end();
	
	$time = 0;
	if(!empty($function['time'])){
		$time = $this->Parser->parse($function['time']);
	}
	
	if(empty(\GApp::instance()->tvout)){
		if(!empty($time)){
			\GApp::document()->addHeaderTag('<meta http-equiv="refresh" content="'.$time.';url='.r2($url).'" />');
		}else{
			\GApp::redirect(r2($url));
		}
	}else{
		echo '
		<script type="text/javascript">
			jQuery(document).ready(function($){
				setTimeout(function() {
					window.location = "'.r2($url, false, true).'";
				}, '.($time * 1000).');
			});
		</script>';
	}