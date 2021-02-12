<?php

defined('_JEXEC') or die;


use Joomla\CMS\Language\Text;
use Joomla\CMS\Date\Date;
use Joomla\CMS\Factory;


class Esan_BlogModelList extends JModelItem {

	protected $tz = null;
	protected $today = null;

	protected $params = null;

	protected $params_defaults = [
		'blog'         => '',
		'destacados'   => '0',
		'pagina_items' => '9',
		'pagina'       => '1',

		'especialidad' => '',
		'categoria'    => '',
		'usuario'      => '',
		'fecha'        => '',
	];

	protected $blog = [];

	protected $related = [];

	protected $filters = [];

	protected $filters_params = [
		'especialidad',
		'categoria',
		'usuario',
		'fecha',
	];

	protected $destacados = null;

	protected $results = null;
	protected $results_info = null;


	public function __construct ($config = []) {
		$this->tz = Factory::getUser()->getTimezone();

		$this->today = new DateTime('now', new DateTimeZone('America/Lima'));
		$this->today = $this->today->format('Y-m-d');

		$this->loadBlogInfo();
		$this->loadRelated();
		$this->loadFilterFechas();

		parent::__construct($config);
	}


	private function loadBlogInfo () {
		$params = $this->getParams();

		$this->blog = $this->dbQuery([
			'from'   => 'blog',
			'where'  => ["alias = '{$params['blog']}'"],
		])->loadAssoc();

		if (!$this->blog) {
			throw new Exception('Blog not found');
		}
	}


	public function getParamsDefaults () {
		return $this->params_defaults;
	}


	public function getParams () {
		if ($this->params) {
			return $this->params;
		}

		$params = [
			'blog'         => '/^[\w\-]{1,32}$/',
			'destacados'   => '/^[01]$/',
			'pagina_items' => '/^\d+$/',
			'pagina'       => '/^\d+$/',

			'especialidad' => '/^[\w\-]{1,100}$/',
			'categoria'    => '/^[\w\-]{1,100}$/',
			'usuario'      => '/^[\w\-]{1,100}$/',
			'fecha'        => '/^\d{4}-\d{2}$/',
		];

		$jinput = JFactory::getApplication()->input;

		foreach ($params as $k => $pattern) {
			$value = $jinput->get($k, null, 'RAW');

			if ($value && preg_match($pattern, $value)) {
				$params[$k] = $value;

			} else if (isset($_GET[$k]) && preg_match($pattern, $_GET[$k])) {
				$params[$k] = $_GET[$k];

			} else {
				$params[$k] = $this->params_defaults[$k];
			}
		}

		foreach (['pagina_items', 'pagina'] as $k) {
			if ($params[$k] === '0') {
				$params[$k] = $this->params_defaults[$k];
			}
		}

		$this->params = $params;

		return $this->params;
	}


	public function getRelated () {
		return $this->related;
	}


	private function loadRelated () {
		$related = [
			'especialidades' => [],
			'categorias'     => [],
			'usuarios'       => [],
		];

		$filters = [
			'especialidades' => [],
			'categorias'     => [],
			'usuarios'       => [],
		];

		$relations = [
			'especialidades' => 'blog_especialidad',
			'categorias'     => 'blog_categoria',
			'usuarios'       => 'usuario',
		];

		$columns = [
			'blog_especialidad' => "DISTINCT t.id, t.alias, t.nombre, t.menu",
			'blog_categoria'    => "DISTINCT t.id, t.alias, t.nombre, 1 AS menu",
			'usuario'           => "DISTINCT t.id, t.alias, TRIM(CONCAT(t.nombres, ' ', t.apellidos)) AS nombre, 1 AS menu",
		];

		foreach ($relations as $k => $table) {
			$cols = $columns[$table];

			$from = "{$table} AS t";

			$joins = [
				['INNER', "blog_articulo_{$table} AS ta ON t.id = ta.{$table}_id"],
				['INNER', "blog_articulo          AS  a ON a.id = ta.blog_articulo_id"],
			];

			$where = [
				'a.blog_id = ' . $this->blog['id'],
				'a.estado = 1',
				"a.fecha_publicacion <= '{$this->today}'",
			];

			$order = 'nombre ASC';

			$query_params = compact('cols', 'from', 'joins', 'where', 'order');

			$rows = $this->dbQuery($query_params)->loadAssocList();

			foreach ($rows as $row) {
				$related[$k][$row['alias']] = $row;

				$filters[$k][] = [
					'value' => $row['alias'],
					'text'  => $row['nombre'],
					'menu'  => intval($row['menu']),
				];
			}
		}


		// ---------------

		$filters_params = $this->filters_params;

		$this->related = $related;

		$this->filters = [
			'items' => array_filter($this->getParams(), function ($k) use ($filters_params) { return in_array($k, $filters_params); }, ARRAY_FILTER_USE_KEY),
			'data'  => $filters,
		];
	}


	// -------------------------------------------------------------------------


	public function getFilters () {
		return $this->filters;
	}


	private function loadFilterFechas () {
		$cols = [
			"MIN(a.fecha_publicacion) AS fecha_min",
			"MAX(a.fecha_publicacion) AS fecha_max",
		];

		$from = 'blog_articulo AS a';

		$where = [
			'a.blog_id = ' . $this->blog['id'],
			'a.estado = 1',
			"a.fecha_publicacion <= '{$this->today}'",
		];

		$query_params = compact('cols', 'from', 'where');

		$result = $this->dbQuery($query_params)->loadAssoc();

		$date_min = new Date($result['fecha_min']);
		$date_min->setTimezone($this->tz);

		$date_max = new Date($result['fecha_max']);
		$date_max->setTimezone($this->tz);

		$data = [
			[
				'value' => $date_min->format('Y-m'),
				'text'  => $date_min->format(Text::_('Y - F')),
				'menu'  => 1,
			],
			[
				'value' => $date_max->format('Y-m'),
				'text'  => $date_max->format(Text::_('Y - F')),
				'menu'  => 1,
			],
		];

		$this->filters['data']['fechas'] = $data;
	}


