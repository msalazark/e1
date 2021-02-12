<?php

defined('_JEXEC') or die;


class Esan_EventosModelDetails extends JModelItem {

	protected $data = null;


	private function getParams () {
		$params = [
			'evento' => '',
		];

		$jinput = JFactory::getApplication()->input;

		foreach ($params as $k => $v) {
			$params[$k] = $jinput->get($k, '', 'RAW');
		}

		if ($params['evento'] && !preg_match('/^[A-Za-z0-9\-]+$/', $params['evento'])) {
			throw new Exception('Invalid identifier');
		}

		return $params;
	}


	public function getData () {
		if (is_null($this->data)) {
			$this->loadData();
		}

		return $this->data;
	}


	private function loadData () {
		$params = $this->getParams();

		$data = [];

		$data['evento'] = $this->dbQuery([
			'from'  => 'evento',
			'where' => sprintf("estado = 1 AND alias = '%s'", addslashes($params['evento'])),
		])->loadAssoc();

		$data['tipo'] = $this->dbQuery([
			'from'  => 'evento_tipo',
			'where' => 'id = ' . $data['evento']['evento_tipo_id'],
		])->loadAssoc();

		$data['area'] = $this->dbQuery([
			'from'  => 'area',
			'where' => 'id = ' . $data['evento']['area_id'],
		])->loadAssoc();

		$data['ciudad'] = $this->dbQuery([
			'from'  => 'ciudad',
			'where' => 'id = ' . $data['evento']['ciudad_id'],
		])->loadAssoc();

		$data['ponentes'] = $this->dbQuery([
			'from'  => 'usuario',
			'joins' => [['INNER', 'evento_usuario ON usuario.id = usuario_id']],
			'where' => 'evento_id = ' . $data['evento']['id'],
		])->loadAssocList();

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
