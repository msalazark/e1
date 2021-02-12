<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<?php
	
	$_result = [];
	$_errors = [];
	$_debug = [];
	
	$upload_save_file = function($name, $extensions, $path, $max_size, $errors) use ($function, &$_errors){
		$pathinfo = pathinfo(\G2\L\Upload::get($name, 'name'));
		if(empty($pathinfo['filename'])){
			return false;
		}
		$ext = $pathinfo['extension'];
		$fname = $pathinfo['filename'];
		
		if(!in_array(strtolower($ext), $extensions)){
			$_errors[] = $this->Parser->parse($errors['file_extension_error']);
			return false;
		}
		
		if(\G2\L\Upload::get($name, 'size')/1000 > (int)$max_size){
			$_errors[] = $this->Parser->parse($errors['max_size_error']);
			return false;
		}
		
		if(!empty($function['filename_provider'])){
			$this->set($function['name'].'.file.fullname', \G2\L\Upload::get($name, 'name'));
			$this->set($function['name'].'.file.name', $fname);
			$this->set($function['name'].'.file.extension', $ext);
			
			$vfilename = $this->Parser->parse($function['filename_provider']);
		}else{
			$fname = \G2\L\Str::slug($fname);
			
			$fname = \G2\L\Dater::datetime('YmdHis').'_'.$fname;
			$vfilename = $fname.'.'.$ext;
		}
		
		$target = $path.$vfilename;
		
		$saved = \G2\L\Upload::save(\G2\L\Upload::get($name, 'tmp_name'), $target);
		
		if($saved){
			$return = [];
			$return['path'] = $target;
			$return['filename'] = $vfilename;
			$return['name'] = \G2\L\Upload::get($name, 'name');
			$return['size'] = filesize($target);
			
			return $return;
		}
	};
	
	//if(!empty($function['config'])){
		
		//list($configs) = $this->Parser->multiline($function['config']);
		
	if(!empty($function['path'])){
		$path = trim($function['path']);
		$path = $this->Parser->parse($path);
		$path = str_replace(array('/', '\\'), DS, $path);
		$path = rtrim($path, DS).DS;
	}else{
		$path = \G2\Globals::ext_path(\GApp::instance()->extension, 'front').'uploads'.DS;
	}
	
	$this->Parser->debug[$function['name']]['path'] = $path;
	
	if(!file_exists($path)){
		$_errors[] = rl('Destination directory not available.');
	}else if(!is_writable($path)){
		$_errors[] = rl('Destination directory not writable.');
	}
	
	if(!empty($_errors)){
		$this->Parser->messages['error'][$function['name']] = $_errors;
		$this->set($function['name'], false);
		$this->Parser->fevents[$function['name']]['fail'] = true;
		return;
	}
	
	$attachments = [];
	if(!empty($function['extensions'])){
		$g_extensions = explode(',', trim($function['extensions']));
	}
	
	if(!empty($function['autofields'])){
		$connection = $this->Parser->_connection();
		
		$stored = \GApp::session()->get($connection['alias'].'.inputs', []);
		
		if(!empty($stored)){
			foreach($stored as $view_name => $field){
				//foreach($fields as $k => $field){
					if(isset($field['name']) AND !empty($field['dynamics']['upload'])){
						$fname = $this->Parser->dpath($field['name']);
						/*
						if(strpos($fname, '[n]') !== false){
							$keys = array_keys(\G2\L\Upload::get($name, 'name'));
							
						}
						*/
						$new_line = $fname.
						(!empty($field['max_size']) ? '/'.$field['max_size'] : '').
						(!empty($field['extensions']) ? ':'.$field['extensions'] : '').
						(!empty($field['errors']) ? '/'.$field['errors'] : '');
						
						$function['config'] = $function['config']."\n".$new_line;
						
						if(!empty(\GApp::session()->get($connection['alias'].'.attach.'.$view_name))){
							//\GApp::session()->set($connection['alias'].'.attach.'.$view_name.'.path', '{var:'.$function['name'].'.'.$fname.'.path}');
						}
						
					}
				//}
			}
		}
		
	}
	
	$processed = [];
	
	$upload_set_data = function($returned, $name) use (&$_result, $function){
		if(!empty($returned)){
			//$_result[$name] = $returned;
			$_result = \G2\L\Arr::setVal($_result, $name, $returned);
			//$this->data[$name] = $returned['filename'];
			$this->data($name, $returned['filename'], true);
			$this->Parser->debug[$function['name']][$name]['saved'] = 1;
			return true;
		}else{
			//$_result = false;
			$_result = \G2\L\Arr::setVal($_result, $name, []);
			$this->Parser->debug[$function['name']][$name]['saved'] = 0;
			//break;
			return false;
		}
	};
	
	$_return = true;
	
	if(!empty($function['config'])){
		list($configs) = $this->Parser->multiline($function['config']);
		
		foreach($configs as $k => $config){
			$name = $config['name'];
			if(in_array($name, $processed)){
				continue;
			}
			$processed[] = $name;
			
			$extensions = [];
			
			if(!empty($config['value'])){
				$extensions = explode(',', $config['value']);
			}
			
			if(empty($extensions) AND !empty($g_extensions)){
				$extensions = $g_extensions;
			}
			
			$max_size = !empty($config['namep']) ? $config['namep'] : $function['max_size'];
			$errors = !empty($config['valuep']) ? json_decode($config['valuep'], true) : [
				'max_size_error' => $function['max_size_error'], 
				'file_extension_error' => $function['file_extension_error']
			];
			
			$this->Parser->debug[$function['name']][$name]['extensions'] = $extensions;
			
			if(empty($extensions) OR empty($name) OR empty(\G2\L\Upload::get($name, 'name'))){
				$this->Parser->debug[$function['name']][$name]['info'] = rl('File is not present.');
				
				continue;
			}
			
			if(strpos($name, '[n]') !== false){
				$files_names = \G2\L\Upload::get($name, 'name');
				//$keys = array_keys($files_names);
				//foreach($keys as $key){
				foreach($files_names as $key => $files){
					//$sub_name = str_replace('[n]', $key, $name);
					$sub_name = implode($key, explode('[n]', $name, 2));
					
					if(!is_array($files)){
						$returned = $upload_save_file($sub_name, $extensions, $path, $max_size, $errors);
						$_return = $upload_set_data($returned, $sub_name);
						if($_return === false){
							break 2;
						}
					}else{
						foreach($files as $kf => $file){
							$sub_name2 = implode($kf, explode('[n]', $sub_name, 2));
							
							$returned = $upload_save_file($sub_name2, $extensions, $path, $max_size, $errors);
							$_return = $upload_set_data($returned, $sub_name2);
							if($_return === false){
								break 3;
							}
						}
					}
				}
			}else{
				$returned = $upload_save_file($name, $extensions, $path, $max_size, $errors);
				$_return = $upload_set_data($returned, $name);
				if($_return === false){
					break;
				}
			}
			
		}
		
	}else{
		$_errors[] = rl('Files config is empty');
		$_result = false;
	}
	
	if(!empty($_errors)){
		$this->Parser->messages['error'][$function['name']] = $_errors;
		$_result = false;
	}
	
	if(!empty($function['autofields'])){
		$connection = $this->Parser->_connection();
		
		$stored = \GApp::session()->get($connection['alias'].'.inputs', []);
		
		if(!empty($stored)){
			foreach($stored as $view_name => $field){
				if(isset($field['name']) AND !empty($field['dynamics']['attach'])){
					$fname = $this->Parser->dpath($field['name']);
					
					if(!empty(\GApp::session()->get($connection['alias'].'.inputs.'.$view_name))){
						$path = \G2\L\Arr::getVal($_result, $fname.'.path', []);
						if(!empty($path)){
							\GApp::session()->set($connection['alias'].'.inputs.'.$view_name.'.path', $path);
						}
					}
					
				}
			}
		}
	}
	
	$this->set($function['name'], $_result);
	
	if($_return === false){
		$this->Parser->fevents[$function['name']]['fail'] = true;
	}else{
		$this->Parser->fevents[$function['name']]['success'] = true;
	}