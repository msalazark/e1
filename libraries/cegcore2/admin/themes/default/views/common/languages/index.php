<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>

<form action="<?php echo r2('index.php?ext='.$this->get('ext_name').'&cont=languages'); ?>" method="post" name="admin_form" id="admin_form" class="ui form">
	
	<h2 class="ui header"><?php el('Languages manager'); ?></h2>
	
	<div class="ui clearing divider"></div>
	
	<div class="ui bottom attached tab segment active" data-tab="general">
		<div class="grouped two fields">
			<div class="field">
				<label><?php el('Language tag'); ?></label>
				<input type="text" placeholder="" name="lang">
				<small><?php el('Type the language tag, e.g: en_GB or fr_FR'); ?></small>
			</div>
			<div class="field">
				<button class="compact ui button green icon labeled" name="build"><i class="search icon"></i><?php el('Build language file'); ?></button>
				<small><?php el('For new translations or updating an existing translation, use build!'); ?></small>
			</div>
			<div class="field">
				<button class="compact ui button purple icon labeled" name="custom"><i class="search icon"></i><?php el('Read existing custom file'); ?></button>
				<small><?php el('For updating custom translation strings, use Read Existing Custom File'); ?></small>
			</div>
		</div>
		
		<?php if(!empty($this->data['lang'])): ?>
			<h2 class="ui header dividing">
				<?php el('Update and save'); ?>
				<div class="sub header"><?php el('You can update any of the language strings below then save.'); ?></div>
			</h2>
			<div class="field">
				<?php if(isset($this->data['build'])): ?>
				<button class="compact ui button green icon labeled" name="update"><i class="warning icon"></i><?php el('Update language file'); ?></button>
				<?php endif; ?>
				<?php if(isset($this->data['custom'])): ?>
				<button class="compact ui button purple icon labeled" name="save"><i class="checkmark icon"></i><?php el('Update custom file'); ?></button>
				<?php endif; ?>
			</div>
			<div class="field">
				<label><?php el('Language strings'); ?></label>
				<textarea name="language_strings" rows="30"></textarea>
			</div>
		<?php endif; ?>
		
	</div>
	
</form>
