<?php
/**
* ChronoCMS version 1.0
* Copyright (c) 2012 ChronoCMS.com, All rights reserved.
* Author: (ChronoCMS.com Team)
* license: Please read LICENSE.txt
* Visit http://www.ChronoCMS.com for regular updates and information.
**/
namespace G2\A\E\Chronofc\H;
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
class Parser extends \G2\L\Helper{
	var $View;
	var $connections = [];
	var $plugins = [];
	var $functions = [];
	var $views = [];
	var $blocks = [];
	var $locales = [];
	var $active_block = null;
	
	var $messages = [];
	var $debug = [];
	var $info = [];
	var $fevents = [];
	var $stopped = false;
	var $viewslimit = 0;
	
	var $pattern = '/{(event|view|function|fn|block|plugin|plg|section|connection|chronoform|var|data|date|user|value|val|session|redirect|page|path|error|success|info|warning|stop|end|lang|language|l|uuid|rand|ip|url|debug)([\/|\.][^:]+)?:([^}]*?)}/i';
	var $pattern2 = '/\((var|data|date|user|value|val|session|lang|language|l|uuid|rand|ip)([\/|\.][^:]+)?:([^}]*?)\)/i';
	
	function __construct(&$_view = null, $settings = []){
		parent::__construct($_view);
		
		$this->View = $_view;
		
		if(empty($settings['mode']) OR $settings['mode'] != 'basic'){
			$connection = $this->_connection();
			$blocks = $this->_blocks();
			$this->setup($connection, $blocks);
		}
	}
	
	function _connection($val = ''){
		if(empty($val)){
			return $this->get('__connection');
		}else{
			return $this->get('__connection.'.$val);
		}
	}
	
	function _blocks(){
		return $this->get('__blocks', []);
	}
	
	function _event($type = null){
		if(empty($type)){
			return $this->get('__event');
		}else if($type == 'active'){
			$active = \GApp::session()->get($this->get('__connection')['alias'].'.active_event', false);
			if($active){
				return $active;
			}
			return $this->get('__active_event', $this->get('__event'));
		}else if($type == 'default'){
			//return $this->get('__connection.params.default_event');
			return $this->get('__default_event');
		}
	}
	
	function setup($connection, $blocks = []){
		$this->plugins = [];
		$this->functions = [];
		$this->views = [];
		$this->blocks = [];
		$this->locales = [];
		
		if(!empty($connection['plugins'])){
			foreach($connection['plugins'] as $key => $plugin){
				$this->plugins[$plugin['name']] = $plugin;
			}
		}
		
		if(!empty($connection['functions'])){
			foreach($connection['functions'] as $key => $function){
				$this->functions[$function['name']] = $function;
			}
		}
		
		if(!empty($connection['views'])){
			foreach($connection['views'] as $key => $view){
				$this->views[$view['name']] = $view;
			}
		}
		
		if(!empty($blocks)){
			foreach($blocks as $key => $block){
				$this->blocks[$block['Block']['id']] = $block['Block'];
			}
		}
		//pr($this->blocks);
		
		if(!empty($connection['locales'])){
			foreach($connection['locales'] as $ltag => $ldata){
				if(!empty($ldata['content'])){
					//fix a common user error
					$ltag = strtoupper(str_replace('-', '_', $ltag));
					
					$options = explode("\n", trim($ldata['content']));
					$options = array_map('trim', $options);
					
					foreach($options as $option){
						$option_data = explode('=', $option, 2);
						$this->locales[$ltag][$option_data[0]] = !empty($option_data[1]) ? $option_data[1] : $option_data[0];
					}
					
				}
			}
		}
	}
	
