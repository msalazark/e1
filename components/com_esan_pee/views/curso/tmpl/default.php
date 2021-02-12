<?php

use function htmlspecialchars as e;

defined('_JEXEC') or die;

$curso    = $this->findDataItem('cursos', 'alias', $this->params['curso']);
$programa = $this->params['programa'] ? $this->findDataItem('curso_programas', 'id', $this->params['programa']) : null;
$eventos  = $this->data['eventos'];

$basepath = JUri::base(true); ?>

<div class="pee-page">
	<?=$this->printTemplate($curso['bloque_info'])?>
	<?=$this->printTemplate($curso['bloque_objetivo'])?>
	<?=$this->printTemplate($curso['bloque_temario'])?>
	<?=$this->printTemplate($curso['bloque_participantes'])?>
	<?=$this->printTemplate($curso['bloque_duracion'])?>
	<?=$this->printTemplate($curso['bloque_docentes'])?>
	<?=$this->printTemplate($curso['bloque_inversion'])?>
	<?=$this->printTemplate($curso['bloque_requerimientos'])?>

	<section id="experiencia" class="experiencia">
		<div class="container">
			<div class="row">
				<div class="col-12 d-flex flex-wrap justify-content-between">
					<div class="content">
					<?=$this->printTemplate($curso['bloque_videos'])?>
					</div>
					<div class="calendario">
						<?php if (count($eventos) > 0) { ?>
						<div class="intro">
							<h2>Calendario</h2>
						</div>
						<?php foreach ($eventos as $evento) { ?>
						<a class="evento-item d-flex flex-wrap justify-content-between" href="/eventos/<?=$evento['alias']?>">
							<div class="fecha d-flex flex-column justify-content-center">
								<span class="dia"><?=e($this->formatDate($evento['fecha'], 'D'))?></span>
								<span class="nro"><?=$this->formatDate($evento['fecha'], 'd')?></span>
							</div>
							<div class="evento-content">
								<span class="categoria">Conferencias</span>
								<h3><?=e($evento['nombre'])?></h3>
							</div>
							<i class="icon icon-next"></i>
						</a>
						<?php } ?>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</section>

	<div class="container">
		<div class="intro">
			<h2>Solicite más información</h2>
		</div>
	</div>
</div>

<script>var EsanCurso = <?=json_encode($this->getCursoData($curso['id']))?>;</script>
<script>var EsanCursoPrograma = <?=json_encode($programa)?>;</script>
<script>window.addEventListener('DOMContentLoaded', function () { document.querySelector('.bloque-solicitud-informacion').classList.remove('force-hidden'); });</script>
<script src="/administrator/templates/pee-registro-form/PEERegistroForm.js"></script>
<link rel="stylesheet" href="/administrator/templates/pee-registro-form/PEERegistroForm.css">
