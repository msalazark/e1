<?php

defined('_JEXEC') or die;


class Esan_BlogRouter implements JComponentRouterInterface {

	private $blogs_alias = [
		'conexion-esan'  => '/conexion-esan/',
		'publicaciones'  => '/publicaciones/',
		'sala-de-prensa' => '/sala-de-prensa/',
	];


	public function build (&$query) {
		$segments = [];

		if (isset($query['view'])) {
			unset($query['view']);
		}

		if (isset($query['articulo'])) {
			$segments[] = $query['articulo'];
			unset($query['articulo']);
		}

		return $segments;
	}


	public function parse (&$segments) {
		$vars = [];

		if (count($segments)) {
			$vars['view'] = 'details';
			$vars['blog'] = $this->identifyBlog();
			$vars['articulo'] = array_shift($segments);
		}

		return $vars;
	}


	public function preprocess ($query) {
		return $query;
	}


	private function identifyBlog () {
		$juri = JUri::getInstance();
		$base = $juri->root(true);
		$uri_path = $juri->getPath();

		foreach ($this->blogs_alias as $k => $path) {
			$pattern = sprintf(':^%s(/.*|$):', $base . rtrim($path, '/'));

			if (preg_match($pattern, $uri_path)) {
				return $k;
			}
		}

		throw new Exception('Blog parameter not found');
	}

}
