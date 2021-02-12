<?php

defined('_JEXEC') or die;


JFormHelper::loadFieldClass('list');


class JFormFieldPrograma extends JFormFieldList {

	protected $type = 'Programa';

	protected function getOptions () {

		$db = FabrikWorker::getDbo(false, 2);

		$query = $db->getQuery(true);
		$query->select('id,nombre_full');
		$query->from('curso_programa');
		$query->order('nombre_full ASC');

		$db->setQuery((string) $query);

		$rows = $db->loadAssocList();

		$options = [
			JHtml::_('select.option', '0', '- Todos -')
		];

		foreach ($rows as $row) {
			$options[] = JHtml::_('select.option', $row['id'], $row['nombre_full']);
		}

		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}

}
