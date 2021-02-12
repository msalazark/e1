<?php
defined('_JEXEC') or die;

use function htmlspecialchars as e;

$filters    = &$this->filters;
$resultados = &$this->results['eventos'];
$destacados = &$this->destacados['eventos'];

$basepath = JUri::base(true);
?>
<div class="genits-slider">
	<div class="slider-items">
		<?php $i = 0; foreach ($destacados as $evento) { ?>
		<div class="slider-item" style="background-image: url(<?="{$basepath}{$evento['imagen_banner']}"?>)">
			<div class="container">
				<div>
					<p class="evento-tipo"><span><?=e($this->find('tipos', $evento['tipo_id'], 'destacados')['nombre'])?></span></p>
					<p class="name"><?=e($evento['nombre'])?></p>
					<p>Fecha: <?=e($evento['fecha_texto'])?></p>
					<p>Costo: <?=e($this->renderCosto($evento))?></p>
					<a class="genit-btn" href="<?=$evento['url'] ?: $this->url('details', ['evento' => $evento['alias']])?>">Conocer más</a>
				</div>
			</div>
		</div>
		<?php } ?>
	</div>
	<div class="genits-slider-dots"></div>
</div>

<div class="genits eventos <?=$destacados?'has-slider':''?>">
	<div class="container">
		<form class="genits-suscribe" action="https://c5341791.sibforms.com/serve/MUIEAFP6eGn-CSMWpf-9LdtQTTnEcB3-MZPHH3I8Fv6mhmdRvlIwMfW4RewUCvtY96vP5E9I4CYyWHOC3pX9olSISbxuPGUAZNPHMblTFJLij2ZsS-ci9napZkvVRzC9oqqd5Xpk0iGvasuBzhbsO09jebNQUYOdL4Yh8CHlo5NsGKIPy_XQRp7Kkq9h4TTgRvVYb5t-YbgrrwWZ?isAjax=1" method="POST">
			<div class="suscribe-title">Suscríbete a nuestro newsletter</div>
			<select name="area">
				<option value="">Área de interés</option>
				<option value="2">Área de prueba</option>
			</select>
			<input name="email" type="text" placeholder="E-mail">
			<label><input name="politicas" type="checkbox"> <span>Acepto las <em>condiciones de tratamiento para mis datos personales</em>.</span></label>
			<button class="genit-btn outline" type="submit">Suscribirme</button>
		</form>

		<form class="genits-filter" action="<?=$this->url('list')?>" method="GET">
			<div class="filter-title">Buscar por:</div>
			<div class="fields">
				<?=$this->buildFilterField('tipo', 'Tipos de eventos')?>
				<?=$this->buildFilterField('area', 'Área de interés')?>
				<?=$this->buildFilterField('fecha', 'Buscar por fechas')?>
				<?=$this->buildFilterField('ciudad', 'Buscar por ciudad')?>
				<button class="genit-btn" type="submit">Buscar</button>
			</div>
		</form>

		<h1 class="genits-title title-mark">Eventos más próximos</h1>

		<div class="genits-items">
			<?php foreach ($resultados as $evento) { ?>

			<div class="genits-item evento">
				<div class="genit-img" style="background-image: url(<?="{$basepath}{$evento['imagen_lista']}"?>)"></div>
				<div class="genit-info">
					<div class="evento-tipo">
						<span><?=e($this->find('tipos', $evento['tipo_id'])['nombre'])?></span>
					</div>
					<h2 class="genit-title">
						<a href="<?=$evento['url'] ?: $this->url('details', ['evento' => $evento['alias']])?>">
							<?=e($evento['nombre'])?>
						</a>
					</h2>
					<div class="evento-icon evento-fecha">
						<span><?=e($evento['fecha_texto'])?></span>
					</div>
					<div class="genit-description">
						<?=$evento['descripcion']?>
					</div>
					<div class="evento-icon evento-lugar">
						<span><?=e($evento['lugar'])?> - <?=e($this->find('ciudades', $evento['ciudad_id'])['nombre'])?></span>
					</div>
					<div class="evento-icon evento-hora">
						<span><?=e($evento['hora'])?></span>
					</div>
					<div class="evento-icon evento-costo">
						<span><?=e($this->renderCosto($evento))?></span>
					</div>
				</div>
			</div>

			<?php } ?>
		</div>

		<?=$this->buildPagination()?>
	</div>
</div>

<script>
(function (form) {
	form.addEventListener('submit', function () {
		var items = this.querySelectorAll('select'), i;
		for (i = 0; i < items.length; i++) {
			if (!items[i].value) {
				items[i].removeAttribute('name');
			}
		}
	});
})(document.querySelector('form.genits-filter'));

document.querySelectorAll('.genits-item').forEach(function (el) {
	var link = el.querySelector('.genit-title a');

	if (link) {
		el.style.cursor = 'pointer';
		el.addEventListener('click', function (event) {
			window.location = link.href;
		});
	}
});

jQuery(document).ready(function () {
	jQuery('.genits-slider .slider-items').slick({
		arrows: false,
		dots: true,
		appendDots: '.genits-slider-dots',
		dotsClass: 'container'
	});
});
</script>
