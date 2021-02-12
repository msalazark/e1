<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<?php
	if($this->get('permissions_deactivated', false)){
		return;
	}
?>
<?php if(!empty($this->data('Connection.'.$type.'.'.$n.'.toggler'))): ?>
	<div class="field">
		<label><?php el('Toggle switch'); ?></label>
		<input type="text" value="" name="Connection[<?php echo $type; ?>][<?php echo $n; ?>][toggler]">
		<small><?php el('If provided and is an empty value then the view will not be rendered.'); ?></small>
	</div>
<?php endif; ?>

<?php
	$rules = $this->data('Connection.'.$type.'.'.$n.'.rules.access', []);
	if(!empty(array_filter($rules)) OR !empty($this->get('enable_permissions'))):
?>
	<div class="two fields">
		<div class="field">
			<label><?php el('Owner id value'); ?></label>
			<input type="text" value="" name="Connection[<?php echo $type; ?>][<?php echo $n; ?>][owner_id]">
			<small><?php el('The value of the owner id with which the owner permission will be checked.'); ?></small>
		</div>
		
	</div>

	<?php $this->view('views.permissions_manager', ['model' => 'Connection['.$type.']['.$n.']', 'perms' => ['access' => rl('Access')], 'groups' => $this->get('groups')]); ?>
<?php else: ?>
	<button type="button" class="ui button icon labeled black fluid load_content" data-url="<?php echo r2('index.php?ext=chronoforms&cont=connections&act=refresh_permissions&type='.$type.'&n='.$n.'&tvout=view'); ?>"><i class="icon refresh"></i><?php el('Enable Permissions'); ?></button>
<?php endif; ?>