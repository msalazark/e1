<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<?php
	$hidden = '';
	if(!empty($this->data('Connection.events.'.$name.'.minimized'))){
		$hidden = ' hidden';
	}
?>
<div class="ui pages-tab main-event main-area area" data-tab="pages-<?php echo $name; ?>" style="margin:0.5em 0em;">
	<!--
	<h4 class="ui top attached center aligned header">
		<i class="file outline icon"></i>
		<?php echo $name; ?>
	</h4>
	-->
	<div class="ui attached primary menu G2-tabs">
		<a class="item title" data-tab="pages-<?php echo $name; ?>-title" data-name="views"><div class="ui compact header small"><?php echo $name; ?></div></a>
		<a class="item active toggle_designer<?php echo $hidden; ?>" data-tab="pages-<?php echo $name; ?>-views" data-name="views"><?php echo el('Views'); ?></a>
		<a class="item toggle_designer<?php echo $hidden; ?>" data-tab="pages-<?php echo $name; ?>-actions" data-name="actions"><?php echo el('Actions'); ?></a>
		
		<a class="item<?php echo $hidden; ?>" data-tab="pages-<?php echo $name; ?>-settings"><?php el('Settings'); ?></a>
		
		<a class="item<?php echo $hidden; ?>" data-tab="pages-<?php echo $name; ?>-preview" data-class="preview-tab" data-name="<?php echo $name; ?>"><?php el('Preview'); ?></a>
		<a class="item<?php echo $hidden; ?>" data-tab="pages-<?php echo $name; ?>-template"><?php el('Template'); ?></a>
		
		<?php if(!empty($this->data('Connection.params.permissions.app'))): ?>
		<a class="item<?php echo $hidden; ?>" data-tab="pages-<?php echo $name; ?>-permissions"><?php el('Permissions'); ?></a>
		<?php endif; ?>
		
		<div class="item right" data-tab="pages-<?php echo $name; ?>-tools">
			
			<i class="icon window <?php if(!empty($this->data('Connection.events.'.$name.'.minimized'))):?>maximize<?php else: ?>minimize<?php endif; ?> teal link minimize_area minimize_page" data-hint="<?php el('Minimize/Maximize'); ?>" data-named="<?php echo $name; ?>"></i>
			
			<i class="icon sort yellow link sort_area" data-hint="<?php el('Sort'); ?>"></i>
			<i class="icon delete red link delete_area" data-hint="<?php el('Delete'); ?>"></i>
		</div>
		
	</div>
	
	<div class="ui bottom attached tab segment active<?php echo $hidden; ?>" data-tab="pages-<?php echo $name; ?>-views">
		<input type="hidden" value="<?php echo $name; ?>" name="Connection[sections][<?php echo $name; ?>][name]" readonly="true">
		<input type="hidden" value="0" name="Connection[sections][<?php echo $name; ?>][minimized]" data-minimized="<?php echo $name; ?>">
		
		<!--<div class="ui segment active green draggable-receiver <?php if(!empty($this->data('Connection.sections.'.$name.'.minimized'))):?>hidden<?php endif; ?>" style="min-height:200px;" data-name="<?php echo $name; ?>">-->
		<div class="ui segment active green draggable-receiver" style="min-height:150px;padding:0.4em 0.4em 2em 0.4em; margin:0;" data-name="<?php echo $name; ?>">
			<?php if(!empty($views)): ?>
				<?php foreach($views as $view_n => $view): ?>
					<?php if(!empty($view['name']) AND ($view['_section'] == $name)): ?>
						<?php $this->view('views.connections.views_config', ['section_name' => $name, 'name' => $view['name'], 'type' => $view['type'], 'count' => $view_n, 'view' => $view, 'views' => $views]); ?>
					<?php endif; ?>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
	</div>
	
	<div class="ui bottom attached tab segment<?php echo $hidden; ?>" data-tab="pages-<?php echo $name; ?>-actions">
		<input type="hidden" value="<?php echo $name; ?>" name="Connection[events][<?php echo $name; ?>][name]" readonly="true">
		<!--
		<input type="hidden" value="<?php echo $type; ?>" name="Connection[events][<?php echo $name; ?>][_type]" readonly="true">
		-->
		<input type="hidden" value="0" name="Connection[events][<?php echo $name; ?>][minimized]" data-minimized="<?php echo $name; ?>">
		
		<!--<div class="ui segment active green draggable-receiver <?php if(!empty($this->data('Connection.events.'.$name.'.minimized'))):?>hidden<?php endif; ?>" style="min-height:200px;" data-name="<?php echo $name; ?>">-->
		<div class="ui segment active blue draggable-receiver" style="min-height:150px;padding:0.4em 0.4em 2em 0.4em; margin:0;" data-name="<?php echo $name; ?>">
			<?php if(!empty($functions)): ?>
				<?php foreach($functions as $function_n => $function): ?>
					<?php if(!empty($function['name']) AND ($function['_event'] == $name)): ?>
						<?php $this->view('views.connections.functions_config', ['event_name' => $name, 'name' => $function['name'], 'type' => $function['type'], 'count' => $function_n, 'function' => $function, 'functions' => $functions]); ?>
					<?php endif; ?>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
	</div>
	
	<div class="ui bottom attached tab segment<?php echo $hidden; ?>" data-tab="pages-<?php echo $name; ?>-settings">
		<div class="field">
			<div class="ui checkbox toggle">
				<input type="hidden" name="Connection[events][<?php echo $name; ?>][auto_view]" data-ghost="1" value="">
				<input type="checkbox" class="hidden" name="Connection[events][<?php echo $name; ?>][auto_view]" value="1" <?php if(empty($this->data('Connection.id'))): ?>checked<?php endif; ?>>
				<label><?php el('Auto display view'); ?></label>
				<small><?php el('If enabled then a view of the same name will be displayed after all the actions have been processed'); ?></small>
			</div>
		</div>
		<div class="field">
			<div class="ui checkbox toggle">
				<input type="hidden" name="Connection[events][<?php echo $name; ?>][debug]" data-ghost="1" value="">
				<input type="checkbox" class="hidden" name="Connection[events][<?php echo $name; ?>][debug]" value="1">
				<label><?php el('Debug'); ?></label>
				<small><?php el('Display debugger data after the event actions'); ?></small>
			</div>
		</div>
		<div class="five wide field">
			<label><?php el('Page Type'); ?></label>
			<select name="Connection[events][<?php echo $name; ?>][type]" class="ui fluid dropdown">
				<option value=""><?php el('Auto - Use page order'); ?></option>
				<option value="standalone"><?php el('Standalone'); ?></option>
				<option value="end"><?php el('End'); ?></option>
			</select>
			<small><?php el('Select the page type.'); ?></small>
		</div>
		<!--
		<div class="field">
			<div class="ui checkbox toggle">
				<input type="hidden" name="Connection[events][<?php echo $name; ?>][validate_fields]" data-ghost="1" value="">
				<input type="checkbox" class="hidden" name="Connection[events][<?php echo $name; ?>][validate_fields]" value="1">
				<label><?php el('Validate fields'); ?></label>
				<small><?php el('Auto validate form fields with configured validation rules'); ?></small>
			</div>
		</div>
		<div class="field">
			<div class="ui checkbox toggle">
				<input type="hidden" name="Connection[events][<?php echo $name; ?>][security]" data-ghost="1" value="">
				<input type="checkbox" class="hidden" name="Connection[events][<?php echo $name; ?>][security]" value="1">
				<label><?php el('Security fields check'); ?></label>
				<small><?php el('Check any existing security fields, Honeypot, Security image and Google reCaptcha'); ?></small>
			</div>
		</div>
		-->
	</div>
	
	<?php if(!empty($this->data('Connection.params.permissions.app'))): ?>
	<div class="ui bottom attached tab segment<?php echo $hidden; ?>" data-tab="pages-<?php echo $name; ?>-permissions">
		<?php if(!empty($this->data('Connection.events.'.$name.'.access_denied'))): ?>
		<div class="two fields">
			<div class="field">
				<label><?php el('On access denied'); ?></label>
				<input type="text" value="" name="Connection[events][<?php echo $name; ?>][access_denied]">
			</div>
		</div>
		<?php endif; ?>
		
		<div class="two fields">
			<div class="field">
				<label><?php el('Owner id value'); ?></label>
				<input type="text" value="" name="Connection[events][<?php echo $name; ?>][owner_id]">
			</div>
		</div>
		
		<?php $this->view('views.permissions_manager', ['model' => 'Connection[events]['.$name.']', 'perms' => ['access' => rl('Access')], 'groups' => $this->get('groups')]); ?>
	</div>
	<?php endif; ?>
	
	<div class="ui bottom attached tab segment<?php echo $hidden; ?>" data-tab="pages-<?php echo $name; ?>-preview" id="<?php echo $name; ?>-preview">
		
	</div>
	
	<div class="ui bottom attached tab segment<?php echo $hidden; ?>" data-tab="pages-<?php echo $name; ?>-template">
		<div class="field">
			<div class="ui checkbox toggle">
				<input type="hidden" name="Connection[sections][<?php echo $name; ?>][auto]" data-ghost="1" value="0">
				<input type="checkbox" checked="checked" class="hidden" name="Connection[sections][<?php echo $name; ?>][auto]" value="1">
				<label><?php el('Auto generate page template when form is saved'); ?></label>
			</div>
		</div>
		<div class="field">
			<label><?php el('Template'); ?></label>
			<textarea name="Connection[sections][<?php echo $name; ?>][template]" rows="10"></textarea>
			<small><?php el('The page template used in emails'); ?></small>
		</div>
	</div>
	
</div>