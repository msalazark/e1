(function (win, doc, EL) {
	var $ = function (s) { return doc.querySelector(s); };

	var $E = function (tag, attrs) {
		var el = doc.createElement(tag), k;

		if (attrs) {
			for (k in attrs) {
				el.setAttribute(k, attrs[k]);
			}
		}

		return el;
	};

	var $T = function (text) {
		return doc.createTextNode(text);
	};

	Object.assign(EL, {
		$  : function (s) { return this.querySelector(s); },
		$$ : function (s) { return Array.prototype.slice.call(this.querySelectorAll(s)); },

		$text: function (text) { this.$adopt($T(text)); return this; },
		$adopt: function () { for (var i in arguments) { this.appendChild(arguments[i]); } return this; },
		$replace: function (target) { if (target.parentNode) { target.parentNode.replaceChild(this, target); } return this; },
		$dispose: function () { if (this.parentNode) { this.parentNode.removeChild(this); } return this; },
		$empty: function () { while (this.firstChild) { this.removeChild(this.firstChild); } return this; },
		$remAttr: function (k) { this.removeAttribute(k); return this; },
		$addClass: function (c) { this.classList.add(c); return this; },
		$removeClass: function (c) { this.classList.remove(c); return this; }
	});


	// -------------------------------------------------------------------------


	var ESANSendinblueSuscribe = {

		dom: {
			inputs  : {},
			buttons : {}
		},

		fields: {
			area      : /.+/,
			email     : /^[\w\.\-]+@[\w\.\-]+\.[\w]+$/,
			politicas : /^1$/
		},


		initialize: function (form) {
			if (form) {
				this.setup(this.dom, form);
			}
		},


		setup: function (dom, form) {
			dom.form             = form;
			dom.inputs.area      = form.$('select[name="area"]');
			dom.inputs.email     = form.$('input[name="email"]');
			dom.inputs.politicas = form.$('input[name="politicas"]');
			dom.buttons.submit   = form.$('button[type="submit"]');

			form.addEventListener('submit', this.submit.bind(this));
		},


		validate: function () {
			for (var k in this.fields) {
				if (!this.validateInput(k)) {
					return false;
				}
			}

			return true;
		},


		validateInput: function (k) {
			var input  = this.dom.inputs[k];
			var value  = this.getInputValue(k);
			var regexp = this.fields[k];

			if (value === '' || !regexp.test(value)) {
				input.$addClass('invalid').focus();
				return false;
			}

			input.$removeClass('invalid');
			return true;
		},


		loading: function (show) {
			this.dom.form.classList[show ? 'add' : 'remove']('loading');
		},


		submit: function (event) {
			event.preventDefault();

			if (this.validate()) {
				this.loading(true);

				win.setTimeout(function () {
					this.send(this.prepareData(this.getData()));
				}.bind(this), 20000);
			}
		},


		send: function (data) {
			var xhr = new XMLHttpRequest();

			xhr.open(this.dom.form.method, this.dom.form.action);
			xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

			xhr.onreadystatechange = function (event) {
				if (xhr.readyState === 4) {
					this.loading(false);
					this.onSend(xhr.status, xhr.responseText);
				}
			}.bind(this);

			xhr.send(data);
		},


		onSend: function (status, response) {
			var success = false;

			if (status === 200) {
				response = JSON.parse(response);
				success = response.success ? true : false;
			}

			this[success ? 'displaySuccess' : 'displayError']();
		},


		getData: function () {
			var data = {}, k;

			for (k in this.dom.inputs) {
				data[k] = this.getInputValue(k);
			}

			return data;
		},


		getInputValue: function (k) {
			var input = this.dom.inputs[k];

			switch (input.getAttribute('type')) {
				case 'checkbox' : return input.checked ? '1' : '0';
				default         : return input.value.trim();
			}
		},


		prepareData: function (data) {
			var prepared = [
				'lists_25[]'          + '=["' + data.area + '"]',
				'EMAIL'               + '=' + data.email,
				'email_address_check' + '=',
				'locale'              + '=es'
			];

			return prepared.join('&');
		},


		displaySuccess: function () {
			this.dom.form.$empty().$remAttr('action').$addClass('success').$adopt(
				$E('p').$text('Gracias por suscribirse!')
			);
		},


		displayError: function () {
			this.dom.form.$empty().$remAttr('action').$addClass('success').$adopt(
				$E('p').$text('Surgió un error!')
			);
		}

	};

	win.ESANSendinblueSuscribe = ESANSendinblueSuscribe;

	win.addEventListener('DOMContentLoaded', function () {
		ESANSendinblueSuscribe.initialize($('form.genits-suscribe'));
	});

})(window, document, Element.prototype);
