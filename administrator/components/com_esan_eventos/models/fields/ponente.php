<?php

defined('_JEXEC') or die;


JFormHelper::loadFieldClass('list');


class JFormFieldPonente extends JFormFieldList {

	protected $type = 'Ponente';

	protected function getOptions () {

		$db = FabrikWorker::getDbo(false, 2);

		$query = $db->getQuery(true);
		$query->select("id, TRIM(CONCAT(nombres, ' ', apellidos)) AS nombre");
		$query->from('usuario');
		$query->order('nombre ASC');

		$db->setQuery((string) $query);

		$rows = $db->loadAssocList();

		$options = [
			JHtml::_('select.option', '0', '- Todos -')
		];

		foreach ($rows as $row) {
			$options[] = JHtml::_('select.option', $row['id'], $row['nombre']);
		}

		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}

}