<?php
/*
# ------------------------------------------------------------------------
# Extensions for Joomla 2.5 - Joomla 3.x
# ------------------------------------------------------------------------
# Copyright (C) 2011-2014 Ext-Joom.com. All Rights Reserved.
# @license - PHP files are GNU/GPL V2.
# Author: Ext-Joom.com
# Websites:  http://www.ext-joom.com 
# Date modified: 17/01/2014 - 13:00
# ------------------------------------------------------------------------
*/
/**
 * @package		Joomla.Site
 * @subpackage	mod_menu
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
error_reporting(E_ALL & ~E_NOTICE);

// Include the syndicate functions only once
require_once (JPATH_SITE.'/modules/mod_menu/helper.php'); 

$list	= modMenuHelper::getList($params);
$app	= JFactory::getApplication();
$menu	= $app->getMenu();
$active	= $menu->getActive();
$active_id = isset($active) ? $active->id : $menu->getDefault()->id;
$path	= isset($active) ? $active->tree : array();
$showAll	= $params->get('showAllChildren');
$class_sfx	= htmlspecialchars($params->get('class_sfx'));

$ext_style_menu	= $params->get('stylemenu', 0);
$ext_menu		= (int)$params->get('ext_menu');
$ext_load_jquery= (int)$params->get('ext_load_jquery', 1);
$ext_jquery_ver	= $params->get('ext_jquery_ver', '1.9.1');
$ext_load_base	= (int)$params->get('ext_load_base', 1);
$animation		= $params->get('animation');
$delay			= $params->get('delay', 800);
$speed			= $params->get('speed', 'normal');
$cssArrows		= $params->get('autoarrows');

$document 		= JFactory::getDocument();
$document->addStyleSheet(JURI::base() . 'modules/mod_ext_superfish_menu/assets/css/superfish.css');
$class_style 	= '';
if ($ext_style_menu == 1) {
	$document->addStyleSheet(JURI::base() . 'modules/mod_ext_superfish_menu/assets/css/superfish-vertical.css'); 
	$class_style='sf-vertical'; 
}
if ($ext_style_menu == 2) { 
	$document->addStyleSheet(JURI::base() . 'modules/mod_ext_superfish_menu/assets/css/superfish-navbar.css');
	$class_style='sf-navbar';
}	

	
if ($ext_menu == 1) 
{
$ext_script = <<<SCRIPT


var SjQ = false;
function initJQ() {
	if (typeof(jQuery) == 'undefined') {
		if (!SjQ) {
			SjQ = true;
			document.write('<scr' + 'ipt type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/$ext_jquery_ver/jquery.min.js"></scr' + 'ipt>');
		}
		setTimeout('initJQ()', 500);
	}
}
initJQ(); 

 if (jQuery) jQuery.noConflict(); 

SCRIPT;

	if ($ext_load_jquery  > 0) {
		$document->addScriptDeclaration($ext_script);		
	}
	if ($ext_load_base > 0) {
		$document->addCustomTag('<script type = "text/javascript" src = "'.JURI::root().'modules/mod_ext_superfish_menu/assets/js/hoverIntent.js"></script>');	
		$document->addCustomTag('<script type = "text/javascript" src = "'.JURI::root().'modules/mod_ext_superfish_menu/assets/js/superfish.js"></script>');		
	}
}


if(count($list)) {
	require JModuleHelper::getLayoutPath('mod_ext_superfish_menu', $params->get('layout', 'default'));
	echo JText::_(COP_JOOMLA);
}
