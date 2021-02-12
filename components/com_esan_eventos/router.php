<?php

defined('_JEXEC') or die;


class Esan_EventosRouter implements JComponentRouterInterface {

	public function build (&$query) {
		$segments = [];

		if (isset($query['view'])) {
			unset($query['view']);
		}

		if (isset($query['evento'])) {
			$segments[] = $query['evento'];
			unset($query['evento']);
		}

		return $segments;
	}


	public function parse (&$segments) {
		$vars = [];

		if (count($segments)) {
			$vars['view'] = 'details';
			$vars['evento'] = array_shift($segments);
		}

		return $vars;
	}


	public function preprocess ($query) {
		return $query;
	}

}
