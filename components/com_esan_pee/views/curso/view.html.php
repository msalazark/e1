<?php

defined('_JEXEC') or die;


use Joomla\CMS\Language\Text;
use Joomla\CMS\Date\Date;
use Joomla\CMS\Factory;


require_once JPATH_SITE . '/modules/mod_esan_curso/helper.php';



class Esan_PEEViewCurso extends JViewLegacy {

	protected $params = null;
	protected $data   = null;


	public function display ($tpl = null) {
		$this->params = $this->get('Params');
		$this->data   = $this->get('Data');

		$this->insertMetaInfo();

		parent::display($tpl);
	}


	private function insertMetaInfo () {
		$app      = JFactory::getApplication();
		$document = JFactory::getDocument();

		$tipo  = $this->findDataItem('especialidad_tipos', 'alias', $this->params['especialidad_tipo']);
		$curso = $this->findDataItem('cursos', 'alias', $this->params['curso']);

		// ------------------------

		$title          = $curso['nombre'] . ' - ' . $tipo['nombre'] . ' | '. $app->getCfg('sitename');
		$og_url         = JUri::getInstance() . '';
		$og_title       = $title;
		$og_description = $curso['descripcion'] ? strip_tags($curso['descripcion']) : null;

		// ------------------------

		$document->setTitle($title);
		$document->addCustomTag('<meta property="og:type" content="article" />');
		$document->addCustomTag('<meta property="og:locale" content="es_PE" />');
		$document->addCustomTag('<meta property="og:url" content="' . htmlspecialchars($og_url) . '" />');
		$document->addCustomTag('<meta property="og:title" content="' . htmlspecialchars($og_title) . '" />');

		if ($og_description) {
			$document->addCustomTag('<meta property="og:description" content="' . htmlspecialchars($og_description) . '" />');
		}
	}


	protected function url ($view, $vars = []) {
		$vars = array_merge([
			'option' => 'com_esan_pee',
			'view'   => $view,
		], $vars);

		return JRoute::_('index.php?' . http_build_query($vars, null, '&'));
	}


	protected function filterDataItems ($group, $col, $value) {
		return array_filter($this->data[$group], function ($item) use ($col, $value) {
			if (is_array($value) && in_array($item[$col], $value)) {
				return true;
			} else if ($item[$col] === $value) {
				return true;
			} else {
				return false;
			}
		});
	}


	protected function findDataItem ($group, $col, $value) {
		foreach ($this->data[$group] as $item) {
			if (is_array($value) && in_array($item[$col], $value)) {
				return $item;
			} else if ($item[$col] === $value) {
				return $item;
			}
		}
	}


	protected function getCursoData ($curso_id) {
		$mod_esan_curso = new ModEsanCurso();
		return $mod_esan_curso->loadData($curso_id, true);
	}


	// -------------------------------------------------------------------------

	public function printTemplate ($tpl_code) {
		$data = [
			'basepath'  => JUri::base(true),
			'data'      => &$this->data,
			'tipo'      => $this->findDataItem('especialidad_tipos', 'alias', $this->params['especialidad_tipo']),
			'curso'     => $this->findDataItem('cursos', 'alias', $this->params['curso']),
			'programa'  => null,
			'modalidad' => null,
			'ciudad'    => null,
			'sede'      => null,
		];

		if ($this->params['programa']) {
			$data['programa']  = $this->findDataItem('curso_programas', 'id', $this->params['programa']);
			$data['modalidad'] = $this->findDataItem('modalidades', 'id', $data['programa']['modalidad_id']);
			$data['ciudad']    = $this->findDataItem('ciudades', 'id', $data['programa']['ciudad_id']);
			$data['sede']      = $this->findDataItem('ciudad_sedes', 'id', $data['programa']['ciudad_sede_id']);
		}

		SimpleTemplateEngine::print($tpl_code, $data);
	}


	protected function formatDate ($date, $format) {
		$fecha = new Date($date);
		$fecha->setTimezone(Factory::getUser()->getTimezone());

		return $fecha->format(Text::_($format));
	}

}
