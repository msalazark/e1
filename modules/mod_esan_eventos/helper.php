<?php

use Joomla\CMS\Language\Text;
use Joomla\CMS\Date\Date;
use Joomla\CMS\Factory;

class ModEsanEventos {

	private $params;

	private $data;

	protected $today = null;


	public function __construct ($params) {
		$this->params = $params;

		$this->today = new DateTime('now', new DateTimeZone('America/Lima'));
		$this->today = $this->today->format('Y-m-d');

		$this->loadData();
	}


	private function loadData () {
		$area_id     = $this->params->get('area_id', 0, 'UINT');
		$tipo_id     = $this->params->get('tipo_id', 0, 'UINT');
		$ciudad_id   = $this->params->get('ciudad_id', 0, 'UINT');
		$ponente_id  = $this->params->get('ponente_id', 0, 'UINT');
		$curso_id    = $this->params->get('curso_id', 0, 'UINT');
		$programa_id = $this->params->get('programa_id', 0, 'UINT');
		$limite      = $this->params->get('limite', 100, 'UINT');
		$destacados  = $this->params->get('destacados', 0, 'UINT');

		// ------------

		$cols = [
			'DISTINCT '.

			'e.id             AS eve_id',
			'e.evento_tipo_id AS eve_tipo_id',
			'e.area_id        AS eve_area_id',
			'e.ciudad_id      AS eve_ciudad_id',
			'e.alias          AS eve_alias',
			'e.nombre         AS eve_nombre',
			'e.imagen1        AS eve_imagen1',
			'e.fecha          AS eve_fecha',
			'e.fecha_texto    AS eve_fecha_texto',
			'e.hora           AS eve_hora',
			'e.lugar          AS eve_lugar',
			'e.lugar_url      AS eve_lugar_url',
			'e.url            AS eve_url',
			'e.costo          AS eve_costo',
			'e.descripcion    AS eve_descripcion',

			't.id             AS tip_id',
			't.alias          AS tip_alias',
			't.nombre         AS tip_nombre',

			'a.id             AS are_id',
			'a.alias          AS are_alias',
			'a.nombre         AS are_nombre',

			'c.id             AS ciu_id',
			'c.alias          AS ciu_alias',
			'c.nombre         AS ciu_nombre',
		];

		$from = 'evento AS e';

		$joins = [
			['INNER', 'evento_tipo AS t  ON t.id = e.evento_tipo_id'],
			['INNER', 'area        AS a  ON a.id = e.area_id'],
			['INNER', 'ciudad      AS c  ON c.id = e.ciudad_id'],
		];

		$where = [
			'e.estado = 1',
			"e.fecha >= '{$this->today}'"
		];

		$order = 'e.fecha ASC';

		$limit  = $limite;
		$offset = 0;

		// -----------------

		if ($destacados) {
			$where[] = 'e.destacado = 1';
		}

		if ($tipo_id) {
			$where[] = "t.id = {$tipo_id}";
		}

		if ($area_id) {
			$where[] = "a.id = {$area_id}";
		}

		if ($ciudad_id) {
			$where[] = "c.id = {$ciudad_id}";
		}

		if ($ponente_id) {
			$joins[] = ['INNER', 'evento_usuario AS ed ON e.id = ed.evento_id'];
			$where[] = "ed.usuario_id = {$ponente_id}";
		}

		if ($programa_id) {
			$joins[] = ['INNER', 'evento_curso_programa AS ep ON e.id = ep.evento_id'];
			$where[] = "ep.curso_programa_id = {$programa_id}";

		} else if ($curso_id) {
			$joins[] = ['INNER', 'evento_curso_programa AS ep ON e.id = ep.evento_id'];
			$joins[] = ['INNER', 'curso_programa AS cp ON cp.id = ep.curso_programa_id'];
			$where[] = "cp.curso_id = {$curso_id}";
		}

		// -----------------

		$query_params = compact('cols', 'from', 'joins', 'where', 'order', 'limit', 'offset');

		$this->data = $this->parseResults($this->dbQuery($query_params)->loadAssocList());
	}


	// -------------------------------------------------------------------------


	private function dbQuery ($params) {
		extract(array_merge([
			'cols'   => ['*'],
			'joins'  => [],
			'where'  => [],
			'order'  => null,
			'limit'  => null,
			'offset' => 0,
		], $params));

		$db = FabrikWorker::getDbo(false, 2);

		$query = $db->getQuery(true);

		$query->select($cols);
		$query->from($from);

		foreach ($joins as $join) {
			$query->join($join[0], $join[1]);
		}

		if (count($where) > 0) {
			$query->where(implode(' AND ', $where));
		}

		if ($order) {
			$query->order($order);
		}

		if ($limit) {
			$query->setLimit($limit, $offset);
		}

		$db->setQuery($query);

		return $db;
	}


	private function parseResults ($rows) {
		$data = [];

		foreach ($rows as $row) {
			$data[] = [
				'id'           => $row['eve_id'],
				'alias'        => $row['eve_alias'],
				'nombre'       => $row['eve_nombre'],
				'imagen1'      => $row['eve_imagen1'],
				'fecha'        => $row['eve_fecha'],
				'fecha_texto'  => $row['eve_fecha_texto'],
				'hora'         => $row['eve_hora'],
				'lugar'        => $row['eve_lugar'],
				'lugar_url'    => $row['eve_lugar_url'],
				'url'          => $row['eve_url'],
				'costo'        => $row['eve_costo'],
				'descripcion'  => $row['eve_descripcion'],
				'href'         => $this->eventoURL($row['eve_alias']),

				'area' => [
					'id'     => $row['are_id'],
					'alias'  => $row['are_alias'],
					'nombre' => $row['are_nombre'],
				],

				'tipo' => [
					'id'     => $row['tip_id'],
					'alias'  => $row['tip_alias'],
					'nombre' => $row['tip_nombre'],
				],

				'ciudad' => [
					'id'     => $row['ciu_id'],
					'alias'  => $row['ciu_alias'],
					'nombre' => $row['ciu_nombre'],
				],
			];
		}

		return $data;
	}


	// -------------------------------------------------------------------------


	protected function eventoURL ($evento) {
		$vars = [
			'option' => 'com_esan_eventos',
			'view'   => 'details',
			'evento' => $evento,
		];

		$menu_item_id = $this->params->get('menu_item');

		if ($menu_item_id) {
			$vars['Itemid'] = $menu_item_id;
		}

		return JRoute::_('index.php?' . http_build_query($vars, null, '&'));
	}


	public function dump () {
		$eventos  = &$this->data;
		$template = trim($this->params->get('template') ?: '');

		if ($template !== '') {
			$tmp_file = tempnam(JPATH_SITE . '/tmp', 'html');
			file_put_contents($tmp_file, $template);
			require_once $tmp_file;
			unlink($tmp_file);
		}
	}


	private function fecha ($fecha, $format) {
		$date = new Date($fecha);
		$date->setTimezone(Factory::getUser()->getTimezone());
		return $date->format(Text::_($format));
	}

}
