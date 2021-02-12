<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<?php
	$items = $this->Parser->parse($view['data_provider']);
	if(empty($items)){
		$items = [];
	}
	$form_id = \G2\L\Str::slug($view['name']);
?>
<?php if(!isset($view['form']) OR !empty($view['form'])): ?>
<form action="<?php echo r2($this->Parser->url('_self')); ?>" method="post" name="<?php echo $form_id; ?>" id="<?php echo $form_id; ?>" data-id="<?php echo $form_id; ?>" class="ui form">
<?php endif; ?>
<?php
	echo $this->Parser->parse($view['header'], true);
	foreach($items as $key => $item){
		$this->set($view['name'].'.row', $item);
		$this->set($view['name'].'.key', $key);
		echo $this->Parser->parse($view['content'], true);
	}
	echo $this->Parser->parse($view['footer'], true);
?>
<?php if(!isset($view['form']) OR !empty($view['form'])): ?>
</form>
<?php endif; ?>