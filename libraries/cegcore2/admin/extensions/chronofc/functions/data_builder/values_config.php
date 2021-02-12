<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<div class="ui fluid container small clonable_container" data-group="values" data-counter="<?php echo !empty($item['values']) ? max(array_keys($item['values'])) : 0; ?>">
<div class="field">
	<button type="button" data-group="values" data-grouptype="rule" class="ui button icon compact labeled blue tiny add_clone"><i class="plus icon"></i><?php el('Value'); ?></button>
</div>
<?php
	if(empty($item['values'])){
		$item['values'] = [1];
	}else{
		$item['values'] = [1] + $item['values'];
	}
	
?>
<?php foreach($item['values'] as $kc => $item_order): ?>
	<div class="field clonable" data-group="values" data-grouptype="rule" data-counter="<?php echo $kc; ?>"  <?php if($kc == 0): ?>data-source="1"<?php endif; ?>>
		<div class="equal width fields">
			
			<div class="four wide field">
				<select name="Connection[<?php echo $type; ?>][<?php echo $n; ?>][values][<?php echo $kc; ?>][type]" class="ui fluid dropdown small" data-origin='{"name":"Connection[<?php echo $type; ?>][<?php echo $n; ?>][values][values-N-][type]"}'>
					<option value="var"><?php el('Variable'); ?></option>
					<option value="data"><?php el('Data'); ?></option>
					<option value="session"><?php el('Session'); ?></option>
				</select>
				<small><?php el('Type'); ?></small>
			</div>
			
			<div class="five wide field">
				<input type="text" name="Connection[<?php echo $type; ?>][<?php echo $n; ?>][values][<?php echo $kc; ?>][name]" data-origin='{"name":"Connection[<?php echo $type; ?>][<?php echo $n; ?>][values][values-N-][name]"}'>
				<small><?php el('Name'); ?></small>
			</div>
			
			<div class="five wide field">
				<input type="text" name="Connection[<?php echo $type; ?>][<?php echo $n; ?>][values][<?php echo $kc; ?>][value]" data-origin='{"name":"Connection[<?php echo $type; ?>][<?php echo $n; ?>][values][values-N-][value]"}'>
				<small><?php el('Value'); ?></small>
			</div>
			
			<div class="two wide field">
				<button type="button" data-group="values" class="ui button icon compact blue tiny add_clone"><i class="plus icon"></i></button>
				<button type="button" data-group="values" class="ui button icon compact red tiny <?php if($kc == 0): ?>hidden<?php endif; ?> delete_clone"><i class="cancel icon"></i></button>
			</div>
		</div>
	</div>
<?php endforeach; ?>

</div>