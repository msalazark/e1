<?php

defined('_JEXEC') or die;


JFormHelper::loadFieldClass('list');


class JFormFieldCurso extends JFormFieldList {

	protected $type = 'Curso';

	protected function getOptions () {

		$db = FabrikWorker::getDbo(false, 2);

		$query = $db->getQuery(true);
		$query->select('id,nombre_full');
		$query->from('curso');
		$query->order('nombre_full ASC');

		$db->setQuery((string) $query);

		$rows = $db->loadAssocList();

		$options = [];

		foreach ($rows as $row) {
			$options[] = JHtml::_('select.option', $row['id'], $row['nombre_full']);
		}

		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}

}
