var EsanEventoRegistro = {

	endpoint: '/plugins/esan/esan_contact_form/process.php',

	fields: {
		nombres: {
			required: true,
			message : 'Debe ingresar su nombre',
			type    : 'text'
		},

		email: {
			required: true,
			message : 'Debe ingresar su email',
			type    : 'email'
		},

		documento_tipo: {
			required: true,
			message : 'Debe seleccionar su tipo de documento',
			type    : 'text'
		},

		documento_numero: {
			required: true,
			message : 'Debe ingresar su número de documento',
			type    : 'dni'
		},

		celular: {
			required: true,
			message : 'Debe ingresar un número de celular válido',
			type    : 'celular'
		},

		empresa: {
			required: true,
			message : 'Debe ingresar nombre de su empresa',
			type    : 'text'
		},

		cargo: {
			required: true,
			message : 'Debe ingresar el cargo que ocupa',
			type    : 'text'
		},

		tratamiento_datos: {
			required: true,
			message : 'Debe aceptar las condiciones de tratamiento de datos personales',
			type    : 'text'
		},

		extra: {
			required: false,
			message : '',
			type    : 'text'
		}
	},


	types: {
		text     : /\S+/,
		dni      : /^\d{8}$/,
		ce       : /^[a-z0-9]{1,12}$/i,
		pasaporte: /^[a-z0-9]{1,12}$/i,
		email    : /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/,
		celular  : /^9\d{8}$/,
	},


	dom: {
		inputs: {},
		msg: {}
	},


	initialize: function (form) {
		if (form) {
			this.dom.form = form;

			this.collectInputs(this.dom);
			this.setup(this.dom);
		}
	},


	collectInputs: function (dom) {
		var inputs = dom.form.querySelectorAll('input,select'), input, i, l;

		for (i = 0, l = inputs.length; i < l; i++) {
			input = inputs[i];

			if (input.name) {
				dom.inputs[input.name] = input;
			}
		}
	},


	setup: function (dom) {
		var k, input, inputs = dom.inputs;

		for (k in inputs) {
			input = inputs[k];

			if (this.fields[k]) {
				input.addEventListener('blur', function (k) {
					this.validateInput(k, false);
				}.bind(this, k));
			}
		}

		inputs.documento_tipo.addEventListener('change', function () {
			this.fields.documento_numero.type = inputs.documento_tipo.value.toLowerCase();
		}.bind(this));

		inputs.celular.addEventListener('keypress', this.allowOnlyNumbers.bind(this));

		dom.button = dom.form.querySelector('button[type="submit"]');

		dom.msg.success = document.querySelector('.evento-suscribe-msg.success');
		dom.msg.success.parentNode.removeChild(dom.msg.success);
		dom.msg.success.removeAttribute('style');

		dom.msg.error = document.querySelector('.evento-suscribe-msg.error');
		dom.msg.error.parentNode.removeChild(dom.msg.error);
		dom.msg.error.removeAttribute('style');

		dom.form.addEventListener('submit', this.submit.bind(this));
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
			console.error('EsanEventoRegistro: [' + k + '] ' + field.message);

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

		if (!this.validateForm()) {
			return;
		}

		this.loading(true);

		this.send(this.buildRequestData());
	},


	send: function (data) {
		var xhr = new XMLHttpRequest();

		xhr.open('POST', this.endpoint, true);
		xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
		xhr.setRequestHeader('Authorization', 'YgGDyQCMMTncBmhnVkHd');

		xhr.onreadystatechange = function () {
			if (xhr.readyState === 4)  {
				this.onSend(xhr.status, xhr.responseText);
			}
		}.bind(this);

		xhr.send(data);
	},


	onSend: function (http_status, response) {
		var is_success = false;

		if (http_status === 200) {
			response = JSON.parse(response);

			if (response && response.success) {
				is_success = true;
			}
		}

		this.loading(false);

		this.displayMessage(is_success);
	},


	displayMessage: function (is_success) {
		this.dom.form.$empty().$adopt(
			is_success ?
			this.dom.msg.success :
			this.dom.msg.error
		);
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


	buildRequestData: function () {
		var form_data = this.getFormData();

		var data = {
			nombre                   : '@nombres',
			apellido_paterno         : '',
			apellido_materno         : '',
			email                    : '@email',
			telefono                 : '',
			celular                  : '@celular',
			documento_tipo           : '@documento_tipo',
			documento_numero         : '@documento_numero',
			grado_academico          : '',
			acepto_tratamiento_datos : '@tratamiento_datos',
			como_nos_conocio         : '',
			empresa                  : '@empresa',
			cargo                    : '@cargo',
			es_exalumno              : '0',
			mensaje                  : '',
			extra                    : '@extra',
			ciudad                   : '',
			modalidad                : '',
			sede                     : '',
			curso                    : '',
			curso_id                 : '',
			seccion_id               : '',
			convocatoria_codigo      : '',
			costo                    : '',
			moneda                   : '',
			formulario_tipo          : 'evento-registro',
			formulario_origen        : 'evento'
		};

		var params = [], k, fk;

		for (k in data) {
			if (/^@(\w+)/.test(data[k])) {
				fk = data[k].substr(1);
				data[k] = form_data[fk] || '';
			}
		}

		for (k in data) {
			if (data[k] !== '') {
				params.push(k + '=' + encodeURIComponent(data[k]));
			}
		}

		return params.join('&');
	}

};


window.addEventListener('DOMContentLoaded', function () {
	EsanEventoRegistro.initialize(document.querySelector('form.evento-form'));
});
