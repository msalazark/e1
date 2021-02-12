<?php
defined('_JEXEC') or die;

use function htmlspecialchars as e;

$params     = &$this->params;
$filters    = &$this->filters;
$resultados = &$this->results;
$destacados = &$this->destacados;

if (!$params['destacados'] || !$destacados || !count($destacados) === 0) {
	$destacados = null;
}

$basepath = JUri::base(true); ?>

<?php if ($destacados) { ?>
<div class="genits-slider">
	<div class="slider-items">
		<?php $i = 0; foreach ($destacados as $articulo) { ?>
		<div class="slider-item" style="background-image: url(<?="{$basepath}{$articulo['imagen_banner']}"?>)">
			<div class="container">
				<div>
					<p class="name"><?=e($articulo['nombre'])?></p>
					<p>Por: <?=e(implode(', ', array_column($articulo['usuarios'], 'nombre')))?></p>
					<a class="genit-btn" href="<?=$this->url('details', ['articulo' => $articulo['alias']])?>">Conocer más</a>
				</div>
			</div>
		</div>
		<?php } ?>
	</div>
	<div class="genits-slider-dots"></div>
</div>
<?php } ?>

<div class="genits articulos<?=$destacados?' has-slider':''?>">
	<div class="container">
		<form class="genits-suscribe" action="https://c5341791.sibforms.com/serve/MUIEAFP6eGn-CSMWpf-9LdtQTTnEcB3-MZPHH3I8Fv6mhmdRvlIwMfW4RewUCvtY96vP5E9I4CYyWHOC3pX9olSISbxuPGUAZNPHMblTFJLij2ZsS-ci9napZkvVRzC9oqqd5Xpk0iGvasuBzhbsO09jebNQUYOdL4Yh8CHlo5NsGKIPy_XQRp7Kkq9h4TTgRvVYb5t-YbgrrwWZ?isAjax=1" method="POST">
			<div class="suscribe-title">Suscríbete a nuestro newsletter</div>
			<select name="area">
				<?php foreach ($this->getBoletinAreas() as $k => $name) { ?>
				<option value="<?=$k?>"><?=e($name)?></option>
				<?php } ?>
			</select>
			<input name="email" type="text" placeholder="E-mail">
			<label><input name="politicas" type="checkbox"> <span>Acepto las <em>condiciones de tratamiento para mis datos personales</em>.</span></label>
			<button class="genit-btn outline" type="submit">Suscribirme</button>
		</form>

		<form id="buscar" class="genits-filter" action="<?=$this->url('list')?>#buscar" method="GET">
			<div class="filter-title">Buscar por:</div>
			<div class="fields">
				<?=$this->buildFilterField('especialidad', '- Especialidad -')?>
				<?=$this->buildFilterField('categoria', '- Sección -')?>
				<?=$this->buildFilterField('usuario', '- Autor -')?>
				<?=$this->buildFilterField('fecha', 'Buscar por fechas')?>
				<button class="genit-btn" type="submit">Buscar</button>
			</div>
		</form>

		<p class="genits-redes-nav">
			<span>Encuéntranos en:</span>
			<a href="https://www.facebook.com/conexionesan" target="_blank"><img src="/images/icon-circle-color-facebook.svg" alt="FACEBOOK"></a>
			<a href="https://www.linkedin.com/school/esan/" target="_blank"><img src="/images/icon-circle-color-linkedin.svg" alt="LINKEDIN"></a>
			<a href="https://twitter.com/esanperu" target="_blank"><img src="/images/icon-circle-color-twitter.svg" alt="TWITTER"></a>
			<a href="https://www.instagram.com/esanperu/" target="_blank"><img src="/images/icon-circle-color-instagram.svg" alt="INSTAGRAM"></a>
		</p>

		<h1 class="genits-title title-mark">Tendencias y negocios</h1>

		<div class="genits-items">
			<?php foreach ($resultados as $articulo) { ?>
			<div class="genits-item articulo">
				<div class="genit-img" style="background-image: url(<?="{$basepath}{$articulo['imagen_lista']}"?>)"></div>
				<div class="genit-info">
					<h2 class="genit-title">
						<a href="<?=$this->url('details', ['articulo' => $articulo['alias']])?>">
						<?=e($articulo['nombre'])?>
						</a>
					</h2>
					<div class="genit-fecha">
						<span><?=e($articulo['fecha_publicacion'])?></span>
					</div>
					<div class="genit-description">
						<?=$articulo['descripcion']?>
					</div>
					<ul class="genit-tags">
						<?php foreach ($articulo['categorias'] as $categoria) { $color = $categoria['color'] ?: '0,0,0'; ?>
						<li data-filter-categoria="<?=$categoria['alias']?>" style="background-color:rgba(<?=$color?>)"><?=e($categoria['nombre'])?></li>
						<?php } ?>
						<?php foreach ($articulo['especialidades'] as $especialidad) { ?>
						<li data-filter-especialidad="<?=$especialidad['alias']?>"><?=e($especialidad['nombre'])?></li>
						<?php } ?>
					</ul>
				</div>
			</div>
			<?php } ?>
		</div>

		<?=$this->buildPagination()?>
	</div>
