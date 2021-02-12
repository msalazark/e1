<?php

defined('_JEXEC') or die;


class Esan_BlogViewList extends JViewLegacy {

	protected $params       = null;
	protected $filters      = null;
	protected $results      = null;
	protected $results_info = null;
	protected $destacados   = null;



	public function display ($tpl = null) {
		$this->params          = $this->get('Params');
		$this->params_defaults = $this->get('ParamsDefaults');
		$this->filters         = $this->get('Filters');
		$this->results         = $this->get('Results');
		$this->results_info    = $this->get('ResultsInfo');
		$this->destacados      = $this->get('Destacados');

		parent::display($tpl);
	}


	protected function url ($view, $vars = [], $include_filters = false) {
		$filters = [];

		if ($include_filters) {
			foreach ($this->filters['items'] as $k => $v) {
				if ($this->params_defaults[$k] != $v) {
					$filters[$k] = $v;
				}
			}
		}

		$vars = array_merge([
			'option' => 'com_esan_blog',
			'view'   => $view,
		], $filters, $vars);

		return JRoute::_('index.php?' . http_build_query($vars, null, '&'));
	}


	protected function buildFilterField ($name, $placeholder = '') {
		$opts = $this->filters['data'][[
			'especialidad' => 'especialidades',
			'categoria'    => 'categorias',
			'usuario'      => 'usuarios',
			'fecha'        => 'fechas',
		][$name]];

		$value = $this->params[$name] ?: '';

		if ($name === 'usuario') {
			$html  = sprintf('<input type="text" name="%s" placeholder="%s" list="datalist-usuario" value="%s">', $name, htmlspecialchars($placeholder), htmlspecialchars($value));

			$html .= '<datalist id="datalist-usuario">';

			foreach ($opts as $opt) { if ($opt['menu']) {
				$html .= sprintf('<option value="%s" data-value="%s">', htmlspecialchars($opt['text']), $opt['value']);
			} }

			$html .= '</datalist>';

			return $html;
		}

		if ($name === 'fecha') {
			$max = $opts[count($opts) - 1]['value'] . '-01';
			$min = date('Y-m-t', strtotime($opts[0]['value'] . '-01'));

			$html = sprintf('<input type="text" name="%s" placeholder="%s" value="%s" min="%s" max="%s">', $name, htmlspecialchars($placeholder), htmlspecialchars($value), $min, $max);

			return $html;
		}


		$html = sprintf('<select name="%s">', $name);

		$html .= sprintf('<option value="">%s</option>', htmlspecialchars($placeholder));

		foreach ($opts as $opt) { if ($opt['menu']) {
			$selected = $value === $opt['value'] ? 'selected' : '';
			$html .= sprintf('<option %s value="%s">%s</option>', $selected, $opt['value'], htmlspecialchars($opt['text']));
		} }

		$html .= '</select>';

		return $html;
	}


	protected function buildPagination () {
		$pages   = intval(ceil($this->results_info['total'] / $this->results_info['limit']));
		$current = intval($this->params['pagina']);

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


	protected function getBoletinAreas () {
		$url     = 'https://b53730da.sibforms.com/serve/MUIEAEo6_HE91f87iNrTqzieSjYp5RaCwPyMIGjnA8A4ppsPl2PYjidl0eVwndbh0bL98VJBwj-FT7-p6UAGzlCpm73f4ixy17fQ-JxWEk9_gBahltr6RxH6eNMS976eRmUVPRE8LZZaVla6NXuyP0YAPSsgXzSEO-z7LN2yCtKJPYgzf8UUpvWJPEsj4hoodJeHH5aT6zU4mCh-';
		$content = file_get_contents($url);

		if (preg_match_all(':data-value="(\d+)":im', $content, $ids) &&
			preg_match_all(':class="sib-multiselect__label-text">(.*)</:im', $content, $names)
		) {
			return array_combine($ids[1], array_map('trim', $names[1]));
		}

		return [];
	}

}
