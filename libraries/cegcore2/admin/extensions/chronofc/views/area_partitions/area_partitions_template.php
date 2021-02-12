<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<?php
	if(!empty($view['sections'])){
		list($sections) = $this->Parser->multiline($view['sections']);
		
		foreach($sections as $section){
			if(!empty($section['value'])){
				echo '<h3 style="padding:0px 7px;">'.$section['value'].'</h3>';
			}
			echo $this->Parser->template($view['name'].'/'.$section['name']);
		}
	}