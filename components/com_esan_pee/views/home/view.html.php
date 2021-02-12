<?php

defined('_JEXEC') or die;


class Esan_PEEViewHome extends JViewLegacy {

	protected $data = null;


	public function display ($tpl = null) {
		$this->data = $this->get('Data');

		parent::display($tpl);
	}


	protected function url ($view, $vars = []) {
		$vars = array_merge([
			'option' => 'com_esan_pee',
			'view'   => $view,
		], $vars);

		return JRoute::_('index.php?' . http_build_query($vars, null, '&'));
	}


	protected function filterDataItems ($group, $col, $value) {
		return array_filter($this->data[$group], function ($item) use ($col, $value) {
			return $item[$col] === $value;
		});
	}


	protected function findDataItem ($group, $col, $value) {
		foreach ($this->data[$group] as $item) {
			if ($item[$col] === $value) {
				return $item;
			}
		}
	}

}
