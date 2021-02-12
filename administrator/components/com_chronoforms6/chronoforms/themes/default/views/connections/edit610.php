<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<?php $this->view(\G2\Globals::ext_path('chronofc', 'admin').DS.'themes'.DS.'default'.DS.'views'.DS.'designer.php'); ?>
<form action="<?php echo r2('index.php?ext=chronoforms&cont=connections'); ?>" method="post" name="admin_form" id="admin_form" class="ui form">

	<h2 class="ui header"><?php echo !empty($this->data['Connection']['title']) ? $this->data['Connection']['title'] : rl('New form'); ?></h2>
	<div class="ui sticky2 white segment">
		<!--
		<button type="button" class="ui button compact green icon labeled toolbar-button" data-fn="saveform" name="save" data-url="<?php echo r2('index.php?ext=chronoforms&cont=connections&act=edit'); ?>">
			<i class="check icon"></i><?php el('Save'); ?>
		</button>
		-->
		<button type="button" class="ui button compact green icon labeled toolbar-button" data-fn="saveform" name="apply" data-url="<?php echo r2('index.php?ext=chronoforms&cont=connections&act=edit&apply=1'); ?>">
			<i class="save icon"></i><?php el('Full Save'); ?>
		</button>
		<?php if(!empty($this->data['Connection']['id'])): ?>
		<button type="button" class="ui button compact blue icon labeled" data-fn="saveform" name="apply" id="apply" data-url="<?php echo r2('index.php?ext=chronoforms&cont=connections&act=edit&tvout=view&apply=1'); ?>">
			<i class="check icon"></i><?php el('Quick Save'); ?>
		</button>
		<?php else: ?>
		
		<?php endif; ?>
		<a class="ui button compact red icon labeled toolbar-button" href="<?php echo r2('index.php?ext=chronoforms&cont=connections'); ?>">
			<i class="cancel icon"></i><?php el('Close'); ?>
		</a>
		
		<a class="ui button compact blue inverted active icon labeled right floated <?php if(empty($this->data['Connection']['alias'])): ?>disabled<?php endif; ?>" target="_blank" href="<?php echo r2('index.php?ext=chronoforms&cont=manager&chronoform='.$this->data['Connection']['alias']); ?>">
			<i class="tv icon"></i><?php el('Preview'); ?>
		</a>
		<a class="ui button compact orange icon labeled toolbar-button right floated <?php if(empty($this->data['Connection']['id'])): ?>disabled<?php endif; ?>" href="<?php echo r2('index.php?ext=chronoforms&cont=connections&act=backup&gcb[]='.$this->data['Connection']['id']); ?>">
			<i class="download icon"></i><?php el('Backup'); ?>
		</a>
		
	</div>
	
	<div class="ui top attached tiny steps G2-tabs">
		<a class="step active" data-tab="general">
			<i class="settings icon"></i>
			<div class="content"><div class="title"><?php el('Settings'); ?></div><div class="description"><?php el('Form settings'); ?></div></div>
		</a>
		<!--<a class="step" data-tab="sections">
			<i class="object group icon"></i>
			<div class="content"><div class="title"><?php el('Design'); ?></div><div class="description"><?php el('Form Views'); ?></div></div>
		</a>
		<a class="step" data-tab="events">
			<i class="tasks icon"></i>
			<div class="content"><div class="title"><?php el('Setup'); ?></div><div class="description"><?php el('Form Actions'); ?></div></div>
		</a>-->
		<a class="step" data-tab="pages">
			<i class="tasks icon"></i>
			<div class="content"><div class="title"><?php el('Pages'); ?></div><div class="description"><?php el('App pages'); ?></div></div>
		</a>
		<a class="step" data-tab="locales">
			<i class="translate icon"></i>
			<div class="content"><div class="title"><?php el('Translate'); ?></div><div class="description"><?php el('Locales strings'); ?></div></div>
		</a>
		<?php if(!empty($this->data('Connection.params.permissions.app'))): ?>
		<a class="step" data-tab="permissions">
			<i class="key icon"></i>
			<div class="content"><div class="title"><?php el('Access control'); ?></div><div class="description"><?php el('App permissions'); ?></div></div>
		</a>
		<?php endif; ?>
	</div>
	
	<div class="ui bottom attached tab segment active" data-tab="general">
		<input type="hidden" name="Connection[id]" value="">
		
		<div class="ui grid equal width divided">
			<div class="column">
				<h3 class="ui center aligned header dividing">
					<div class="content">
						<?php el('Basic'); ?>
					</div>
				</h3>
				<div class="field">
					<label><?php el('Title'); ?></label>
					<input type="text" placeholder="<?php el('Title'); ?>" name="Connection[title]">
				</div>
				<div class="field">
					<label><?php el('Alias'); ?></label>
					<input type="text" placeholder="<?php el('Alias'); ?>" name="Connection[alias]">
					<small style="color:red;"><?php el('Use this alias to call your form in URLs or shortcodes.'); ?></small>
				</div>
				
				<div class="field">
					<div class="ui checkbox toggle">
						<input type="hidden" name="Connection[published]" data-ghost="1" value="">
						<input type="checkbox" checked="checked" class="hidden" name="Connection[published]" value="1">
						<label><?php el('Published'); ?></label>
						<small><?php el('Enable or disable this form.'); ?></small>
					</div>
				</div>
				<div class="field">
					<div class="ui checkbox toggle">
						<input type="hidden" name="Connection[public]" data-ghost="1" value="">
						<input type="checkbox" checked="checked" class="hidden" name="Connection[public]" value="1">
						<label><?php el('Public'); ?></label>
						<small><?php el('Enable frontend view of this form.'); ?></small>
					</div>
				</div>
			
			</div>
			
			<div class="column">
				<h3 class="ui center aligned header dividing">
					<div class="content">
						<?php el('Advanced'); ?>
					</div>
				</h3>
				<div class="field">
					<label><?php el('App Type'); ?></label>
					<select name="Connection[params][type]" class="ui fluid dropdown">
						<option value=""><?php el('Custom'); ?></option>
						<option value="form"><?php el('Form'); ?></option>
					</select>
					<small><?php el('Select a default type for this app, type form will auto include a form area'); ?></small>
				</div>
				<div class="field">
					<div class="ui checkbox toggle">
						<input type="hidden" name="Connection[params][events_ordered]" data-ghost="1" value="">
						<input type="checkbox" <?php if(empty($this->data('Connection.id'))): ?>checked="checked"<?php endif; ?> class="hidden" name="Connection[params][events_ordered]" value="1">
						<label><?php el('Follow pages order'); ?></label>
						<small><?php el('The order of pages will affect how the form works, users will have to fill the form pages in the same order, its recommended to enable this setting.'); ?></small>
					</div>
				</div>
				<div class="field">
					<div class="ui checkbox toggle">
						<input type="hidden" name="Connection[params][multipage]" data-ghost="1" value="">
						<input type="checkbox" <?php if(empty($this->data('Connection.id'))): ?>checked="checked"<?php endif; ?> class="hidden" name="Connection[params][multipage]" value="1">
						<label><?php el('Multi page form'); ?></label>
						<small><?php el('Handle form pages data automatically, form data is available in all pages and is cleared after the last page'); ?></small>
					</div>
				</div>
				<div class="field">
					<div class="ui checkbox toggle">
						<input type="hidden" name="Connection[params][ajax]" data-ghost="1" value="">
						<input type="checkbox" class="hidden" name="Connection[params][ajax]" value="1">
						<label><?php el('Use AJAX'); ?></label>
						<small><?php el('Use AJAX for form submit to avoid full page loading.'); ?></small>
					</div>
				</div>
				<div class="field">
					<div class="ui checkbox toggle">
						<input type="hidden" name="Connection[params][validate_fields]" data-ghost="1" value="">
						<input type="checkbox" <?php if(empty($this->data('Connection.id'))): ?>checked="checked"<?php endif; ?> class="hidden" name="Connection[params][validate_fields]" value="1">
						<label><?php el('Enable server validations'); ?></label>
						<small><?php el('Fields with validation rules will be auto validated.'); ?></small>
					</div>
				</div>
				<div class="field">
					<div class="ui checkbox toggle">
						<input type="hidden" name="Connection[params][check_security]" data-ghost="1" value="">
						<input type="checkbox" <?php if(empty($this->data('Connection.id'))): ?>checked="checked"<?php endif; ?> class="hidden" name="Connection[params][check_security]" value="1">
						<label><?php el('Check security fields'); ?></label>
						<small><?php el('Security fields (reCaptcha, security image, honeypot) will be auto checked.'); ?></small>
					</div>
				</div>
				<div class="field">
					<div class="ui checkbox toggle">
						<input type="hidden" name="Connection[params][upload_files]" data-ghost="1" value="">
						<input type="checkbox" class="hidden" name="Connection[params][upload_files]" value="1">
						<label><?php el('Upload files'); ?></label>
						<small><?php el('Auto upload files from file fields with auto upload enabled.'); ?></small>
					</div>
				</div>
				<div class="field">
					<div class="ui checkbox toggle">
						<input type="hidden" name="Connection[params][log][enabled]" data-ghost="1" value="">
						<input type="checkbox" class="hidden" name="Connection[params][log][enabled]" value="1">
						<label><?php el('Log data'); ?></label>
						<small><?php el('Store all the form data in the Chronoforms database log table'); ?></small>
					</div>
				</div>
				<div class="field">
					<div class="ui checkbox toggle">
						<input type="hidden" name="Connection[params][debug]" data-ghost="1" value="">
						<input type="checkbox" class="hidden" name="Connection[params][debug]" value="1">
						<label><?php el('Debug'); ?></label>
						<small><?php el('Display debug results in all the app pages'); ?></small>
					</div>
				</div>
				<div class="field">
					<div class="ui checkbox toggle">
						<input type="hidden" name="Connection[params][permissions][app]" data-ghost="1" value="">
						<input type="checkbox"<?php if(!empty($this->data('Connection.params.permissions_deactivated'))): ?> checked<?php endif; ?> class="hidden" name="Connection[params][permissions][app]" value="1">
						<label><?php el('Enable permissions'); ?></label>
						<small><?php el('Enables ACL permissions control in the form, the form must be saved for changes here to take effect.'); ?></small>
					</div>
				</div>
				<div class="field">
					<div class="ui checkbox toggle">
						<input type="hidden" name="Connection[params][404_default]" data-ghost="1" value="">
						<input type="checkbox" checked="checked" class="hidden" name="Connection[params][404_default]" value="1">
						<label><?php el('Load default page if non found'); ?></label>
						<small><?php el('If a form page is not found then the default page will be loaded, if this is disabled then a page named 404 will be called'); ?></small>
					</div>
				</div>
			</div>
			
		</div>
		
		<div class="ui divider"></div>
		
		<div class="field">
			<label><?php el('Description'); ?></label>
			<textarea placeholder="<?php el('Description'); ?>" name="Connection[description]" id="conndesc" rows="7"></textarea>
			<small><?php el('Form description will be visible in the form edit page and form listing in the admin area.'); ?></small>
		</div>
		
		<?php if($this->data('Connection.id') AND (!empty($this->data('Connection.params.default_event')) OR !empty($this->data('Connection.params.event_not_found')))): ?>
		<div class="equal width fields">
			<div class="field">
				<label><?php el('Default event'); ?></label>
				<input type="text" value="" name="Connection[params][default_event]">
				<small><?php el('The form event to be loaded when no event parameter is passed in the url.'); ?></small>
			</div>
			<div class="field">
				<label><?php el('Event not found'); ?></label>
				<input type="text" value="" name="Connection[params][event_not_found]">
				<small><?php el('Output displayed when the called form event does not exist.'); ?></small>
			</div>
		</div>
		<?php endif; ?>
		
		<!--
		<div class="field">
			<div class="ui checkbox toggle">
				<input type="hidden" name="Connection[params][denied_event]" data-ghost="1" value="">
				<input type="checkbox" class="hidden" name="Connection[params][denied_event]" value="1">
				<label><?php el('Call 403 page on app/page access denied'); ?></label>
				<small><?php el('If the access is denied to the app or a page then a page named 403 will be called when this setting is enabled.'); ?></small>
			</div>
		</div>
		-->
		<?php //$this->view(dirname(__FILE__).DS.'models_config.php', ['item' => $this->data('Connection'), 'type' => 'models', 'n' => 99]); ?>
		
	
		<input type="hidden" name="Connection[params][limited_edition]" value="1">
		<input type="hidden" name="Connection[params][_version]" value="610">
	</div>
	
	<div class="ui bottom attached tab segment structures-list" data-tab="pages" data-name="page">
		<div class="ui grid">
			<input type="hidden" value="<?php echo empty($this->data['Connection']['functions']) ? 1 : max(array_keys($this->data['Connection']['functions'])) + 1; ?>" id="functions-count" name="functions-count">
			<input type="hidden" value="<?php echo empty($this->data['Connection']['views']) ? 1 : max(array_keys($this->data['Connection']['views'])) + 1; ?>" id="views-count">
			
			<div class="four wide column scrollableBox" data-name="views">
				<?php
					//get views files
					$views = \G2\L\Folder::getFolders(\G2\Globals::ext_path('chronofc', 'admin').'views'.DS);
					$views_info = [];
					foreach($views as $view){
						$name = basename($view);
						$info_file = $view.DS.$name.'.ini';
						$info = parse_ini_file($info_file);
						if(!empty($info['apps'])){
							if(!in_array('forms', $info['apps'])){
								continue;
							}
						}
						$views_info[$name] = $info;
					}
					$types = ['core', 'more'];
					$views_info2 = ['core' => $views_info, 'more' => $blocks_views];
					$views_groups = ['core' => array_unique(array('Fields', 'Advanced Fields', 'Security Fields') + \G2\L\Arr::getVal($views_info, '[n].group', [])), 'more' => array_unique(array('Default') + \G2\L\Arr::getVal($blocks_views, '[n].group', []))];
				?>
				<div class="ui secondary pointing menu small G2-tabs">
					<a class="item active" data-tab="viewslist-core"><?php el('Core'); ?></a>
					<a class="item" data-tab="viewslist-more"><?php el('More'); ?></a>
				</div>
				
				<?php foreach($types as $kt => $type): ?>
				<div class="ui bottom attached tab <?php if($kt == 0): ?>active<?php endif; ?>" data-tab="viewslist-<?php echo $type; ?>">
					<div class="ui fluid accordion styled draggable-list">
						<?php foreach($views_groups[$type] as $kvg => $views_group): ?>
						<div class="title ui header small blue <?php if(empty($kvg)): ?> active<?php endif; ?>"><i class="dropdown icon"></i><?php echo $views_group; ?></div>
						<div class="content <?php if(empty($kvg)): ?> active<?php endif; ?>">
							
							<div class="ui grid center aligned small views-list">
								<?php foreach($views_info2[$type] as $vw_name => $vw_info): ?>
									<?php if($vw_info['group'] == $views_group): ?>
									<div class="eight wide large screen sixteen wide small screen column draggable" data-type="view" data-info='<?php echo json_encode($vw_info); ?>' style="padding:5px;" data-url="<?php echo r2('index.php?ext=chronoforms&cont=connections&act=block_config&tvout=view'); ?>">
										<div class="ui segment tiny" style="padding:5px 1px;" <?php if(!empty($vw_info['desc'])): ?>data-hint="<?php echo nl2br($vw_info['desc']); ?>"<?php endif; ?>>
											<div class="ui header small">
												<?php if(!empty($vw_info['icon'])): ?>
												<i class="icon <?php echo $vw_info['icon']; ?> fitted"></i>
												<?php endif; ?>
												<?php echo $vw_info['title']; ?>
											</div>
										</div>
									</div>
									<?php endif; ?>
								<?php endforeach; ?>
							</div>
							
						</div>
						<?php endforeach; ?>
					</div>
				</div>
				<?php endforeach; ?>
				
			</div>
			
			<div class="four wide column hidden scrollableBox" data-name="actions">
				<?php
					//get views files
					$functions = \G2\L\Folder::getFolders(\G2\Globals::ext_path('chronofc', 'admin').'functions'.DS);
					$functions_info = [];
					foreach($functions as $function){
						$name = basename($function);
						$info_file = $function.DS.$name.'.ini';
						$info = parse_ini_file($info_file);
						if(!empty($info['apps'])){
							if(!in_array('forms', $info['apps'])){
								continue;
							}
						}
						if(!isset($info['color'])){
							$info['color'] = 'blue';
						}
						if(!empty($info['private']) AND !empty($public)){
							continue;
						}
						if(!empty($info['platform']) AND !in_array(\G2\Globals::get('app'), $info['platform'])){
							continue;
						}
						$functions_info[$name] = $info;
					}
					$types = ['core', 'more'];
					$functions_info2 = ['core' => $functions_info, 'more' => $blocks_functions];
					$functions_groups = ['core' => array_unique(array('Basic') + \G2\L\Arr::getVal($functions_info, '[n].group', [])), 'more' => array_unique(array('Default') + \G2\L\Arr::getVal($blocks_functions, '[n].group', []))];
					asort($functions_groups['core']);
				?>
				<div class="ui secondary pointing menu small G2-tabs">
					<a class="item active" data-tab="functionslist-core"><?php el('Core'); ?></a>
					<a class="item" data-tab="functionslist-more"><?php el('More'); ?></a>
				</div>
				
				<?php foreach($types as $kt => $type): ?>
				<div class="ui bottom attached tab <?php if($kt == 0): ?>active<?php endif; ?>" data-tab="functionslist-<?php echo $type; ?>">
					<div class="ui fluid accordion styled draggable-list">
						<?php foreach($functions_groups[$type] as $kfg => $functions_group): ?>
						<div class="title ui header small blue <?php if(empty($kfg)): ?> active<?php endif; ?>"><i class="dropdown icon"></i><?php echo $functions_group; ?></div>
						<div class="content<?php if(empty($kfg)): ?> active<?php endif; ?>">
							
							<div class="ui grid center aligned small functions-list">
								<?php foreach($functions_info2[$type] as $fn_name => $fn_info): ?>
									<?php if($fn_info['group'] == $functions_group): ?>
									<div class="eight wide large screen sixteen wide small screen column draggable" data-type="function" data-private="<?php echo !empty($fn_info['private']) ? 1 : 0; ?>" data-info='<?php echo json_encode($fn_info); ?>' style="padding:5px;" data-url="<?php echo r2('index.php?ext=chronoforms&cont=connections&act=block_config&tvout=view'); ?>">
										<div class="ui segment tiny" style="padding:5px 1px;">
											<div class="ui header small">
											<?php if(!empty($fn_info['icon'])): ?>
											<i class="icon <?php echo $fn_info['icon']; ?> fitted"></i>
											<?php endif; ?>
											<?php echo $fn_info['title']; ?>
											</div>
										</div>
									</div>
									<?php endif; ?>
								<?php endforeach; ?>
							</div>
							
						</div>
						<?php endforeach; ?>
					</div>
				</div>
				<?php endforeach; ?>
				
			</div>
			
			<div class="twelve wide column">
				<!--
				<div class="ui secondary pointing menu small G2-tabs">
					<?php $active = 'active'; ?>
					<?php foreach($this->data['Connection']['types'] as $type_n => $type): ?>
					<a class="item <?php echo $active; ?>" data-tab="types-<?php echo $type_n; ?>"><?php echo $type['name']; ?></a>
					<?php $active = ''; ?>
					<?php endforeach; ?>
				</div>
				-->
				<?php $active = 'active'; ?>
				<?php foreach($this->data['Connection']['types'] as $type_n => $type): ?>
					<div class="ui tab <?php echo $active; ?>" data-tab="types-<?php echo $type_n; ?>">
						<!--
						<div class="ui secondary menu small G2-tabs">
							<a class="item active" data-tab="types-<?php echo $type_n; ?>-pages"><?php el('Pages'); ?></a>
							<a class="item" data-tab="types-<?php echo $type_n; ?>-settings"><?php el('Settings'); ?></a>
						</div>
						-->
						<div class="ui tab active" data-tab="types-<?php echo $type_n; ?>-pages">
							<div class="ui container fluid page-data areas">
								<?php foreach($this->data['Connection']['sections'] as $section_n => $section): ?>
									<?php if(!in_array($section_n, array_keys($this->data['Connection']['events']))): ?>
										<?php $this->view('views.connections.pages_config', ['name' => $section['name'], 'count' => $section_n, 'type' => $type_n, 'functions' => !empty($this->data['Connection']['functions']) ? $this->data['Connection']['functions'] : array(), 'views' => !empty($this->data['Connection']['views']) ? $this->data['Connection']['views'] : array()]); ?>
									<?php endif; ?>
								<?php endforeach; ?>
								
								<?php foreach($this->data['Connection']['events'] as $event_n => $event): ?>
									<?php if((empty($event['_type'])) OR (!empty($event['_type']) AND $event['_type'] == $type_n)): ?>
									<?php $this->view('views.connections.pages_config', ['name' => $event['name'], 'count' => $event_n, 'type' => $type_n, 'functions' => !empty($this->data['Connection']['functions']) ? $this->data['Connection']['functions'] : array(), 'views' => !empty($this->data['Connection']['views']) ? $this->data['Connection']['views'] : array()]); ?>
									<?php endif; ?>
								<?php endforeach; ?>
							</div>
							<?php $active = ''; ?>
							
							<div class="ui form">
								<div class="ui action input fluid">
									<input type="text" placeholder="<?php el('Page name should be unique without spaces or special characters...'); ?>" class="page-name">
									<button type="button" class="ui button green compact icon labeled disabled add-page" data-url="<?php echo r2('index.php?ext='.\GApp::instance()->extension.'&cont=connections&act=pages_config&type='.$type_n.'&tvout=view'); ?>">
										<i class="icon add"></i><?php el('Page'); ?>
									</button>
								</div>
							</div>
						</div>
						
						<?php /* ?>
						<div class="ui tab" data-tab="types-<?php echo $type_n; ?>-settings">
							
							<div class="equal width fields">
								<div class="field required">
									<label><?php el('Name'); ?></label>
									<input type="text" value="<?php echo $type_n; ?>" name="Connection[types][<?php echo $type_n; ?>][name]">
								</div>
								<div class="field required">
									<label><?php el('Database table'); ?></label>
									<select name="Connection[types][<?php echo $n; ?>][db_table]" data-fulltextsearch="1" class="ui fluid search selection dropdown">
										<option value=""><?php el('------Select table------'); ?></option>
										<?php foreach($db_tables as $ntable => $table): ?>
										<option value="<?php echo $ntable; ?>"><?php echo $table; ?></option>
										<?php endforeach; ?>
									</select>
									<small><?php el('Which database table should be used for this type ?'); ?></small>
								</div>
							</div>
							
						</div>
						<?php */ ?>
						
					</div>
				<?php endforeach; ?>
				<!--
				<div class="ui form">
					<div class="ui action input fluid">
						<input type="text" placeholder="<?php el('Page name should be unique...'); ?>" class="page-name">
						<button type="button" class="ui button green compact icon labeled disabled add-page" data-url="<?php echo r2('index.php?ext='.\GApp::instance()->extension.'&cont=connections&act=pages_config&tvout=view'); ?>">
							<i class="icon add"></i><?php el('Page'); ?>
						</button>
					</div>
				</div>
				-->
			</div>
		</div>
	</div>
	
	<div class="ui bottom attached tab segment structures-list" data-tab="locales" data-name="locale">
		<div class="ui grid">
		
			<div class="five wide column">
				<div class="ui vertical pointing menu fluid G2-tabs locale-list">
					<?php foreach($this->data['Connection']['locales'] as $locale_n => $locale): ?>
						<a class="blue item <?php if($locale_n == \G2\L\Config::get('site.language')): ?>active<?php endif; ?>" data-tab="locale-<?php echo $locale['name']; ?>">
							<?php if($locale_n != \G2\L\Config::get('site.language')): ?><div class="ui red label delete_block"><?php el('Delete'); ?></div><?php endif; ?>
							<?php echo $locale['name']; ?>
						</a>
					<?php endforeach; ?>
				</div>
				<div class="ui action input fluid">
					<input type="text" placeholder="<?php el('Locale tag...'); ?>" class="locale-name">
					<button type="button" id="add_new_locale" class="ui button green compact disabled add-locale" data-url="<?php echo r2('index.php?ext='.\GApp::instance()->extension.'&cont=connections&act=locales_config&tvout=view'); ?>">
						<?php el('Add locale'); ?>
					</button>
				</div>
			</div>
			
			<div class="eleven wide stretched column locale-data">
				<?php foreach($this->data['Connection']['locales'] as $locale_n => $locale): ?>
					<?php $this->view('views.connections.locales_config', ['name' => $locale['name'], 'count' => $locale_n]); ?>
				<?php endforeach; ?>
			</div>
			
		</div>
	</div>
	
	<?php if(!empty($this->data('Connection.params.permissions.app'))): ?>
	<div class="ui bottom attached tab segment" data-tab="permissions">
		<?php $this->view('views.permissions_manager', ['model' => 'Connection', 'perms' => ['access' => rl('Access')], 'groups' => $_groups]); ?>
	</div>
	<?php endif; ?>
	
</form>
