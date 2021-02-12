<?php

defined('_JEXEC') or die;

use function htmlspecialchars as e;

$articulo = &$this->data['articulo'];

$usuarios = &$this->data['usuarios'];

$basepath = JUri::base(true);
?>

<div class="blog-articulo">
	<div class="page-top2">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<p id="responsive"><img src="/images/evento/conferencia.png"></p>
					<h1 class="page-title"><?=e($articulo['nombre'])?></h1>
				</div>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-lg-6">
				<h1 id="title"><?=e($articulo['nombre'])?></h1>
				<div class="descripcion"><?=$articulo['descripcion']?></div>
				<p class="author">Por: <strong><?php foreach ($usuarios as $i => $usuario) { ?><?=($i>0)?($i===count($usuarios)-1?' y ':', '):''?><?=e("{$usuario['nombres']} {$usuario['apellidos']}")?><?php } ?></strong> el <?=e($articulo['fecha_publicacion'])?></p>
				<p class="genits-redes-nav">
					<span>Compartir en:</span>
					<a href="<?=$this->shareURL('facebook')?>"><img src="/images/icon-circle-color-facebook.svg" alt="FACEBOOK"></a>
					<a href="<?=$this->shareURL('linkedin')?>"><img src="/images/icon-circle-color-linkedin.svg" alt="LINKEDIN"></a>
					<a href="<?=$this->shareURL('twitter')?>"><img src="/images/icon-circle-color-twitter.svg" alt="TWITTER"></a>
					<a href="<?=$this->shareURL('whatsapp')?>"><img src="/images/icon-circle-color-whatsapp.svg" alt="WHATSAPP"></a>
				</p>
			</div>
			<div class="col-lg-6">
				<figure id="imagen1">
					<img src="<?=$basepath?><?=$articulo['imagen1']?>">
					<?php if ($articulo['imagen_descripcion']) { ?>
					<figcaption><?=e($articulo['imagen_descripcion'])?></figcaption>
					<?php } ?>
				</figure>
			</div>
		</div>
	</div>

	<div class="segundo-contenido">
		<div class="container">
			<div class="row">
				<div class="col-lg-8">
					<div class="genits-html-content"><?=$articulo['contenido']?></div>
				</div>
				<div class="col-lg-4">
					<?php if (trim(strval($articulo['texto_destacado'])) !== '') { ?>
					<div class="cuadro1"><?=$articulo['texto_destacado']?></div>
					<?php } ?>

					<?php foreach ($usuarios as $usuario) { $foto = $usuario['foto_avatar'] ?: '/images/usuarios/placeholder.png'; ?>
					<div class="cuadro2">
						<img src="<?="{$basepath}{$foto}"?>" alt="<?=e($usuario['nombres'] . ' ' . $usuario['apellidos'])?>" style="float: right;">
						<p id="profe-title"><strong><?=e($usuario['nombres'] . ' ' . $usuario['apellidos'])?></strong></p>
						<p id="parrafo"><?=($usuario['resena_personalizada'] ?: $usuario['resena'])?></p>
						<?php if (count($usuario['cursos_docente']) > 0 || count($usuario['cursos_alumno']) > 0) { ?>
						<div class="cuandro-interno">
							<ul>
								<?php foreach ($usuario['cursos_docente'] as $curso) { ?>
								<li>Profesor en <a href="/<?=$curso['tipo_alias']?>/<?=$curso['alias']?>"><?=e($curso['nombre'])?></a></li>
								<?php } ?>
								<?php foreach ($usuario['cursos_alumno'] as $curso) { ?>
								<li><?=($usuario['usuario_tipo_id'] == '2' ? 'Alumno' : 'Graduado')?> en <a href="/<?=$curso['tipo_alias']?>/<?=$curso['alias']?>"><?=e($curso['nombre'])?></a></li>
								<?php } ?>
							</ul>
						</div>
						<?php } ?>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>

	<?php if (array_sum(array_map(function ($a) { return count($a); }, array_column($usuarios, 'articulos'))) > 0) { ?>
	<div class="container">
		<h2 class="genits-subtitle title-mark">Otros artículos del autor</h2>
		<div class="genits-items">
			<?php $i = 0; $otros_articulos_ids = []; foreach ($usuarios as $usuario) { foreach ($usuario['articulos'] as $item) { if (++$i > 3) { break; } if (in_array($item['id'], $otros_articulos_ids)) { continue; } $otros_articulos_ids[] = $item['id']; ?>
			<div class="genits-item articulo">
				<div class="genit-img" style="background-image: url(<?="{$basepath}{$item['imagen_lista']}"?>)"></div>
				<div class="genit-info">
					<h2 class="genit-title"><a href="<?=$this->url('details', ['articulo' => $item['alias']])?>"><?=e($item['nombre'])?></a></h2>
					<div class="genit-fecha"><span><?=e($item['fecha_publicacion'])?></span></div>
					<div class="genit-description"> <?=$item['descripcion']?> </div>
					<ul class="genit-tags">
						<?php foreach ($item['categorias'] as $categoria) { $color = $categoria['color'] ?: '0,0,0'; ?>
						<li data-filter-categoria="<?=$categoria['alias']?>" style="background-color:rgba(<?=$color?>)"><?=e($categoria['nombre'])?></li>
						<?php } ?>
						<?php foreach ($item['especialidades'] as $especialidad) { ?>
						<li data-filter-especialidad="<?=$especialidad['alias']?>"><?=e($especialidad['nombre'])?></li>
						<?php } ?>
					</ul>
				</div>
			</div>
			<?php } } ?>
		</div>
	</div>
	<?php } ?>
</div>

<script>
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

var applyFilter = function (k, v) {
	window.location = '<?=$this->url('list')?>?' + k + '=' + v + '#buscar';
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

</script>
