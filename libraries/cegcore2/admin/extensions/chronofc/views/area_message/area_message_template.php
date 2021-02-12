<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<?php
	if(!empty($view['header'])){
		echo '<h3 style="padding:0px 7px;">'.$view['header'].'</h3>';
	}
	echo $this->Parser->template($view['name'].'/body');