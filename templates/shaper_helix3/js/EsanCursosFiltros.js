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


	// -------------------------------------------------------------------------


	var EsanCursosFiltros = {

		options: {
			display_tipo: false
		},

		dom: {
			modal  : null,
			form   : null,
			inputs : {},
			buttons: {},
			summary: null,
		},


		initialize: function (form, options) {
			this.dom.form = form;

			if (options) {
				Object.assign(this.options, options);
			}

			this.build(this.dom);
			this.setup(this.dom);

			this.fillComboCiudad();

			this.loadDefault();
		},


		build: function (dom) {
			dom.form.$$('input,select,button').forEach(function (el) {
				var k = el.name.toLowerCase();

				switch (el.tagName.toUpperCase()) {
					case 'INPUT':
						switch (el.type.toLowerCase()) {
							case 'radio':
								if (k in dom.inputs) {
									dom.inputs[k].push(el);
								} else {
									dom.inputs[k] = [el];
								}

								break;

							default:
								dom.inputs[k] = el;
						}

						break;

					case 'SELECT':
						dom.inputs[k] = el;
						el._placeholder = el.$('option');
						break;

					case 'BUTTON':
						dom.buttons[k] = el;
						break;

					default:
						dom.inputs[k] = el;
				}
			});

			dom.inputs.search = $('.buscador-page .searchbox input');
			dom.summary       = $('.buscador-summary');
			dom.resultados    = $('.resultados .row');
		},


		setup: function (dom) {
			dom.inputs.dirigido_a.forEach(function (el) {
				el.addEventListener('change', this.onChangeDirigidoA.bind(this));
			}, this);

			dom.inputs.ciudad.addEventListener('change', this.fillComboModalidad.bind(this));
			dom.inputs.modalidad.addEventListener('change', this.fillComboArea.bind(this));
			dom.inputs.area.addEventListener('change', this.fillComboDuracion.bind(this));
			dom.inputs.search.addEventListener('input', this.applyFilter.bind(this));

			dom.form.addEventListener('submit', this.applyFilter.bind(this));
		},


		// ---------------------------------------------------------------------


		setupModal: function (dom) {
			dom.modal   = $E('div', {'class': 'buscador-modal'});
			dom.overlay = $E('div', {'class': 'buscador-overlay'});

			dom.overlay.$adopt(dom.modal);

			$('.buscador-page .actions > span').addEventListener('click', this.displayModal.bind(this));
		},


		displayModal: function (event) {
			if (event) {
				event.preventDefault();
			}

			this.dom.modal.$adopt(this.dom.form);

			document.body.$adopt(this.dom.overlay);
		},


		hideModal: function () {
			this.dom.overlay.$dispose();
		},


		// ---------------------------------------------------------------------


		applyFilter: function (event) {
			if (event) {
				event.preventDefault();
			}

			if (this.getInputValue('dirigido_a') !== '1') {
				return win.open(/empresa-y-gobierno/);
			}

			this.persistToLocalStorage();
			this.displaySummary();
			this.displayFiltered();

			if (this.dom.modal) {
				this.hideModal();
			} else {
				this.setupModal(this.dom);
			}
		},


		displaySummary: function () {
			var data = this.getData(), k;

			var params = {
				dirigido_a : {'1': 'Personas', '2': 'Empresas'}[data.dirigido_a],
				ciudad     : this.findDataItem('ciudades', data.ciudad),
				modalidad  : this.findDataItem('modalidades', data.modalidad),
				area       : this.findDataItem('areas', data.area),
				duracion   : data.duracion
			};

			params.ciudad     = params.ciudad ? params.ciudad.nombre : null;
			params.modalidad  = params.modalidad ? params.modalidad.nombre : null;
			params.area       = params.area ? params.area.nombre : null;

			var msg = 'Programas';

			if (params.area) {
				msg += ' de {area}';
			}

			msg += ' dirigido a {dirigido_a}';

			if (params.ciudad) {
				msg += ' en {ciudad}';

				if (params.modalidad) {
					msg += '  ({modalidad})';
				}

			} else if (params.modalidad) {
				msg += ' en modalidad {modalidad}';
			}

			if (params.duracion) {
				msg += '. Duración {duracion} meses';
			}

			for (k in params) {
				msg = msg.replace(new RegExp('{' + k + '}', 'g'), '<b>' + params[k] + '</b>');
			}

			this.dom.summary.$empty().innerHTML = msg + '.';
		},


		displayFiltered: function () {
			var programas = this.findSelected();

			var container = this.dom.resultados.$empty();

			var cursos = this.findCursos(this.getColumn(programas, 'curso_id'));

			var findPrograma = function (curso_id) {
				return programas.find(function (item) { return item.curso_id === curso_id; });
			};

			var findTipo = function (tipo_id) {
				return EsanCursos.tipos.find(function (item) { return item.id === tipo_id; });
			};

			cursos.forEach(function (curso) {
				var programa = findPrograma(curso.id);
				var tipo     = findTipo(curso.curso_tipo_id);
				var nombre   = curso.nombre;

				if (this.options.display_tipo) {
					nombre += ' (' + tipo.nombre + ')';
				}

				if (programa.nombre) {
					nombre = programa.nombre;
				}

				/*
				<div class="col-12 col-md-6 col-lg-4">
					<div class="resultado-item">
						<div class="fecha">Inicio: 29 de Mayo 2020</div>
						<h2 class="title">Nombre curso</h2>
						<div class="checkbox">
							<label>
								<input type="checkbox" />
								<span class="checkmark"></span>
								<span class="etiqueta">Enviar por email</span>
							</label>
						</div>
						<a class="link-next" href="#">
							<i class="icon icon-next"></i>
						</a>
					</div>
				</div>
				*/

				container.$adopt(
					$E('div', {'class': 'col-12 col-md-6 col-lg-4'}).$adopt(
						$E('a', {'class': 'resultado-item', 'href': (curso.url_path || '/' + tipo.alias + '/' + curso.alias)}).$adopt(
							$E('div', {'class': 'fecha'}).$text('Inicio: ' + programa.inicio),
							$E('h2', {'class': 'title'}).$text(nombre),
							$E('span', {'class': 'link-next'}).$adopt(
								$E('i', {'class': 'icon icon-next'})
							)
						)
					)
				);
			}, this);
		},


		loadDefault: function () {
			this.setData(this.getDataFromLocalStorage() || this.getDataDefault());
			this.applyFilter();
		},


		// ---------------------------------------------------------------------


		getDataFromLocalStorage: function () {
			var data = localStorage.getItem('EsanCursosFiltros');
			return data ? JSON.parse(data) : null;
		},


		persistToLocalStorage: function () {
			localStorage.setItem('EsanCursosFiltros', JSON.stringify(this.getData()));
		},


		// ---------------------------------------------------------------------


		enableInputs: function () {
			var inputs = this.dom.inputs, k;

			for (k in inputs) {
				if (k === 'dirigido_a') {
					continue;
				}

				if (Array.isArray(inputs[k])) {
					inputs[k].forEach(function (input) { input.disabled = false; });
				} else {
					inputs[k].disabled = false;
				}
			}
		},


		disableInputs: function () {
			var inputs = this.dom.inputs, k;

			for (k in inputs) {
				if (k === 'dirigido_a') {
					continue;
				}

				if (Array.isArray(inputs[k])) {
					inputs[k].forEach(function (input) { input.disabled = true; });
				} else {
					inputs[k].disabled = true;
				}
			}
		},


		// ---------------------------------------------------------------------


		onChangeDirigidoA: function  () {
			var dirigido_a = this.getInputValue('dirigido_a');

			if (dirigido_a === '1') {
				this.enableInputs();
				this.fillComboCiudad();
			} else {
				this.disableInputs();
			}
		},


		// ---------------------------------------------------------------------


		fillComboCiudad: function () {
			var combo = this.emptyCombo('ciudad');

			var cursos_ids   = this.getColumn(this.findCursos(), 'id');
			var ciudades_ids = this.getColumn(this.findProgramas(cursos_ids), 'ciudad_id');

			this.findCiudades(ciudades_ids).forEach(function (opt) {
				combo.element.$adopt(
					$E('option', {value: opt.id}).$text(opt.nombre)
				);
			});

			this.restoreComboValue(combo);

			this.fillComboModalidad();
		},


		fillComboModalidad: function () {
			var combo = this.emptyCombo('modalidad');

			var data = this.getData();

			var cursos_ids      = this.getColumn(this.findCursos(), 'id');
			var ciudades_ids    = this.buildArray(data.ciudad);
			var modalidades_ids = this.getColumn(this.findProgramas(cursos_ids, ciudades_ids), 'modalidad_id');

			this.findModalidades(modalidades_ids).forEach(function (opt) {
				combo.element.$adopt(
					$E('option', {value: opt.id}).$text(opt.nombre)
				);
			});

			this.restoreComboValue(combo);

			this.fillComboArea();
		},


		fillComboArea: function () {
			var combo = this.emptyCombo('area');

			var data = this.getData();

			var ciudades_ids    = this.buildArray(data.ciudad);
			var modalidades_ids = this.buildArray(data.modalidad);
			var cursos_ids      = this.getColumn(this.findProgramas(null, ciudades_ids, modalidades_ids), 'curso_id');
			var areas_ids       = this.getColumn(this.findCursos(cursos_ids), 'area_id');

			this.findAreas(areas_ids).forEach(function (opt) {
				combo.element.$adopt(
					$E('option', {value: opt.id}).$text(opt.nombre)
				);
			});

			this.restoreComboValue(combo);

			this.fillComboDuracion();
		},


		fillComboDuracion: function () {
			var combo = this.emptyCombo('duracion');

			var data = this.getData();

			var areas_ids       = this.buildArray(data.area);
			var cursos_ids      = this.getColumn(this.findCursos(null, areas_ids), 'id');
			var ciudades_ids    = this.buildArray(data.ciudad);
			var modalidades_ids = this.buildArray(data.modalidad);

			var duraciones = [];

			this.findProgramas(cursos_ids, ciudades_ids, modalidades_ids).forEach(function (programa) {
				var duracion = parseInt(programa.duracion);

				if (duraciones.indexOf(duracion) < 0) {
					duraciones.push(duracion);
				}
			});

			duraciones.sort(function (a, b) { return a - b; }).forEach(function (opt) {
				combo.element.$adopt(
					$E('option', {value: opt}).$text(opt + ' meses')
				);
			});

			this.restoreComboValue(combo);
		},


		emptyCombo: function (k) {
			var combo = this.dom.inputs[k], value = combo.value;

			combo.$empty().$adopt(combo._placeholder);

			return {element: combo, value: value};
		},


		restoreComboValue: function (combo) {
			if (combo.element.$('option[value="' + combo.value + '"]')) {
				combo.element.value = combo.value;
			}
		},


		// ---------------------------------------------------------------------


		findCursos: function (ids, areas_ids) {
			return EsanCursos.cursos.filter(function (item) {
				return (!ids       || ids.includes(item.id)) &&
				       (!areas_ids || areas_ids.includes(item.area_id));
			});
		},


		findCiudades: function (ids) {
			return EsanCursos.ciudades.filter(function (item) {
				return (!ids || ids.includes(item.id));
			});
		},


		findModalidades: function (ids) {
			return EsanCursos.modalidades.filter(function (item) {
				return (!ids || ids.includes(item.id));
			});
		},


		findAreas: function (ids) {
			return EsanCursos.areas.filter(function (item) {
				return (!ids || ids.includes(item.id));
			});
		},


		findProgramas: function (
			cursos_ids,
			ciudades_ids,
			modalidades_ids,
			duracion
		) {

			return EsanCursos.programas.filter(function (item) {
				return (!cursos_ids      || cursos_ids.includes(item.curso_id)) &&
				       (!ciudades_ids    || ciudades_ids.includes(item.ciudad_id)) &&
					   (!modalidades_ids || modalidades_ids.includes(item.modalidad_id)) &&
					   (!duracion        || duracion === item.duracion);
			});
		},


		findSelected: function () {
			var data = this.getData();

			var search = data.search.toLowerCase().trim();

			var ciudades_ids    = this.buildArray(data.ciudad);
			var modalidades_ids = this.buildArray(data.modalidad);
			var areas_ids       = this.buildArray(data.area);

			var cursos = this.findCursos(null, areas_ids);

			if (search) {
				cursos = cursos.filter(function (item) {
					return item.nombre.toLowerCase().indexOf(search) >= 0;
				});
			}

			var cursos_ids = this.getColumn(cursos, 'id');

			return this.findProgramas(cursos_ids, ciudades_ids, modalidades_ids, data.duracion);
		},


		findDataItem: function (k, id) {
			return EsanCursos[k].find(function (item) {
				return item.id === id;
			});
		},


		// ---------------------------------------------------------------------


		getInputValue: function (k) {
			var input = this.dom.inputs[k];

			switch (k) {
				case 'dirigido_a':
					return input.find(function (el) { return el.checked; }).value;

				default:
					return input.value;
			}
		},


		setInputValue: function (k, v) {
			var inputs = this.dom.inputs;

			var actions = {
				ciudad    : 'fillComboModalidad',
				modalidad : 'fillComboArea',
				area      : 'fillComboDuracion',
			};

			if (k === 'dirigido_a') {
				inputs[k].forEach(function (el) { el.checked = el.value === v; });

			} else {
				inputs[k].value = v;

				if (actions[k]) {
					this[actions[k]]();
				}
			}
		},


		setData: function (data) {
			for (var k in this.dom.inputs) {
				this.setInputValue(k, data[k]);
			}
		},

		getData: function () {
			var data = {}, k;

			for (k in this.dom.inputs) {
				data[k] = this.getInputValue(k);
			}

			return data;
		},


		getDataDefault: function () {
			return {
				dirigido_a : "1",
				ciudad     : "",
				modalidad  : "",
				area       : "",
				duracion   : "",
				search     : ""
			};
		},



		// -------------------------------------------------------------------------


		getColumn: function (list, k) {
			return list.map(function (item) {
				return item[k];
			});
		},


		buildArray: function (item) {
			return item === '' ? null : [item];
		}

	};


	// -------------------------------------------------------------------------


	win.addEventListener('DOMContentLoaded', function () {
		var form = $('form.cursos-buscardor');

		if (form) {
			form.$dispose().removeAttribute('style');
			EsanCursosFiltros.initialize(form, win.EsanCursosFiltrosOptions || {});
		}
	});

	win.EsanCursosFiltros = EsanCursosFiltros;

})(window, document, Element.prototype);