</div>

<script>
window.addEventListener('DOMContentLoaded', function () {
	(function (form) {
		var fecha      = form.querySelector('input[name="fecha"]');
		var usuario    = form.querySelector('input[name="usuario"]');
		var usuario_dl = form.querySelector('#' + usuario.getAttribute('list'));
		var button     = form.querySelector('button[type="submit"]');

		var header     = document.querySelector('#sp-header');
		var resultados = document.querySelector('.genits-items');

		var getUsuario = function () {
			var option = usuario_dl.querySelector('option[value="' + usuario.value + '"]');
			usuario.value = option ? option.getAttribute('data-value') : '';
		};

		var setUsuario = function () {
			var option = usuario_dl.querySelector('option[data-value="' + usuario.value + '"]');
			usuario.value = option ? option.value : usuario.value;
		};

		var scrollIntoView = function (el, offset) {
			offset += -(header.offsetHeight);

			window.scrollTo({
				top      : el.getBoundingClientRect().top + window.pageYOffset + offset,
				behavior : 'smooth'
			});
		};

		// ---------------

		var datepicker = jQuery(fecha).datepicker({
			language: 'es',
			dateFormat: 'yyyy-mm',
			view: 'months',
			minView: 'months',
			clearButton: true,
			autoClose: true,
			minDate: new Date(fecha.getAttribute('min')),
			maxDate: new Date(fecha.getAttribute('max'))
		}).data('datepicker');

		if (fecha.value) {
			datepicker.selectDate(new Date(fecha.value + '-10'));
		}

		// ---------------

		Array.prototype.slice.apply(form.querySelectorAll('select')).forEach(function (select) {
			select.addEventListener('change', function () { button.click(); });
		});

		// ---------------

		form.addEventListener('submit', function (event) {
			getUsuario();

			var items = this.querySelectorAll('input,select'), i;

			for (i = 0; i < items.length; i++) {
				if (!items[i].value) {
					items[i].removeAttribute('name');
				}
			}

			scrollIntoView(button, -10);
			button.innerHTML = 'Buscando ...';
		});

		setUsuario();

		// ---------------

		if (window.location.hash === '#buscar' && resultados) {
			window.setTimeout(function () {
				scrollIntoView(resultados, -30);
			}, 1000);
		}
	})(document.querySelector('form.genits-filter'));


	if ('jQuery' in window && jQuery('.genits-slider .slider-item').length > 1) {
		jQuery('.genits-slider .slider-items').slick({
			arrows     : false,
			dots       : true,
			appendDots : '.genits-slider-dots',
			dotsClass  : 'container'
		});
	}


	// ----------------------
	// grid items clickables

	document.querySelectorAll('.genits-item').forEach(function (el) {
		var link = el.querySelector('.genit-title a');

		if (link) {
			el.style.cursor = 'pointer';
			el.addEventListener('click', function (event) {
				window.location = link.href;
			});
		}
	});


	// ----------------------
	// etiquetas filters

	var url_args = (function (qs) {
		var params = {}, tokens, re = /[?&]?([^=]+)=([^&]*)/g;

		qs = qs.split('+').join(' ');

		while (tokens = re.exec(qs)) {
			params[decodeURIComponent(tokens[1])] = decodeURIComponent(tokens[2]);
		}

		return params;
	})(location.search);

	var applyFilter = function (k, v) {
		var qs = [], j, url = location.href.replace(/\?.*/, '');

		url_args[k] = v;
		delete url_args.pagina;

		for (j in url_args) {
			qs.push(j+'='+url_args[j]);
		}

		url += '?' + qs.join('&') + '#buscar';

		if (url !== location.href) {
			window.location = url;
		}
	};

	['especialidad', 'categoria'].forEach(function (k) {
		var attr = 'data-filter-' + k;

		document.querySelectorAll('[' + attr + ']').forEach(function (el) {
			el.addEventListener('click', function (event) {
				event.preventDefault();
				event.stopPropagation();
				applyFilter(k, this.getAttribute(attr));
			});
		});
	});
});
</script>
