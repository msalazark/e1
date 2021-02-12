<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<?php
	
	$_result = true;
	$_fail_event = true;
	$_errors = [];
	$_debug = [];
	
	if(empty($function['data_provider'])){
		$function['data_provider'] = '{data:}';
	}
	$data = (array)$this->Parser->parse($function['data_provider']);
	
	$rulesMap = [
		'empty' => 'required',
		'checked' => 'required',
		'integer' => 'is_integer',
		'regExp' => 'regex',
		'minChecked' => 'minCount',
		'maxChecked' => 'maxCount',
		'exactChecked' => 'exactCount',
	];
	
	$validator = new \G2\L\Validate();
	
	$validate_fields_check = function($fname, $rule, $ruledata) use ($validator, $data){
		if(!is_null($ruledata)){
			if(in_array($rule, ['match', 'different'])){
				$ruledata = \G2\L\Arr::getVal($data, $ruledata);
			}
			$condition = (bool)$validator::$rule(\G2\L\Arr::getVal($data, $fname), $ruledata);
		}else{
			$condition = (bool)$validator::$rule(\G2\L\Arr::getVal($data, $fname));
		}
		
		return $condition;
	};
	
	$validate_fields_error = function($field, $fname, $counter) use ($function, &$_result, &$_fail_event){
		$_result = false;
		$error_message = !empty($field['verror']) ? $field['verror'] : (!empty($field['label']) ? $field['label'] : $function['error_message']);
		$error_message = $this->Parser->parse($error_message);
		$error_message = str_replace('-N-', $counter, $error_message);
		$_fail_event = $field['event'];
		
		$errors[] = $error_message;
		//$_result = false;
		
		if(!empty($function['list_errors'])){
			$this->errors = \G2\L\Arr::setVal($this->errors, $fname, $error_message);
			//break;
		}
	};
	
	$this->Parser->debug[$function['name']]['log'] = rl('Automatic validation enabled.');
	
	$connection = $this->Parser->_connection();
	$stored = \GApp::session()->get($connection['alias'].'.inputs', []);
	
	
	if(!empty($function['fields_list'])){
		$fields_list = explode("\n", trim($function['fields_list']));
		$fields_list = array_map('trim', $fields_list);
		
		if($function['fields_selection'] == 'include'){
			foreach($stored as $view_name => $field){
				//foreach($fields as $k => $field){
				$fname = $this->Parser->dpath($field['name']);
				if(!in_array($fname, $fields_list)){
					unset($stored[$view_name]);
				}
				//}
			}
		}else if($function['fields_selection'] == 'exclude'){
			foreach($stored as $view_name => $field){
				//foreach($fields as $k => $field){
				$fname = $this->Parser->dpath($field['name']);
				if(in_array($fname, $fields_list)){
					unset($stored[$view_name]);
				}
				//}
			}
		}
	}
	
	//$fevents = \GApp::session()->get($connection['alias'].'.fevents', []);
	
	if(!empty($stored) AND is_array($stored)){
		//foreach($stored as $vfieldname => $vfstatus){
		foreach($stored as $view_name => $field){
			if(empty($field['name'])){
				continue;
			}
			//foreach($fields as $k => $field){
				$fname = $this->Parser->dpath($field['name']);
				
				//$field['validation'] = array_filter($field['validation']);
				if(!empty($field['validation'])){
					if(!empty($field['validation']['disabled'])){
						continue;
					}
					if(!empty($field['validation']['optional']) AND strlen(\G2\L\Arr::getVal($data, $fname)) == 0){
						continue;
					}
					
					foreach($field['validation'] as $rule => $ruledata){
						
						if(!empty($rulesMap[$rule])){
							$rule = $rulesMap[$rule];
						}
						
						if(!method_exists($validator, $rule)){
							continue;
						}
						
						if(!empty($field['multiplier'])){
							$values = \G2\L\Arr::getVal($data, $fname, []);
							foreach($values as $key => $value){
								$nfname = str_replace('[n]', $key, $fname);
								$condition = $validate_fields_check($nfname, $rule, $ruledata);
								
								if($condition !== true){
									$validate_fields_error($field, $nfname, $key);
								}
							}
						}else{
							$condition = $validate_fields_check($fname, $rule, $ruledata);
							if($condition !== true){
								$validate_fields_error($field, $fname, 0);
							}
						}
						
					}
				}
				
			//}
			//\GApp::session()->set($connection['alias'].'.fields.validated', true);
		}
		
		
	}else if(!empty($stored) AND $stored === true){
		$_result = true;
	}else{
		//$_result = false;
		
		if(!empty($function['list_errors'])){
			//$this->errors = \G2\L\Arr::setVal($this->errors, $function['name'], $function['error_message']);
		}
	}
	
	//\GApp::session()->clear($connection['alias'].'.fields');
	
	$this->set($function['name'], $_result);
	
	if(empty($_result)){
		$this->Parser->fevents[$function['name']]['fail'] = $_fail_event;//true;
	}else{
		$this->Parser->fevents[$function['name']]['success'] = true;
	}