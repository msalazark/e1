<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<?php
	$item = is_array($function['data_provider']) ? $function['data_provider'] : $this->Parser->parse($function['data_provider']);
	$mode = $this->Parser->parse($function['mode_provider']);
	$id = $this->Parser->parse($function['id_data_provider']);
	$quantity = $this->Parser->parse($function['quantity_data_provider']);
	
	$session = \GApp::session();
	
	$cart = $session->get($function['name'], []);
	
	if(!empty($mode)){
		$method = $mode;
	}
	
	if($method == 'remove'){
		if(isset($cart[$id])){
			unset($cart[$id]);
		}
	}else{
		if(!empty($item) AND !empty($id) AND !empty($quantity)){
			
			if(!isset($cart[$id]) OR $method == 'replace'){
				$cart[$id] = $item;
				$cart[$id]['quantity'] = $quantity;
			}else{
				$cart[$id]['quantity'] = $cart[$id]['quantity'] + $quantity;
			}
		}
	}
	
	$session->set($function['name'], $cart);