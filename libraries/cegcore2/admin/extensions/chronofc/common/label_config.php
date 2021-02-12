<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<?php
	if(!empty($this->data['Connection'][$type][$n]['label'])){
		$this->data['Connection'][$type][$n]['designer_label'] = $this->data['Connection'][$type][$n]['label'];
	}
?>
<div class="ui message" style="margin-top:10px;">
	<div class="field">
		<label><?php el('Designer label'); ?></label>
		<input type="text" value="" name="Connection[<?php echo $type; ?>][<?php echo $n; ?>][designer_label]">
		<small><?php el('A label text for this item in the form designer'); ?></small>
	</div>
	<div class="field">
		<label><?php el('Name'); ?></label>
		<input type="text" name="Connection[<?php echo $type; ?>][<?php echo $n; ?>][name]" class="dragged-name">
		<small><?php el('The name should be unique'); ?></small>
	</div>
</div>