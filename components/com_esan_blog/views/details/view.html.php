<?php

use function PHPSTORM_META\type;

defined('_JEXEC') or die;


class Esan_BlogViewDetails extends JViewLegacy {

	public function display ($tpl = null) {
		$this->data = $this->get('data');
		$this->insertMetaInfo();
		parent::display($tpl);
	}


	private function insertMetaInfo () {
		$app      = JFactory::getApplication();
		$document = JFactory::getDocument();
		$articulo = $this->data['articulo'];

		// ------------------------

		$title          = $articulo['nombre'] . ' | ' . $app->getCfg('sitename');
		$og_url         = JUri::getInstance() . '';
		$og_title       = $title;
		$og_image       = $articulo['imagen1'] ? rtrim(JUri::base(), '/') . '/' . ltrim($articulo['imagen1'], '/') : null;
		$og_description = $articulo['descripcion'] ? strip_tags($articulo['descripcion']) : null;

		// ------------------------

		$document->setTitle($title);
		$document->addCustomTag('<meta property="og:type" content="article" />');
		$document->addCustomTag('<meta property="og:locale" content="es_PE" />');
		$document->addCustomTag('<meta property="og:url" content="' . htmlspecialchars($og_url) . '" />');
		$document->addCustomTag('<meta property="og:title" content="' . htmlspecialchars($og_title) . '" />');

		if ($og_description) {
			$document->addCustomTag('<meta property="og:description" content="' . htmlspecialchars($og_description) . '" />');
		}

		if ($og_image) {
			$document->addCustomTag('<meta property="og:image" content="' . htmlspecialchars($og_image) . '" />');
		}
	}


	protected function url ($view, $vars = [], $include_filters = false) {
		$filters = $include_filters ? array_filter($this->filters['items']) : [];

		$vars = array_merge([
			'option' => 'com_esan_blog',
			'view'   => $view,
		], $filters, $vars);

		return JRoute::_('index.php?' . http_build_query($vars, null, '&'));
	}

	protected function shareURL ($k) {
		$types = [
			'facebook'  => 'https://www.facebook.com/sharer/sharer.php?u={url}',
			'twitter'   => 'https://twitter.com/intent/tweet?url={url}&text={title}',
			'linkedin'  => 'http://www.linkedin.com/shareArticle?mini=true&url={url}&title={title}',
			'pinterest' => 'http://pinterest.com/pin/create/button/?url={url}&media=&description={title}',
			'whatsapp'  => 'https://wa.me/?text={url}',
		];

		if (!isset($types[$k])) {
			return '#';
		}

		$url   = JUri::getInstance() . '';
		$title = $this->data['articulo']['descripcion'];

		return str_replace(['{url}', '{title}'], [urlencode($url), urlencode($title)], $types[$k]);
	}

}
