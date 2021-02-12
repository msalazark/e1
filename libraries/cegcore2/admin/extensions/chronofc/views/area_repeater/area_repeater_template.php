<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<?php
	if(!empty($view['model'])){
		$this->set('_repeater_model', $view['model']);
		echo '<?php foreach($this->data("'.$view['model'].'", []) as $k =>  $item): ?>';
		echo "\n";
		echo '<h3 style="padding:0px 7px;">'.$view['model'].' #<?php echo $k; ?></h3>';
		echo "\n";
		echo $this->Parser->template($view['name'].'/body');
		echo "\n";
		echo '<?php endforeach; ?>';
		$this->set('_repeater_model', null);
	}