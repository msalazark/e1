<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<div class="ui fluid container small clonable_container" data-group="conditions" data-counter="<?php echo !empty($item['conditions']) ? max(array_keys($item['conditions'])) : 0; ?>">
<div class="field">
	<button type="button" data-group="conditions" data-grouptype="rule" class="ui button icon compact labeled blue tiny add_clone"><i class="plus icon"></i><?php el('Condition'); ?></button>
	<button type="button" data-group="conditions" data-grouptype="param" class="ui button icon compact labeled green tiny add_clone"><i class="plus icon"></i><?php el('Operator'); ?></button>
	<button type="button" data-group="conditions" data-grouptype="custom" class="ui button icon compact labeled red tiny add_clone"><i class="plus icon"></i><?php el('Rules list'); ?></button>
</div>
<?php
	if(empty($item['conditions'])){
		$item['conditions'] = [1];
	}else{
		$item['conditions'] = [1] + $item['conditions'];
	}
?>
<?php foreach($item['conditions'] as $kc => $item_condition): ?>
	<?php if($kc == 0 OR $item_condition['_type'] == 'rule'): ?>
	<div class="field clonable" data-group="conditions" data-grouptype="rule" data-counter="<?php echo $kc; ?>"  <?php if($kc == 0): ?>data-source="1"<?php endif; ?>>
		<div class="equal width fields">
			<input type="hidden" value="rule" name="Connection[<?php echo $type; ?>][<?php echo $n; ?>][conditions][<?php echo $kc; ?>][_type]" data-origin='{"name":"Connection[<?php echo $type; ?>][<?php echo $n; ?>][conditions][conditions-N-][_type]"}'>
			
			<div class="four wide field">
				<input type="text" value="" name="Connection[<?php echo $type; ?>][<?php echo $n; ?>][conditions][<?php echo $kc; ?>][name]" data-origin='{"name":"Connection[<?php echo $type; ?>][<?php echo $n; ?>][conditions][conditions-N-][name]"}'>
				<small><?php el('Table field name'); ?></small>
			</div>
			<div class="three wide field">
				<select name="Connection[<?php echo $type; ?>][<?php echo $n; ?>][conditions][<?php echo $kc; ?>][namep]" class="ui fluid dropdown small" data-origin='{"name":"Connection[<?php echo $type; ?>][<?php echo $n; ?>][conditions][conditions-N-][namep]"}'>
					<option value="=">=</option>
					<option value="!=">!=</option>
					<option value=">">></option>
					<option value=">=">>=</option>
					<option value="<"><</option>
					<option value="<="><=</option>
					<option value="LIKE">LIKE</option>
					<option value="IN">IN</option>
					<option value="NOT IN">NOT IN</option>
					<option value="IS">IS</option>
					<option value="IS NOT">IS NOT</option>
				</select>
				<small><?php el('Condition'); ?></small>
			</div>
			<div class="four wide field">
				<input type="text" value="" name="Connection[<?php echo $type; ?>][<?php echo $n; ?>][conditions][<?php echo $kc; ?>][value]" data-origin='{"name":"Connection[<?php echo $type; ?>][<?php echo $n; ?>][conditions][conditions-N-][value]"}'>
				<small><?php el('Value'); ?></small>
			</div>
			<div class="two wide field">
				<select name="Connection[<?php echo $type; ?>][<?php echo $n; ?>][conditions][<?php echo $kc; ?>][valuep]" class="ui fluid dropdown small" data-origin='{"name":"Connection[<?php echo $type; ?>][<?php echo $n; ?>][conditions][conditions-N-][valuep]"}'>
					<option value=""><?php el('Use'); ?></option>
					<option value="-"><?php el('Continue'); ?></option>
					<option value="+"><?php el('Stop'); ?></option>
				</select>
				<small><?php el('Null value'); ?></small>
			</div>
			<div class="two wide field">
				<i data-group="conditions" class="plus icon circular inverted blue link add_clone"></i>
				<i data-group="conditions" class="delete icon circular inverted red link delete_clone <?php if($kc == 0): ?>hidden<?php endif; ?>"></i>
				<i data-group="conditions" class="sort icon circular inverted yellow link sort_clone"></i>
			</div>
		</div>
	</div>
	<?php endif; ?>
	
	<?php if($kc == 0 OR $item_condition['_type'] == 'param'): ?>
	<div class="field clonable" data-group="conditions" data-grouptype="param" data-counter="<?php echo $kc; ?>"  <?php if($kc == 0): ?>data-source="1"<?php endif; ?>>
		<div class="fields">
			<input type="hidden" value="param" name="Connection[<?php echo $type; ?>][<?php echo $n; ?>][conditions][<?php echo $kc; ?>][_type]" data-origin='{"name":"Connection[<?php echo $type; ?>][<?php echo $n; ?>][conditions][conditions-N-][_type]"}'>
			<div class="three wide field">
				<select name="Connection[<?php echo $type; ?>][<?php echo $n; ?>][conditions][<?php echo $kc; ?>][name]" class="ui fluid dropdown small" data-origin='{"name":"Connection[<?php echo $type; ?>][<?php echo $n; ?>][conditions][conditions-N-][name]"}'>
					<option value="AND">AND</option>
					<option value="OR">OR</option>
					<option value="(">(</option>
					<option value=")">)</option>
				</select>
			</div>
			<div class="field">
				<i data-group="conditions" class="plus icon circular inverted blue link add_clone"></i>
				<i data-group="conditions" class="delete icon circular inverted red link delete_clone <?php if($kc == 0): ?>hidden<?php endif; ?>"></i>
				<i data-group="conditions" class="sort icon circular inverted yellow link sort_clone"></i>
			</div>
		</div>
	</div>
	<?php endif; ?>
	
	<?php if($kc == 0 OR $item_condition['_type'] == 'custom'): ?>
	<div class="field clonable" data-group="conditions" data-grouptype="custom" data-counter="<?php echo $kc; ?>"  <?php if($kc == 0): ?>data-source="1"<?php endif; ?>>
		<div class="fields">
			<input type="hidden" value="custom" name="Connection[<?php echo $type; ?>][<?php echo $n; ?>][conditions][<?php echo $kc; ?>][_type]" data-origin='{"name":"Connection[<?php echo $type; ?>][<?php echo $n; ?>][conditions][conditions-N-][_type]"}'>
			<div class="fourteen wide field">
				<textarea name="Connection[<?php echo $type; ?>][<?php echo $n; ?>][conditions][<?php echo $kc; ?>][string]" rows="5" data-origin='{"name":"Connection[<?php echo $type; ?>][<?php echo $n; ?>][conditions][conditions-N-][string]"}'></textarea>
				<small><?php el('Multiline list of conditions rules'); ?></small>
			</div>
			<div class="two wide field">
				<i data-group="conditions" class="delete icon circular inverted red link delete_clone <?php if($kc == 0): ?>hidden<?php endif; ?>"></i>
				<i data-group="conditions" class="sort icon circular inverted yellow link sort_clone"></i>
			</div>
		</div>
	</div>
	<?php endif; ?>
<?php endforeach; ?>

</div>