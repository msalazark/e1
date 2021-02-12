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

	EL.$children = function () {
		return Array.prototype.slice.call(this.children);
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

	EL.$addClass = function (class_name) {
		this.classList.add(class_name);
		return this;
	};

	EL.$removeClass = function (class_name) {
		this.classList.remove(class_name);
		return this;
	};

	EL.$addEvent = function (event, fn) {
		this.addEventListener(event, fn);
		return this;
	};


	// -------------------------------------------------------------------------



	var MBAProgramaSelector = {

		data: null,

		preferred: {
			ciudad: 'lima',
			modalidad: 'tiempo-completo',
		},

		selected: {
			ciudad_id    : null,
			modalidad_id : null,

			programa  : null,
			ciudad    : null,
			modalidad : null,
		},

		mode: 'inline',


		initialize: function (data, dom) {
			this.data = data;

			this.build(dom);

			this.fillCiudades();

			if (this.loadFromURL() || this.loadFromLocalStorage()) {
				this.applyFilter();

			} else if (this.loadPreferred() || this.loadDefault()) {
				this.updateData();
			}

			this.displayBlock('ciudad');

			this.saveToLocalStorage();
		},


		build: function (dom) {

			this.buildModal(dom);

			dom.blocks           = $E('div', {'class': 'ms-blocks'});
			dom.blocks_inner     = $E('div', {'class': 'ms-blocks-inner'});
			dom.blocks_ciudad    = $E('div', {'class': 'ms-blocks-ciudad'});
			dom.blocks_modalidad = $E('div', {'class': 'ms-blocks-modalidad'});

			dom.options_ciudad    = $E('div', {'class': 'ms-options'});
			dom.options_modalidad = $E('div', {'class': 'ms-options'});

			dom.blocks.$adopt(
				$E('div', {'class': 'ms-blocks-title'}).$text('Completa los pasos para brindarte información del MBA'),
				$E('div', {'class': 'ms-blocks-wrapper'}).$adopt(
					dom.blocks_inner.$adopt(
						dom.blocks_ciudad.$adopt(
							$E('div', {'class': 'ms-blocks-steps step-1'}).$text('Paso 1/2: Elige tu ciudad'),
							dom.options_ciudad
						),
						dom.blocks_modalidad.$adopt(
							$E('div', {'class': 'ms-blocks-steps step-2'}).$text('Paso 2/2: Elige tu modalidad'),
							dom.options_modalidad
						)
					)
				)
			);

			if (dom.wrapper) {
				dom.wrapper.$adopt(dom.blocks);
			}

			// --------------

			dom.triggers.forEach(this.setupTrigger, this);

			this.dom = dom;

			// --------------

			if ('SolicitudInformacionFormulario' in win && SolicitudInformacionFormulario.dom.form) {
				var ms_trigger = $E('div', {'data-ms-trigger': ''});
				SolicitudInformacionFormulario.dom.form.insertBefore(ms_trigger, SolicitudInformacionFormulario.dom.form.firstChild);
				this.addTrigger(ms_trigger);
			}
		},


		addTrigger: function (el) {
			this.dom.triggers.push(this.setupTrigger(el));
		},


		setupTrigger: function (el) {
			if (el.getAttribute('data-ms-trigger') && el.getAttribute('data-ms-trigger') === '1') {
				return;
			}

			el.setAttribute('data-ms-trigger', '1');
			el.$addEvent('click', this.displayModal.bind(this));

			return el;
		},


		// ---------------------------------------------------------------------


		buildModal: function (dom) {
			dom.modal_overlay = $E('div', {'class': 'mms-overlay'});
			dom.modal         = $E('div', {'class': 'mms-modal'});
			dom.modal_close   = $E('div', {'class': 'mms-close'});
			dom.modal_content = $E('div', {'class': 'mms-content'});

			dom.modal_overlay.$adopt(
				dom.modal.$adopt(
					dom.modal_close.$text('×'),
					dom.modal_content
				)
			);

			dom.modal_close.$addEvent('click', this.closeModal.bind(this));
		},


		displayModal: function (event) {
			if (event) {
				event.preventDefault();
			}

			doc.documentElement.$addClass('no-scroll');
			this.dom.modal_content.$adopt(this.dom.blocks);
			doc.body.$adopt(this.dom.modal_overlay);
			this.displayBlock('ciudad');
		},


		closeModal: function () {
			this.dom.modal_overlay.$dispose();
			doc.documentElement.$removeClass('no-scroll');
		},


		// ---------------------------------------------------------------------


		buildOptions: function (type, container, data) {
			container.$empty();

			data.forEach(function (item) {
				var option = $E('div', {'class': 'ms-option', 'data-id': item.id});

				container.$adopt(
					option.$text(item.nombre)
				);

				option.$addEvent('click', this.pickOption.bind(this, type, item.id));
			}, this);
		},


		fillCiudades: function () {
			this.buildOptions(
				'ciudad',
				this.dom.options_ciudad,
				this.getCiudades()
			);
		},


		fillModalidades: function () {
			this.buildOptions(
				'modalidad',
				this.dom.options_modalidad,
				this.getModalidades()
			);
		},


		// ---------------------------------------------------------------------


		getCiudades: function () {
			var ciudades_ids = this.data.programas.map(function (programa) {
				return programa.ciudad_id;
			});

			return this.data.ciudades.filter(function (ciudad) {
				return ciudades_ids.includes(ciudad.id);
			});
		},


		getModalidades: function () {
			var ciudad_id = this.selected.ciudad_id;

			var modalidades_ids = this.data.programas.filter(function (programa) {
				return programa.ciudad_id === ciudad_id;
			}).map(function (programa) {
				return programa.modalidad_id;
			});

			return this.data.modalidades.filter(function (modalidad) {
				return modalidades_ids.includes(modalidad.id);
			});
		},


		getSelected: function (return_bool) {
			var selected = this.selected;

			selected.ciudad = this.data.ciudades.find(function (ciudad) {
				return ciudad.id === selected.ciudad_id;
			});

			selected.modalidad = this.data.modalidades.find(function (modalidad) {
				return modalidad.id === selected.modalidad_id;
			});

			selected.programa = this.data.programas.find(function (programa) {
				return programa.ciudad_id    === selected.ciudad_id &&
				       programa.modalidad_id === selected.modalidad_id;
			});

			if (return_bool) {
				return selected.ciudad && selected.modalidad && selected.programa;
			}

			return selected;
		},


		// ---------------------------------------------------------------------

		pickOption: function (type, option_id) {
			this.selected[type + '_id'] = option_id;

			if (type === 'ciudad') {
				this.selected.modalidad_id = null;
				this.fillModalidades();
				this.displayBlock('modalidad');
			}

			if (type === 'modalidad') {
				this.saveToLocalStorage();
				this.applyFilter();
			}
		},


		displayBlock: function (type) {
			var dom      = this.dom;
			var options  = dom['options_' + type];
			var active   = options.$('.active');
			var selected = this.getSelected(false);

			if (active) {
				active.$removeClass('active');
			}

			if (selected[type]) {
				options.$('[data-id="' + selected[type].id + '"]').$addClass('active');
			}

			// -----------

			var block = dom['blocks_' + type];
			var track = block.parentNode.parentNode;
			var index = block.parentNode.$children().indexOf(block);
			var left  = track.offsetWidth * index;

			track.scrollTo({left: left, behavior: 'smooth'});
		},


		applyFilter: function () {
			if (this.mode === 'modal') {
				this.closeModal();
			}

			if (this.mode === 'inline') {
				this.mode = 'modal';
			}

			this.updateData();
		},


		updateData: function () {
			var selected = this.getSelected(false);

			this.dom.triggers.forEach(function (trigger) {
				trigger.$empty().$adopt(
					$E('span').$text('Estás en MBA en ' + selected.ciudad.nombre + ' - ' + selected.modalidad.nombre + ' '),
					$E('span', {'class': 'btn-modificar-custom'}).$adopt(
						$E('a', {'class': 'open-popup-modificar btn-modal-modificar'}).$text('+ Opciones')
					)
				);
			});

			['programa', 'ciudad', 'modalidad'].forEach(function (k) {
				['mba', 'esan'].forEach(function (prefix) {
					var attr = 'data-' + prefix + '-' + k;

					$$('[' + attr + ']').forEach(function (el) {
						if (el.getAttribute(attr) && el.getAttribute(attr) in selected[k]) {
							var value = selected[k][el.getAttribute(attr)];
							var is_html = el.getAttribute('data-content-type') === 'html';
							el.$empty()[is_html ? '$html' : '$text'](value || '');
						}
					})
				})
			});
		},


		loadFromURL: function () {
			var args = (function (qs) {
				var obj = {}, tokens, re = /[?&]?([^=]+)=([^&]*)/g;

				qs = qs.split('+').join(' ');

				while (tokens = re.exec(qs)) {
					obj[decodeURIComponent(tokens[1])] = decodeURIComponent(tokens[2]);
				}

				return obj;
			})(location.search);

			if (args.ciudad && args.modalidad) {
				var ciudad = this.data.ciudades.find(function (ciudad) {
					return ciudad.alias === args.ciudad;
				});

				var modalidad = this.data.modalidades.find(function (modalidad) {
					return modalidad.alias === args.modalidad;
				});

				if (ciudad && modalidad) {
					this.selected.ciudad_id = ciudad.id;
					this.selected.modalidad_id = modalidad.id;

					return !!this.getSelected(true);
				}
			}

			return null;
		},


		saveToLocalStorage: function () {
			var selected = this.selected;

			var data = JSON.stringify({
				ciudad_id    : selected.ciudad_id,
				modalidad_id : selected.modalidad_id,
				ciudad       : selected.ciudad_id,
				modalidad    : selected.modalidad_id,
			});

			win.localStorage.setItem('MBAProgramaSelector', data);
		},


		loadFromLocalStorage: function () {
			var data = win.localStorage.getItem('MBAProgramaSelector');

			if (data) {
				data = JSON.parse(data);

				if (data.ciudad_id && data.modalidad_id) {
					this.selected.ciudad_id = data.ciudad_id;
					this.selected.modalidad_id = data.modalidad_id;

					return !!this.getSelected(true);
				}
			}
		},


		loadPreferred: function () {
			var preferred = this.preferred;
			var ciudad    = this.data.ciudades.find(function (item) { return item.alias === preferred.ciudad; });
			var modalidad = this.data.modalidades.find(function (item) { return item.alias === preferred.modalidad; });

			if (!ciudad || !modalidad) {
				return false;
			}

			this.selected.ciudad_id    = ciudad.id;
			this.selected.modalidad_id = modalidad.id;

			return !!this.getSelected(true);
		},


		loadDefault: function () {
			var programa = this.data.programas[0] || null;

			if (!programa) {
				return false;
			}

			this.selected.ciudad_id    = programa.ciudad_id;
			this.selected.modalidad_id = programa.modalidad_id;

			return !!this.getSelected(true);
		}
	};


	// -------------------------------------------------------------------------


	win.addEventListener('DOMContentLoaded', function () {
		if (win.EsanCurso) {
			MBAProgramaSelector.initialize(win.EsanCurso, {
				section  : $('#sp-page-title'),
				wrapper  : $('.ms-wrapper'),
				triggers : $$('.ms-trigger,[data-ms-trigger]')
			});
		}
	});

	win.MBAProgramaSelector = MBAProgramaSelector;

})(window, document, Element.prototype);
