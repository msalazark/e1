<?php

defined('_JEXEC') or die('Restricted access');

JModelLegacy::addIncludePath(COM_FABRIK_FRONTEND . '/models', 'FabrikFEModel');

require_once __DIR__ . '/helper.php';

$mod_esan_blog = new ModEsanBlogHelper($params);
$mod_esan_blog->loadData();
$mod_esan_blog->dump();
