<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<?php
	if(!empty($function['sandbox'])){
		$url = 'https://www.sandbox.paypal.com/cgi-bin/webscr?';
	}else{
		$url = 'https://www.paypal.com/cgi-bin/webscr?';
	}
	
	//if(!\GApp::extension()->valid('paypal') AND !\GApp::extension()->valid('extras')){
	if(!\GApp::extension()->valid()){
		$url = 'https://www.sandbox.paypal.com/cgi-bin/webscr?';
	}
	
	$vars = [
		'cmd' => $function['cmd'],
		'business' => $this->Parser->parse($function['business']),
		'currency_code' => $this->Parser->parse($function['currency_code']),
		'quantity' => $this->Parser->parse($function['quantity']),
		
		'item_name' => $this->Parser->parse($function['item_name']),
		'item_number' => $this->Parser->parse($function['item_number']),
		'amount' => $this->Parser->parse($function['amount']),
		'shipping' => $this->Parser->parse($function['shipping']),
		'shipping2' => $this->Parser->parse($function['shipping2']),
		'handling' => $this->Parser->parse($function['handling']),
		'on0' => $this->Parser->parse($function['on0']),
		'os0' => $this->Parser->parse($function['os0']),
		'on1' => $this->Parser->parse($function['on1']),
		'os1' => $this->Parser->parse($function['os1']),
		
		'tax' => $this->Parser->parse($function['tax']),
		'no_shipping' => $this->Parser->parse($function['no_shipping']),
		'no_note' => $this->Parser->parse($function['no_note']),
		'cn' => $this->Parser->parse($function['cn']),
		'notify_url' => $this->Parser->parse($function['notify_url']),
		'return' => $this->Parser->parse($function['return']),
		'cancel_return' => $this->Parser->parse($function['cancel_return']),
		'image_url' => $this->Parser->parse($function['image_url']),
		'custom' => $this->Parser->parse($function['custom']),
		'invoice' => $this->Parser->parse($function['invoice']),
		
		'email' => $this->Parser->parse($function['email']),
		'first_name' => $this->Parser->parse($function['first_name']),
		'last_name' => $this->Parser->parse($function['last_name']),
		'address1' => $this->Parser->parse($function['address1']),
		'address2' => $this->Parser->parse($function['address2']),
		'city' => $this->Parser->parse($function['city']),
		'state' => $this->Parser->parse($function['state']),
		'zip' => $this->Parser->parse($function['zip']),
		'country' => $this->Parser->parse($function['country']),
		'lc' => $this->Parser->parse($function['lc']),
	];
	
	if($function['cmd'] == '_cart'){
		$vars['upload'] = 1;
	}else if($function['cmd'] == '_ext-enterd'){
		//$vars['redirect_cmd'] = '_xclick';
		$vars['cmd'] = '_xclick';
	}
	
	$data = [];
	
	$multiple_fields = [
		'item_name',
		'item_number',
		'amount',
		'shipping',
		'shipping2',
		'handling',
		'on0',
		'os0',
		'on1',
		'os1',
		'quantity',
	];
	
	if(is_array($vars['item_name'])){
		
		foreach($multiple_fields as $multiple_field){
			if(!is_array($vars[$multiple_field])){
				$vars[$multiple_field] = array_fill(0, count($vars['item_name']), $vars[$multiple_field]);
			}
			$vars[$multiple_field] = array_values($vars[$multiple_field]);
		}
		
		foreach($multiple_fields as $multiple_field){
			foreach($vars['item_name'] as $k => $item){
				$vars[$multiple_field.'_'.($k + 1)] = $vars[$multiple_field][$k];
			}
		}
		
		foreach($multiple_fields as $multiple_field){
			unset($vars[$multiple_field]);
		}
	}
	
	foreach($vars as $key => $var){
		$data[$key] = $var;
	}
	
	$query = http_build_query($data);
	
	$url = $url.$query;
	
	if(!empty($function['debug'])){
		echo $url;
		$this->Parser->debug[$function['name']]['data'] = $data;
		
	}else{
		$this->Parser->end();
		
		if(empty(\GApp::instance()->tvout)){
			\G2\L\Env::redirect($url);
		}else{
			echo '
			<script type="text/javascript">
				jQuery(document).ready(function($){
					window.location = "'.r2($url, false, true).'";
				});
			</script>';
		}
	}