(function (win, doc, EL) {

	// -------------------------------------------------------------------------

	var $T = function (text) {
		return doc.createTextNode(text);
	};

	var $E = function (tag, attrs, props) {
		var el = doc.createElement(tag), k;

		if (attrs) {
			for (k in attrs) {
				el.setAttribute(k, attrs[k]);
			}
		}

		if (props) {
			for (k in props) {
				el[k] = props[k];
			}
		}

		return el;
	};


	// -------------------------------------------------------------------------

	var $ = function (selector) {
		return doc.querySelector(selector);
	};

	var $$ = function (selector) {
		return Array.prototype.slice.call(doc.querySelectorAll(selector));
	};

	EL.$ = function (selector) {
		return this.querySelector(selector);
	};

	EL.$$ = function (selector) {
		return Array.prototype.slice.call(this.querySelectorAll(selector));
	};

	EL.$text = function (text) {
		this.appendChild($T(text));
		return this;
	};

	EL.$html = function (html) {
		this.innerHTML = html;
		return this;
	};

	EL.$empty = function () {
		while (this.firstChild) {
			this.removeChild(this.firstChild);
		}

		return this;
	};

	EL.$adopt = function () {
		for (var i = 0, l = arguments.length; i < l; i++) {
			this.appendChild(arguments[i]);
		}

		return this;
	};

	EL.$dispose = function () {
		if (this.parentNode) {
			this.parentNode.removeChild(this);
		}

		return this;
	};

	EL.$setAttr = function (k, v) {
		this.setAttribute(k, v);
		return this;
	};


	// -------------------------------------------------------------------------


	var EsanCursoProgramaSelector = {

		data: null,

		dom: {},


		initialize: function (data, container) {

			this.data = data;

			this.build(this.dom, container);

			this.setup(this.dom);
			this.selectDefault();
		},


		build: function (dom, container) {

			dom.container = container;

			dom.trigger = $E('div', {'class': 'esan-programa-selector-trigger'});

			dom.modal_overlay = $E('div', {'class': 'esan-programa-selector-modal-overlay'});
			dom.modal_close   = $E('div', {'class': 'esan-programa-selector-modal-close'});
			dom.modal         = $E('div', {'class': 'esan-programa-selector-modal'});

			dom.ciudad    = $E('select', {'name': 'ciudad'});
			dom.modalidad = $E('select', {'name': 'modalidad'});
			dom.button    = $E('button', {'class': 'btn btn-primary btn-lg text-uppercase'}).$text('Modificar');

			if (this.data.programas.length <= 1) {
				dom.container.$dispose();
				return;
			}

			dom.container.removeAttribute('style');

			dom.container.parentNode.insertBefore(
				$E('p').$adopt(
					dom.trigger
				),
				dom.container
			);

			dom.modal_overlay.$adopt(
				dom.modal.$adopt(
					dom.modal_close.$text('✕'),
					dom.container.$adopt(
						$E('div', {'class': 'intro'}).$adopt(
							$E('h2').$text('Modificar'),
						),
						dom.ciudad,
						dom.modalidad,
						dom.button
					)
				)
			);
		},


		setup: function (dom) {

			dom.trigger.addEventListener('click', this.displayModal.bind(this));
			dom.modal_close.addEventListener('click', this.hideModal.bind(this));

			dom.ciudad.addEventListener('change', this.fillComboModalidades.bind(this));
			dom.button.addEventListener('click', this.updateSelection.bind(this));
		},


		displayModal: function () {

			document.documentElement.classList.add('no-scroll');
			document.body.$adopt(this.dom.modal_overlay);
		},


		hideModal: function () {

			document.documentElement.classList.remove('no-scroll');
			this.dom.modal_overlay.$dispose()
		},


		selectDefault: function () {

			var selected     = localStorage.getItem('EsanCursosFiltros');
			var ciudad_id    = '';
			var modalidad_id = '';

			if (selected) {
				selected     = JSON.parse(selected);
				ciudad_id    = selected.ciudad;
				modalidad_id = selected.modalidad;
			}

			this.fillComboCiudades(ciudad_id);
			this.fillComboModalidades(modalidad_id);
		},


		persistLocalStorage: function (ciudad_id, modalidad_id) {

			var selected = localStorage.getItem('EsanCursosFiltros');

			if (selected) {
				selected = JSON.parse(selected);

			} else {
				selected = {
					dirigido_a : "1",
					ciudad     : "",
					modalidad  : "",
					area       : "",
					duracion   : "",
					search     : ""
				};
			}

			selected.ciudad = ciudad_id;
			selected.modalidad = modalidad_id;

			localStorage.setItem('EsanCursosFiltros', JSON.stringify(selected));
		},


		updateSelection: function () {

			var ciudad_id    = this.dom.ciudad.value;
			var modalidad_id = this.dom.modalidad.value;

			var data = {
				curso     : this.data,
				programa  : this.data.findPrograma(ciudad_id, modalidad_id),
				ciudad    : this.data.findCiudad(ciudad_id),
				modalidad : this.data.findModalidad(modalidad_id)
			};

			for (var k in data) {
				$$('[data-esan-'+k+']').forEach(function (el) {
					if (el.getAttribute('data-esan-'+k) && el.getAttribute('data-esan-'+k) in data[k]) {
						var value = data[k][el.getAttribute('data-esan-'+k)];
						var is_html = el.getAttribute('data-content-type') === 'html';
						el.$empty()[is_html ? '$html' : '$text'](value || '');
					}
				});
			}

			this.updateFechaDestacada(data);

			this.dom.trigger.$empty().$adopt(
				$E('span').$text('Estás en ' + data.ciudad.nombre + ' - ' + data.modalidad.nombre),
				$T(' '),
				$E('a').$text('Modificar')
			);

			this.persistLocalStorage(ciudad_id, modalidad_id);

			this.hideModal();
		},


		updateFechaDestacada: function (data) {
			var texto = 'Fecha de inicio:';
			var fecha = data.programa.inicio;
			var lugar = '';
			var href  = null;

			var eventos = this.data.findEventos(data.programa.id);
			var evento = eventos.length ? eventos[0] : null;

			if (evento) {
				texto = 'Evento: ' + evento.nombre;
				fecha = evento.fecha_texto;
				lugar = evento.lugar;
				href  = evento.href;
			}

			$$('[data-esan-programa="fecha_destacada"]').forEach(function (el) {
				var link = $E('a');

				if (href) {
					link.$setAttr('class', 'link-next').$setAttr('href', href).$adopt(
						$E('span').$text('Saber más'),
						$E('i', {'class': 'icon icon-next'})
					);
				}

				el.$empty().$adopt(
					$E('p').$text(texto),
					$E('div', {'class': 'evento-item'}).$adopt(
						$E('div', {'class': 'fecha'}).$text(fecha),
						$E('div', {'class': 'lugar'}).$text(lugar),
						link
					)
				);
			});
		},


		fillComboCiudades: function (selected_value) {

			var select = this.dom.ciudad;

			this.data.ciudades.forEach(function (opt) {
				select.$adopt(
					$E('option', {value: opt.id}).$text(opt.nombre)
				);
			});

			if (select.$('option[value="' + selected_value + '"]')) {
				select.value = selected_value;
			}
		},


		fillComboModalidades: function (selected_value) {

			var ciudad_id = this.dom.ciudad.value;
			var select    = this.dom.modalidad.$empty();

			this.data.findModalidades(ciudad_id).forEach(function (opt) {
				select.$adopt(
					$E('option', {value: opt.id}).$text(opt.nombre)
				);
			});

			if (select.$('option[value="' + selected_value + '"]')) {
				select.value = selected_value;
			}

			this.updateSelection();
		}
	};


	// -------------------------------------------------------------------------

	if (!win.EsanCurso) {
		return;
	}


	win.EsanCurso.findCiudad = function (ciudad_id) {

		return this.ciudades.find(function (ciudad) {
			return ciudad.id === ciudad_id;
		});
	};


	win.EsanCurso.findModalidad = function (modalidad_id) {

		return this.modalidades.find(function (modalidad) {
			return modalidad.id === modalidad_id;
		});
	};


	win.EsanCurso.findPrograma = function (ciudad_id, modalidad_id) {

		return this.programas.find(function (programa) {
			return programa.ciudad_id    === ciudad_id &&
			       programa.modalidad_id === modalidad_id;
		});
	};


	win.EsanCurso.findModalidades = function (ciudad_id) {

		var modalidades_ids = this.programas.filter(function (programa) {
			return programa.ciudad_id === ciudad_id;
		}).map(function (item) {
			return item.modalidad_id;
		});

		return this.modalidades.filter(function (modalidad) {
			return modalidades_ids.includes(modalidad.id);
		});
	};


	win.EsanCurso.findEventos = function (programa_id) {

		var eventos_ids = this.programas.find(function (programa) {
			return programa.id === programa_id;
		}).eventos_ids;

		if (eventos_ids.length === 0) {
			return [];
		}

		return this.eventos.filter(function (evento) {
			return eventos_ids.includes(evento.id);
		});
	};


	// -------------------------------------------------------------------------


	win.addEventListener('DOMContentLoaded', function () {
		var selector = $('.esan-programa-selector');

		if (selector) {
			EsanCursoProgramaSelector.initialize(win.EsanCurso, selector);
		}
	});

	win.EsanCursoProgramaSelector = EsanCursoProgramaSelector;

})(window, document, Element.prototype);
