<?php

defined('_JEXEC') or die;


JFormHelper::loadFieldClass('list');


class JFormFieldBlogAlias extends JFormFieldList {

	protected $type = 'BlogAlias';

	protected function getOptions () {

		$db = FabrikWorker::getDbo(false, 2);

		$query = $db->getQuery(true);
		$query->select('alias,nombre');
		$query->from('blog');
		$query->order('nombre ASC');

		$db->setQuery((string) $query);

		$rows = $db->loadAssocList();

		$options = [];

		foreach ($rows as $row) {
			$options[] = JHtml::_('select.option', $row['alias'], $row['nombre']);
		}

		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}

}
