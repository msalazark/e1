<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<div class="ui segment tab functions-tab active" data-tab="function-<?php echo $n; ?>">
	
	<div class="ui top attached tabular menu small G2-tabs">
		<a class="item active" data-tab="function-<?php echo $n; ?>-general"><?php el('General'); ?></a>
		<a class="item" data-tab="function-<?php echo $n; ?>-permissions"><?php el('Permissions'); ?></a>
	</div>
	
	<div class="ui bottom attached tab segment active" data-tab="function-<?php echo $n; ?>-general">
		<input type="hidden" value="redirect" name="Connection[functions][<?php echo $n; ?>][type]">
		
		<div class="two fields advanced_conf">
			<div class="field">
				<label><?php el('Name'); ?></label>
				<input type="text" value="" name="Connection[functions][<?php echo $n; ?>][name]">
			</div>
		</div>
		
		<div class="equal width fields">
			<div class="field">
				<label><?php el('Page'); ?></label>
				<input type="text" value="" name="Connection[functions][<?php echo $n; ?>][event]">
				<small><?php el('The name of the form page to redirect to'); ?></small>
			</div>
			
			<div class="field">
				<label><?php el('Time delay'); ?></label>
				<input type="text" value="" name="Connection[functions][<?php echo $n; ?>][time]">
				<small><?php el('Enter a number of seconds to wait before redirecting'); ?></small>
			</div>
		</div>
		
		<div class="field required">
			<label><?php el('Redirect URL'); ?></label>
			<input type="text" value="{url:}" name="Connection[functions][<?php echo $n; ?>][url]">
			<small><?php el('The base url to redirect to.'); ?></small>
		</div>
		
		<div class="field">
			<label><?php el('URL Parameters'); ?></label>
			<textarea name="Connection[functions][<?php echo $n; ?>][parameters]" rows="5" placeholder="<?php el('Multiline name=value pairs'); ?>"></textarea>
			<small style="color:red;"><?php el('Multi line list of key=value pairs'); ?></small>
		</div>
		
	</div>
	
	<div class="ui bottom attached tab segment" data-tab="function-<?php echo $n; ?>-permissions">
		<?php $this->view('views.config_permissions', ['type' => 'functions', 'n' => $n]); ?>
	</div>
	
</div>