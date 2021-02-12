<?php

use Joomla\CMS\Language\Text;
use Joomla\CMS\Date\Date;
use Joomla\CMS\Factory;


class ModEsanCurso {

	private $params;

	protected $today = null;

	private $data;


	public function __construct ($params = null) {
		$this->params = $params;

		$this->today = (new DateTime('now', new DateTimeZone('America/Lima')))->format('Y-m-d');

		if ($params && $this->checkParams()) {
			$this->loadData(
				$params->get('curso_id'),
				$params->get('programas') === '1'
			);
		}
	}


	private function checkParams () {
		$curso_id = $this->params->get('curso_id');

		if ($curso_id && ctype_digit($curso_id)) {
			return true;
		}

		return false;
	}


	public function loadData (
		$curso_id,
		$cargar_programas
	) {

		$cols = [
			'c.id                    AS curso_id',
			'c.alias                 AS curso_alias',
			'c.nombre                AS curso_nombre',
			'c.color                 AS curso_color',
			'c.descripcion           AS curso_descripcion',
			'c.admision_pdf          AS curso_admision_pdf',

			'c.bloque_info           AS curso_bloque_info',
			'c.bloque_objetivo       AS curso_bloque_objetivo',
			'c.bloque_temario        AS curso_bloque_temario',
			'c.bloque_participantes  AS curso_bloque_participantes',
			'c.bloque_duracion       AS curso_bloque_duracion',
			'c.bloque_docentes       AS curso_bloque_docentes',
			'c.bloque_inversion      AS curso_bloque_inversion',
			'c.bloque_requerimientos AS curso_bloque_requerimientos',

			't.id                    AS tipo_id',
			't.nombre                AS tipo_nombre',
		];

		$from = 'curso AS c';

		$joins = [
			['INNER', 'curso_tipo AS t ON t.id = c.curso_tipo_id'],
		];

		$where = ["c.id = {$curso_id}"];

		$order = ['c.id ASC'];

		if ($cargar_programas) {
			$cols = array_merge($cols, [
				'p.id             AS programa_id',
				'p.nombre         AS programa_nombre',
				'p.codigo_pago    AS programa_codigo_pago',
				'p.costo          AS programa_costo',
				'p.duracion_meses AS programa_duracion_meses',
				'p.horario        AS programa_horario',
				'p.contacto_info  AS programa_contacto_info',
				'p.fecha_inicio   AS programa_fecha_inicio',
				'p.fecha_fin      AS programa_fecha_fin',

				'p.costo          AS programa_inversion',
				'p.duracion_meses AS programa_duracion',
				'p.fecha_inicio   AS programa_inicio',
				'p.fecha_fin      AS programa_fin',

				'z.id           AS ciudad_id',
				'z.nombre       AS ciudad_nombre',
				'z.alias        AS ciudad_alias',

				'm.id           AS modalidad_id',
				'm.nombre       AS modalidad_nombre',
				'm.alias        AS modalidad_alias',

				'd.id           AS docente_id',
				'd.nombres      AS docente_nombres',
				'd.apellidos    AS docente_apellidos',
				'd.foto_avatar  AS docente_foto_avatar',
				'd.cargo        AS docente_cargo',
				'd.area         AS docente_area',
				'd.resena       AS docente_resena',
				'd.experiencia  AS docente_experiencia',

				'd.resena       AS docente_resumen',
				'd.foto_avatar  AS docente_foto',

				'e.id           AS evento_id',
				'e.nombre       AS evento_nombre',
				'e.alias        AS evento_alias',
				'e.lugar        AS evento_lugar',
				'e.fecha_texto  AS evento_fecha_texto',

				'UNIX_TIMESTAMP(e.fecha) AS evento_fecha_ts',
			]);

			$joins = array_merge($joins, [
				['LEFT', 'curso_programa           AS p  ON c.id = p.curso_id'],
				['LEFT', 'ciudad                   AS z  ON z.id = p.ciudad_id'],
				['LEFT', 'modalidad                AS m  ON m.id = p.modalidad_id'],
				['LEFT', 'curso_programa_usuario   AS pd ON p.id = pd.curso_programa_id'],
				['LEFT', 'usuario                  AS d  ON d.id = pd.usuario_id'],
				['LEFT', 'evento_curso_programa    AS ep ON p.id = ep.curso_programa_id'],
				['LEFT', "evento                   AS e  ON e.id = ep.evento_id AND e.estado = 1 AND e.fecha >= '{$this->today}'"],
			]);

			$order[] = 'p.nombre ASC';
			$order[] = 'z.nombre ASC';
			$order[] = 'm.nombre ASC';
			$order[] = 'd.nombres ASC';
			$order[] = 'e.fecha ASC';
		}

		// -----------------

		$db = FabrikWorker::getDbo(false, 2);

		$query = $db->getQuery(true);

		$query->select($cols);
		$query->from($from);

		foreach ($joins as $join) {
			$query->join($join[0], $join[1]);
		}

		$query->where('(' . implode(') AND (', $where) . ')');
		$query->order(implode(', ', $order));

		$db->setQuery($query);

		$this->data = $this->parseResults($db->loadAssocList(), $cargar_programas);

		return $this->data;
	}


