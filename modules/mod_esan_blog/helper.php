<?php

use Joomla\CMS\Language\Text;
use Joomla\CMS\Date\Date;
use Joomla\CMS\Factory;


class ModEsanBlogHelper {

	private $params;

	private $data;

	protected $today = null;
	protected $tz = null;


	public function __construct ($params) {
		$this->params = $params;

		$this->today = new \DateTime('now', new \DateTimeZone('America/Lima'));
		$this->today = $this->today->format('Y-m-d');

		$this->tz = Factory::getUser()->getTimezone();
	}


	public function loadData () {
		$params = $this->params;

		$this->data = $this->getResults(
			$params->get('blog_id', 0, 'UINT'),
			$params->get('especilidad_id', 0, 'UINT'),
			$params->get('categoria_id', 0, 'UINT'),
			$params->get('usuario_id', 0, 'UINT'),
			$params->get('destacados', 0, 'UINT'),
			$params->get('limite', 100, 'UINT')
		);

		return $this->data;
	}


	// used in ajax
	public static function getAutorArticulosAjax () {
		$params = JFactory::getApplication()->input;
		$module = new self($params);
		$data   = $module->loadData();

		foreach ($data as $i => $item) {
			$data[$i]['url'] = self::itemURL($item['alias'], $params->get('menu_item_id', 0, 'UINT'));
		}

		return $data;
	}


	// -------------------------------------------------------------------------


	private function getResults (
		$blog_id,
		$especialidad_id = null,
		$categoria_id    = null,
		$usuario_id      = null,
		$destacados      = null,
		$limite          = null
	) {

		$cols = [
			'DISTINCT '.
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
			'a.blog_id = ' . $blog_id,
			'a.estado = 1',
			"a.fecha_publicacion <= '{$this->today}'",
		];

		$order = 'a.fecha_publicacion DESC';

		$limit = $limite;

		// -----------------

		if ($especialidad_id) {
			$joins[] = ['INNER', 'blog_articulo_blog_especialidad AS ae ON a.id = ae.blog_articulo_id'];
			$where[] = "ae.blog_especialidad_id = {$especialidad_id}";
		}

		if ($categoria_id) {
			$joins[] = ['INNER', 'blog_articulo_blog_categoria AS ac ON a.id = ac.blog_articulo_id'];
			$where[] = "ac.blog_categoria_id = {$categoria_id}";
		}

		if ($usuario_id) {
			$joins[] = ['INNER', 'blog_articulo_usuario AS au ON a.id = au.blog_articulo_id'];
			$where[] = "au.usuario_id = {$usuario_id}";
		}

		if ($destacados) {
			$where[] = 'a.destacado = 1';
		}

		// -----------------

		$query_params = compact('cols', 'from', 'joins', 'where', 'order', 'limit');

		$articulos = self::dbQuery($query_params)->loadAssocList();

		foreach ($articulos as $i => $articulo) {
			$articulos[$i]['fecha_publicacion_str'] = $this->formatDate($articulo['fecha_publicacion']);
			$articulos[$i]['href']                  = $this->articuloURL($articulo['alias']);
		}

		return $articulos;
	}


	private static function dbQuery ($params) {
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



	// -------------------------------------------------------------------------


	protected function articuloURL ($articulo) {
		$menu_item_id = $this->params->get('menu_item') ?: null;
		return self::itemURL($articulo, $menu_item_id);
	}


	protected static function itemURL ($articulo, $menu_item_id = null) {
		$vars = [
			'option'   => 'com_esan_blog',
			'view'     => 'details',
			'articulo' => $articulo,
		];

		if ($menu_item_id) {
			$vars['Itemid'] = $menu_item_id;
		}

		return JRoute::_('index.php?' . http_build_query($vars, null, '&'));
	}


	private function formatDate ($date) {
		$date = new Date($date);
		$date->setTimezone($this->tz);
		return $date->format(Text::_('d F Y'));
	}


	public function dump () {
		$articulos = &$this->data;
		$template  = trim($this->params->get('template') ?: '');

		if ($template !== '') {
			$tmp_file = tempnam(JPATH_SITE . '/tmp', 'html');
			file_put_contents($tmp_file, $template);
			require_once $tmp_file;
			unlink($tmp_file);
		}
	}

}
