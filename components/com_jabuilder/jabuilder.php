<?php 
/**
 * ------------------------------------------------------------------------
 * JA Builder Package
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2018 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - Copyrighted Commercial Software
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites:  http://www.joomlart.com -  http://www.joomlancers.com
 * This file may not be redistributed in whole or significant part.
 * ------------------------------------------------------------------------
 */

defined('_JEXEC') or die;

JLoader::register('JabuilderHeper', JPATH_ADMINISTRATOR. '/components/com_jabuilder/helpers/jabuilder.php');

$controller = JControllerLegacy::getInstance('jabuilder');

$input = JFactory::getApplication()->input;
// by pass other task
$input->del('layout', 'default');
$input->set('view', 'page');

$controller->execute($input->getCmd('task'));

$controller->redirect();
