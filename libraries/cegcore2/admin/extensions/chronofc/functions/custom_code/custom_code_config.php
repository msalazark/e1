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
		<input type="hidden" value="custom_code" name="Connection[functions][<?php echo $n; ?>][type]">
		
		<div class="two fields">
			<div class="field">
				<label><?php el('Name'); ?></label>
				<input type="text" value="" name="Connection[functions][<?php echo $n; ?>][name]">
			</div>
		</div>
		
		<div class="field">
			<div class="ui checkbox toggle">
				<input type="hidden" name="Connection[functions][<?php echo $n; ?>][return]" data-ghost="1" value="">
				<input type="checkbox" class="hidden" name="Connection[functions][<?php echo $n; ?>][return]" value="1">
				<label><?php el('Var only result?'); ?></label>
				<small><?php el('Should the result parsed content be displayed or only returned inside a var accessible using {var:NAME} ?'); ?></small>
			</div>
		</div>
		
		<div class="field required">
			<label><?php el('Content'); ?>
			<i class="icon green write circular" onclick="jQuery.G2.tinymce.init('#custom_editor<?php echo $n; ?>');" data-hint="<?php el('Enable WYSIWYG editor'); ?>"></i>
			<i class="icon red cancel circular" onclick="jQuery.G2.tinymce.remove('#custom_editor<?php echo $n; ?>');" data-hint="<?php el('Disable WYSIWYG editor'); ?>"></i>
			</label>
			<textarea name="Connection[functions][<?php echo $n; ?>][content]" rows="10" data-editor="0" id="custom_editor<?php echo $n; ?>"></textarea>
			<small><?php el('The content to be parsed, may contain chrono syntax commands.'); ?></small>
		</div>
		
	</div>
	
	<div class="ui bottom attached tab segment" data-tab="function-<?php echo $n; ?>-permissions">
		<?php $this->view('views.config_permissions', ['type' => 'functions', 'n' => $n]); ?>
	</div>
	
</div>