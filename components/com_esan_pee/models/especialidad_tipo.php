<?php

defined('_JEXEC') or die;


class Esan_PEEModelEspecialidad_Tipo extends JModelItem {

	protected $params = null;
	protected $data   = null;

	protected $today = null;


	// -------------------------------------------------------------------------


	public function __construct($config = []) {
		$this->today = new DateTime('now', new DateTimeZone('America/Lima'));
		$this->today = $this->today->format('Y-m-d');

		parent::__construct($config);
	}


	public function getParams () {
		if ($this->params) {
			return $this->params;
		}

		$params = [
			'especialidad_tipo' => '/^[A-Za-z0-9\-]{1,128}$/',
		];

		$jinput = JFactory::getApplication()->input;

		foreach ($params as $k => $pattern) {
			$value = $jinput->get($k, null, 'RAW');

			if ($value && preg_match($pattern, $value)) {
				$params[$k] = $value;

			} else if (isset($_GET[$k]) && preg_match($pattern, $_GET[$k])) {
				$params[$k] = $_GET[$k];

			} else {
				$params[$k] = '';
			}
		}

		if (!$params['especialidad_tipo']) {
			throw new Exception('Missing `especialidad_tipo` param');
		}

		$this->params = $params;

		return $this->params;
	}


	// -------------------------------------------------------------------------


	public function getData () {
		if (is_null($this->data)) {
			$this->loadData();
		}

		if (count($this->data['especialidad_tipos']) === 0) {
			throw new Exception('`especialidad_tipo` item not found');
		}

		return $this->data;
	}


	private function loadData () {
		$params = $this->getParams();

		$groups = [
			'curso_programas' => [
				'table' => 'curso_programa',
				'alias' => 'cp',
				'columns' => [
					'id',
					'curso_id',
					'modalidad_id',
					'ciudad_id',
					'ciudad_sede_id',
					'nombre',
					'duracion_meses',
					'fecha_inicio',
					'nuevo',
					'inicial',
				],
				'where' => ['$.estado = 1'],
				'order' => [
					'fecha_inicio',
				],
			],

			'cursos' => [
				'table' => 'curso',
				'alias' => 'c',
				'columns' => [
					'id',
					'curso_tipo_id',
					'area_id',
					'especialidad_id',
					'alias',
					'nombre',
				],
				'where' => ['$.estado = 1'],
				'join' => ['INNER', 'cp.curso_id = c.id'],
				'order' => ['nombre'],
			],

			'curso_tipos' => [
				'table' => 'curso_tipo',
				'alias' => 'ct',
				'columns' => [
					'id',
					'alias',
					'nombre',
				],
				'where' => ['$.estado = 1', "$.alias = 'pee'"],
				'join' => ['INNER', 'c.curso_tipo_id = ct.id'],
				'order' => ['nombre'],
			],

			'especialidades' => [
				'table' => 'especialidad',
				'alias' => 'e',
				'columns' => [
					'id',
					'especialidad_tipo_id',
					'area_id',
					'alias',
					'nombre',
				],
				'where' => ['$.estado = 1'],
				'join' => ['INNER', 'c.especialidad_id = e.id'],
			],

			'especialidad_tipos' => [
				'table' => 'especialidad_tipo',
				'alias' => 'et',
				'columns' => [
					'id',
					'alias',
					'nombre',
					'bloque_top',
					'bloque_ventajas',
					'bloque_certificado',
					'bloque_certificado_opciones',
					'bloque_curso_partes',
					'bloque_diplomado',
					'bloque_videos',
				],
				'where' => ['$.estado = 1', "$.alias = '{$params['especialidad_tipo']}'"],
				'join' => ['INNER', 'e.especialidad_tipo_id = et.id'],
				'order' => ['nombre'],
			],

			'areas' => [
				'table' => 'area',
				'alias' => 'a',
				'columns' => [
					'id',
					'alias',
					'nombre',
				],
				'where' => ['$.estado = 1'],
				'join' => ['INNER', 'e.area_id = a.id'],
				'order' => ['nombre'],
			],

			'modalidades' => [
				'table' => 'modalidad',
				'alias' => 'm',
				'columns' => [
					'id',
					'alias',
					'nombre',
				],
				'where' => ['$.estado = 1'],
				'join' => ['INNER', 'cp.modalidad_id = m.id'],
				'order' => ['nombre'],
			],

			'ciudades' => [
				'table' => 'ciudad',
				'alias' => 'z',
				'columns' => [
					'id',
					'alias',
					'nombre',
				],
				'where' => ['$.estado = 1'],
				'join' => ['INNER', 'cp.ciudad_id = z.id'],
				'order' => ['nombre'],
			],

			'ciudad_sedes' => [
				'table' => 'ciudad_sede',
				'alias' => 'zs',
				'columns' => [
					'id',
					'ciudad_id',
					'alias',
					'nombre',
				],
				'where' => ['$.estado = 1'],
				'join' => ['INNER', 'z.id = zs.ciudad_id'],
				'order' => ['nombre'],
			],

			'eventos' => [
				'table' => 'evento',
				'alias' => 'ev',
				'columns' => [
					'id',
					'alias',
					'nombre',
					'fecha',
					'fecha_texto',
				],
				'join' => ['LEFT', "ev.area_id = a.id AND ev.estado = 1 AND ev.fecha >= '{$this->today}'"],
				'order' => ['fecha DESC'],
			],
		];


		$cols  = [];
		$from  = null;
		$joins = [];
		$where = [];
		$order = [];

		foreach ($groups as $k => $group) {
			$table = $group['table'];
			$alias = $group['alias'];

			if (is_null($from)) {
				$from = "{$table} AS {$alias}";
			} else {
				$joins[] = [
					$group['join'][0],
					"{$table} AS {$alias} ON " . $group['join'][1],
				];
			}

			if (isset($group['columns']) && count($group['columns']) > 0) {
				foreach ($group['columns'] as $col) {
					$cols[] = "{$alias}.{$col} AS {$k}___{$col}";
				}
			}

			if (isset($group['where']) && count($group['where']) > 0) {
				foreach ($group['where'] as $cond) {
					$where[] = str_ireplace('$.', "{$alias}.", $cond);
				}
			}

			if (isset($group['order']) && count($group['order']) > 0) {
				foreach ($group['order'] as $col) {
					$order[] = "{$alias}.{$col}";
				}
			}
		}


		// -----------------

		$query_params = compact('cols', 'from', 'joins', 'where', 'order');

		$this->data = $this->parseData(
			array_keys($groups),
			$this->dbQuery($query_params)->loadAssocList()
		);
	}


	// -------------------------------------------------------------------------


	private function dbQuery ($params) {
		extract(array_merge([
			'cols'   => ['*'],
			'joins'  => [],
			'where'  => [],
			'order'  => [],
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
			$query->order(implode(',', $order));
		}

		if ($limit) {
			$query->setLimit($limit, $offset);
		}

		$db->setQuery($query);

		return $db;
	}


	private function parseData ($groups, $rows) {
		$data = [];

		foreach ($groups as $group) {
			$data[$group] = [];
		}

		foreach ($rows as $row) {
			$item = [];

			foreach ($row as $column => $value) {
				list ($prefix, $column) = explode('___', $column, 2);

				if (!isset($item[$prefix])) {
					$item[$prefix] = [];
				}

				$item[$prefix][$column] = $value;
			}

			foreach ($item as $prefix => $item_data) {
				if ($item_data['id'] !== null) {
					$data[$prefix][$item_data['id']] = $item_data;
				}
			}
		}

		return $data;
	}

}