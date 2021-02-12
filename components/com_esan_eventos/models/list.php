<?php

defined('_JEXEC') or die;


class Esan_EventosModelList extends JModelItem {

	protected $filters = null;

	protected $results = null;
	protected $results_info = null;

	protected $destacados = null;

	protected $today = null;


	public function __construct($config = []) {
		$this->today = new DateTime('now', new DateTimeZone('America/Lima'));
		$this->today = $this->today->format('Y-m-d');

		parent::__construct($config);
	}


	public function getParams () {
		$params = [
			'tipo'   => '/^[A-Za-z0-9\-]{1,64}$/',
			'area'   => '/^[A-Za-z0-9\-]{1,128}$/',
			'ciudad' => '/^[A-Za-z0-9\-]{1,64}$/',
			'fecha'  => '/^\d{4}-\d{2}-\d{2}+$/',
			'pagina' => '/^\d+$/',
		];

		foreach ($params as $k => $pattern) {
			if (isset($_GET[$k]) && preg_match($pattern, $_GET[$k])) {
				$params[$k] = $_GET[$k];
			} else {
				$params[$k] = '';
			}
		}

		if ($params['pagina'] === '') {
			$params['pagina'] = 1;
		}

		return $params;
	}


	// -------------------------------------------------------------------------


	public function getFilters () {
		if (is_null($this->filters)) {
			$this->loadFilters();
		}

		return $this->filters;
	}


	private function loadFilters () {
		$cols = [
			'e.fecha  AS eve_id',
			'e.fecha  AS eve_value',
			'e.fecha  AS eve_text',

			't.id     AS tip_id',
			't.alias  AS tip_value',
			't.nombre AS tip_text',

			'a.id     AS are_id',
			'a.alias  AS are_value',
			'a.nombre AS are_text',

			'c.id     AS ciu_id',
			'c.alias  AS ciu_value',
			'c.nombre AS ciu_text',
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

		$order = 'tip_text, are_text, ciu_text';

		// -----------------

		$query_params = compact('cols', 'from', 'joins', 'where', 'order');

		$this->filters = [
			'items' => $this->getParams(),
			'data'  => $this->parseResults($this->dbQuery($query_params)->loadAssocList()),
		];
	}


	// -------------------------------------------------------------------------


	public function getResults () {
		if (is_null($this->results)) {
			$this->loadResults();
		}

		return $this->results;
	}


	public function getResultsInfo () {
		if (is_null($this->results_info)) {
			$this->loadResults();
		}

		return $this->results_info;
	}


	private function loadResults () {
		$cols = [
			'e.id                AS eve_id',
			'e.evento_tipo_id    AS eve_tipo_id',
			'e.area_id           AS eve_area_id',
			'e.ciudad_id         AS eve_ciudad_id',
			'e.alias             AS eve_alias',
			'e.nombre            AS eve_nombre',
			'e.imagen_lista      AS eve_imagen_lista',
			'e.fecha             AS eve_fecha',
			'e.fecha_texto       AS eve_fecha_texto',
			'e.hora              AS eve_hora',
			'e.lugar             AS eve_lugar',
			'e.lugar_url         AS eve_lugar_url',
			'e.url               AS eve_url',
			'e.costo             AS eve_costo',
			'e.descripcion       AS eve_descripcion',
			'e.pronto_pago_costo AS eve_pronto_pago_costo',
			'e.pronto_pago_fecha AS eve_pronto_pago_fecha',

			't.id      AS tip_id',
			't.alias   AS tip_alias',
			't.nombre  AS tip_nombre',

			'a.id      AS are_id',
			'a.alias   AS are_alias',
			'a.nombre  AS are_nombre',

			'c.id      AS ciu_id',
			'c.alias   AS ciu_alias',
			'c.nombre  AS ciu_nombre',
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

		$limit  = 6;
		$offset = 0;

		// -----------------

		$params = $this->getParams();

		if ($params['tipo']) {
			$where[] = "t.alias = '{$params['tipo']}'";
		}

		if ($params['area']) {
			$where[] = "a.alias = '{$params['area']}'";
		}

		if ($params['fecha']) {
			$where[] = "e.fecha = '{$params['fecha']}'";
		}

		if ($params['ciudad']) {
			$where[] = "c.alias = '{$params['ciudad']}'";
		}

		if ($params['pagina']) {
			$offset .= (intval($params['pagina']) - 1) * $limit;
		}


		// -----------------

		$count_params = compact('from', 'joins', 'where');
		$count_params['cols'] = 'COUNT(1)';

		$this->results_info = [
			'limit' => $limit,
			'total' => $this->dbQuery($count_params)->loadResult(),
		];


		// -----------------

		$query_params = compact('cols', 'from', 'joins', 'where', 'order', 'limit', 'offset');

		$this->results = $this->parseResults($this->dbQuery($query_params)->loadAssocList());
	}


	// -------------------------------------------------------------------------


	public function getDestacados () {
		if (is_null($this->destacados)) {
			$this->loadDestacados();
		}

		return $this->destacados;
	}


	private function loadDestacados () {
		$cols = [
			'e.id                AS eve_id',
			'e.evento_tipo_id    AS eve_tipo_id',
			'e.area_id           AS eve_area_id',
			'e.ciudad_id         AS eve_ciudad_id',
			'e.alias             AS eve_alias',
			'e.nombre            AS eve_nombre',
			'e.imagen_banner     AS eve_imagen_banner',
			'e.fecha_texto       AS eve_fecha_texto',
			'e.url               AS eve_url',
			'e.costo             AS eve_costo',
			'e.pronto_pago_costo AS eve_pronto_pago_costo',
			'e.pronto_pago_fecha AS eve_pronto_pago_fecha',

			't.id     AS tip_id',
			't.alias  AS tip_alias',
			't.nombre AS tip_nombre',

			'a.id     AS are_id',
			'a.alias  AS are_alias',
			'a.nombre AS are_nombre',

			'c.id     AS ciu_id',
			'c.alias  AS ciu_alias',
			'c.nombre AS ciu_nombre',
		];

		$from = 'evento AS e';

		$joins = [
			['INNER', 'evento_tipo AS t  ON t.id = e.evento_tipo_id'],
			['INNER', 'area        AS a  ON a.id = e.area_id'],
			['INNER', 'ciudad      AS c  ON c.id = e.ciudad_id'],
		];

		$where = [
			'e.estado = 1',
			'e.destacado = 1',
			"e.fecha >= '{$this->today}'"
		];

		$order = 'e.fecha ASC';


		// -----------------

		$query_params = compact('cols', 'from', 'joins', 'where', 'order');

		$this->destacados = $this->parseResults($this->dbQuery($query_params)->loadAssocList());
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
		$results = [];
		$sample  = [];

		foreach (['eventos', 'tipos', 'areas', 'ciudades'] as $k) {
			$results[$k] = [];
			$sample[substr($k, 0, 3)] = [];
		}

		foreach ($rows as $row) {
			$item = $sample;

			foreach ($row as $column => $value) {
				list ($prefix, $column) = explode('_', $column, 2);

				$item[$prefix][$column] = $value;
			}

			foreach ($results as $k => $tmp) {
				$q = substr($k, 0, 3);
				$results[$k][$item[$q]['id']] = $item[$q];
			}
		}

		return $results;
	}

}
