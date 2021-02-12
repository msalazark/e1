<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<div class="ui segment tab views-tab active" data-tab="view-<?php echo $n; ?>">
	
	<div class="ui top attached tabular menu small G2-tabs">
		<a class="item active" data-tab="view-<?php echo $n; ?>-general"><?php el('General'); ?></a>
		<a class="item" data-tab="view-<?php echo $n; ?>-permissions"><?php el('Permissions'); ?></a>
	</div>
	
	<div class="ui bottom attached tab segment active" data-tab="view-<?php echo $n; ?>-general">
		<input type="hidden" value="field_honeypot" name="Connection[views][<?php echo $n; ?>][type]">
		
		<div class="two fields advanced_conf">
			<div class="field">
				<label><?php el('Name'); ?></label>
				<input type="text" value="" name="Connection[views][<?php echo $n; ?>][name]">
			</div>
			<div class="field">
				<label><?php el('Category'); ?></label>
				<input type="text" value="" name="Connection[views][<?php echo $n; ?>][category]">
			</div>
		</div>
		
		<div class="field">
			<label><?php el('Label'); ?></label>
			<input type="text" value="Optional email address" name="Connection[views][<?php echo $n; ?>][label]">
			<small><?php el('Most users will not see this label, the label will be visible only to users without CSS'); ?></small>
		</div>

		<div class="field">
			<label><?php el('Name'); ?></label>
			<input type="text" value="email_address_<?php echo $n.'_'.rand(1000,9999); ?>" name="Connection[views][<?php echo $n; ?>][params][name]">
			<small><?php el('No spaces or special characters should be used here, copy this to your check honeypot action.'); ?></small>
		</div>
		
		<div class="field required">
			<label><?php el('Error message'); ?></label>
			<input type="text" value="You didn't pass the honeypot test." name="Connection[views][<?php echo $n; ?>][failed_error]">
			<small><?php el('Error message to display when the test fails'); ?></small>
		</div>
		
	</div>
	
	<div class="ui bottom attached tab segment" data-tab="view-<?php echo $n; ?>-permissions">
		<?php $this->view('views.config_permissions', ['type' => 'views', 'n' => $n]); ?>
	</div>
	
	<button type="button" class="ui button compact red tiny close_config forms_conf"><?php el('Close'); ?></button>
</div>