<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<?php
	if(!empty($view['rows'])){
		foreach($view['rows'] as $rk => $row){
			foreach($row['columns'] as $ck => $column){
				$section_name = $view['sections'][$rk.'_'.$ck]['name'];
				echo $this->Parser->template($view['name'].'/'.$section_name);
			}
		}
	}