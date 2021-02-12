<?php

defined('_JEXEC') or die;


use Joomla\CMS\Language\Text;
use Joomla\CMS\Date\Date;
use Joomla\CMS\Factory;


class Esan_EventosViewList extends JViewLegacy {

	protected $filters      = null;
	protected $results      = null;
	protected $results_info = null;
	protected $destacados   = null;



	public function display ($tpl = null) {
		$this->filters      = $this->get('Filters');
		$this->results      = $this->get('Results');
		$this->results_info = $this->get('ResultsInfo');
		$this->destacados   = $this->get('Destacados');

		parent::display($tpl);
	}


	protected function url ($view, $vars = [], $include_filters = false) {
		$filters = $include_filters ? array_filter($this->filters['items']) : [];

		$vars = array_merge([
			'option' => 'com_esan_eventos',
			'view'   => $view,
		], $filters, $vars);

		return JRoute::_('index.php?' . http_build_query($vars, null, '&'));
	}


	protected function buildFilterField ($name, $placeholder = '') {
		$rows = $this->filters['data'][[
			'tipo'   => 'tipos',
			'area'   => 'areas',
			'fecha'  => 'eventos',
			'ciudad' => 'ciudades',
		][$name]];

		$html = sprintf('<select name="%s">', $name);

		$html .= sprintf('<option value="">%s</option>', htmlspecialchars($placeholder));

		foreach ($rows as $row) {
			$selected = isset($_GET[$name]) && $_GET[$name] === $row['value'] ? 'selected' : '';
			$html .= sprintf('<option %s value="%s">%s</option>', $selected, $row['value'], htmlspecialchars($row['text']));
		}

		$html .= '</select>';

		return $html;
	}


	protected function buildPagination () {
		$pages   = intval(ceil($this->results_info['total'] / $this->results_info['limit']));
		$current = intval($this->filters['items']['pagina']);

		$margin  = 1;
		$from    = max($current - $margin, 2);
		$to      = min($current + $margin, $pages - 1);

		if ($pages <= 1) {
			return '';
		}

		$links = [1];

		if ($from > 2) {
			$links[] = '...';
		}

		for ($i = $from; $i <= $to; $i++) {
			$links[] = $i;
		}

		if ($to < $pages - 1) {
			$links[] = '...';
		}

		if ($pages > 1) {
			$links[] = $pages;
		}

		// ------------

		$html = '<ul class="genits-pagination">';

		foreach ($links as $n) {
			if ($n === '...') {
				$html .= '<li><a>...</a></li>';
			} else {
				$active = $n === $current ? ' class="active"' : '';
				$url = $this->url('list', ['pagina' => $n], true);
				$html .= sprintf('<li%s><a href="%s">%s</a></li>', $active, $url, $n);
			}
		}

		$html .= '</ul>';

		return $html;
	}


	protected function find ($group, $id, $from = 'results') {
		return $this->{$from}[$group][$id];
	}


	protected function renderCosto ($evento) {
		if ($evento['pronto_pago_costo'] && $evento['pronto_pago_fecha']) {
			$fecha = new Date($evento['pronto_pago_fecha']);
			$fecha->setTimezone(Factory::getUser()->getTimezone());

			return sprintf('%s hasta %s', $evento['pronto_pago_costo'], $fecha->format(Text::_('d F')));

		} else {
			return $evento['costo'];
		}
	}

}
