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

		$dispose: function () {
			if (this.parentNode) {
				this.parentNode.removeChild(this);
			}

			return this;
		},

		$remAttr: function (attr) {
			this.removeAttribute(attr);
			return this;
		},

		$text: function (str) {
			this.appendChild($T(str));
			return this;
		}
	});


	// -------------------------------------------------------------------------

	var ESANModal = function (target) {
		this.dom = {};

		this.build(this.dom, target);
	};


	Object.assign(ESANModal.prototype, {

		build: function (dom, target) {
			dom.overlay = $E('div', {'class': 'esan-modal-overlay'});
			dom.modal   = $E('div', {'class': 'esan-modal'});
			dom.close   = $E('div', {'class': 'esan-modal-close'});
			dom.content = $E('div', {'class': 'esan-modal-content'});

			dom.overlay.$adopt(
				dom.modal.$adopt(
					dom.close.$text('×'),
					dom.content
				)
			);

			this.setContent(target);

			dom.overlay.$addEvent('click', this.close.bind(this));
			dom.close.$addEvent('click', this.close.bind(this));
		},


		setContent: function (content) {
			if (content) {
				this.dom.content.$adopt(
					content.$dispose().$remAttr('data-esan-modal')
				);
			}
		},


		display: function (content) {
			this.setContent(content);
			doc.body.$addClass('no-scroll').$adopt(this.dom.overlay);
		},


		close: function (event) {
			if (event) {
				if (!event.target.$hasClass('esan-modal-overlay') &&
				    !event.target.$hasClass('esan-modal-close')) {
					return;
				}

				event.preventDefault();
				event.stopPropagation();
			}

			doc.body.$removeClass('no-scroll');
			this.dom.overlay.$dispose();

			if (this.onClose) {
				console.log(this.onClose);
				console.log('ON CLOSE!!!!');
				this.onClose();
			}
		}

	});


	// -------------------------------------------------------------------------


	ESANModal.instances = {};


	ESANModal.modal = function (event) {
		event.preventDefault();

		var modal_id = this.getAttribute('data-esan-modal-target');
		var target   = $('[data-esan-modal="' + modal_id + '"]');

		if (!(modal_id in ESANModal.instances)) {
			ESANModal.instances[modal_id] = new ESANModal(target);
		}

		ESANModal.instances[modal_id].display();
	};


	ESANModal.scan = function () {
		$$('[data-esan-modal-target]').forEach(function (el) {
			el.$addEvent('click', ESANModal.modal);
		});
	};


	// -------------------------------------------------------------------------

	win.addEventListener('DOMContentLoaded', ESANModal.scan)

	win.ESANModal = ESANModal;

})(window, document);
