(function (win, doc, El_Proto) {

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

	El_Proto.$ = function (selector) {
		return this.querySelector(selector);
	};

	El_Proto.$$ = function (selector) {
		return Array.prototype.slice.call(this.querySelectorAll(selector));
	};

	El_Proto.$text = function (text) {
		this.appendChild($T(text));
		return this;
	};

	El_Proto.$html = function (html) {
		this.innerHTML = html;
		return this;
	};

	El_Proto.$empty = function () {
		while (this.firstChild) {
			this.removeChild(this.firstChild);
		}

		return this;
	};

	El_Proto.$adopt = function () {
		for (var i = 0, l = arguments.length; i < l; i++) {
			this.appendChild(arguments[i]);
		}

		return this;
	};

	El_Proto.$dispose = function () {
		if (this.parentNode) {
			this.parentNode.removeChild(this);
		}

		return this;
	};


	// -------------------------------------------------------------------------

	var sendJsonRequest = function (url, data, callback) {
		var xhr = (function () {
			return 'ActiveXObject' in win ?
				new ActiveXObject('Microsoft.XMLHTTP') :
				new XMLHttpRequest();
		})();

		xhr.open('POST', '/administrator/templates/pee-registro-form/proxy.php', true);

		xhr.setRequestHeader('Content-Type', 'application/json; charset=UTF-8');

		xhr.onreadystatechange = function () {
			if (xhr.readyState === 4)  {
				callback(xhr.status, xhr.responseText);
			}
		};

		xhr.send(JSON.stringify({
			method  : 'POST',
			url     : url,
			headers : ['Content-Type: application/json; charset=UTF-8'],
			body    : JSON.stringify(data),
		}));
	};


	// -------------------------------------------------------------------------

	var PEERegistroData = {

		endpoint: 'http://restws.esan.edu.pe/GestionAcademica/InscripcionOnline/Inscripcion.svc/EspecialidadCurso',

		token: {},

		data: null,


		loadData: function (token_raw) {
			this.parseToken(token_raw);

			sendJsonRequest(
				this.endpoint,
				{tipoConvocatoria: 'PEE', prodCodigo: 'pee'},
				this.onLoadData.bind(this)
			);
		},


		onLoadData: function (http_status, response) {
			if (http_status === 200) {
				response = JSON.parse(response);

				if (response && response.ListarEspecialidadCursoResult) {
					this.find(response.ListarEspecialidadCursoResult);
				}
			}
		},


		find: function (results) {
			var token = this.token;

			this.data = results.find(function (item) {
				return item.Conv_Codigo   === token.Conv_Codigo,
					   item.IdCurso       === token.IdCurso,
					   item.IdEspecialiad === token.IdEspecialiad,
					   item.IdSeccion     === token.IdSeccion,
					   item.SeccionCodigo === token.SeccionCodigo,
					   item.Prom_Codigo   === token.Prom_Codigo
			});

			if (this.data) {
				PEERegistroForm.initialize(this.data);
			} else {
				console.error("[PEERegistroData]: Can't find data for `codigo_pago` = " + this.token_raw);
			}
		},


		parseToken: function (token_raw) {
			var parts = token_raw.split('~');

			this.token_raw = token_raw;

			this.token = {
				Conv_Codigo   : parts[0],
				IdCurso       : parseInt(parts[1]),
				IdEspecialiad : parseInt(parts[2]),
				IdSeccion     : parseInt(parts[3]),
				SeccionCodigo : parts[4],
				Prom_Codigo   : parts[5]
			};
		}
	};


	// -------------------------------------------------------------------------

	var PEERegistroForm = {

		endpoint: 'http://restws.esan.edu.pe/GestionAcademica/InscripcionOnline/Inscripcion.svc/RegistrarPreInscripcionPEE',

		fields: {
			nombre: {
				required    : true,
				message     : 'Debe ingresar su nombre',
				placeholder : 'Ingrese su nombre',
				type        : 'text'
			},

			apellido_paterno: {
				required    : true,
				message     : 'Debe ingresar su apellido paterno',
				placeholder : 'Ingrese su apellido paterno',
				type        : 'text'
			},

			apellido_materno: {
				required    : true,
				message     : 'Debe ingresar su apellido materno',
				placeholder : 'Ingrese su apellido materno',
				type        : 'text'
			},

			documento_tipo: {
				required    : true,
				message     : 'Debe seleccionar su tipo de documento',
				placeholder : 'Seleccione documento de identidad',
				type        : 'text',
				options     : {
					'CE'       : 'CARNET DE EXTRANJERÍA',
					'LE / DNI' : 'DOC. NAC. DE IDENTIDAD',
					'PA'       : 'PASAPORTE',
				}
			},

			documento_numero: {
				required    : true,
				message     : 'Debe ingresar su número de documento',
				placeholder : 'Ingrese su número de documento',
				type        : 'documento'
			},

			telefono: {
				required    : true,
				message     : 'Debe ingresar un número válido',
				placeholder : 'Ingrese su número de teléfono',
				type        : 'telefono'
			},

			email: {
				required    : true,
				message     : 'Debe ingresar su email',
				placeholder : 'Ingrese su email',
				type        : 'email'
			}
		},


		types: {
			text      : /\S+/,
			documento : /^\d{8,20}$/,
			email     : /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/,
			telefono  : /^\d{6,11}$/,
		},


		dom: {
			inputs  : {},
			buttons : {},
			modal   : {},
			extra   : {}
		},


		info: null,


		initialize: function (info) {
			this.info = info;

			this.buildForm(this.dom);
			this.buildModal(this.dom);
			this.setup(this.dom);
		},


		buildForm: function (dom) {
			var form   = $E('form', {'class': 'prf'}), k;
			var button = $E('button', {type: 'submit', 'class': 'btn btn-primary btn-lg text-uppercase'}).$text('Registrarse');
			var moneda = {'S': 'S/'};

			form.$adopt(
				$E('div', {'class': 'prf-title'}).$text('CURSO: ' + this.info.NombreCurso),
				$E('div', {'class': 'prf-price'}).$text(moneda[this.info.Moneda] + ' ' + this.info.costoCurso.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'))
			);

			Object.keys(this.fields).forEach(function (name) {
				var field = this.fields[name], input;

				if ('options' in field) {
					input = $E('select', {name: name});
					input.$adopt($E('option', {value: ''}).$text(field.placeholder))

					for (var k in field.options) {
						input.$adopt($E('option', {value: k}).$text(field.options[k]));
					}

				} else {
					input = $E('input', {name: name, placeholder: field.placeholder});
				}

				form.$adopt(
					$E('div', {'class': 'prf-input'}).$adopt(input)
				);

				dom.inputs[name] = input;
			}, this);

			form.$adopt(
				$E('div', {'class': 'prf-button'}).$adopt(button)
			);

			dom.form = form;
			dom.buttons.submit = button;
		},


		buildModal: function (dom) {
			dom.modal.overlay = $E('div', {'class': 'prfm-overlay'});
			dom.modal.wrapper = $E('div', {'class': 'prfm-wrapper'});
			dom.modal.close   = $E('div', {'class': 'prfm-close'});
			dom.modal.content = $E('div', {'class': 'prfm-content'});

			dom.modal.overlay.$adopt(
				dom.modal.wrapper.$adopt(
					dom.modal.close.$text('×'),
					dom.modal.content
				)
			);

			dom.modal.close.addEventListener('click', this.closeModal.bind(this));
		},


		setup: function (dom) {
			dom.form.addEventListener('submit', this.submit.bind(this));

			var k, input;

			for (k in dom.inputs) {
				input = dom.inputs[k];

				if (['documento_numero', 'telefono'].includes(input.type)) {
					input.addEventListener('keypress', this.allowOnlyNumbers.bind(this));
				}

				input.addEventListener('blur', function (k) {
					this.validateInput(k, false);
				}.bind(this, k));
			}

			// ----

			$$('.prf-register').forEach(function (button) {
				button.addEventListener('click', function (event) {
					event.preventDefault();
					this.displayForm();
				}.bind(this));
			}, this);
		},


		// ---------------------------------------------------------------------


		closeModal: function () {
			document.documentElement.classList.remove('no-scroll');
			this.dom.modal.overlay.$dispose();
		},


		displayModal: function () {
			document.documentElement.classList.add('no-scroll');
			document.body.$adopt(this.dom.modal.overlay);
		},


		displayForm: function () {
			this.dom.modal.content.$empty().$adopt(this.dom.form);
			this.displayModal();
		},


		// ---------------------------------------------------------------------


		validateInput: function (k, focus_on_error) {
			var is_valid = true;

			var value = this.getInputValue(k);
			var field = this.fields[k];
			var input = this.dom.inputs[k];

			if (is_valid && value === '' && field.required) {
				is_valid = false;
			}

			if (is_valid && value !== '' && !this.types[field.type].test(value)) {
				is_valid = false;
			}

			if (is_valid) {
				input.classList.remove('is-invalid');
				input.classList.add('is-valid');

				if (input.error) {
					input.error.$dispose();
				}

			} else {
				console.error('PEERegistroForm: [' + k + '] ' + field.message);

				if (!input.error) {
					input.error = $E('span', {'class': 'prf-input-error'}).$text(field.message);
				}

				input.parentNode.$adopt(input.error);

				input.classList.remove('is-valid');
				input.classList.add('is-invalid');

				if (focus_on_error) {
					input.focus();
				}
			}

			return is_valid;
		},


		validateForm: function () {
			for (var k in this.dom.inputs) {
				if (k in this.fields && !this.validateInput(k, true)) {
					return false;
				}
			}

			return true;
		},


		// ---------------------------------------------------------------------


		getInputValue: function (k) {
			var value, input = this.dom.inputs[k];

			if (input.type === 'checkbox') {
				value = input.checked ? '1' : '0';
			} else {
				value = input.value;
			}

			return value;
		},


		getFormData: function () {
			var data = {}, k;

			for (k in this.dom.inputs) {
				data[k] = this.getInputValue(k);
			}

			return data;
		},


		// ---------------------------------------------------------------------


		loading: function (is_loading) {
			var button = this.dom.buttons.submit;

			if (is_loading) {
				button.disabled = true;
				button.original_content = button.textContent;
				button.innerHTML = 'Enviando...';

			} else {
				button.disabled = false;
				button.innerHTML = button.original_content;
			}
		},


		submit: function (event) {
			event.preventDefault();

			if (!this.validateForm()) {
				return;
			}

			this.loading(true);

			sendJsonRequest(
				this.endpoint,
				this.prepareData(this.getFormData()),
				this.onSubmit.bind(this)
			);
		},


		onSubmit: function (http_status, response) {
			var is_success = false;

			if (http_status === 200) {
				response = JSON.parse(response);

				if (response && response.RegistraPreInscripcionPEEResult) {
					is_success = true;
				}
			}

			this.loading(false);

			this.displayMessage(is_success);
		},


		prepareData: function (form_data) {
			var prepared = {
				aPaterno              : '$apellido_paterno',
				aMaterno              : '$apellido_materno',
				nombre                : '$nombre',
				tipoDocumento         : '$documento_tipo',
				nroDocumentoIdentidad : '$documento_numero',
				telefono              : '$telefono',
				email                 : '$email',

				convCodigo            : '@Conv_Codigo',
				idSeccion             : '@IdSeccion',
				idCurso               : '@IdCurso',
				nombreCurso           : '@NombreCurso',
				costo                 : '@costoCurso',
				moneda                : '@Moneda'
			}, k, v;

			for (k in prepared) {
				v = prepared[k];

				if (v.substr(0, 1) === '$') {
					prepared[k] = form_data[v.substr(1)];
				} else if (v.substr(0, 1) === '@') {
					prepared[k] = this.info[v.substr(1)];
				}
			}

			return prepared;
		},


		// ---------------------------------------------------------------------

		displayMessage: function (is_success) {
			var msg = is_success ? '¡Gracias!' : 'Error!!'
			this.dom.modal.content.$empty().$text(msg);
			this.displayModal();
		},


		// ---------------------------------------------------------------------


		allowOnlyNumbers: function (event) {
			if (event.key && ! /^\d+$/.test(event.key)) {
				event.preventDefault();
			}
		}
	};


	// -------------------------------------------------------------------------


	win.addEventListener('DOMContentLoaded', function () {
		if (!('EsanCursoPrograma' in win) || !win.EsanCursoPrograma) {
			console.error('[PEERegistroData] Missing `EsanCursoPrograma` data.');
			return;
		}

		if (!win.EsanCursoPrograma.codigo_pago) {
			console.error('[PEERegistroData] Missing `codigo_programa` in `EsanCursoPrograma`.');
			return;
		}

		PEERegistroData.loadData(win.EsanCursoPrograma.codigo_pago);
	});

	win.PEERegistroData = PEERegistroData;
	win.PEERegistroForm = PEERegistroForm;

})(window, document, Element.prototype);
