<?php
/**
*
* @package  seftranslate
* @copyright 2011 Andrey Kvasnevskiy-OrdaSoft(akbet@mail.ru); Rob de Cleen(rob@decleen.com);
* Homepage: http://www.ordasoft.com
* @version: 5.0 free
* @license GNU General Public license version 2 or later; see LICENSE.txt
*
**/


defined('_JEXEC') or die('Restricted access');

$filename = JPATH_BASE .'/components/com_seftranslate/seftranslate.php';

if (!file_exists($filename)) {
    echo "Please install SefTranslate component ";
    exit;
}


$remeber_language=$params->get('remeber_language','1');
$show_lang=$params->get('use_lang','');
$show_list_or_text_or_text_with_flag=$params->get('lang_dropdown_list_or_plain_text_list','');
$show_flag_lang=$params->get('use_flag_lang');
$show_langs_direction=$params->get('direction');
$trans_metod=$params->get('trans_metod');
$use_sef=$params->get('use_sef');
$alertnottransl=$params->get('alertnottransl');
$msgnottransl=$params->get('msgnottransl');
$hide_module_sef=$params->get('hide_module_sef');
$flag_size=$params->get('flag_size');
$flag_type=$params->get('flag_type');
$mod_position = $params->get('mod_position','');
if($mod_position != 'mod_position_default'){
    $show_lang = array();
}
$isUseSefTranslateImage = $params->get('isUseSefTranslateImage',1);

$main_url = $_SERVER['HTTP_HOST'];

if($_SERVER['SERVER_PORT'] != '80')
    $main_url = substr($main_url, 0, strpos($main_url, ':'));


$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));
/*
 $g = getCWD();

$g = str_replace("\\", "/" , $g);
$mas = explode("/",$g);
$dir_name = $mas[(int)count($mas)-1]; */
require JModuleHelper::getLayoutPath('mod_seftranslate', $params->get('layout', 'default'));
