<?php

defined('_JEXEC') or die('Restricted access');

JModelLegacy::addIncludePath(COM_FABRIK_FRONTEND . '/models', 'FabrikFEModel');

require_once __DIR__ . '/helper.php';

$mod_esan_eventos = new ModEsanEventos($params);
$mod_esan_eventos->dump();
