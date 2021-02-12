(function (win, doc, EL) {

	// NODE CREATION ---------------------------------------------------------------

	var $T = function (text) {
		return doc.createTextNode(text);
	};

	var $E = function (tag, attrs, props) {
		if (attrs === undefined) {
			attrs = {};
		}

		if (props === undefined) {
			props = {};
		}

		if (/\..+/.test(tag)) {
			attrs['class'] = tag.split('.').slice(1).join(' ');
			tag = tag.replace(/\..+$/, '');
		}

		var el = doc.createElement(tag), k;

		for (k in attrs) {
			el.setAttribute(k, attrs[k]);
		}

		for (k in props) {
			el[k] = props[k];
		}

		return el;
	};


	// SELECTORS -------------------------------------------------------------------

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


	// CONTENT MANIPULATION --------------------------------------------------------

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


	// CLASS MANIPULATION ----------------------------------------------------------

	EL.$addClass = function (c) {
		this.classList.add(c);
		return this;
	};

	EL.$removeClass = function (c) {
		this.classList.remove(c);
		return this;
	};

	EL.$toggleClass = function (c) {
		this.classList.toggle(c);
		return this;
	};

	EL.$hasClass = function (c) {
		return this.classList.contains(c);
	};


	// DOM MANIPULATION ----------------------------------------------------------

	EL.$adopt = function () {
		for (var i = 0, l = arguments.length; i < l; i++) {
			if (arguments[i] instanceof Node) {
				this.appendChild(arguments[i]);
			} else {
				Array.from(arguments[i]).forEach(function (node) {
					this.appendChild(node);
				}, this);
			}
		}

		return this;
	};

	EL.$inject = function (target, location) {
		switch (location) {
			case 'top':
				target.insertBefore(this, target.firstChild);
				break;

			case 'before':
				target.parentNode.insertBefore(this, target);
				break;

			case 'after':
				target.parentNode.insertBefore(this, target.nextElementSibling);
				break;

			case 'bottom':
			default:
				target.appendChild(this);
		}

		return this;
	};

	EL.$grab = function (target, location) {
		target.$inject(this, location);
		return this;
	};

	EL.$replace = function (target) {
		this.$inject(target, 'before');
		target.$dispose();
		return this;
	};

	EL.$dispose = function () {
		if (this.parentNode) {
			this.parentNode.removeChild(this);
		}

		return this;
	};


	// EVENT HANDLING  -------------------------------------------------------------

	EL.$addEvent = function (type, listener) {
		this.addEventListener(type, listener);
		return this;
	};

	EL.$removeEvent = function (type, listener) {
		this.removeEventListener(type, listener);
		return this;
	};


	// STYLE MANIPULATION ----------------------------------------------------------

	EL.$setStyle = function (prop, value) {
		if (value === null) {
			this.style.removeProperty(prop);
		} else {
			prop = prop.replace(/-\w/g, function (s) { return s.substr(1).toUpperCase(); });
			this.style[prop] = value;
		}

		return this;
	};


	// -----------------------------------------------------------------------------

	var from = function (list) {
		return typeof list === 'string' ? EsanUniversidadesData[list] : list;
	};

	var find = function (list, col, val) {
		return from(list).find(function (item) {
			return val === 'string' ? item[col] === val : val.includes(item[col]);
		});
	};

	var filter = function (list, col, val) {
		return from(list).filter(function (item) {
			return typeof val === 'string' ? item[col] === val : val.includes(item[col]);
		});
	};

	var column = function (list, k) {
		return from(list).map(function (item) { return item[k]; });
	};


	// -----------------------------------------------------------------------------

	var Tabs = function (handlers, container) {
		this.dom = {
			handlers  : handlers,
			blocks    : container.$children(),
			container : container.$empty(),
		};

		this.previous = null;
		this.current  = null;

		this.setup(this.dom);
	};

	Object.assign(Tabs.prototype, {
		setup: function (dom) {
			dom.handlers.forEach(function (handler, i) {
				handler.$addEvent('click', this.display.bind(this, i));
			}, this);
		},

		display: function (index) {
			if (this.current === index) {
				return;
			}

			this.previous = this.current;
			this.current  = index;

			if (this.previous !== null) {
				this.dom.handlers[this.previous].$removeClass('active');
				this.dom.blocks[this.previous].$dispose();
			}

			this.dom.handlers[index].$addClass('active');
			this.dom.blocks[index].$inject(this.dom.container);

			if (this.onDisplay) {
				this.onDisplay(this.dom.handlers[index], this.dom.blocks[index], index);
			}
		}
	});


	// -----------------------------------------------------------------------------

	var ComboTabs = function (combo, container) {
		this.dom = {
			combo     : combo,
			blocks    : container.$children(),
			container : container.$empty(),
		};

		this.previous = null;
		this.current  = null;

		this.setup(this.dom);
	};

	Object.assign(ComboTabs.prototype, {
		setup: function (dom) {
			var combo = dom.combo;

			combo.$addEvent('change', function () {
				this.display(
					combo.$children().indexOf(combo.$('option[value="' + combo.value + '"]'))
				);
			}.bind(this));
		},

		display: function (index) {
			if (this.current === index) {
				return;
			}

			this.previous = this.current;
			this.current  = index;

			if (this.previous !== null) {
				this.dom.blocks[this.previous].$dispose();
			}

			this.dom.blocks[index].$inject(this.dom.container);
		}
	});


	// -----------------------------------------------------------------------------

	var EsanUniversidadesIntercambio = {

		dom: {},


		initialize: function (container, flags) {
			this.build(this.dom, container, flags);
		},


		build: function (dom, container, flags) {
			var k = 't_i', kb = k + '_b';

			// ------------------

			dom[k]  = $E('ul.tabs-intercambios-handlers.' + k);
			dom[kb] = $E('div.tabs-intercambios-blocks.' + kb);

			var flags_with_data = [];

			flags.forEach(function (intercambio) {
				var data = this.getIntercambioData(intercambio);

				if (data.universidades.length > 0) {
					flags_with_data.push(intercambio);
					this.buildIntercambio(data, intercambio, k, kb);
				}
			}, this);

			flags = flags_with_data;

			// ------------------

			dom.container = container.$adopt(dom[k], dom[kb]);
			dom[k].setAttribute('data-length', flags.length);

			// ------------------

			var universidades_tabs = new Tabs(dom[k].$children(), dom[kb]);

			universidades_tabs.onDisplay = function (handler, block) {
				jQuery(block).find('ul.universidad-gallery.slick-slider').slick('setPosition');

				if (jQuery.fn.videoPopup) {
					jQuery(block).find('[video-url]').videoPopup({
						autoplay: true,
						showControls: true,
						showVideoInformations: false
					});
				}
			};

			universidades_tabs.display(0);
		},


		buildIntercambio: function (data, intercambio, pk, pkb) {
			var k   = pk + '_' + intercambio + '_r';
			var kb  = k + '_b';
			var dom = this.dom;

			// ------------------

			dom[k]  = $E('select.tabs-regiones-handlers.' + k);
			dom[kb] = $E('div.tabs-regiones-blocks.' + kb);

			// ------------------

			dom[pk].$adopt(
				$E('li').$adopt(
					$E('span').$adopt(
						$T('Programas de '),
						$E('br'),
						$T(intercambio.toUpperCase())
					)
				)
			);

			dom[pkb].$adopt(
				$E('div.intercambio-block').$adopt(
					dom[k],
					dom[kb],
				)
			);

			// ------------------

			data.regiones.forEach(function (region) {
				this.buildRegion(data, region, k, kb);
			}, this)

			// ------------------

			new ComboTabs(dom[k], dom[kb]).display(0);
		},


		buildRegion: function (data, region, pk, pkb) {
			var k   = pk + '_' + region.id + '_paises';
			var kb  = k + '_b';
			var dom = this.dom;

			// ------------------

			dom[k]  = $E('div.tabs-paises-handlers.' + k);
			dom[kb] = $E('div.tabs-paises-blocks.' + kb);

			// ------------------

			dom[pk].$adopt(
				$E('option', {value: region.id}).$text(region.nombre)
			);

			dom[pkb].$adopt(
				$E('div.region-block').$adopt(
					dom[k],
					dom[kb],
				)
			);

			// ------------------

			filter(data.paises, 'region_id', region.id).forEach(function (pais) {
				this.buildPais(data, pais, k, kb)
			}, this);

			// ------------------

			var regiones_tabs = new Tabs(
				dom[k].$$('.universidad'),
				dom[kb]
			);

			regiones_tabs.onDisplay = function (handler, block) {
				jQuery(block).find('ul.universidad-gallery:not(.slick-slider)').slick({
					dots: true,
					infinite: true,
					speed: 300,
					slidesToShow: 1,
					centerMode: false,
					variableWidth: false,
					customPaging: function (slick, i) {
						return '<a>0' + (i + 1) + '</a>/0' + slick.slideCount;
					}
				});

				if (jQuery.fn.videoPopup) {
					jQuery(block).find('[video-url]').videoPopup({
						autoplay: true,
						showControls: true,
						showVideoInformations: false
					});
				}
			};

			regiones_tabs.display(0);
		},


		buildPais: function (data, pais, pk, pkb) {
			var dom = this.dom;
			var ul  = $E('ul');

			// ------------------

			dom[pk].$adopt(
				$E('div.pais').$text(pais.nombre),
				ul
			);

			filter(data.universidades, 'pais_id', pais.id).forEach(function (universidad) {
				ul.$adopt(
					$E('li.universidad').$adopt(
						$E('strong').$text(universidad.nombre),
						$E('span').$text(universidad.ubicacion)
					)
				);

				dom[pkb].$adopt(
					$E('div.universidad-block').$adopt(
						$E('div.universidad-nombre').$text(universidad.nombre),
						$E('div.universidad-descripcion').$text(universidad.descripcion),
						$E('div.universidad-contenido').$html(universidad.contenido),
					)
				);
			}, this);
		},



		getIntercambioData: function (intercambio) {
			var universidades = filter('universidades', 'intercambio_' + intercambio, '1');
			var paises        = filter('paises', 'id', column(universidades, 'pais_id'));
			var regiones      = filter('regiones', 'id', column(paises, 'region_id'));

			return {
				universidades : universidades,
				paises        : paises,
				regiones      : regiones,
			};
		}
	};


	// -----------------------------------------------------------------------------

	win.EsanUniversidadesIntercambio = EsanUniversidadesIntercambio;

})(window, document, Element.prototype);
