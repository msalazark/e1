<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<?php
	if(!empty($function['event_name'])){
		echo $this->Parser->parse('{event:'.$function['event_name'].'}');
	}
	
	if(!empty($function['stop'])){
		echo $this->Parser->parse('{stop:}');
	}