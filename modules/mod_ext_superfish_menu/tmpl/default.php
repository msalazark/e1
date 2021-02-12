<?php
/*
# ------------------------------------------------------------------------
# Extensions for Joomla 2.5 - Joomla 3.x
# ------------------------------------------------------------------------
# Copyright (C) 2011-2014 Ext-Joom.com. All Rights Reserved.
# @license - PHP files are GNU/GPL V2.
# Author: Ext-Joom.com
# Websites:  http://www.ext-joom.com 
# Date modified: 17/01/2014 - 10:00
# ------------------------------------------------------------------------
*/

defined('_JEXEC') or die;
$class_cssArrows='';
if ($cssArrows===true) {
	$class_cssArrows='sf-arrows';
}
?>
	
<div class="mod_ext_superfish_menu">
	
	<ul class="nav sf-menu <?php echo $class_style;?> <?php echo $class_cssArrows;?> <?php echo $class_sfx;?>"<?php
		$tag = '';
		if ($params->get('tag_id')!=NULL) {
			$tag = $params->get('tag_id').'';
			echo ' id="'.$tag.'"';
		}
	?>>
	<?php
	foreach ($list as $i => &$item) :
		$class = 'item-'.$item->id;
		if ($item->id == $active_id) {
			$class .= ' current';
		}

		if (in_array($item->id, $path)) {
			$class .= ' active';
		}
		elseif ($item->type == 'alias') {
			$aliasToId = $item->params->get('aliasoptions');
			if (count($path) > 0 && $aliasToId == $path[count($path)-1]) {
				$class .= ' active';
			}
			elseif (in_array($aliasToId, $path)) {
				$class .= ' alias-parent-active';
			}
		}

		if ($item->deeper) {
			$class .= ' deeper';
		}

		if ($item->parent) {
			$class .= ' parent';
		}

		if (!empty($class)) {
			$class = ' class="'.trim($class) .'"';
		}

		echo '<li'.$class.'>';

		// Render the menu item.
		switch ($item->type) :
			case 'separator':
			case 'url':
			case 'component':
				require JModuleHelper::getLayoutPath('mod_ext_superfish_menu', 'default_'.$item->type);
				break;

			default:
				require JModuleHelper::getLayoutPath('mod_ext_superfish_menu', 'default_url');
				break;
		endswitch;

		// The next item is deeper.
		if ($item->deeper) {
			echo '<ul>';
		}
		// The next item is shallower.
		elseif ($item->shallower) {
			echo '</li>';
			echo str_repeat('</ul></li>', $item->level_diff);
		}
		// The next item is on the same level.
		else {
			echo '</li>';
		}
	endforeach;
	?></ul>
	
	
	<?php if ($ext_menu == 1) { ?>
	<script type="text/javascript"> 
	   jQuery(document).ready(function(){ 
			jQuery("ul.sf-menu").superfish({ 
				animation:  <?php echo $animation; ?>,
				delay:      <?php echo $delay; ?>,
				speed:      '<?php echo $speed; ?>',
				<?php if ($ext_style_menu == 2) { ?>
				pathClass:	'current',
				<?php } ?>
				cssArrows: <?php echo $cssArrows; ?>
			}); 
		}); 
	</script>
	<?php } ?>
	<div style="clear: both;"></div>
</div>