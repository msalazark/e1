<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<?php
	$field_class = $this->Field->setup('textarea', $view);
	
	if(!empty($view['editor']['enabled'])){
		\GApp::document()->_('tinymce');
	}
	
	echo $this->Html->input('textarea')->field($field_class);