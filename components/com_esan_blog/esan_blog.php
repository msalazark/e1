<?php

defined('_JEXEC') or die;

JModelLegacy::addIncludePath(COM_FABRIK_FRONTEND . '/models', 'FabrikFEModel');

$controller = JControllerLegacy::getInstance('Esan_Blog');
$controller->execute(JFactory::getApplication()->input->getCmd('task'));
$controller->redirect();
