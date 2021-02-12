<?php

use function htmlspecialchars as e;

defined('_JEXEC') or die;

$grupos = &$this->groups;

$especialidad_tipo = $this->findDataItem('especialidad_tipos', 'alias', $this->params['especialidad_tipo']);

$basepath = JUri::base(true); ?>

<div class="pee-page">
	<?=$especialidad_tipo['bloque_top']?>

	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<div class="intro">
					<h2>Especialidades empresariales</h2>
					<p>El programa de especialización para ejecutivos (PEE) ofrece al público en general las siguientes especialidades:</p>
				</div>

				<ul class="nav nav-tabs" id="PeeCursosTab" role="tablist">
					<?php foreach ($grupos as $i => $g) { if (count($g['areas']) > 0) { ?>
					<li class="nav-item">
						<a class="nav-link <?=($i?'':'active')?>" id="grupo-<?=$g['id']?>-tab" data-toggle="tab" href="#grupo-<?=$g['id']?>" role="tab" aria-controls="grupo-<?=$g['id']?>" <?=($i?'':'aria-selected="true"')?>>
							<h2><?=e($g['name'])?></h2>
						</a>
					</li>
					<?php } } ?>
				</ul>


				<div class="tab-content" id="PeeCursosTabContent">
					<?php foreach ($grupos as $i => $g) { if (count($g['areas']) > 0) { ?>
					<div class="tab-pane fade <?=($i?'':'show active')?>" id="grupo-<?=$g['id']?>" role="tabpanel" aria-labelledby="grupo-<?=$g['id']?>-tab">
						<div class="cursos-interna d-flex flex-wrap justify-content-between">
							<ul class="cursos-interna-nav">
								<?php foreach ($g['areas'] as $a) { ?>
								<li>
									<h3>Área de <?=e($a['nombre'])?></h3>
									<div>
										<p><small>Certificado de especialización en:</small></p>
										<ul>
											<?php foreach ($a['especialidades'] as $e) { ?>
											<li>
												<h4><?=e($e['nombre'])?></h4>
												<ul>
													<?php foreach ($e['cursos'] as $c) { $programas = $this->filterDataItems('curso_programas', function ($item) use ($g, $c) { return (!$g['sede_id'] || $item['ciudad_sede_id'] === $g['sede_id']) && $item['curso_id'] === $c['id']; }); ?>
													<?php foreach ($programas as $p) {
														$modalidad = $this->findDataItem('modalidades', 'id', $p['modalidad_id']);
														$ciudad = $this->findDataItem('ciudades', 'id', $p['ciudad_id']);
														$sede = $this->findDataItem('ciudad_sedes', 'id', $p['ciudad_sede_id']);
														$p_nombre = $p['nombre'] ?: "{$modalidad['nombre']} / {$c['nombre']} - {$ciudad['nombre']} ({$sede['nombre']})"; ?>
													<li>
														<a href="<?=$this->url('curso', ['especialidad_tipo' => $e['tipo']['alias'], 'curso' => $c['alias'], 'programa' => $p['id']])?>"><?=e($p_nombre)?></a>
														<?php if ($p['nuevo'] == '1') { ?><span class="p-label nuevo">Nuevo</span><?php } ?>
														<?php if ($p['inicial'] == '1') { ?><span class="p-label inicial">Curso inicial de área</span><?php } ?>
													</li>
													<?php } ?>
													<?php } ?>
												</ul>
											</li>
											<?php } ?>
										</ul>
									</div>
								</li>
								<?php } ?>
							</ul>

							<div class="row content-list">
								<div class="col-lg-6">
									<div class="cursos-interna-list"></div>
								</div>
								<div class="col-lg-6">
									<div class="img-list">
										<img src="/images/pee-cursos.png" alt="">
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php } } ?>
				</div>

			</div>
		</div>
	</div>

	<?=$especialidad_tipo['bloque_ventajas']?>
	<?=$especialidad_tipo['bloque_certificado']?>
	<?=$especialidad_tipo['bloque_certificado_opciones']?>
	<?=$especialidad_tipo['bloque_curso_partes']?>
	<?=$especialidad_tipo['bloque_diplomado']?>

	<section id="experiencia" class="experiencia">
		<div class="container">
			<div class="row">
				<div class="col-12 d-flex flex-wrap justify-content-between">
					<div class="content">
						<?=$especialidad_tipo['bloque_videos']?>
					</div>
					<div class="calendario">
						<?php if (count($this->data['eventos']) > 0) { ?>
						<div class="intro">
							<h2>Calendario</h2>
						</div>
						<?php foreach ($this->data['eventos'] as $evento) { ?>
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

</div>