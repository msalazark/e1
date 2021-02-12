<?php

defined('_JEXEC') or die;


class Esan_EventosViewDetails extends JViewLegacy {

	public function display ($tpl = null) {
		$this->data = $this->get('data');
		$this->insertAssets();
		$this->insertMetaInfo();
		parent::display($tpl);
	}


	private function insertAssets () {
		$document = JFactory::getDocument();
		$document->addScript('/administrator/templates/eventos/EsanEventoRegistro.js');
	}


	private function insertMetaInfo () {
		$app      = JFactory::getApplication();
		$document = JFactory::getDocument();
		$evento   = $this->data['evento'];

		// ------------------------

		$title          = $evento['nombre'] . ' | Eventos - ' . $app->getCfg('sitename');
		$og_url         = JUri::getInstance() . '';
		$og_title       = $title;
		$og_image       = $evento['imagen1'] ? rtrim(JUri::base(), '/') . '/' . ltrim($evento['imagen1'], '/') : null;
		$og_description = $evento['descripcion'] ? strip_tags($evento['descripcion']) : null;

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

}
