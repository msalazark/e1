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


	var MaestriaDocentes = {

		dom: {},


		initialize: function (container) {

			this.dom.container = container;

			var docentes = this.getDocentes();

			if (docentes.length === 0) {
				return;
			}

			this.build(this.dom);

			this.displayDocentes(docentes);
		},


		build: function (dom) {

			dom.wrapper       = $E('div', {'class': 'docentes-list'});

			dom.modal         = $E('div', {'class': 'docente-modal'});
			dom.modal_overlay = $E('div', {'class': 'docente-modal-overlay'});
			dom.modal_content = $E('div', {'class': 'docente-modal-content'});
			dom.modal_close   = $E('div', {'class': 'docente-modal-close'});

			dom.container.$adopt(
				$E('div', {'class': 'intro'}).$adopt(
					$E('h2').$text('Docentes')
				),
				dom.wrapper
			);

			dom.modal_overlay.$adopt(
				dom.modal.$adopt(
					dom.modal_close,
					dom.modal_content
				)
			);

			dom.modal_overlay.addEventListener('click', this.hideModal.bind(this));
			dom.modal_close.addEventListener('click', this.hideModal.bind(this));
		},


		displayDocentes: function (docentes) {

			this.dom.wrapper.$empty();

			docentes.forEach(function (docente) {
				this.dom.wrapper.$adopt(
					this.builDocenteItem(docente)
				);
			}, this);
		},


		displayDocenteDetails: function (docente) {

			this.displayModal(this.builDocenteModalItem(docente));
		},


		getDocentes: function () {

			var selected = localStorage.getItem('EsanCursosFiltros');
			var ciudad_id, modalidad_id, programa, docentes = [];

			if (selected) {
				selected     = JSON.parse(selected);
				ciudad_id    = selected.ciudad;
				modalidad_id = selected.modalidad;
			}

			if (ciudad_id && modalidad_id) {
				programa = EsanCurso.programas.find(function (item) {
					return item.ciudad_id === ciudad_id &&
					       item.modalidad_id === modalidad_id;
				});
			}

			if (!programa) {
				programa = EsanCurso.programas[0] || null;
			}

			if (programa) {
				docentes = EsanCurso.docentes.filter(function (item) {
					return programa.docentes_ids.includes(item.id);
				});
			}

			return docentes;
		},


		builDocenteItem: function (docente) {

			var item    = $E('div', {'class': 'docente'});
			var foto    = docente.foto || '/images/usuarios/placeholder.png';
			var tmp     = $E('div').$html(docente.resumen);
			var resumen = tmp.textContent, limit = 200;

			if (resumen.length > limit) {
				resumen = resumen.substr(0, resumen.indexOf(' ', limit)) + ' ...';
			}

			item.$adopt(
				$E('div', {'class': 'perfil'}).$adopt(
					$E('div', {'class': 'foto'}).$adopt(
						$E('img', {'src': foto})
					),
					$E('div', {'class': 'nombre'}).$adopt(
						$E('strong').$html(docente.apellidos + ', ' + docente.nombres),
						$E('em').$text(docente.cargo)
					)
				),
				$E('div', {'class': 'resumen'}).$adopt(
					$E('div').$html(resumen),
					$E('span', {'class': 'cv'}).$text('Ver CV →')
				)
			);

			item.addEventListener('click', this.displayDocenteDetails.bind(this, docente));

			return item;
		},


		builDocenteModalItem: function (docente) {

			var wrapper = $E('div', {'class': 'docente-details'});
			var foto    = docente.foto || '/images/usuarios/placeholder.png';

			wrapper.$adopt(
				$E('div', {'class': 'perfil'}).$adopt(
					$E('div', {'class': 'foto'}).$adopt(
						$E('img', {'src': foto})
					),
					$E('div', {'class': 'nombre'}).$adopt(
						$E('span').$text(docente.cargo),
						$E('strong').$text(docente.apellidos + ', ' + docente.nombres),
						$E('em').$text(docente.area)
					)
				),

				$E('div', {'class': 'resumen'}).$html(docente.resumen)
			);

			this.loadAutorArticulos(docente, wrapper);

			return wrapper;
		},


		displayArticulos: function (wrapper, articulos) {

			var container = $E('div', {'class': 'docente-articulos'});
			var list = $E('ul');

			container.$adopt(
				$E('h3').$text('Artículos más recientes del docente'),
				list
			);

			articulos.forEach(function (articulo) {
				list.$adopt(
					$E('li').$adopt(
						$E('a', {href: articulo.url}).$text(articulo.nombre)
					)
				);
			});

			wrapper.$adopt(container);
		},


		displayExperiencia: function (wrapper, docente) {

			wrapper.$adopt(
				$E('div', {'class': 'docente-experiencia'}).$adopt(
					$E('h3').$text('Experiencia'),
					$E('div').$html(docente.experiencia || '')
				)
			);
		},


		loadAutorArticulos: function (docente, wrapper) {
			var xhr = new XMLHttpRequest();
			var url = '/index.php' +
			          '?option=com_ajax' +
			          '&module=esan_blog' +
			          '&method=getAutorArticulos' +
			          '&format=json' +
			          '&menu_item_id=580' +
			          '&blog_id=1' +
			          '&usuario_id=' + docente.id +
					  '&limite=5';

			var onload = function () {
				if (!(xhr.readyState === 4 && xhr.status === 200)) {
					return;
				}

				var response = JSON.parse(xhr.responseText);

				if (!response.success || !response.data || response.data.length === 0) {
					this.displayExperiencia(wrapper, docente);
				} else {
					this.displayArticulos(wrapper, response.data);
				}
			}.bind(this);

			xhr.open('GET', url);
			xhr.onreadystatechange = onload;
			xhr.send();
		},


		displayModal: function (content) {

			this.dom.modal_content.$empty().$adopt(content);

			document.body.$adopt(this.dom.modal_overlay);

			document.documentElement.classList.add('no-scroll');
		},


		hideModal: function () {

			this.dom.modal_overlay.$dispose();

			document.documentElement.classList.remove('no-scroll');
		}

	};


	win.addEventListener('DOMContentLoaded', function () {
		var refer = $('.docentes-page .container');

		if (refer) {
			MaestriaDocentes.initialize(refer);
		}
	});

})(window, document, Element.prototype);
