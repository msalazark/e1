<?php

defined('_JEXEC') or die;


use Joomla\CMS\Language\Text;
use Joomla\CMS\Date\Date;
use Joomla\CMS\Factory;


class Esan_BlogModelDetails extends JModelItem {

	protected $today  = null;
	protected $params = null;
	protected $blog   = null;
	protected $data   = null;


	public function __construct ($config = []) {
		$this->today = new DateTime('now', new DateTimeZone('America/Lima'));
		$this->today = $this->today->format('Y-m-d');

		$this->loadBlogInfo();

		parent::__construct($config);
	}


	private function loadBlogInfo () {
		$params = $this->getParams();

		$this->blog = $this->dbQuery([
			'from'  => 'blog',
			'where' => ["alias = '{$params['blog']}'"],
		])->loadAssoc();

		if (!$this->blog) {
			throw new Exception('Blog not found');
		}
	}


	public function getParams () {
		if ($this->params) {
			return $this->params;
		}

		$params = [
			'blog'     => '/^[\w\-]{1,32}$/',
			'articulo' => '/^[\w\-]{1,255}$/',
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

		$this->params = $params;

		return $this->params;
	}


	// -------------------------------------------------------------------------


	public function getData () {
		if (is_null($this->data)) {
			$this->loadData();
		}

		return $this->data;
	}


	private function loadData () {
		$params = $this->getParams();

		$articulo = $this->dbQuery([
			'from'  => "blog_articulo",
			'where' => "blog_id = {$this->blog['id']} AND fecha_publicacion <= '{$this->today}' AND estado = 1 AND alias = '{$params['articulo']}'",
		])->loadAssoc();

		if (!$articulo) {
			throw new Exception('Blog Article not found');
		}

		$date = new Date($articulo['fecha_publicacion']);
		$date->setTimezone(Factory::getUser()->getTimezone());

		$articulo['fecha_publicacion'] = $date->format(Text::_('d F Y'));

		// ----------------

		$data = [];

		$data['articulo'] = $articulo;

		$data['especialidades'] = $this->dbQuery([
			'cols'  => ['blog_especialidad.*'],
			'from'  => 'blog_especialidad',
			'joins' => [['INNER', 'blog_articulo_blog_especialidad ON blog_especialidad.id = blog_especialidad_id']],
			'where' => "blog_articulo_id = {$articulo['id']}",
		])->loadAssocList();

		$data['categorias'] = $this->dbQuery([
			'cols'  => ['blog_categoria.*'],
			'from'  => 'blog_categoria',
			'joins' => [['INNER', 'blog_articulo_blog_categoria ON blog_categoria.id = blog_categoria_id']],
			'where' => "blog_articulo_id = {$articulo['id']}",
		])->loadAssocList();

		$data['usuarios'] = $this->dbQuery([
			'cols'  => ['a.*', 'b.resena_personalizada'],
			'from'  => 'usuario AS a',
			'joins' => [['INNER', 'blog_articulo_usuario AS b ON a.id = b.usuario_id']],
			'where' => "b.blog_articulo_id = {$articulo['id']}",
		])->loadAssocList();

		foreach ($data['usuarios'] as $i => $usuario) {
			$data['usuarios'][$i]['articulos'] = $this->dbQuery([
				'cols'  => ['blog_articulo.id', 'nombre', 'alias', 'fecha_publicacion', 'imagen_lista', 'descripcion'],
				'from'  => 'blog_articulo',
				'joins' => [['INNER', 'blog_articulo_usuario ON blog_articulo.id = blog_articulo_id']],
				'where' => "blog_articulo.id != {$articulo['id']} AND blog_id = {$this->blog['id']} AND fecha_publicacion <= '{$this->today}' AND estado = 1 AND usuario_id = {$usuario['id']}",
				'order' => 'fecha_publicacion DESC',
				'limit' => '3',
			])->loadAssocList();

			foreach ($data['usuarios'][$i]['articulos'] as $j => $item) {
				$data['usuarios'][$i]['articulos'][$j]['especialidades'] = $this->dbQuery([
					'cols'  => ['blog_especialidad.*'],
					'from'  => 'blog_especialidad',
					'joins' => [['INNER', 'blog_articulo_blog_especialidad ON blog_especialidad.id = blog_especialidad_id']],
					'where' => "blog_articulo_id = {$item['id']}",
				])->loadAssocList();

				$data['usuarios'][$i]['articulos'][$j]['categorias'] = $this->dbQuery([
					'cols'  => ['blog_categoria.*'],
					'from'  => 'blog_categoria',
					'joins' => [['INNER', 'blog_articulo_blog_categoria ON blog_categoria.id = blog_categoria_id']],
					'where' => "blog_articulo_id = {$item['id']}",
				])->loadAssocList();

				$date = new Date($item['fecha_publicacion']);
				$date->setTimezone(Factory::getUser()->getTimezone());
				$data['usuarios'][$i]['articulos'][$j]['fecha_publicacion'] = $date->format(Text::_('d F Y'));
			}

			$data['usuarios'][$i]['cursos_docente'] = [];
			$data['usuarios'][$i]['cursos_alumno'] = [];

			if ($usuario['usuario_tipo_id'] == '1') {
				$data['usuarios'][$i]['cursos_docente'] = $this->dbQuery([
					'cols'  => ['DISTINCT '. 'ct.alias AS tipo_alias', 'c.alias', 'c.nombre'],
					'from'  => 'curso AS c',
					'joins' => [
						['INNER', 'curso_tipo AS ct ON c.curso_tipo_id = ct.id'],
						['INNER', 'curso_programa AS cp ON c.id = cp.curso_id'],
						['INNER', 'curso_programa_usuario AS cpd ON cp.id = cpd.curso_programa_id'],
					],
					'where' => "cpd.usuario_id = {$usuario['id']}",
					'order' => 'c.nombre ASC',
					'limit' => 5,
				])->loadAssocList();
			}

			$data['usuarios'][$i]['cursos_alumno'] = $this->dbQuery([
				'cols'  => ['DISTINCT '. 'ct.alias AS tipo_alias', 'c.alias', 'c.nombre'],
				'from'  => 'curso AS c',
				'joins' => [
					['INNER', 'curso_tipo AS ct ON c.curso_tipo_id = ct.id'],
					['INNER', 'usuario_curso AS cu ON c.id = cu.curso_id'],
				],
				'where' => "cu.usuario_id = {$usuario['id']}",
				'order' => 'c.nombre ASC',
				'limit' => 5,
			])->loadAssocList();
		}

		$this->data = $data;
	}


	private function dbQuery ($params) {
		extract(array_merge([
			'cols'   => ['*'],
			'joins'  => [],
			'where'  => null,
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

		if ($where) {
			$query->where($where);
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

}
