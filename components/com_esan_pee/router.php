<?php

defined('_JEXEC') or die;


class Esan_PEERouter implements JComponentRouterInterface {


	public function build (&$query) {
		$segments = [];

		if (isset($query['view'])) {
			unset($query['view']);
		}

		if (isset($query['especialidad_tipo'])) {
			$segments[] = $query['especialidad_tipo'];
			unset($query['especialidad_tipo']);
		}

		if (isset($query['curso'])) {
			$segments[] = $query['curso'];
			unset($query['curso']);
		}

		if (isset($query['programa'])) {
			$segments[] = $query['programa'];
			unset($query['programa']);
		}

		return $segments;
	}


	public function parse (&$segments) {
		$vars = [];

		$vars['view'] = 'home';

		if (count($segments)) {
			$vars['view'] = 'especialidad_tipo';
			$vars['especialidad_tipo'] = array_shift($segments);
		}

		if (count($segments)) {
			$vars['view'] = 'curso';
			$vars['curso'] = array_shift($segments);
		}

		if (count($segments)) {
			$vars['programa'] = array_shift($segments);
		}

		return $vars;
	}


	public function preprocess ($query) {
		return $query;
	}

}