	public function parse($code, $eval = false, $pat = 1){
		$return = true;
		
		if(!is_string($code)){
			return $code;
		}
		
		$output = $code;
		
		if($eval){
			ob_start();
			eval('?>'.$code);
			$output = ob_get_clean();
		}
		
		if($pat == 2){
			preg_match_all($this->pattern2, $output, $matches);
		}else{
			preg_match_all($this->pattern, $output, $matches);
		}
		
		if(!empty($matches[0])){
			
			$tags = $matches[0];
			$value_required = ($return === true);
			
			if($value_required AND count($tags) > 1 AND (strpos($code, '|') !== false OR strpos($code, '&') !== false OR strpos($code, '+') !== false OR strpos($code, '-') !== false OR strpos($code, 'U') !== false)){
				//operator used
				//if(substr_count($code, '|') == count($tags) - 1){
				if(substr_count($code, '|') + strlen(implode('', $tags)) == strlen(trim($code))){
					$parts = explode('|', trim($code));
					foreach($parts as $k => $part){
						$return_value = $this->parse($part);
						if(!is_null($return_value)){
							return $return_value;
						}
					}
				}
				
				if(substr_count($code, '&') + strlen(implode('', $tags)) == strlen(trim($code))){
					$fullArray = [];
					$parts = explode('&', trim($code));
					foreach($parts as $k => $part){
						$return_value = $this->parse($part);
						$fullArray = array_replace_recursive($fullArray, (array)$return_value);
					}
					return $fullArray;
				}
				
				if(substr_count($code, '+') + strlen(implode('', $tags)) == strlen(trim($code))){
					$fullArray = [];
					$parts = explode('+', trim($code));
					foreach($parts as $k => $part){
						$return_value = $this->parse($part);
						$fullArray = array_merge_recursive($fullArray, (array)$return_value);
					}
					return $fullArray;
				}
				
				if(substr_count($code, '-') + strlen(implode('', $tags)) == strlen(trim($code))){
					$fullArray = [];
					$first_tag = array_shift($tags);
					$fullArray = $this->parse($first_tag);
					
					$parts = explode('-', trim($code));
					foreach($parts as $k => $part){
						$return_value = $this->parse($part);
						$fullArray = array_diff($fullArray, (array)$return_value);
					}
					return $fullArray;
				}
				
				if(substr_count($code, 'U') + strlen(implode('', $tags)) == strlen(trim($code))){
					$fullArray = [];
					$parts = explode('U', trim($code));
					foreach($parts as $k => $part){
						$return_value = $this->parse($part);
						if(!in_array($return_value, $fullArray)){
							$fullArray = array_merge_recursive($fullArray, (array)$return_value);
						}
					}
					return $fullArray;
				}
			}
			
			$single_tag_required = ($return === true AND count($tags) == 1 AND strlen($tags[0]) == strlen(trim($code)));
			
			foreach($tags as $k => $tag){
				$type = $matches[1][$k];
				$method = ltrim($matches[2][$k], '/.');
				$name = $matches[3][$k];
				
				if($type == 'fn'){
					$type = 'function';
				}
				
				if($type == 'plg'){
					$type = 'plugin';
				}
				
				if($type == 'val'){
					$type = 'value';
				}
				
				if($type == 'l'){
					$type = 'lang';
				}
				
				if($this->stopped === true){
					$output = str_replace($tag, '', $output);
					continue;
				}
				
				//$value_required = ($return === true AND count($tags) == 1 AND strlen($tag) == strlen(trim($code)));
				
				if($type == 'event'){
					$result = $this->event($name);
					
				}else if($type == 'connection'){
					$event = null;
					
					$name = $this->params($name, true);
					
					if(strpos($name, '/') !== false){
						list($name, $event) = explode('/', $name);
					}
					$result = $this->connection($name, $event, $method);
					
				}else if($type == 'chronoform'){
					$event = null;
					
					$name = $this->params($name, true);
					
					if(strpos($name, '/') !== false){
						list($name, $event) = explode('/', $name);
					}
					$result = $this->chronoform($name, $event, $method);
				
				}else if($type == 'section'){
					$result = $this->section($name, $method);
					
				}else if($type == 'plugin'){
					$result = $this->plugin($name, $method);
					
				}else if($type == 'function'){
					$result = $this->fn($name, $method);
					
				}else if($type == 'view'){
					$result = $this->view($name);
					
				}else if($type == 'block'){
					$result = $this->block($name);
				
				}else if($type == 'lang'){
					$result = $this->lang($name);
					
				}else if($type == 'var'){
					list($name, $default) = $this->varInfo($name);
					$default = $this->parse($default, false, 2);
					
					$result = $this->get($name, $default);
					
					if($method == 'clear'){
						$this->set($name, null);
						$result = null;
					}else if($method == 'set'){
						list($name, $params) = $this->params($name);
						$result = array_pop($params);
						$this->set($name, $result);
						$result = null;
					}else{
						$result = $this->methodInfo($method, $result);
					}
				
				}else if($type == 'data'){
					list($name, $default) = $this->varInfo($name);
					$default = $this->parse($default, false, 2);
					
					$result = $this->data($name, $default);
					$result = str_replace('<?php', '< ?php', $result);
					
					if($method == 'clear'){
						$this->data[$name] = null;
						$result = null;
					}else if($method == 'set'){
						list($name, $params) = $this->params($name);
						$result = array_pop($params);
						$this->data[$name] = $result;
						$result = null;
					}else{
						$result = $this->methodInfo($method, $result);
					}
				
				}else if($type == 'value'){
					$result = $this->value($name);
				
				}else if(in_array($type, ['error', 'success', 'info', 'warning'])){
					$result = $this->message($type, $name);
				
				}else if($type == 'redirect'){
					$result = $this->redirect($name);
				
				}else if($type == 'url'){
					$result = $this->url($name, ($method == 'full'));
				
				}else if($type == 'page'){
					$result = $this->page($name);
					
				}else if($type == 'path'){
					if(empty($name)){
						$name = \GApp::instance()->site;
					}
					if($method == 'url'){
						$result = \G2\Globals::ext_url(\GApp::instance()->extension, $name);
					}else{
						if($name == 'root'){
							$result = \G2\Globals::get('ROOT_PATH');
						}else{
							$result = \G2\Globals::ext_path(\GApp::instance()->extension, $name);
						}
					}
					$result = rtrim($result, DS);
				
				}else if($type == 'date'){
					list($name, $params) = $this->params($name);
					
					if(empty($name)){
						$name = 'Y-m-d H:i:s';
					}
					
					$method = !empty($method) ? $method : 'utc';
					
					if(!empty($params)){
						$result = \G2\L\Dater::datetime($name, array_pop($params), $method);
					}else{
						$result = \G2\L\Dater::datetime($name, null, $method);
					}
				
				}else if($type == 'session'){
					list($name, $default) = $this->varInfo($name);
					$default = $this->parse($default, false, 2);
					
					$result = \GApp::session()->get($name, $default);
					
					if($method == 'clear'){
						\GApp::session()->clear($name);
						$result = null;
					}else if($method == 'set'){
						list($name, $params) = $this->params($name);
						$result = array_pop($params);
						\GApp::session()->set($name, $result);
						$result = null;
					}else{
						$result = $this->methodInfo($method, $result);
					}
				
				}else if($type == 'user'){
					$result = \GApp::user()->get($name);
				
				}else if($type == 'language'){
					$result = \G2\L\Config::get('site.language');
					
					if($name == 'short'){
						$langs = explode('_', $result);
						$result = $langs[0];
					}
					
				}else if($type == 'debug'){
					if(!empty($this->debug)){
						foreach($this->debug as $dname => $dval){
							$this->debug[$dname]['var'] = $this->get($dname);
							if(!empty($this->debug[$dname]['recipients']) AND !empty($this->debug[$dname]['body'])){
								echo '<h3 class="ui header dividing">'.$dname.' Body</h3>';
								echo $this->debug[$dname]['body'];
							}
						}
					}
					if(empty($name) OR !isset($this->debug[$name])){
						$result = pr($this->data, true).pr($this->debug, true);
					}else{
						$result = pr($this->debug[$name], true);
					}
					
				}else if($type == 'uuid'){
					$result = \G2\L\Str::uuid();
					
				}else if($type == 'rand'){
					if(!empty($name) AND is_numeric($name)){
						$first = str_repeat('%04X', ceil((float)$name/4));
						$result = substr(sprintf($first, mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)), 0, $name);
					}else{
						$result = mt_rand();
					}
					
				}else if($type == 'ip'){
					$result = $_SERVER['REMOTE_ADDR'];
					
				}else{
					if($type == 'stop' OR $type == 'end'){
						$this->stopped = true;
						$result = '';
					}else{
						$result = '';
					}
				}
				
				if($single_tag_required == true){
					
					if(is_string($result)){
						$result = str_replace($tag, $result, $output);
					}
					
					return $result;
					
				}else{
					if(is_array($result)){
						$result = json_encode($result, JSON_UNESCAPED_UNICODE);
					}
				}
				
				//$output = str_replace($tag, $result, $output);
				$output = substr_replace($output, $result, strpos($output, $tag), strlen($tag));
			}
			
		}
		
