<?php

defined('_JEXEC') or die;


JFormHelper::loadFieldClass('list');


class JFormFieldBlog extends JFormFieldList {

	protected $type = 'Blog';

	protected function getOptions () {

		$db = FabrikWorker::getDbo(false, 2);

		$query = $db->getQuery(true);
		$query->select('id,nombre');
		$query->from('blog');
		$query->order('nombre ASC');

		$db->setQuery((string) $query);

		$rows = $db->loadAssocList();

		$options = [];

		foreach ($rows as $row) {
			$options[] = JHtml::_('select.option', $row['id'], $row['nombre']);
		}

		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}

}
