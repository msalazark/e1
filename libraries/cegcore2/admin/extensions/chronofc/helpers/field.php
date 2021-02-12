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
class Field extends \G2\L\Helper{
	//var $tooltip_loaded = false;
	
	function setup($field_type, $view){
		$connection = $this->view->Parser->_connection();
		
		$this->parseBasics($view);
		
		if(isset($view['attrs'])){
			$this->setAttrs($view['attrs']);
		}
		
		$state_class = '';
		if(!empty($view['states'])){
			if(!empty($view['states']['nonexistent'])){
				$this->view->Html->out = false;
				return false;
			}
			if(!empty($view['states']['hidden'])){
				$state_class .= ' hidden';
			}
			if(!empty($view['states']['disabled'])){
				$state_class .= ' disabled';
				$this->view->Html->attr('disabled', true);
			}
		}
		
		if(!empty($view['description']['text'])){
			$this->view->Html->desc($this->view->Parser->parse($view['description']['text']));
		}
		
		if(!empty($view['validation'])){
			$this->setValidations($view['validation'], $view);
		}
		
		if(!empty($view['tooltip']['text'])){
			$tooltip = '<i class="'.$view['tooltip']['class'].'" data-hint="'.htmlspecialchars($this->view->Parser->parse($view['tooltip']['text'])).'"></i>';
			$view['label'] = $view['label'].'&nbsp;'.$tooltip;
			
		}
		
		if(!empty($view['label'])){
			$this->view->Html->label($this->view->Parser->parse($view['label']));
		}
		
		if(!empty($view['multiple'])){
			$this->view->Html->attr('multiple', 'multiple');
			
			if(substr($view['params']['name'], -2) != '[]'){
				$view['params']['name'] .= '[]';
			}
		}
		
		if(!empty($view['options'])){
			$this->setOptions($view['options']);
		}
		
		if(!empty($view['params']['name']) AND $view['type'] != 'field_button'){
			$this->setDynamics($view);
			//$this->view->Parser->add_dynamics($view);
		}
		
		if(!empty($view['events'])){
			$this->setEvents($view);
		}
		
		if(!empty($view['dynamics']) OR !empty($view['validation'])){
			//$this->setDynamics($view);
		}
		
		if(!empty($view['data-values'])){
			$this->setDataValues($view['data-values']);
		}
		
		if(!empty($view['selected'])){
			$this->setSelected($view['selected']);
		}
		
		if(!empty($view['ghost']['enabled'])){
			$this->view->Html->ghost($this->view->Parser->parse($view['ghost']['value']));
		}
		
		if(!empty($view['content'])){
			$this->view->Html->content($this->view->Parser->parse($view['content']));
		}
		
		if(!empty($view['checked'])){
			$this->view->Html->attr('checked', 'checked');
		}
		
		if(!empty($view['checked_provider']) AND !empty($this->view->Parser->parse($view['checked_provider']))){
			$this->view->Html->attr('checked', 'checked');
		}
		
		if(!empty($view['autocomplete']['event'])){
			$this->view->Html->addClass('search selection');
			$this->view->Html->attr('data-autocomplete', 1);
			$this->view->Html->attr('data-url', r2($this->view->Parser->_url().rp('event', $view['autocomplete']).rp('tvout', 'view')));
		}
		
		if(!empty($view['searchable'])){
			$this->view->Html->addClass('search selection');
		}
		
		if(!empty($view['allowadditions'])){
			$this->view->Html->addClass('search selection');
			$this->view->Html->attr('data-allowadditions', 1);
		}
		
		if(!empty($view['fulltextsearch'])){
			$this->view->Html->attr('data-fulltextsearch', 1);
		}
		
		if(!empty($view['reload']['event'])){
			$this->view->Html->attr('data-reloadurl', r2($this->view->Parser->_url().rp('event', $view['reload']).rp('tvout', 'view')));
		}
		
		if(!empty($view['editor']['enabled'])){
			$this->view->Html->attr('data-editor', 1);
		}
		
		if(!empty($view['resize']['enabled'])){
			$this->view->Html->attr('data-autoresize', 1);
		}
		
		if(!empty($view['inputmask'])){
			$this->view->Html->attr('data-inputmask', "'".implode("': '", explode(':', $view['inputmask'], 2))."'");
		}
		
		if(!empty($view['color'])){
			$this->view->Html->addClass($this->view->Parser->parse($view['color']));
		}
		
		if(!empty($view['class'])){
			$this->view->Html->addClass($this->view->Parser->parse($view['class']));
		}
		
		if(!empty($view['fluid'])){
			$this->view->Html->addClass('fluid');
		}
		
		if(!empty($view['toolbar']['enabled'])){
			$this->view->Html->addClass('toolbar-button');
		}
		
		if(!empty($view['calendar'])){
			foreach($view['calendar'] as $k => $v){
				if($k == 'format'){
					//legacy format support
					$newformat = str_replace(['y', 'm', 'd', 'h', 'i'], ['YYYY', 'MM', 'DD', 'HH', 'mm'], $v);
					$this->view->Html->attr('data-dformat', $newformat);
					$this->view->Html->attr('data-sformat', $newformat);
				}else{
					$this->view->Html->attr('data-'.$k, $this->view->Parser->parse($v));
				}
			}
		}
		
		$layout_class = '';
		if($field_type == 'checkboxes' OR $field_type == 'radios'){
			$layout_class = !empty($view['layout']) ? $view['layout'].' fields' : 'grouped fields';
		}
		
		//fix for old radios/checkboxes saved with container class = "field"
		if($field_type == 'checkboxes' OR $field_type == 'radios'){
			if($view['container']['class'] == 'field'){
				$view['container']['class'] = 'multifield';
			}else if(strpos($view['container']['class'], 'field ') === 0){
				$view['container']['class'] = str_replace('field ', 'multifield ', $view['container']['class']);
			}
		}
		
		$field_class = '';
		$field_width = !empty($view['container']['width']) ? ' '.$view['container']['width'] : '';
		if(!empty($view['container']['class'])){
			$field_class = $view['container']['class'].' '.$layout_class.$field_width;
		}else{
			if(!empty($layout_class)){
				$field_class = 'multifield '.$layout_class.$field_width;
			}else{
				
			}
		}
		
		$this->view->Html->attrs($view['params']);
		
		return $field_class.$state_class;
	}
	