	// -------------------------------------------------------------------------


	public function getDestacados () {
		if (is_null($this->destacados)) {
			$this->loadDestacados();
		}

		return $this->destacados;
	}


	private function loadDestacados () {
		if ($this->getParams()['destacados'] !== '1') {
			return;
		}

		$cols = [
			'id',
			'alias',
			'nombre',
			'imagen_lista',
			'imagen_banner',
			'imagen1',
		];

		$from = 'blog_articulo';

		$where = [
			'blog_id = ' . $this->blog['id'],
			'estado = 1',
			'destacado = 1',
			"fecha_publicacion <= '{$this->today}'",
		];

		$order = 'fecha_publicacion DESC';

		$query_params = compact('cols', 'from', 'where', 'order');

		$this->destacados = $this->dbQuery($query_params)->loadAssocList();

		foreach ($this->destacados as $i => $articulo) {
			$this->destacados[$i]['imagen_banner'] = $articulo['imagen_banner'] ?: ($articulo['imagen1'] ?: $articulo['imagen_lista']);
			$this->loadArticuloExtraData($this->destacados[$i], ['usuarios']);
		}
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


	private function findFilter ($k, $alias) {
		return isset($this->related[$k][$alias]) ? $this->related[$k][$alias]['id'] : null;
	}


	private function loadResults () {
		$params = $this->getParams();

		$cols = [
			'a.id',
			'a.alias',
			'a.nombre',
			'a.imagen_lista',
			'a.imagen1',
			'a.descripcion',
			'a.fecha_publicacion',
		];

		$from = 'blog_articulo AS a';

		$joins = [];

		$where = [
			'a.blog_id = ' . $this->blog['id'],
			'a.estado = 1',
			"a.fecha_publicacion <= '{$this->today}'",
		];

		$order = 'a.fecha_publicacion DESC';

		$limit  = intval($params['pagina_items']);
		$offset = (intval($params['pagina']) - 1) * $limit;

		// -----------------

		if ($params['especialidad']) {
			$especialidad_id = $this->findFilter('especialidades', $params['especialidad']);

			if ($especialidad_id) {
				$joins[] = ['INNER', 'blog_articulo_blog_especialidad AS ae ON a.id = ae.blog_articulo_id'];
				$where[] = "ae.blog_especialidad_id = '{$especialidad_id}'";
			}
		}

		if ($params['categoria']) {
			$categoria_id = $this->findFilter('categorias', $params['categoria']);

			if ($categoria_id) {
				$joins[] = ['INNER', 'blog_articulo_blog_categoria AS ac ON a.id = ac.blog_articulo_id'];
				$where[] = "ac.blog_categoria_id = '{$categoria_id}'";
			}
		}

		if ($params['usuario']) {
			$usuario_id = $this->findFilter('usuarios', $params['usuario']);

			if ($usuario_id) {
				$joins[] = ['INNER', 'blog_articulo_usuario AS au ON a.id = au.blog_articulo_id'];
				$where[] = "au.usuario_id = '{$usuario_id}'";
			}
		}

		if ($params['fecha']) {
			list ($y, $m) = array_map('intval', explode('-', $params['fecha']));
			$where[] = "YEAR(a.fecha_publicacion) = {$y} AND MONTH(a.fecha_publicacion) = {$m}";
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

		$this->results = $this->dbQuery($query_params)->loadAssocList();


		// -----------------

		foreach ($this->results as $i => $articulo) {
			$this->loadArticuloExtraData($this->results[$i], ['especialidades', 'categorias']);
		}
	}


	private function loadArticuloExtraData (&$articulo, $extras = []) {
		$tables = [
			'especialidades' => 'blog_especialidad',
			'categorias'     => 'blog_categoria',
			'usuarios'       => 'usuario',
		];

		$columns = [
			'blog_especialidad' => ['t.nombre', 't.alias'],
			'blog_categoria'    => ['t.nombre', 't.alias', 't.color'],
			'usuario'           => ['t.alias', "TRIM(CONCAT(t.nombres, ' ', t.apellidos)) AS nombre"],
		];

		if (isset($articulo['fecha_publicacion'])) {
			$date = new Date($articulo['fecha_publicacion']);
			$date->setTimezone($this->tz);
			$articulo['fecha_publicacion'] = $date->format(Text::_('d F Y'));
		}

		foreach ($extras as $k) {
			$table = $tables[$k];

			$articulo[$k] = $this->dbQuery([
				'cols'  => $columns[$table],
				'from'  => "{$table} AS t",
				'joins' => [['INNER', "blog_articulo_{$table} AS r ON t.id = r.{$table}_id"]],
				'where' => ["r.blog_articulo_id = {$articulo['id']}"],
			])->loadAssocList();
		}
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


	/* private function parseResults ($rows, $groups = []) {
		$results = [];

		foreach ($groups as $group) {
			$results[$group] = [];
		}

		foreach ($rows as $row) {
			$item = [];

			foreach ($row as $column => $value) {
				list ($prefix, $column) = explode('_', $column, 2);

				if (!isset($item[$prefix])) {
					$item[$prefix] = [];
				}

				$item[$prefix][$column] = $value;
			}

			foreach ($item as $prefix => $data) {
				if ($data['id'] !== null) {
					$results[$prefix][$data['id']] = $data;
				}
			}
		}

		return $results;
	} */

}
