(function (win, doc, EL) {

	var $$ = function (selector) {
		return Array.prototype.slice.call(doc.querySelectorAll(selector));
	};

	EL.$ = function (selector) {
		return this.querySelector(selector);
	};

	EL.$$ = function (selector) {
		return Array.prototype.slice.call(this.querySelectorAll(selector));
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


	var PEECursosInterna = {

		initialize: function (blocks) {
			this.build(blocks);
		},


		build: function (blocks) {
			blocks.forEach(function (block) {
				var cursos_container = block.$('.cursos-interna-list');

				block.$$('.cursos-interna-nav > li').forEach(function (l1, i) {
					var handler1 = l1.$('h3');

					handler1.addEventListener('click', function () {
						var open = block.$('li.open');

						if (open) {
							open.classList.remove('open');
						}

						if (!this.parentNode.classList.contains('open')) {
							l1.$('div > ul > li h4').click();
						}

						this.parentNode.classList.toggle('open');
					});

					l1.$$('div > ul > li').forEach(function (l2, j) {
						var handler2 = l2.$('h4');

						handler2._cursos = l2.$('ul').$dispose();

						handler2.addEventListener('click', function () {
							var active = block.$('li.active');

							if (active) {
								active.classList.remove('active');
							}

							l2.classList.add('active');

							cursos_container.$empty().$adopt(
								handler2.cloneNode(true),
								handler2._cursos
							);
						});

						if (i === 0 && j === 0) {
							handler2.click();
						}
					});

					if (i === 0) {
						handler1.parentNode.classList.add('open');
					}
				});
			});
		}
	};


	// -------------------------------------------------------------------------


	win.addEventListener('DOMContentLoaded', function () {
		var blocks = $$('.cursos-interna')

		if (blocks && blocks.length) {
			PEECursosInterna.initialize(blocks);
		}
	});

	win.PEECursosInterna = PEECursosInterna;

})(window, document, Element.prototype);