	function parseBasics(&$view){
		if($this->get('__multiplier_model') AND isset($view['params']['name'])){
			$name_pcs = explode('[', $view['params']['name']);
			$name_pcs[0] = $name_pcs[0].']';
			$new = array_merge([$this->get('__multiplier_model'), '{var:'.$this->get('__multiplier_name').'.key}'.']'], $name_pcs);
			$view['params']['name'] = implode('[', $new);
			$view['params']['id'] = str_replace([']', '['], '', implode('[', $new)).'_'.$view['params']['id'];
		}
		
		foreach(['label', 'name', 'id', 'placeholder', 'value', 'content', 'src', 'href'] as $attr){
			if(isset($view['params'][$attr])){
				$view['params'][$attr] = $this->view->Parser->parse($view['params'][$attr]);
			}
			if(isset($view[$attr])){
				$view[$attr] = $this->view->Parser->parse($view[$attr]);
			}
		}
	}
	
	function setAttrs($attrs){
		if(!empty($attrs)){
			$extra_attrs = explode("\n", $attrs);
			$extra_attrs = array_map('trim', $extra_attrs);
			
			foreach($extra_attrs as $k => $extra_attr){
				$attribute = $this->view->Parser->parse($extra_attr);
				$extra_attr_data = explode(':', $attribute, 2);
				
				if(!isset($extra_attr_data[1])){
					$this->view->Html->attr($extra_attr_data[0], $extra_attr_data[0]);
				}else{
					$this->view->Html->attr($extra_attr_data[0], $extra_attr_data[1]);
				}
			}
		}
	}
	
