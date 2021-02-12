(function (win, doc) {

	var $ = function (selector, node) {
		return (node || doc).querySelector(selector);
	};

	var $$ = function (selector, node) {
		return Array.prototype.slice.call((node || doc).querySelectorAll(selector));
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

	var $T = function (str) {
		return doc.createTextNode(str);
	};


	Object.assign(Element.prototype, {

		$: function (selector) {
			return $(selector, this);
		},

		$$: function (selector) {
			return $$(selector, this);
		},

		$addClass: function (class_name) {
			this.classList.add(class_name);
			return this;
		},

		$removeClass: function (class_name) {
			this.classList.remove(class_name);
			return this;
		},

		$hasClass: function (class_name) {
			return this.classList.contains(class_name);
		},

		$addEvent: function (k, fn) {
			this.addEventListener(k, fn);
			return this;
		},

		$adopt: function () {
			for (var i = 0, l = arguments.length; i < l; i++) {
				this.appendChild(arguments[i]);
			}

			return this;
		},

		$inject: function (target, location) {
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
		},

		$dispose: function () {
			if (this.parentNode) {
				this.parentNode.removeChild(this);
			}

			return this;
		},

		$setAttr: function (attr, value) {
			this.setAttribute(attr, value);
			return this;
		},

		$remAttr: function (attr) {
			this.removeAttribute(attr);
			return this;
		},

		$text: function (str) {
			this.appendChild($T(str));
			return this;
		},

		$html: function (html) {
			this.innerHTML = html;
			return this;
		}
	});

	// -------------------------------------------------------------------------

	var SolicitudInformacionFormulario = {

		endpoint: 'https://staging.esanbackoffice.com/websites/products/information-request/',

		fields: {
			nombres: {
				required : true,
				message  : 'Debe ingresar su nombre',
				type     : 'text'
			},

			apellidos: {
				required : true,
				message  : 'Debe ingresar sus apellidos',
				type     : 'text'
			},

			documento_tipo: {
				required : true,
				message  : 'Debe seleccionar su tipo de documento',
				type     : 'text'
			},

			documento_nro: {
				required : true,
				message  : 'Debe ingresar su Nro Documento',
				type     : 'dni'
			},

			email: {
				required : true,
				message  : 'Debe ingresar su email',
				type     : 'email'
			},

			celular: {
				required : true,
				message  : 'Debe ingresar un número de celular válido',
				type     : 'celular'
			},

			pais_nacionalidad: {
				required : true,
				message  : 'Debe seleccionar su nacionalidad',
				type     : 'text'
			},

			ciudad_residencia: {
				required : true,
				message  : 'Debe seleccionar lugar de residencia',
				type     : 'text'
			},

			grado_academico: {
				required : true,
				message  : 'Debe seleccionar el grado académico',
				type     : 'text'
			},

			encuesta: {
				required : true,
				message  : 'Debe seleccionar una opción',
				type     : 'text'
			},

			terminos: {
				required : true,
				message  : 'Debe aceptar los términos',
				type     : 'text'
			},

			boletin: {
				required : false,
				message  : '',
				type     : 'text'
			},

			publicidad: {
				required : false,
				message  : '',
				type     : 'text'
			}
		},


		types: {
			text    : /\S+/,
			dni     : /^\d{8}$/,
			email   : /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/,
			celular : /^9\d{8}$/,
		},


		dom: {
			inputs: {}
		},


		validate_recaptcha: true,
		recaptcha_valid: true,


		initialize: function (form, modal) {
			this.dom.form = form;

			this.collectInputs(this.dom);
			this.setup(this.dom);
			this.setupTratamientoDatos(this.dom);
			this.setupMessageModal(this.dom);
			this.setupDownloadModalForm();

			if (modal) {
				this.setupModalForm(this.dom);
			} else {
				this.renderRecaptcha();
			}
		},


		collectInputs: function (dom) {
			var inputs = dom.form.querySelectorAll('input,select,textarea'), input, i, l;

			for (i = 0, l = inputs.length; i < l; i++) {
				input = inputs[i];

				if (input.name) {
					dom.inputs[input.name] = input;
				}
			}
		},


		setup: function (dom) {
			dom.form.addEventListener('submit', this.submit.bind(this));

			var k, input;

			for (k in dom.inputs) {
				input = dom.inputs[k];

				if (this.fields[k]) {
					input.addEventListener('blur', function (k) {
						this.validateInput(k, false);
					}.bind(this, k));

					if (['documento_nro', 'celular'].includes(this.fields[k].type)) {
						input.addEventListener('keypress', this.allowOnlyNumbers.bind(this));
					}
				}
			}

			dom.button = dom.form.querySelector('button[type="submit"]');
		},


		setupTratamientoDatos: function (dom) {
			dom.tratamiento = doc.querySelector('#lg-modal-tratamiento');

			dom.tratamiento.addEventListener('click', function () {
				dom.tratamiento.classList.remove('open');
			});

			dom.form.querySelector('.open-popup-terminos').addEventListener('click', function () {
				dom.tratamiento.classList.toggle('open');
			});

			doc.body.appendChild(dom.tratamiento);
		},


		setupDownloadModalForm: function () {
			$$('[data-form-solicitud-download-trigger]').forEach(function (el) {
				el.$addEvent('click', function (event) {
					event.preventDefault();
					event.stopPropagation();
					this.displayDownloadModal();
				}.bind(this));
			}, this);
		},


		setupModalForm: function (dom) {
			var refer = dom.form.parentNode;
			refer.parentNode.removeChild(refer);

			dom.modal = doc.createElement('div');
			dom.modal.setAttribute('class', 'solicitud-form-modal');

			dom.modal_wrapper = doc.createElement('div');
			dom.modal_wrapper.setAttribute('class', 'solicitud-form-modal-overlay');

			dom.modal_close = doc.createElement('div');
			dom.modal_close.setAttribute('class', 'solicitud-form-modal-close');

			// -----------

			var title = doc.createElement('h2');
			var subtitle = doc.createElement('h3');

			title.innerHTML = 'Solicitud de información';
			subtitle.innerHTML = 'Llene sus datos y nos comunicaremos con usted.';

			// -----------

			dom.modal_wrapper.appendChild(dom.modal);
			dom.modal.appendChild(dom.modal_close);
			dom.modal.appendChild(title);
			dom.modal.appendChild(subtitle);
			dom.modal.appendChild(dom.form);

			// -----------

			dom.modal_close.addEventListener('click', this.hideModalForm.bind(this));

			Array.prototype.slice.call(doc.querySelectorAll('.actions .btn,[data-form-si-trigger]')).forEach(function (el) {
				el.addEventListener('click', function (event) {
					event.preventDefault();
					this.showModalForm();
				}.bind(this));
			}, this);
		},


		showModalForm: function () {
			if (this.dom.modal) {
				doc.documentElement.classList.add('no-scroll');
				doc.body.appendChild(this.dom.modal_wrapper);
				this.renderRecaptcha();
			}
		},


		hideModalForm: function () {
			if (this.dom.modal && this.dom.modal_wrapper.parentNode) {
				doc.documentElement.classList.remove('no-scroll');
				doc.body.removeChild(this.dom.modal_wrapper);
			}
		},


		allowOnlyNumbers: function (event) {
			if (event.key && ! /^\d+$/.test(event.key)) {
				event.preventDefault();
			}
		},


		getInputValue: function (k) {
			var value, input = this.dom.inputs[k];

			if (input.type === 'checkbox') {
				value = input.checked ? '1' : '0';
			} else {
				value = input.value;
			}

			return value;
		},


		getCookieValue: function (k) {
			var pattern = '(^|;\\s*)' + k + '=([^;]+)';
			var matches = doc.cookie.match(new RegExp(pattern));
			return matches ? matches[2] : '';
		},


		renderRecaptcha: function () {
			if ('recaptcha' in this) {
				return;
			}

			var render_timer = win.setInterval(function () {
				if ('grecaptcha' in win && grecaptcha.render) {
					win.clearInterval(render_timer);

					this.recaptcha_valid = false;
					this.recaptcha = grecaptcha.render(this.dom.form.querySelector('.g-recaptcha'), {
						sitekey  : '6LfTkLkZAAAAAGuCLmgE_2Fdl-jX5h0Ok5xQb_Bw',
						callback : function (response) { this.recaptcha_valid = !!response; }.bind(this)
					});
				}
			}.bind(this), 500);
		},


		validateRecaptcha: function () {
			var valid = this.validate_recaptcha && !!this.recaptcha_valid;

			if (!valid) {
				win.alert('Debe resolver reCAPTCHA');
			}

			return valid;
		},


		validateForm: function () {
			for (var k in this.dom.inputs) {
				if (k in this.fields && !this.validateInput(k, true)) {
					return false;
				}
			}

			return true;
		},


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

			} else {
				console.error('Form Error: [' + k + '] ' + field.message);

				input.classList.remove('is-valid');
				input.classList.add('is-invalid');

				if (focus_on_error) {
					input.focus();
				}
			}

			return is_valid;
		},


		getFormData: function () {
			var data = {}, k;

			for (k in this.dom.inputs) {
				data[k] = this.getInputValue(k);
			}

			return data;
		},


		submit: function (event) {
			event.preventDefault();

			if (!this.validateForm() || !this.validateRecaptcha()) {
				return;
			}

			this.loading(true);

			this.send(this.buildRequestData());
			this.sendToLocal(this.buildRequestData(true));
		},


		onSubmit: function (http_status, response) {
			var is_success = false;

			if (http_status === 200) {
				response = JSON.parse(response);

				if (response && response.information_requested) {
					is_success = true;
				}
			}

			this.loading(false);

			this.displayMessage(is_success);
		},


		setupMessageModal: function (dom) {
			var msg         = doc.createElement('div');
			var msg_inner   = doc.createElement('div');
			var msg_content = doc.createElement('div');

			msg.setAttribute('class', 'lg-modal');
			msg_inner.setAttribute('class', 'lg-modal-inner');
			msg_content.setAttribute('class', 'lg-modal-content');

			msg.appendChild(msg_inner);
			msg_inner.appendChild(msg_content);

			msg.addEventListener('click', function () {
				this.classList.remove('open');
			});

			doc.body.appendChild(msg);

			dom.msg         = msg;
			dom.msg_content = msg_content;
			dom.msg_success = doc.body.querySelector('.msg-success');
			dom.msg_error   = doc.body.querySelector('.msg-error');

			dom.msg_success.parentNode.removeChild(dom.msg_success);
			dom.msg_error.parentNode.removeChild(dom.msg_error);

			dom.msg_success.classList.remove('force-hidden');
			dom.msg_error.classList.remove('force-hidden');
		},


		displayMessage: function (is_success) {
			var dom = this.dom;

			dom.msg_content.innerHTML = '';

			if (is_success) {
				dom.msg_content.appendChild(dom.msg_success);
			} else {
				dom.msg_content.appendChild(dom.msg_error);
			}

			dom.msg.classList.add('open');
		},


		loading: function (is_loading) {
			var button = this.dom.button;

			if (is_loading) {
				button.disabled = true;
				button.original_content = button.innerHTML;
				button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> &nbsp; Enviando...';

			} else {
				button.disabled = false;
				button.innerHTML = button.original_content;
			}
		},


		send: function (data) {
			var xhr =new XMLHttpRequest();

			xhr.open('POST', this.endpoint, true);

			xhr.setRequestHeader('Content-Type', 'application/json; charset=UTF-8');

			xhr.onreadystatechange = function () {
				if (xhr.readyState === 4)  {
					this.onSubmit(xhr.status, xhr.responseText);
				}
			}.bind(this);

			xhr.send(JSON.stringify(data));
		},


		sendToLocal: function (data) {
			var xhr =new XMLHttpRequest();
			xhr.open('POST', '/plugins/esan/esan_contact_form/process.php', true);
			xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
			xhr.setRequestHeader('Authorization', 'YgGDyQCMMTncBmhnVkHd');
			xhr.send(data);
		},


		buildRequestData: function (local) {
			var form_data = this.getFormData();
			var data;

			var is_mba_page = /^\/mba/.test(location.pathname);

			var selected  = localStorage.getItem(is_mba_page ? 'MBAProgramaSelector' : 'EsanCursosFiltros');
			var curso     = EsanCurso ? EsanCurso.nombre : 'Maestría';
			var ciudad    = null;
			var modalidad = null;

			if (selected && EsanCurso) {
				selected  = JSON.parse(selected);
				ciudad    = EsanCurso.ciudades.find(function (item) { return item.id === selected.ciudad; });
				modalidad = EsanCurso.modalidades.find(function (item) { return item.id === selected.modalidad; });

				ciudad    = ciudad && ciudad.nombre ? ciudad.nombre : '';
				modalidad = modalidad && modalidad.nombre ? modalidad.nombre : '';
			}

			if (!ciudad) {
				ciudad = EsanCurso.ciudades.length > 0 ? EsanCurso.ciudades[0].nombre : 'Lima';
			}

			if (!modalidad) {
				modalidad = EsanCurso.modalidades.length > 0 ? EsanCurso.modalidades[0].nombre : 'Tiempo completo';
			}

			if (local) {
				data = {
					nombre                   : '@nombres',
					apellido_paterno         : '@apellidos',
					apellido_materno         : '',
					email                    : '@email',
					telefono                 : '',
					celular                  : '@celular',
					documento_tipo           : '',
					documento_numero         : '@documento_nro',
					grado_academico          : '@grado_academico',
					acepto_tratamiento_datos : '@terminos',
					como_nos_conocio         : '@encuesta',
					empresa                  : '',
					cargo                    : '',
					es_exalumno              : '0',
					mensaje                  : '',
					ciudad                   : ciudad,
					modalidad                : modalidad,
					sede                     : '',
					curso                    : curso,
					curso_id                 : '',
					seccion_id               : '',
					convocatoria_codigo      : '',
					costo                    : '',
					moneda                   : '',
					formulario_tipo          : 'solicitud-informacion',
					formulario_origen        : ''
				};

			} else {
				data = {
					nombres                       : '@nombres',
					apellido_paterno              : '@apellidos',
					apellido_materno              : '',
					tipo_de_id                    : '',
					numero_de_id                  : '@documento_nro',
					user_agent_uuid               : this.getCookieValue('user_agent_uuid'),
					edad                          : '',
					academic_degree_code          : '@grado_academico',
					exalumno                      : '',
					correo_electrnico             : '@email',
					telefono                      : '@celular',
					acepta_politica_de_privacidad : '@terminos',
					empresa                       : '',
					job_industry_code             : '',
					cargo                         : '',
					job_function_code             : '',
					ciudad                        : ciudad,
					programa                      : curso,
					curso                         : curso,
					consulta                      : '',
					meses_para_llevarlo           : '',
					conferencia                   : '',
					datos_de_la_conferencia       : '',
					como_te_enteraste             : '@encuesta',
					url_del_formulario            : win.location.href,
					procedencia                   : this.getCookieValue('traffic_source'),

					pais_nacionalidad_iso3        : '@pais_nacionalidad',
					ciudad_de_residencia          : '@ciudad_residencia',
				};
			}

			var k, fk;

			for (k in data) {
				if (/^@(\w+)/.test(data[k])) {
					fk = data[k].substr(1);
					data[k] = form_data[fk] || '';
				}
			}

			if (local) {
				var params = [];

				for (k in data) {
					if (data[k] !== '') {
						params.push(k + '=' + encodeURIComponent(data[k]));
					}
				}

				return params.join('&');
			}

			return {
				timestamp : new Date().toJSON(),
				payload   : data
			};
		},

		// ----------------------------------

		displayDownloadModal: function () {
			var form = this.dom.form, placeholder, title, email, button;

			if (this.download) {
				placeholder = this.download.dom.placeholder;
				title       = this.download.dom.title;
				email       = this.download.dom.email;
				button      = this.download.dom.button;

			} else {
				placeholder = $E('span');
				title       = $E('div', {'class': 'form-row-download frw-desc'}).$html('Autorizo el envío del <strong>brochure</strong> en formato PDF a mi correo electrónico.');
				email       = form.$('input[name="email"]');
				button      = form.$('button[type="submit"]');

				this.download = {
					modal: new ESANModal(),
					dom: {
						placeholder : placeholder,
						title       : title,
						email       : email,
						button      : button
					}
				};

				this.download.modal.onClose = this.closeDownloadModal.bind(this);
			}

			placeholder.$inject(form, 'before');
			title.$inject(form, 'top');
			button.$setAttr('data-textContent', button.textContent).textContent = 'Enviar PDF';

			['email', 'button'].forEach(function (k) {
				var el = this.download.dom[k];

				while(el && !el.$hasClass('form-row')) {
					el = el.parentNode;
				}

				el.$addClass('form-row-download').$addClass('frw-' + k);
			}, this);

			this.fields.nombres.required           = false;
			this.fields.apellidos.required         = false;
			this.fields.documento_tipo.required    = false;
			this.fields.documento_nro.required     = false;
			this.fields.celular.required           = false;
			this.fields.pais_nacionalidad.required = false;
			this.fields.ciudad_residencia.required = false;
			this.fields.grado_academico.required   = false;
			this.fields.encuesta.required          = false;
			this.fields.terminos.required          = false;

			this.validate_recaptcha = false;

			this.download.modal.display(form.$addClass('download'));
		},


		closeDownloadModal: function () {
			var form        = this.dom.form;
			var placeholder = this.download.dom.placeholder;
			var title       = this.download.dom.title;
			var button      = this.download.dom.button;

			form.$removeClass('download').$inject(placeholder, 'before');
			placeholder.$dispose();
			title.$dispose();
			button.textContent = button.getAttribute('data-textContent');

			this.fields.nombres.required           = true;
			this.fields.apellidos.required         = true;
			this.fields.documento_tipo.required    = true;
			this.fields.documento_nro.required     = true;
			this.fields.celular.required           = true;
			this.fields.pais_nacionalidad.required = true;
			this.fields.ciudad_residencia.required = true;
			this.fields.grado_academico.required   = true;
			this.fields.encuesta.required          = true;
			this.fields.terminos.required          = true;

			this.validate_recaptcha = true;

			this.download.modal.close();
		}

	};

	// -----------------------------------------------------------------------------

	win.SolicitudInformacionFormulario = SolicitudInformacionFormulario;

	win.addEventListener('DOMContentLoaded', function () {
		var form     = doc.querySelector('form.form-solicitud-informacion');
		var is_modal =
			(new RegExp('^/(maestrias|diploma|diploma-internacional|pae|pade)/([^/]+)/([^/]+)')).test(win.location.pathname) ||
			(new RegExp('^/mba/([^/]+)')).test(win.location.pathname) ;

		if (form) {
			SolicitudInformacionFormulario.initialize(form, is_modal);
		}
	});

})(window, document);
