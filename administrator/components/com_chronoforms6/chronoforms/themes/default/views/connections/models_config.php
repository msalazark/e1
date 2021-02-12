<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<h3 class="ui center aligned dividing header">
	<i class="settings icon"></i>
	<div class="content">
		<?php el('Models settings'); ?>
		<div class="sub header"><?php el(''); ?></div>
	</div>
</h3>
<div class="ui fluid container small clonable_container" data-group="models" data-counter="<?php echo !empty($item['models']) ? max(array_keys($item['models'])) : 0; ?>">
<div class="field">
	<button type="button" data-group="models" class="ui button icon compact labeled blue tiny add_clone"><i class="plus icon"></i><?php el('Model'); ?></button>
</div>
<?php
	if(empty($item['models'])){
		$item['models'] = [['relations' => [1], 'fields' => [1]]];
	}else{
		$item['models'] = [['relations' => [1], 'fields' => [1]]] + $item['models'];
	}
	
?>
<div class="ui segmentx">
<?php foreach($item['models'] as $kc => $item_model): ?>
	<div class="field clonable ui message" data-group="models" data-grouptype="rule" data-counter="<?php echo $kc; ?>"  <?php if($kc == 0): ?>data-source="1"<?php endif; ?>>
		<div class="ui top attached tabular menu small G2-tabs">
			<a class="item active" data-tab="models-<?php echo $kc; ?>-general" data-origin='{"data-tab":"models-models-N--general"}'><?php el('General'); ?></a>
			<a class="item" data-tab="models-<?php echo $kc; ?>-relations" data-origin='{"data-tab":"models-models-N--relations"}'><?php el('Relations'); ?></a>
			<a class="item" data-tab="models-<?php echo $kc; ?>-fields" data-origin='{"data-tab":"models-models-N--fields"}'><?php el('Fields'); ?></a>
			<a class="item" data-tab="models-<?php echo $kc; ?>-db" data-origin='{"data-tab":"models-models-N--db"}'><?php el('External DB'); ?></a>
		</div>
		<div class="ui bottom attached tab segment small active" data-tab="models-<?php echo $kc; ?>-general" data-origin='{"data-tab":"models-models-N--general"}'>
			<div class="equal width fields">
				<div class="field">
					<div class="ui checkbox toggle">
						<input type="hidden" name="Connection[<?php echo $type; ?>][<?php echo $kc; ?>][enabled]" data-ghost="1" value="" data-origin='{"name":"Connection[<?php echo $type; ?>][models-N-][enabled]"}'>
						<input type="checkbox" class="hidden" name="Connection[<?php echo $type; ?>][<?php echo $kc; ?>][enabled]" value="1" data-origin='{"name":"Connection[<?php echo $type; ?>][models-N-][enabled]"}'>
						<label><?php el('Enabled'); ?></label>
						<small><?php el('The model will be used for storing the form data and create a table.'); ?></small>
					</div>
				</div>
				<div class="field">
					<div class="ui checkbox toggle">
						<input type="hidden" name="Connection[<?php echo $type; ?>][<?php echo $kc; ?>][sync]" data-ghost="1" value="" data-origin='{"name":"Connection[<?php echo $type; ?>][models-N-][sync]"}'>
						<input type="checkbox" class="hidden" name="Connection[<?php echo $type; ?>][<?php echo $kc; ?>][sync]" value="1" data-origin='{"name":"Connection[<?php echo $type; ?>][models-N-][sync]"}'>
						<label><?php el('Sync table'); ?></label>
						<small><?php el('Keep the database table up to date with the form fields.'); ?></small>
					</div>
				</div>
				<!--
				<div class="field">
					<div class="ui checkbox toggle">
						<input type="hidden" name="Connection[<?php echo $type; ?>][<?php echo $kc; ?>][multi]" data-ghost="1" value="" data-origin='{"name":"Connection[<?php echo $type; ?>][models-N-][multi]"}'>
						<input type="checkbox" class="hidden" name="Connection[<?php echo $type; ?>][<?php echo $kc; ?>][multi]" value="1" data-origin='{"name":"Connection[<?php echo $type; ?>][models-N-][multi]"}'>
						<label><?php el('Multiple'); ?></label>
						<small><?php el('Is this a multi record model ?'); ?></small>
					</div>
				</div>
				-->
			</div>
			<div class="equal width fields">
				<div class="field">
					<input type="text" value="" name="Connection[<?php echo $type; ?>][<?php echo $kc; ?>][name]" data-origin='{"name":"Connection[<?php echo $type; ?>][models-N-][name]","value":"Model_models-N-"}'>
					<small><?php el('Unique model name, no spaces or special characters'); ?></small>
				</div>
				<?php if(!empty($this->data('Connection.models.'.$kc.'.db_table'))): ?>
				<div class="field">
					<input type="text" value="" readonly name="Connection[<?php echo $type; ?>][<?php echo $kc; ?>][db_table]" data-origin='{"name":"Connection[<?php echo $type; ?>][models-N-][db_table]"}'>
					<small><?php el('The database table of this model, it can not be changed'); ?></small>
				</div>
				<?php else: ?>
				<div class="field">
					<select name="Connection[<?php echo $type; ?>][<?php echo $kc; ?>][db_table]" data-fulltextsearch="1" class="ui fluid search selection dropdown" data-origin='{"name":"Connection[<?php echo $type; ?>][models-N-][db_table]"}'>
						<option value=""><?php el('------Select table------'); ?></option>
						<?php foreach($this->get('db_tables') as $ntable => $table): ?>
						<option value="<?php echo $ntable; ?>"><?php echo $table; ?></option>
						<?php endforeach; ?>
					</select>
					<small><?php el('The associated database table, if left empty or if does not exist then a new table will be created'); ?></small>
				</div>
				<?php endif; ?>
				
				
				<div class="two wide field">
					<div class="ui icon buttons mini">
						<!--
						<button type="button" data-group="models" class="ui button icon compact blue tiny add_clone"><i class="clone icon"></i></button>
						-->
						<button type="button" data-group="models" class="ui button icon compact red tiny <?php if($kc == 0): ?>hidden<?php endif; ?> delete_clone"><i class="cancel icon"></i></button>
					</div>
				</div>
			</div>
			
			
		</div>
		
		<div class="ui bottom attached tab segment small" data-tab="models-<?php echo $kc; ?>-relations" data-origin='{"data-tab":"models-models-N--relations"}'>
			<div class="ui fluid container clonable_container" data-group="relations" data-counter="<?php echo !empty($item_model['relations']) ? max(array_keys($item_model['relations'])) : 0; ?>">
				
				<div class="field">
					<button type="button" data-group="relations" class="ui button icon compact green labeled icon tiny add_clone"><i class="icon plus"></i><?php el('Relation'); ?></button>
				</div>
				<?php
					if(empty($item_model['relations'])){
						$item_model['relations'] = [1];
					}else{
						$item_model['relations'] = [1] + $item_model['relations'];
					}
				?>
				<?php foreach($item_model['relations'] as $kg => $relation_group): ?>
					<div class="field clonable" data-group="relations" data-counter="<?php echo $kg; ?>" <?php if($kg == 0): ?>data-source="1"<?php endif; ?>>
						<div class="equal width fields">
							<div class="field">
								<select name="Connection[<?php echo $type; ?>][<?php echo $kc; ?>][relations][<?php echo $kg; ?>][type]" class="ui fluid selection dropdown" data-origin='{"name":"Connection[<?php echo $type; ?>][models-N-][relations][relations-N-][type]"}'>
									<option value="belongsto"><?php el('Belongs To'); ?></option>
								</select>
								<small><?php el('Relation type'); ?></small>
							</div>
							<div class="field">
								<input type="text" name="Connection[<?php echo $type; ?>][<?php echo $kc; ?>][relations][<?php echo $kg; ?>][model]" data-origin='{"name":"Connection[<?php echo $type; ?>][models-N-][relations][relations-N-][model]"}'>
								<small><?php el('Related model name'); ?></small>
							</div>
							<div class="field">
								<input type="text" name="Connection[<?php echo $type; ?>][<?php echo $kc; ?>][relations][<?php echo $kg; ?>][fkey]" data-origin='{"name":"Connection[<?php echo $type; ?>][models-N-][relations][relations-N-][fkey]"}'>
								<small><?php el('Foreign key'); ?></small>
							</div>
							<div class="two wide field">
								<div class="ui icon buttons mini">
									<button type="button" data-group="relations" class="ui button icon compact green tiny add_clone"><i class="clone icon"></i></button>
									<button type="button" data-group="relations" class="ui button icon compact red tiny <?php if($kg == 0): ?>hidden<?php endif; ?> delete_clone"><i class="cancel icon"></i></button>
								</div>
							</div>
						</div>
					</div>
				
				<?php endforeach; ?>
			</div>
		</div>
		
		<div class="ui bottom attached tab segment small" data-tab="models-<?php echo $kc; ?>-fields" data-origin='{"data-tab":"models-models-N--fields"}'>
			<div class="ui fluid container clonable_container" data-group="fields" data-counter="<?php echo !empty($item_model['fields']) ? max(array_keys($item_model['fields'])) : 0; ?>">
				
				<div class="field">
					<button type="button" data-group="fields" class="ui button icon compact green labeled icon tiny add_clone"><i class="icon plus"></i><?php el('Field'); ?></button>
				</div>
				<?php
					if(empty($item_model['fields'])){
						$item_model['fields'] = [1];
					}else{
						$item_model['fields'] = [1] + $item_model['fields'];
					}
				?>
				<?php foreach($item_model['fields'] as $kg => $field): ?>
					<div class="field clonable" data-group="fields" data-counter="<?php echo $kg; ?>" <?php if($kg == 0): ?>data-source="1"<?php endif; ?>>
						<div class="equal width fields">
							<div class="field">
								<input type="text" name="Connection[<?php echo $type; ?>][<?php echo $kc; ?>][fields][<?php echo $kg; ?>][name]" data-origin='{"name":"Connection[<?php echo $type; ?>][models-N-][fields][fields-N-][name]"}'>
								<small><?php el('Field name'); ?></small>
							</div>
							<div class="field">
								<select name="Connection[<?php echo $type; ?>][<?php echo $kc; ?>][fields][<?php echo $kg; ?>][type]" class="ui fluid selection dropdown" data-origin='{"name":"Connection[<?php echo $type; ?>][models-N-][fields][fields-N-][type]"}'>
									<option value="varchar"><?php el('VARCHAR'); ?></option>
									<option value="int"><?php el('INT'); ?></option>
									<option value="text"><?php el('TEXT'); ?></option>
									<option value="datetime"><?php el('DATETIME'); ?></option>
									<option value="tinyint"><?php el('TINYINT'); ?></option>
									<option value="float"><?php el('FLOAT'); ?></option>
									<option value="longtext"><?php el('LONGTEXT'); ?></option>
								</select>
								<small><?php el('Type'); ?></small>
							</div>
							<div class="field">
								<input type="text" name="Connection[<?php echo $type; ?>][<?php echo $kc; ?>][fields][<?php echo $kg; ?>][length]" data-origin='{"name":"Connection[<?php echo $type; ?>][models-N-][fields][fields-N-][length]"}'>
								<small><?php el('Length'); ?></small>
							</div>
							<div class="field">
								<input type="text" name="Connection[<?php echo $type; ?>][<?php echo $kc; ?>][fields][<?php echo $kg; ?>][default]" data-origin='{"name":"Connection[<?php echo $type; ?>][models-N-][fields][fields-N-][default]"}'>
								<small><?php el('Default'); ?></small>
							</div>
							<div class="two wide field">
								<div class="ui icon buttons mini">
									<button type="button" data-group="fields" class="ui button icon compact green tiny add_clone"><i class="clone icon"></i></button>
									<button type="button" data-group="fields" class="ui button icon compact red tiny <?php if($kg == 0): ?>hidden<?php endif; ?> delete_clone"><i class="cancel icon"></i></button>
								</div>
							</div>
						</div>
					</div>
				
				<?php endforeach; ?>
			</div>
		</div>
		
		<div class="ui bottom attached tab segment small" data-tab="models-<?php echo $kc; ?>-db" data-origin='{"data-tab":"models-models-N--db"}'>
			
		</div>
		
	</div>
<?php endforeach; ?>
</div>

</div>