		if($return === true){
			return $output;
		}else{
			echo $output;
		}
	}
	
	function value($name){
		//eval('$newValue = '.str_replace(['(', ')'], '', substr($name, 0, 10)).';');
		//return $newValue;
		if(strpos($name, '[') === 0 AND strpos($name, ':') !== false){
			//associative array
			$name = trim($name, '[]');
			$name = '{'.$name.'}';
		}
		
		$newValue = json_decode($name, true);
		
		if(is_null($newValue) AND strtolower($name) != 'null'){
			return $name;
		}
		
		return $newValue;
	}
	
	function varInfo($name){
		$default = null;
		if(strpos($name, '/') !== false){
			$__name = explode('/', $name);
			$name = $__name[0];
			$default = $this->value($__name[1]);
		}
		
		return [$name, $default];
	}
	
	function methodInfo($method, $result){
		if(!empty($method)){
			if(strpos($method, '[') !== false){
				$pcs = explode('[', $method);
				$params = explode(';', rtrim($pcs[1], ']'));
				$method = $pcs[0];
			}
			
			if($method == 'count'){
				$result = count($result);
			}else if($method == 'strlen' OR $method == 'length'){
				$result = strlen($result);
			}else if($method == 'empty'){
				$result = empty($result);
			}else if($method == 'sum'){
				$result = array_sum($result);
			}else if($method == 'trim'){
				$result = trim($result);
			}else if($method == 'pr' OR $method == 'print'){
				$result = pr($result, true);
			}else if($method == 'br'){
				$result = nl2br($result);
			}else if($method == 'slug'){
				$result = \G2\L\Str::slug($result);
			}else if($method == 'jsonen'){
				$result = json_encode($result, JSON_UNESCAPED_UNICODE);
			}else if($method == 'jsonde'){
				$result = json_decode($result, true);
			}else if($method == 'join'){
				$params[0] = !empty($params[0]) ? $this->value($params[0]) : ',';
				$result = implode($params[0], (array)$result);
			}else if($method == 'split'){
				$params[0] = !empty($params[0]) ? $this->value($params[0]) : ',';
				$result = explode($params[0], $result);
			}else if($method == 'ul'){
				if(is_array($result) AND !empty(array_filter($result))){
					$result = '<ul><li>'.implode('</li><li>', (array)$result).'</li></ul>';
				}else{
					$result = implode(' ', (array)$result);
				}
			}
		}
		
		return $result;
	}
	
	function params($string, $set = false){
		$params = [];
		if(strpos($string, '$') !== false){// AND strpos($string, '=') !== false){
			$parts = explode('$', $string);
			$string = $parts[0];
			$_parts = explode('&', $parts[1]);
			$_parts = array_filter($_parts);
			
			foreach($_parts as $_part){
				if(strpos($_part, '=') !== false){
					$temp = explode('=', $_part);
					$params[$temp[0]] = $this->parse($temp[1], false, 2);
					if(!empty($set)){
						$this->set($temp[0], $params[$temp[0]]);
					}
				}else{
					$params[] = $this->parse($_part, false, 2);
				}
			}
			/*parse_str($parts[1], $params);
			
			foreach($params as $key => $value){
				$value = $this->parse($value, false, 2);
				if(strlen($value) == 0){
					$value = $key;
					$key = null;
				}
				if(!empty($set) AND !empty($key)){
					$this->set($key, $value);
				}else{
					$params[$key] = $value;
				}
			}*/
		}
		if(!$set){
			return [$string, $params];
		}else{
			return $string;
		}
	}
	
	function redirect($name){
		$connection = $this->_connection();
		
		$events = array_keys($connection['events']);
		if(in_array($name, $events)){
			$url = $this->_url();
			$url = \G2\L\Url::build($url, ['event' => $name]);
		}else{
			$url = $this->parse($name, false, 2);
		}
		
		\G2\L\Env::redirect(r2($url));
	}
	
	function page($name){
		if($name == 'url'){
			return \G2\L\Url::current();
		}
		
		if($name == 'title'){
			return \GApp::document()->title();
		}
		
		if($name == 'referrer'){
			return \G2\L\Url::referer();
		}
	}
	
	function url($name = false, $full = false){
		$connection = $this->_connection();
		
		$events = array_keys($connection['events']);
		$url = $this->_url();
		
		list($name, $params) = $this->params($name);
		
		if(!empty($name)){
			if(in_array($name, $events)){
				$url = \G2\L\Url::build($url, array_merge(['event' => $name, 'tvout' => $this->data('tvout')], $params));
			}else if($name == '_self'){
				//return the current url without passing by sef
				if(\GApp::instance()->extension == 'chronoconnectivity'){
					$url = \G2\L\Url::build(\G2\L\Url::current(), array_merge(['conn' => $connection['alias']], $params));
				}else{
					$url = \G2\L\Url::build(\G2\L\Url::current(), array_merge(['chronoform' => $connection['alias']], $params));
				}
				return $url;
			}else{
				//string which is not a form event
				$url = $this->parse($name, false, 2);
				if(!empty($params)){
					$url = \G2\L\Url::build($url, $params);
				}
			}
		}
		
		if(!$full){
			return r2($url);
		}else{
			return \G2\L\Url::full(r2($url));
		}
	}
	
	function _url(){
		$connection = $this->_connection();
		
		if(\GApp::instance()->extension == 'chronoconnectivity'){
			$url = 'index.php?ext=chronoconnectivity&cont=manager'.rp('conn', $connection['alias']);
		//}else if(\GApp::instance()->extension == 'chronoforms'){
		}else{
			if(\GApp::instance()->site == 'admin'){
				$url = 'index.php?ext=chronoforms&cont=manager'.rp('chronoform', $connection['alias']);
			}else{
				$url = 'index.php?ext=chronoforms'.rp('chronoform', $connection['alias']);
			}
		}
		
		return $url;
	}
	
	function message($type, $name){
		\GApp::session()->flash($type, $this->lang($name));
	}
	
	function connection($name, $event = 'index', $method = 'event'){
		$original = $this->_connection();
		
		$Connection = new \G2\A\E\Chronoconnectivity\M\Connection();
		$new = $Connection->where('alias', $name)->select('first', ['json' => ['events', 'sections', 'views', 'functions', 'locales', 'rules']]);
		
		if(!empty($new)){
			$this->set('__connection', $new['Connection']);
			$this->setup($new['Connection']);
			
			if(empty($method)){
				$method = 'event';
			}
			
			if(empty($event)){
				$event = 'index';
			}
			
			$return = $this->parse('{'.$method.':'.$event.'}');
			
			$this->set('__connection', $original);
			$this->setup($original);
			
			return $return;
		}
	}
	
	function chronoform($name, $event = 'load', $method = 'event'){
		$original = $this->_connection();
		
		$Connection = new \G2\A\E\Chronoforms\M\Connection();
		$new = $Connection->where('alias', $name)->select('first', ['json' => ['events', 'sections', 'views', 'functions', 'locales', 'rules']]);
		
		if(!empty($new)){
			$this->set('__connection', $new['Connection']);
			$this->setup($new['Connection']);
			
			if(empty($method)){
				$method = 'event';
			}
			
			if(empty($event)){
				$event = 'load';
			}
			
			$return = $this->parse('{'.$method.':'.$event.'}');
			
			$this->set('__connection', $original);
			$this->setup($original);
			
			return $return;
		}
	}
	
	function event_function($name, $function){
		$connection = $this->_connection();
		
		$result = '';
		
		$this->fn($function);
		
		if($this->get($function['name']) === false){
			$load_event = isset($this->fevents[$function['name']]['fail']) ? $this->fevents[$function['name']]['fail'] : null;
			if($load_event === true){
				$load_event = !empty($function['event']) ? $function['event'] : $this->_event('default');
			}
			if(!empty($load_event) AND $name != $load_event){
				$result .= $this->event($load_event);
			}
			//var_dump($load_event);pr($function);
			//pr('failure:'.$function['name']);
			$this->stopped = true;
		}
		
		return $result;
	}
	
	function event_start($name){
		$connection = $this->_connection();
		$result = '';
		
		$events = array_keys($connection['events']);
		$name_key = array_search($name, $events);
		
		$stored = \GApp::session()->get($connection['alias'].'.inputs', []);
		
		if(!$this->event_type($name, 'standalone')){
			foreach($stored as $vname => $vdata){
				if($this->_connection('params.events_ordered')){
					if(!empty($vdata['event'])){
						$event_key = array_search($vdata['event'], $events);
						if($event_key >= $name_key){
							\GApp::session()->clear($connection['alias'].'.inputs.'.$vname);
							\GApp::session()->clear($connection['alias'].'.events.'.$vdata['event']);
						}
					}
				}else{
					if(!empty($vdata['event']) AND $vdata['event'] == $name){
						\GApp::session()->clear($connection['alias'].'.inputs.'.$vname);
					}
				}
			}
		}
		//pr($name);
		//pr(\GApp::session()->get($connection['alias'], []));
		
		if($this->_connection('params.events_ordered') AND !$this->event_type($name, 'standalone')){
			$last_valid = $events[0];
			//pr('name:'.$name);
			if($name != $last_valid){
				foreach($events as $event){
					if($this->event_type($event, 'standalone') OR $this->event_type($event, 'end')){
						continue;
					}
					if(\GApp::session()->get($connection['alias'].'.events.'.$event) !== true){
						//$result .= $this->event($last_valid);
						//if($name != $event){
						//pr('event1:'.$event);
						if($name_key > array_search($event, $events)){
							//pr('event2:'.$event);
							$result .= $this->event($event);
							$this->stopped = true;
						}
						break;
					}
					
					$last_valid = $event;
				}
			}
		}
		
		//pr('passed:'.$name);
		
		if($this->_connection('params.multipage') AND !$this->event_type($name, 'standalone')){
			$stored = \GApp::session()->get($connection['alias'].'.multipage', []);
			$new = array_replace_recursive($stored, $this->data);
			\GApp::session()->set($connection['alias'].'.multipage', $new);
			$this->data = array_replace_recursive($new, $this->data);
		}
		
		if(empty($this->stopped) AND $this->_connection('params.check_security') AND !$this->event_type($name, 'standalone')){
			$stored = \GApp::session()->get($connection['alias'].'.inputs', []);
			
			foreach($stored as $vname => $vdata){
				if(!empty($vdata['dynamics']['security'])){
					$vdata = $vdata + ['name' => $name.'_'.$vdata['type']];
					$result .= $this->event_function($name, $vdata);
					if(!empty($this->stopped)){
						break;
					}else{
						//\GApp::session()->clear($connection['alias'].'.security.'.$vname);
					}
				}
			}
		}
		
		//if(empty($this->stopped) AND !empty($connection['events'][$name]['validate_fields'])){
		if(empty($this->stopped) AND $this->_connection('params.validate_fields') AND !$this->event_type($name, 'standalone') AND $name != $events[0]){
			$validate_fields = [
				'type' => 'validate_fields',
				'name' => $name.'_validate_fields',
				'error_message' => 'Missing form data',
				'data_provider' => '{data:}',
				'list_errors' => true,
			];
			
			$result .= $this->event_function($name, $validate_fields);
		}
		
		if(empty($this->stopped) AND $this->_connection('params.upload_files') AND !$this->event_type($name, 'standalone') AND $name != $events[0]){
			$upload_files = [
				'type' => 'upload',
				'name' => $name.'_upload',
				'autofields' => '1',
				'config' => '',
				'max_size' => '1000',
				'max_size_error' => $this->lang('file size overlimit'),
				'file_extension_error' => $this->lang('file extension not permitted'),
			];
			
			$result .= $this->event_function($name, $upload_files);
		}
		
		
		if($this->_connection('params.multipage') AND !$this->event_type($name, 'standalone')){
			$stored = \GApp::session()->get($connection['alias'].'.multipage', []);
			$new = array_replace_recursive($stored, $this->data);
			\GApp::session()->set($connection['alias'].'.multipage', $new);
			$this->data = array_replace_recursive($new, $this->data);
			/*
			$vars = \GApp::session()->get($this->_connection('alias').'.vars', []);
			foreach($vars as $fname => $fdata){
				if(is_array($fdata)){
					$this->set($fname, array_replace_recursive($fdata, $this->get($fname, [])));
				}else{
					$this->set($fname, $fdata);
				}
			}
			*/
		}
		
		return $result;
	}
	
	function event($name, $fnEvent = false){
		$connection = $this->_connection();
		
		$result = '';
		
		$events = array_keys($connection['events']);
		
		if(!empty($connection['events'][$name])){
			$this->set('__active_event', $name);
		}
		
		//check permissions
		if(empty($fnEvent) AND !empty($connection['events'][$name]['rules'])){
			$rules = array_filter($connection['events'][$name]['rules']['access']);
			
			if(!empty($rules)){
				$owner_id = !empty($connection['events'][$name]['owner_id']) ? $this->parse($connection['events'][$name]['owner_id']) : null;
				
				if(\GApp::access($connection['events'][$name]['rules'], 'access', $owner_id) !== true){
					
					if(!empty($connection['events'][$name]['access_denied'])){
						$result .= $this->parse($connection['events'][$name]['access_denied']);
					}
					
					return $result;
				}
			}
		}
		
		if(!empty($connection['events'][$name])){
			$result .= $this->event_start($name);
			
			if(empty($this->stopped) AND !$this->event_type($name, 'standalone')){
				\GApp::session()->set($connection['alias'].'.events.'.$name, true);
				\GApp::session()->set($connection['alias'].'.active_event', $name);
			}
		}
		
		$functions = $connection['functions'];
		if(!empty($this->active_block)){
			$functions = $this->blocks[$this->active_block]['content'];
		}
		
		if($fnEvent OR !empty($connection['events'][$name]) OR (strpos($name, 'plg.') === 0)){
			if(isset($connection['events'][$name]['content'])){
				//connectivity mode
				$result .= $this->parse($connection['events'][$name]['content']);
			}else{
				if(strpos($name, 'plg.') === 0){
					//plugins mode
					$name = str_replace('plg.', '', $name);
					$result = $this->plugin($name, 'event');
				}else if(empty($this->stopped) AND !empty($functions)){
					//forms mode
					foreach($functions as $function){
						if(empty($this->stopped) AND !empty($function['_event']) AND $function['_event'] == $name){
							//$result .= $this->parse($this->fn($function));
							$result .= $this->fn($function);
							
							if(!empty($this->stopped)){
								break;
							}
							
							if(!empty($this->fevents[$function['name']])){
								foreach($this->fevents[$function['name']] as $fevent => $fevent_result){
									if($fevent_result){
										$result .= $this->event($function['name'].'/'.$fevent, true);
									}
								}
							}
						}
					}
				}
			}
		}
		
		if(!empty($connection['events'][$name]) AND (empty($this->stopped) OR $name == $events[0])){
			$result .= $this->event_finish($name);
		}
		
		return $result;
	}
	
	function event_finish($name){
		$connection = $this->_connection();
		$result = '';
		//pr('finish:'.$name);
		//if(!empty($this->stopped)){
		//	return $result;
		//}
		
		if(!empty($this->_connection('params.log.enabled')) AND $this->event_type($name, 'end')){
			
			$saved = array_diff_key($this->data, ['option' => '', 'event' => '', 'cont' => '', 'chronoform' => '']);
			
			$inputs = \GApp::session()->get($this->_connection('alias').'.inputs', []);
			$inputs_names = \G2\L\Arr::getVal($inputs, '[n].name', []);
			$saved = [];
			foreach($inputs_names as $view_name => $field_name){
				if(!empty($inputs[$view_name]['dynamics']['security'])){
					continue;
				}
				
				$data_path = explode('.', $this->dpath($field_name))[0];
				$saved[$data_path] = $this->data($data_path);
				//$saved = \G2\L\Arr::setVal($saved, explode('.', $this->dpath($field_name))[0], $this->data($this->dpath($field_name)));
			}
			
			$data = [
				'aid' => \GApp::session()->get($this->_connection('alias').'.log.aid'),
				'form_id' => $connection['id'],
				'uid' => \GApp::session()->get($this->_connection('alias').'.log.uid', \G2\L\Str::uuid()),
				'user_id' => \GApp::user()->get('id'),
				'created' => \GApp::session()->get($this->_connection('alias').'.log.created', \G2\L\Dater::datetime()),
				'modified' => \GApp::session()->get($this->_connection('alias').'.log.aid') ? \G2\L\Dater::datetime() : null,
				'ipaddress' => $_SERVER['REMOTE_ADDR'],
				'page' => $this->_event(),
				'data' => json_encode($saved, JSON_UNESCAPED_UNICODE),
			];
			
			$save_data = [
				'type' => 'save_data',
				'name' => 'save_datalog',
				'db_table' => '#__chronoengine_forms6_datalog',
				'model_name' => 'LOG',
				'data_provider' => $data,
				'action' => 'insert:update',
			];
			
			$result = $this->event_function('save_datalog', $save_data);
			
			if(!empty($this->get('save_datalog'))){
				\GApp::session()->set($this->_connection('alias').'.log', $this->get('save_datalog'));
			}
			
		}
		
		if(!empty($this->_connection('models')) AND $this->event_type($name, 'end')){
			foreach($this->_connection('models') as $model){
				if(empty($model['enabled'])){
					continue;
				}
				
				$data_path = '';
				$multi = false;
				if(!empty($this->data[$model['name']]) AND is_array($this->data[$model['name']])){
					$data_path = $model['name'];
					if(\G2\L\Arr::is_assoc($this->data[$model['name']]) == false){
						$multi = true;
					}
				}
				
				if(!empty($model['relations'])){
					foreach($model['relations'] as $relation){
						if(!empty($relation['type']) AND !empty($relation['model']) AND !empty($relation['fkey'])){
							if($relation['type'] == 'belongsto'){
								if($multi AND !empty($this->data[$model['name']])){
									foreach($this->data($model['name']) as $kd => $vd){
										$path = implode('.', array_filter([$data_path, $kd, $relation['fkey']]));
										$this->data($path, $this->data($relation['model'].'.'.$this->info[$name.'_save_data_'.$relation['model']]['pkey']), true);
									}
								}else{
									$path = implode('.', array_filter([$data_path, $relation['fkey']]));
									$this->data($path, $this->data($relation['model'].'.'.$this->info[$name.'_save_data_'.$relation['model']]['pkey']), true);
								}
							}
						}
					}
				}
				
				$save_data_name = $name.'_save_data_'.$model['name'];
				
				$save_data = [
					'type' => 'save_data',
					'name' => $save_data_name,
					'db_table' => $model['db_table'],
					'model_name' => $model['name'],
					'data_provider' => '{data:'.$data_path.'}',
					'action' => 'insert:ignore',
					//'autofields' => true,
					'overrides' => [
						['action' => 'insert', 'name' => 'created', 'value' => '{date:Y-m-d H:i:s}'],
						['action' => 'insert', 'name' => 'user_id', 'value' => '{user:id}'],
						['action' => 'update', 'name' => 'modified', 'value' => '{date:Y-m-d H:i:s}'],
					],
				];
				
				if($multi AND !empty($this->data[$model['name']])){
					$save_data_var = [];
					foreach($this->data($model['name']) as $kd => $vd){
						$save_data['name'] = $save_data_name.'_'.$kd;
						$save_data['data_provider'] = $vd;
						
						$result .= $this->event_function($name, $save_data);
						$save_data_var[$kd] = $this->get($save_data['name'], []);
					}
				}else{
					$result .= $this->event_function($name, $save_data);
					$save_data_var = $this->get($save_data_name, []);
				}
				
				$this->data = array_replace_recursive($this->data, [$model['name'] => $save_data_var]);
			}
		}
		
		if(!empty($connection['events'][$name]['auto_view'])){
			$result = $result.$this->section($name);
		}
		
		if(!empty($connection['events'][$name]['debug']) OR $this->_connection('params.debug')){
			$result .= $this->parse('{debug:}');
		}
		
		if($this->_connection('params.events_ordered') AND $this->event_type($name, 'end')){
			\GApp::session()->clear($connection['alias']);
		}
		
		return $result;
	}
	
	function event_type($name, $type = false){
		$connection = $this->_connection();
		
		if($type === false){
			return $connection['events'][$name]['type'];
		}else if($type == 'standalone'){
			return (!empty($connection['events'][$name]['type']) AND $connection['events'][$name]['type'] == $type);
		}else if($type == 'start'){
			return (empty($connection['events'][$name]['type']) AND $connection['params']['default_event'] == $name);
		}else if($type == 'end'){
			$events2 = array_keys($connection['events']);
			$events = [];
			foreach($events2 as $event){
				if(empty($connection['events'][$event]['type'])){
					$events[] = $event;
				}
			}
			$name_key = array_search($name, $events);
			
			return (
				(empty($connection['events'][$name]['type']) AND ($name_key == max(array_keys($events)))) 
				OR 
				(!empty($connection['events'][$name]['type']) AND $connection['events'][$name]['type'] == $type)
			);
		}
		
		return false;
	}
	
	function section($name, $method = false){
		$result = null;
		
		$connection = $this->_connection();
		
		//check permissions
		if(!empty($connection['sections'][$name]['rules'])){
			$rules = array_filter($connection['sections'][$name]['rules']['access']);
			
			if(!empty($rules)){
				$owner_id = !empty($connection['sections'][$name]['owner_id']) ? $this->parse($connection['sections'][$name]['owner_id']) : null;
				
				if(\GApp::access($connection['sections'][$name]['rules'], 'access', $owner_id) !== true){
					return;
				}
			}
		}
		
		if($method == 'template'){
			return $this->parse($connection['sections'][$name]['template']);
		}
		
		if(empty($connection['views'])){
			return '';
		}
		
		$views = $connection['views'];
		if(!empty($this->active_block)){
			$views = $this->blocks[$this->active_block]['content'];
		}
		
		if(!empty($views)){
			foreach($views as $view){
				if(!empty($view['_section']) AND $view['_section'] == $name){
					//$result .= $this->view($view['name']);
					$result .= $this->view($view);
				}
			}
		}
		
		if(!empty($connection['sections'][$name])){
			$result = $this->section_finish($name, $result);
		}
		
		return $result;
	}
	
	function section_finish($name, $result){
		$connection = $this->_connection();
		
		$events = array_keys($connection['events']);
		$event_key = array_search($this->_event('active'), $events);
		$next_event = !empty($events[$event_key + 1]) ? $events[$event_key + 1] : $this->_event('active');
		
		if($this->_connection('params.type') == 'form' AND strpos($result, '<form') === false){
			$form = [
				'type' => 'form',
				'name' => $name.'_form',
				'event' => $next_event,
				'keepalive' => 1,
				'submit_animation' => 1,
				'dynamic' => $this->_connection('params.ajax', 0),
				'validation' => ['type' => 'inlinetext'],
				'content' => $result,
			];
			
			$result = $this->view($form);
		}
		
		return $result;
	}
	/*
	function section_view($name, $view){
		$connection = $this->_connection();
		
		$result = '';
		
		$result = $this->view($view);
		
		return $result;
	}
	*/
	function children($name, $type){
		$connection = $this->_connection();
		$items = $connection[$type];
		if(!empty($this->active_block)){
			$items = $this->blocks[$this->active_block]['content'];
		}
		
		$results = [];
		
		$area = ($type == 'views') ? '_section' : '_event';
		if(!empty($items)){
			foreach($items as $item){
				if($item[$area] == $name){
					$results[] = $item;
				}
			}
		}
		
		return $results;
	}
	
	function template($name, $main = false, $multi = false){
		$result = '';
		
		$connection = $this->_connection();
		
		if($main){
			$result .= '<table width="100%" cellpadding="0" cellspacing="0" border="0" class="" style="border:3px solid #e2e2e2; border-collapse:separate; border-radius:7px;">';
			$result .= "\n";
		}else{
			$result .= '<table width="100%" cellpadding="0" cellspacing="0" border="0">';
			$result .= "\n";
		}
		
		$items = [];
		if(!empty($connection['views'])){
			foreach($connection['views'] as $view){
				if($view['_section'] == $name){
					$item = $this->_template($view);
					if(!empty($item)){
						$items[] = $item;
					}
				}
			}
		}
		
		if(empty($items)){
			return '';
		}
		
		if($main){
			$result .= '<tr><td width="100%" style="padding:5px; border-bottom:1px solid #e2e2e2;">';
			$result .= "\n";
			$result .= implode('</td></tr>'."\n".'<tr><td width="100%" style="padding:5px; border-bottom:1px solid #e2e2e2;">', $items);
			$result .= "\n";
			$result .= '</td></tr>';
		}else{
			if($multi){
				$result .= '<tr>';
				$result .= "\n";
				foreach($items as $item){
					$width = round(100/count($items), 2);
					$result .= '<td width="'.$width.'%" style="border:1px solid white; vertical-align:top;">'.$item.'</td>';
				}
				$result .= "\n";
				$result .= '</tr>';
			}else{
				$result .= '<tr><td width="100%">';
				$result .= "\n";
				$result .= implode('</td></tr>'."\n".'<tr><td width="100%">', $items);
				$result .= "\n";
				$result .= '</td></tr>';
			}
		}
		
		//if($main){
			$result .= '</table>';
		//}
		
		return $result;
	}
	
	function _template($view_data){
		$views_path = \G2\Globals::ext_path('chronofc', 'admin').'views'.DS.$view_data['type'].DS.$view_data['type'].'_template.php';
		
		if(file_exists($views_path)){
			$returned = $this->View->view($views_path, ['view' => $view_data], true);
			
			if(!empty($returned)){
				return $returned;
			}else{
				return '';
			}
		}else{
			if(!empty($view_data['params']['name'])){
				$name = $this->dpath($view_data['params']['name']);
				$label = $name;
				if(!empty($view_data['label'])){
					$label = $view_data['label'];
				}else if(!empty($view_data['params']['placeholder'])){
					$label = $view_data['params']['placeholder'];
				}
				
				if(!empty($this->get('_repeater_model'))){
					$name = $this->get('_repeater_model').'.<?php echo $k; ?>.'.$name;
				}
				$shrotcode = '{data:'.$name.'}';
				if($view_data['type'] == 'field_textarea'){
					$shrotcode = '{data.br:'.$name.'}';
				}
				if(strpos($view_data['params']['name'], '[]') !== false OR !empty($view_data['multiple'])){
					$shrotcode = '{data.ul:'.$name.'}';
				}
				
				return '
				
						<table width="100%" cellpadding="0" cellspacing="0" border="0">
							<tr style="background-color:#fafafb">
								<td style="padding:7px;"><strong>'.$label.'</strong></td>
							</tr>
							<tr>
								<td style="padding:7px;">'.$shrotcode.'</td>
							</tr>
						</table>
					';
			}else{
				return '';
			}
		}
	}
	
	function lang($name){
		$site_language = \G2\L\Config::get('site.language');
		$site_language = strtoupper($site_language);
		//check permissions
		if(!empty($this->locales[$site_language][$name])){
			return $this->parse($this->locales[$site_language][$name]);
		}
		
		return $name;
	}
	
	function plugin($name, $method = false){
		$result = null;
		
		if(is_string($name)){
			$name = $this->params($name, true);
		}
		
		if(strpos($name, '.') !== false){
			$info = explode('.', $name);
			$name = $info[0];
			$act = $info[1];
		}
		
		if(empty($this->plugins[$name])){
			return false;
		}
		
		$plugin_data = $this->plugins[$name];
		
		if(!empty($plugin_data)){
			if(isset($plugin_data['enabled']) AND empty($plugin_data['enabled'])){
				return false;
			}
			//check permissions
			if(!empty($plugin_data['rules'])){
				$rules = array_filter($plugin_data['rules']['access']);
				
				if(!empty($rules)){
					$owner_id = !empty($plugin_data['owner_id']) ? $this->parse($plugin_data['owner_id']) : null;
					
					if(\GApp::access($plugin_data['rules'], 'access', $owner_id) !== true){
						return false;
					}
				}
			}
			//get output file
			$plugins_path = \G2\Globals::ext_path('chronofc', 'admin').'plugins'.DS.$plugin_data['type'].DS.$method.'s'.DS.$act.'.php';
			
			$result = $this->View->view($plugins_path, ['plugin' => $plugin_data], true);
		}
		
		return $result;
	}
	
	function fn($name, $method = false){
		$result = null;
		
		if(is_string($name)){
			$name = $this->params($name, true);
		}
		
		$function_data = null;
		if(is_array($name)){
			$function_data = $name;
		}else if(!empty($this->functions[$name])){
			$function_data = $this->functions[$name];
		}
		
		if(!empty($function_data)){
			if(isset($function_data['enabled']) AND empty($function_data['enabled'])){
				return false;
			}
			//check permissions
			if(!empty($function_data['rules'])){
				$rules = array_filter($function_data['rules']['access']);
				
				if(!empty($rules)){
					$owner_id = !empty($function_data['owner_id']) ? $this->parse($function_data['owner_id']) : null;
					
					if(\GApp::access($function_data['rules'], 'access', $owner_id) !== true){
						return false;
					}
				}
			}
			//get output file
			$functions_path = \G2\Globals::ext_path('chronofc', 'admin').'functions'.DS.$function_data['type'].DS.$function_data['type'].'_output.php';
			
			$result = $this->View->view($functions_path, ['function' => $function_data, 'method' => $method], true);
			
		}
		
		return $result;
	}
	
	function view($name){
		$result = null;
		
		if(is_string($name)){
			$name = $this->params($name, true);
		}
		
		$view_data = null;
		if(is_array($name)){
			$view_data = $name;
		}else if(!empty($this->views[$name])){
			$view_data = $this->views[$name];
		}
		
		if(!empty($view_data)){
			//check permissions
			if(!empty($view_data['rules'])){
				$rules = array_filter($view_data['rules']['access']);
				
				if(!empty($rules)){
					$owner_id = !empty($view_data['owner_id']) ? $this->parse($view_data['owner_id']) : null;
					
					if(\GApp::access($view_data['rules'], 'access', $owner_id) !== true){
						return;
					}
				}
			}
			//check the toggle switch
			if(isset($view_data['toggler']) AND strlen($view_data['toggler'])){
				$toggler = $this->parse($view_data['toggler']);
				if(empty($toggler)){
					return;
				}
			}
			
			if($this->viewslimit > $this->get('__viewslimit', 999999)){
				\GApp::session()->flash('warning', 'One element is not displayed on the frontend because the extension is not validated.');
				return '';
			}
			if(strpos($view_data['type'], 'area_') !== 0){
				$this->viewslimit++;
			}
			//get output file
			$views_path = \G2\Globals::ext_path('chronofc', 'admin').'views'.DS.$view_data['type'].DS.$view_data['type'].'_output.php';
			
			$result = $this->View->view($views_path, ['view' => $view_data], true);
		}
		
		return $result;
	}
	
	function block($id){
		$output = '';
		if(!empty($this->blocks[$id])){
			$this->active_block = $id;
			$block = $this->blocks[$id];
			if($block['type'] == 'views'){
				foreach($block['content'] as $k => $view){
					if(strpos($view['_section'], '/') === false){
						$output .= $this->view($view);
					}
				}
			}else if($block['type'] == 'functions'){
				foreach($block['content'] as $k => $fn){
					if(strpos($fn['_event'], '/') === false){
						//$output .= $this->fn($fn);
						$output .= $this->event($fn['_event'], true);
						break;
					}
				}
			}
			$this->active_block = null;
			//pr($block);
		}else{
			$aliases = \G2\L\Arr::getVal($this->blocks, '[n].block_id');
			$names = explode('.', $id, 2);
			
			if(in_array($names[0], $aliases)){
				$block_id = array_search($names[0], $aliases);
				
				$this->active_block = $block_id;
				
				if(count($names) == 1){
					$output = $this->block($block_id);
				}else{
					$target_name = $names[1];
					
					foreach($this->blocks[$block_id]['content'] as $item){
						if($item['name'] == $target_name){
							if($this->blocks[$block_id]['type'] == 'views'){
								$output = $this->view($item);
							}else if($this->blocks[$block_id]['type'] == 'functions'){
								$output = $this->event($item['_event'], true);
							}
							
							break;
						}
					}
				}
				
				$this->active_block = null;
			}
		}
		
		return $output;
	}
	
	function multiline($string, $process = true, $params = true, $eval = true){
		$evaled = [];
		
		if($eval){
			ob_start();
			$evaled = eval('?>'.$string);
			$plain_string = ob_get_clean();
		}else{
			$plain_string = $string;
		}
		
		if(!is_array($evaled)){
			$evaled = false;
		}
		
		$plains = [];
		if(!empty($plain_string)){
			$plain_fields = explode("\n", $plain_string);
			$plain_fields = array_map('trim', $plain_fields);
			$plain_fields = array_filter($plain_fields, 'strlen');
			
			if($process === true){
				foreach($plain_fields as $k => $plain_field){
					$plain_field_data = explode(':', $plain_field, 2);
					
					$plains[$k]['name'] = $plain_field_data[0];
					
					if(($params === true OR $params == 'name') AND strpos($plain_field_data[0], '/') !== false){
						$plain_field_name = explode('/', $plain_field_data[0], 2);
						$plains[$k]['name'] = $plain_field_name[0];
						$plains[$k]['namep'] = $plain_field_name[1];
					}
					
					if(isset($plain_field_data[1])){
						$plains[$k]['value'] = $plain_field_data[1];
						
						if(($params === true OR $params == 'value') AND strpos($plain_field_data[1], '/') !== false){
							$plain_field_value = explode('/', $plain_field_data[1]);
							
							if(count($plain_field_value) == 2){
								if(substr_count($plain_field_value[1], '{') == substr_count($plain_field_value[1], '}')){
									$plains[$k]['valuep'] = $plain_field_value[1];
									$plains[$k]['value'] = $plain_field_value[0];
								}else{
									$plains[$k]['value'] = $plain_field_data[1];
								}
							}else{
								$valuep = array_pop($plain_field_value);
								if(substr_count($valuep, '{') == substr_count($valuep, '}')){
									$plains[$k]['valuep'] = $valuep;
									$plains[$k]['value'] = implode('/', $plain_field_value);
								}else{
									$plains[$k]['value'] = implode('/', $plain_field_value).'/'.$valuep;
								}
							}
						}
					}
				}
			}else if($process === 'assoc'){
				foreach($plain_fields as $k => $plain_field){
					$plain_field_data = explode(':', $plain_field, 2);
					
					$plains[$plain_field_data[0]] = isset($plain_field_data[1]) ? $plain_field_data[1] : $plain_field_data[0];
				}
			}else{
				$plains = $plain_fields;
			}
			
		}else{
			$plains = [];
		}
		
		return [$plains, $evaled];
	}
	
	function rparams($string, $sign = '='){
		if(empty(trim($string))){
			return [];
		}
		
		$options = explode("\n", trim($string));
		$options = array_map('trim', $options);
		$options = array_filter($options);
		$params = [];
		foreach($options as $option){
			
			$option = $this->parse($option);
			if(is_array($option)){
				$params = array_replace($params, $option);
				continue;
			}
			
			$option_data = explode($sign, $option, 2);
			
			$params[$option_data[0]] = $option_data[1];
		}
		return $params;
	}
	
	function signed($string, $return = 'name'){
		if(strpos($string, '/') !== false){
			$plain_field_name = explode('/', $string);
			
			if($return == 'name'){
				return $plain_field_name[0];
			}else{
				return $plain_field_name[1];
			}
		}
		
		return $string;
	}
	
	function dpath($field_name, $add_keys = false){
		static $parsed = [];
		if($add_keys AND strpos($field_name, '[]') !== false){
			$count = array_count_values($parsed);
			$next = isset($count[$field_name]) ? $count[$field_name] : 0;
			$parsed[] = $field_name;
			$field_name = str_replace('[]', '['.$next.']', $field_name);
		}else{
			$field_name = str_replace('[]', '', $field_name);
		}
		
		$field_name = str_replace(['[', ']'], ['.', ''], $field_name);
		$field_name = str_replace(['-N-'], ['[n]'], $field_name);
		
		return $field_name;
	}
	
	function lname($fname){
		$parts = explode('.', $fname);
		$parts = array_reverse($parts);
		foreach($parts as $part){
			if(strpos($part, '}') === false AND strpos($part, '{') === false AND strpos($part, ']') === false AND strpos($part, '[') === false){
				return $part;
			}
		}
	}
	
	function add_security($view, $params){
		$connection = $this->_connection();
		
		$params['event'] = $this->_event('active');
		$params['dynamics']['security'] = 1;
		
		//\GApp::session()->set($connection['alias'].'.security.'.$view['name'], $params);
		\GApp::session()->set($connection['alias'].'.inputs.'.$view['name'], $params);
	}
	/*
	function add_dynamics($view){
		$connection = $this->_connection();
		
		$storage_name = $view['name'];
		if(!empty($this->active_block)){
			$storage_name = $this->active_block.':'.$view['name'];
		}
		$view['storage_name'] = $storage_name;
		
		$storage['name'] = str_replace('[]', '[-N-]', $view['params']['name']);
		$storage['type'] = $view['type'];
		$storage['event'] = $this->_event('active');
		$storage['label'] = isset($view['label']) ? $view['label'] : $view['params']['name'];
		if($view['type'] == 'field_file'){
			$storage['extensions'] = $view['extensions'];
			$storage['max_size'] = !empty($view['max_size']) ? $view['max_size'] : '';
			$storage['errors'] = json_encode([
				'file_extension_error' => !empty($view['file_extension_error']) ? $view['file_extension_error'] : '',
				'max_size_error' => !empty($view['max_size_error']) ? $view['max_size_error'] : '',
			]);
		}
		
		if(!empty($this->view->Html->options)){
			$storage['options'] = $this->view->Html->options;
		}
		
		if(!empty($view['verror'])){
			$storage['verror'] = $view['verror'];
		}
		if(!empty(array_filter($view['validation']))){
			$storage['validation'] = array_filter($view['validation']);
			$storage['dynamics']['validation'] = 1;
		}
		if(!empty($view['dynamics'])){
			foreach($view['dynamics'] as $dtype => $dynamic){
				if(!empty($dynamic['enabled'])){
					$storage['dynamics'][$dtype] = 1;
				}
			}
		}
		
		if($this->get('__multiplier_source')){
			//skip repeater source item
			$storage['name'] = str_replace($this->get('__multiplier_source'), '-N-', $storage['name']);
			$storage['label'] = str_replace($this->get('__multiplier_source'), '-N-', $storage['label']);
			$storage['multiplier'] = true;
		}
		
		if(!$this->get('__multiplier_clone')){
			\GApp::session()->set($connection['alias'].'.inputs.'.$storage_name, $storage);
		}
	}
	*/
	function end(){
		
	}
}