	private function parseResults (
		$results,
		$cargar_programas
	) {

		$tipo        = null;
		$curso       = null;
		$programas   = [];
		$ciudades    = [];
		$modalidades = [];
		$docentes    = [];
		$eventos     = [];

		foreach ($results as $row) {
			if (is_null($curso)) {
				$curso = [];

				foreach ($row as $k => $v) {
					if (strpos($k, 'curso_') === 0) {
						$q = substr($k, 6);
						$curso[$q] = $v;
					}
				}

				$curso['color'] = "rgb({$curso['color']})";
			}


			if (is_null($tipo) && isset($row['tipo_id'])) {
				$tipo = [];

				foreach ($row as $k => $v) {
					if (strpos($k, 'tipo_') === 0) {
						$tipo[substr($k, 5)] = $v;
					}
				}
			}


			if ($cargar_programas && isset($row['ciudad_id'], $row['modalidad_id'])) {
				$programa_id  = $row['programa_id'];
				$ciudad_id    = $row['ciudad_id'];
				$modalidad_id = $row['modalidad_id'];
				$docente_id   = $row['docente_id'];
				$evento_id    = $row['evento_id'];

				$programa  = [];
				$ciudad    = [];
				$modalidad = [];
				$docente   = [];
				$evento    = [];

				foreach ($row as $k => $v) {
					if (strpos($k, 'programa_') === 0) {
						$programa[substr($k, 9)] = $v;
					}

					if (strpos($k, 'ciudad_') === 0) {
						$ciudad[substr($k, 7)] = $v;
					}

					if (strpos($k, 'modalidad_') === 0) {
						$modalidad[substr($k, 10)] = $v;
					}

					if (strpos($k, 'docente_') === 0) {
						$docente[substr($k, 8)] = $v;
					}

					if (strpos($k, 'evento_') === 0) {
						$evento[substr($k, 7)] = $v;
					}
				}

				if (isset($programas[$programa_id])) {
					$programa = $programas[$programa_id];

				} else {
					$programa['ciudad_id']    = $ciudad_id;
					$programa['modalidad_id'] = $modalidad_id;
					$programa['docentes_ids'] = [];
					$programa['eventos_ids']  = [];

					// ---------------

					$date = new Date($programa['inicio']);
					$date->setTimezone(Factory::getUser()->getTimezone());

					$programa['inicio'] = $date->format(Text::_('d F Y'));
					$programa['inversion'] = number_format($programa['inversion']);
				}

				if ($docente_id) {
					$programa['docentes_ids'][] = $docente_id;
					$docentes[$docente_id]  = $docente;
				}

				if ($evento_id) {
					$programa['eventos_ids'][] = $evento_id;
					$eventos[$evento_id] = $evento;
				}

				// ---------------

				$programas[$programa_id]    = $programa;
				$ciudades[$ciudad_id]       = $ciudad;
				$modalidades[$modalidad_id] = $modalidad;
			}
		}

		$curso['tipo'] = $tipo;

		foreach ($eventos as $k => $evento) {
			$Itemid = '491'; // Id de Menú (Eventos)
			$vars = ['Itemid' => $Itemid, 'option' => 'com_esan_eventos', 'view' => 'details', 'evento' => $evento['alias']];
			$eventos[$k]['href'] = JRoute::_('index.php?' . http_build_query($vars, '', '&'));
		}

		if ($cargar_programas) {
			$curso['programas']   = array_values($programas);
			$curso['ciudades']    = array_values($ciudades);
			$curso['modalidades'] = array_values($modalidades);
			$curso['docentes']    = array_values($docentes);
			$curso['eventos']     = array_values($eventos);
		}


		// ---------------

		return $curso;
	}


	public function dump () {
		SimpleTemplateEngine::print($this->params->get('html_code'), [
			'cargar_programas' => $this->params->get('programas') === '1',
			'curso'            => $this->data,
		]);

		if ($this->params->get('dump_js') === '1') {
			printf("<script>var EsanCurso=%s;</script>", json_encode($this->data));
		}
	}

}
