<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<?php
	if(!empty($function['var_name'])){
		echo $this->Parser->parse('{debug:'.$function['var_name'].'}');
	}else{
		echo $this->Parser->parse('{debug:}');
	}