<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<?php
	$connection = $this->get('__connection');
	$event = $this->get('__event');
	//check if event does not exist
	if(!isset($connection['events'][$event])){
		if(!empty($connection['params']['event_not_found'])){
			echo $this->Parser->parse($connection['params']['event_not_found']);
			return;
		}else{
			$event = '404';
		}
	}
	//check permissions
	if(!empty($connection['events'][$event]['rules'])){
		$rules = array_filter($connection['events'][$event]['rules']['access']);
		if(!empty($rules)){
			$owner_id = !empty($connection['events'][$event]['owner_id']) ? $this->parse($connection['events'][$event]['owner_id']) : null;
			
			if(\GApp::access($connection['events'][$event]['rules'], 'access', $owner_id) !== true){
				if(!empty($connection['events']['403'])){
					$event = '403';
				}else{
					if(!empty($connection['events'][$event]['access_denied'])){
						echo $this->Parser->parse($connection['events'][$event]['access_denied']);
					}
					return;
				}
			}
		}
	}
	
	$output = $this->Parser->parse('{event:'.$event.'}');
	
	if(!empty($this->errors)){
		\GApp::session()->flash('error', $this->errors);
	}
	
	if(!empty($this->Parser->messages)){
		foreach($this->Parser->messages as $type => $messages){
			\GApp::session()->flash($type, $messages);
		}
	}
	
	echo \G2\H\Message::render(\GApp::session()->flash());
	
	echo $output;