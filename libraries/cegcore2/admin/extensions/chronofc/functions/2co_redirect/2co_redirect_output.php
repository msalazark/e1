<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<?php
	if(!empty($function['sandbox'])){
		$url = 'https://sandbox.2checkout.com/checkout/purchase?';
	}else{
		$url = 'https://www.2checkout.com/checkout/purchase?';
	}
	
	$vars = [
		'mode' => $function['mode'],
		'sid' => $this->Parser->parse($function['sid']),
		
		'card_holder_name' => $this->Parser->parse($function['card_holder_name']),
		'email' => $this->Parser->parse($function['email']),
		'street_address' => $this->Parser->parse($function['street_address']),
		'street_address2' => $this->Parser->parse($function['street_address2']),
		'city' => $this->Parser->parse($function['city']),
		'state' => $this->Parser->parse($function['state']),
		'zip' => $this->Parser->parse($function['zip']),
		'country' => $this->Parser->parse($function['country']),
		'phone' => $this->Parser->parse($function['phone']),
		'phone_extension' => $this->Parser->parse($function['phone_extension']),
		
		
		'ship_name' => $this->Parser->parse($function['ship_name']),
		'ship_street_address' => $this->Parser->parse($function['ship_street_address']),
		'ship_street_address2' => $this->Parser->parse($function['ship_street_address2']),
		'ship_city' => $this->Parser->parse($function['ship_city']),
		'ship_state' => $this->Parser->parse($function['ship_state']),
		'ship_zip' => $this->Parser->parse($function['ship_zip']),
		'ship_country' => $this->Parser->parse($function['ship_country']),
		
		'demo' => $this->Parser->parse($function['demo']),
		'paypal_direct' => $this->Parser->parse($function['paypal_direct']) ? 'Y' : null,
		'currency_code' => $this->Parser->parse($function['currency_code']),
		'lang' => $this->Parser->parse($function['lang']),
		'merchant_order_id' => $this->Parser->parse($function['merchant_order_id']),
		'purchase_step' => $this->Parser->parse($function['purchase_step']),
		'x_receipt_link_url' => $this->Parser->parse($function['x_receipt_link_url']),
		'coupon' => $this->Parser->parse($function['coupon']),
	];
	
	//if(!\GApp::extension()->valid('2checkout') AND empty($function['market'])){
	if(!\GApp::extension()->valid() AND empty($function['market'])){
		$vars['demo'] = 'Y';
	}
	
	$products = $this->Parser->parse($function['products_provider']);
	$this->Parser->debug[$function['name']]['products'] = $products;
	
	if(empty($products) OR !is_array($products)){
		$this->Parser->messages['error'][$function['name']] = rl('Error getting the products list.');
		$this->set($function['name'], false);
	}
	$products = array_values($products);
	/*
	if(!empty($function['hash'])){
		$hashed_items = [];
		foreach($products as $k => $product){
			if(isset($product['product_id'])){
				$hashed_items[$k]['product_id'] = $product['product_id'];
			}
			if(isset($product['price'])){
				$hashed_items[$k]['price'] = $product['price'];
			}
			if(isset($product['quantity'])){
				$hashed_items[$k]['quantity'] = $product['quantity'];
			}
		}
		$vars['custom_hash'] = md5($function['hash'].json_encode($hashed_items));
	}
	*/
	foreach($products as $k => $product){
		foreach($product as $attr => $val){
			$vars['li_'.$k.'_'.$attr] = $val;
		}
	}
	
	if(!empty(trim($function['parameters']))){
		$vars = array_replace($vars, $this->Parser->rparams($function['parameters']));
	}
	
	foreach($vars as $key => $var){
		if(!empty($var) OR $var == '0'){
			$data[$key] = $var;
		}
	}
	
	$query = http_build_query($data);
	
	$url = $url.$query;
	//pr($data);pr($url);die();
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