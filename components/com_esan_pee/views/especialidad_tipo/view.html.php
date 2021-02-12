<?php

defined('_JEXEC') or die;


use Joomla\CMS\Language\Text;
use Joomla\CMS\Date\Date;
use Joomla\CMS\Factory;


class Esan_PEEViewEspecialidad_Tipo extends JViewLegacy {

	protected $params = null;
	protected $data   = null;
	protected $groups = null;


	public function display ($tpl = null) {
		$this->params = $this->get('Params');
		$this->data   = $this->get('Data');
		$this->groups = $this->parseData($this->data);

		$this->insertMetaInfo();

		parent::display($tpl);
	}


	private function insertMetaInfo () {
		$app      = JFactory::getApplication();
		$document = JFactory::getDocument();

		$tipo = $this->findDataItem('especialidad_tipos', 'alias', $this->params['especialidad_tipo']);

		// ------------------------

		$title    = $tipo['nombre'] . ' | '. $app->getCfg('sitename');
		$og_url   = JUri::getInstance() . '';
		$og_title = $title;

		// ------------------------

		$document->setTitle($title);
		$document->addCustomTag('<meta property="og:type" content="website" />');
		$document->addCustomTag('<meta property="og:locale" content="es_PE" />');
		$document->addCustomTag('<meta property="og:url" content="' . htmlspecialchars($og_url) . '" />');
		$document->addCustomTag('<meta property="og:title" content="' . htmlspecialchars($og_title) . '" />');
	}


	protected function url ($view, $vars = []) {
		$vars = array_merge([
			'option' => 'com_esan_pee',
			'view'   => $view,
		], $vars);

		return JRoute::_('index.php?' . http_build_query($vars, null, '&'));
	}


	protected function filterDataItems ($group, $fn) {
		if (is_string($group)) {
			$group = $this->data[$group];
		}

		return array_filter($group, $fn);
	}


	protected function filterDataItemsByColum ($group, $col, $value) {
		if (is_string($group)) {
			$group = $this->data[$group];
		}

		return array_filter($group, function ($item) use ($col, $value) {
			return (is_array($value) && in_array($item[$col], $value)) || $item[$col] === $value;
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


	private function parseData ($data) {
		$groups = [];


		// TODOS LOS CURSOS -------

		$group = [
			'id'      => 'todos',
			'name'    => 'Todos los programas',
			'sede_id' => null,
			'areas'   => [],
		];

		foreach ($data['areas'] as $a) {
			$area = $a;

			$especialidades = $this->filterDataItemsByColum('especialidades', 'area_id', $a['id']);

			foreach ($especialidades as $i => $e) {
				$especialidades[$i]['tipo'] = $this->findDataItem('especialidad_tipos', 'id', $e['especialidad_tipo_id']);
				$especialidades[$i]['cursos'] = $this->filterDataItemsByColum('cursos', 'especialidad_id', $e['id']);
			}

			$area['especialidades'] = $especialidades;
			$group['areas'][] = $area;
		}

		$groups[] = $group;


		// CURSOS POR SEDE -------

		foreach ($data['ciudad_sedes'] as $cs) {
			$_curso_programas = $this->filterDataItemsByColum('curso_programas', 'ciudad_sede_id', $cs['id']);
			$_cursos          = $this->filterDataItemsByColum('cursos', 'id', array_column($_curso_programas, 'curso_id'));
			$_especialidades  = $this->filterDataItemsByColum('especialidades', 'id', array_column($_cursos, 'especialidad_id'));
			$_areas           = $this->filterDataItemsByColum('areas', 'id', array_column($_especialidades, 'area_id'));

			$group = [
				'id'      => $cs['alias'],
				'name'    => 'Programas en ' . $cs['nombre'],
				'sede_id' => $cs['id'],
				'areas'   => [],
			];

			foreach ($_areas as $a) {
				$area = $a;

				$especialidades = $this->filterDataItemsByColum($_especialidades, 'area_id', $a['id']);

				foreach ($especialidades as $i => $e) {
					$especialidades[$i]['tipo'] = $this->findDataItem('especialidad_tipos', 'id', $e['especialidad_tipo_id']);
					$especialidades[$i]['cursos'] = $this->filterDataItemsByColum($_cursos, 'especialidad_id', $e['id']);
				}

				$area['especialidades'] = $especialidades;
				$group['areas'][] = $area;
			}

			$groups[] = $group;
		}



		// -------

		return $groups;
	}


	protected function formatDate ($date, $format) {
		$fecha = new Date($date);
		$fecha->setTimezone(Factory::getUser()->getTimezone());

		return $fecha->format(Text::_($format));
	}

}