	function setDynamics(&$view){
		$connection = $this->view->Parser->_connection();
		
		$storage_name = $view['name'];
		if(!empty($this->view->Parser->active_block)){
			$storage_name = $this->view->Parser->active_block.':'.$view['name'];
		}
		$view['storage_name'] = $storage_name;
		
		$storage['name'] = str_replace('[]', '[-N-]', $view['params']['name']);
		$storage['type'] = $view['type'];
		$storage['event'] = $this->view->Parser->_event('active');
		$storage['label'] = isset($view['label']) ? $view['label'] : $view['params']['name'];
		if($view['type'] == 'field_file'){
			$storage['extensions'] = $this->view->Parser->parse($view['extensions']);
			$storage['max_size'] = !empty($view['max_size']) ? $this->view->Parser->parse($view['max_size']) : '';
			$storage['errors'] = json_encode([
				'file_extension_error' => !empty($view['file_extension_error']) ? $view['file_extension_error'] : '',
				'max_size_error' => !empty($view['max_size_error']) ? $view['max_size_error'] : '',
			]);
		}
		
		if(!empty($this->view->Html->options)){
			$storage['options'] = $this->view->Html->options;
		}
		
		if(!empty($view['verror'])){
			$storage['verror'] = $this->view->Parser->parse($view['verror']);
		}
		
		if(!empty($view['validation']) AND !empty(array_filter($view['validation']))){
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
	
	/*
	function setSecurity($view, $params){
		$connection = $this->view->Parser->_connection();
		
		$array = [];
		foreach($params as $k => $v){
			$array[$k] = $v;
		}
		$params['event'] = $this->view->Parser->_event('active');
		
		\GApp::session()->set($connection['alias'].'.security.'.$view['name'], $params);
	}
	*/
	function setValidations($validations, $view){
		$field_id = $view['params']['id'];
		
		$field_vrules = [];
		$validate_tag = ['identifier' => $field_id.'-main'];
		
		$optional = true;
		if(!empty($validations['rules'])){
			$validation_rules = $validations['rules'];
			$vrules = explode("\n", $validation_rules);
			$vrules = array_map('trim', $vrules);
			
			foreach($vrules as $k => $vrule){
				$vrule = $this->view->Parser->parse($vrule);
				
				if($vrule == 'optional'){
					$validate_tag['optional'] = true;
					continue;
				}
				
				if(!empty($vrule)){
					$vrule_data = explode(':', $vrule, 2);
					$field_vrules[$k]['type'] = array_shift($vrule_data);
					
					if(strpos($field_vrules[$k]['type'], 'required') !== false OR stripos($field_vrules[$k]['type'], 'checked') !== false){
						$optional = false;
					}
					
					if(in_array($view['type'], ['field_checkbox', 'field_radios', 'field_secicon', 'field_checkboxes']) AND $field_vrules[$k]['type'] == 'required'){
						if($view['type'] == 'field_checkbox'){
							$field_vrules[$k]['type'] = 'checked';
						}else{
							$field_vrules[$k]['type'] = 'minChecked[1]';
						}
					}
					
					if(!empty($vrule_data)){
						$field_vrules[$k]['prompt'] = array_shift($vrule_data);
					}
				}
				
			}
		}
		unset($validations['rules']);
		//other rules
		if(!empty($validations)){
			if(!empty($validations['disabled'])){
				$validate_tag['disabled'] = 'true';
				unset($validations['disabled']);
			}
			
			if(empty($validations['required']) AND empty($validations['minChecked']) AND $optional){
				$validate_tag['optional'] = true;
				if(isset($validations['optional'])){
					if(empty($validations['optional'])){
						$validate_tag['optional'] = false;
					}
					unset($validations['optional']);
				}
			}
			
			$prompt = !empty($view['params']['placeholder']) ? $view['params']['placeholder'] : $view['label'];
			if(!empty($view['verror'])){
				$prompt = $view['verror'];
			}
			
			foreach($validations as $rule => $value){
				if(!empty($value)){
					if($value == 'true'){
						if(in_array($view['type'], ['field_checkbox', 'field_radios', 'field_secicon', 'field_checkboxes']) AND $rule == 'required'){
							if($view['type'] == 'field_checkbox'){
								$rule = 'checked';
							}else{
								$rule = 'minChecked[1]';
							}
						}
						$field_vrules[] = ['type' => $rule, 'prompt' => $prompt];
					}else{
						$field_vrules[] = ['type' => $rule.'['.$value.']', 'prompt' => $prompt];
					}
				}
			}
		}
		
		if(!empty($field_vrules)){
			$validate_tag['rules'] = array_values($field_vrules);
			
			$this->view->Html->attr('data-validationrules', json_encode($validate_tag));
			$this->view->Html->attr('data-validate', $field_id.'-main');
			
		}
	}
	
	function setEvents($view){
		$events = $view['events'];
		
		$valid_events = [];
		foreach($events as $field_event){
			if(!empty($field_event['identifier'])){
				$ids = explode("\n", $field_event['identifier']);
				$field_event['identifier'] = [];
				foreach($ids as $id){
					$field_event['identifier'][] = $this->view->Parser->parse(trim($id));
				}
				
				if(isset($field_event['value']) AND strlen(trim($field_event['value']))){
					$values = explode("\n", $field_event['value']);
					$field_event['value'] = [];
					foreach($values as $value){
						$field_event['value'][] = $this->view->Parser->parse(trim($value));
					}
				}
				
				$valid_events[] = $field_event;
			}
		}
		if(!empty($valid_events)){
			$this->view->Html->attr('data-events', json_encode($valid_events));
			//$connection = $this->view->Parser->_connection();
			//\GApp::session()->set($connection['alias'].'.fevents.'.$view['storage_name'], $valid_events);
		}
	}
	
	function setOptions($options_string){
		$options = explode("\n", $options_string);
		$options = array_map('trim', $options);
		
		$field_options = [];
		foreach($options as $option){
			
			$option = $this->view->Parser->parse($option);
			if(is_array($option)){
				$field_options = array_replace($field_options, $option);
				continue;
			}
			
			$option_data = explode('=', $option, 2);
			
			if(count($option_data) == 1){
				$field_options[$option_data[0]] = $option_data[0];
			}else{
				$field_options[$option_data[0]] = $option_data[1];
			}
		}
		
		$this->view->Html->options($field_options);
	}
	
	function setDataValues($options_string){
		$options = explode("\n", $options_string);
		$options = array_map('trim', $options);
		
		$field_options = [];
		foreach($options as $option){
			
			$option = $this->view->Parser->parse($option);
			if(is_array($option)){
				$field_options = array_replace($field_options, $option);
				continue;
			}
			
			$option_data = explode('=', $option, 2);
			
			if(count($option_data) == 1){
				$field_options[$option_data[0]] = $option_data[0];
			}else{
				$field_options[$option_data[0]] = $option_data[1];
			}
		}
		
		$this->view->Html->multiAttr('data-value', $field_options);
	}
	
	function setSelected($selected_string){
		$selected = explode("\n", $selected_string);
		$selected = array_map('trim', $selected);
		
		foreach($selected as $k => $selected_v){
			$selected[$k] = $this->view->Parser->parse($selected[$k]);
		}
		
		$selected = \G2\L\Arr::flatten($selected);
		
		$this->view->Html->selected($selected);
	}
	
}