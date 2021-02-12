<?php

defined('_JEXEC') or die;

use function htmlspecialchars as e;

$data = &$this->data;

$basepath = JUri::base(true); ?>

<style>
.pee-page {
	padding: 40px;
}
.pee-page a {
	color: #0080FF !important;
	text-decoration: underline !important;
}
.pee-page hr {
	margin: 2rem 0;
}
.pee-page h1 {
	margin: 2rem 0;
}
.pee-page ul {
	margin: 1rem 0;
}
.pee-page ul ul {
	margin: 0.5rem 0;
}
.pee-page ul li {
	margin-left: 1rem;
}
.pee-page ul li em {
	font-size: 0.75em;
	color: grey;
}
</style>

<div class="pee-page">
	<h1>PEE</h1>

	<hr>
	<h2>Especialidad Tipos</h2>

	<ul>
		<?php foreach ($data['especialidad_tipos'] as $et) { ?>
		<li>
			<a href="<?=$this->url('especialidad_tipo', ['especialidad_tipo' => $et['alias']])?>"><?=e($et['nombre'])?></a> <em>especialidad-tipo</em>
		</li>
		<?php } ?>
	</ul>

	<hr>
	<h2>Programas</h2>

	<ul>
		<?php foreach ($data['curso_tipos'] as $ct) { ?>
		<li>
			<?=e($ct['nombre'])?> <em>curso-tipo</em>
			<ul>
				<?php foreach ($this->filterDataItems('cursos', 'curso_tipo_id', $ct['id']) as $c) {
					$especialidad      = $this->findDataItem('especialidades', 'id', $c['especialidad_id']);
					$especialidad_tipo = $this->findDataItem('especialidad_tipos', 'id', $especialidad['especialidad_tipo_id']);
				?>
				<li>
					<a href="<?=$this->url('curso', ['especialidad_tipo' => $especialidad_tipo['alias'], 'curso' => $c['alias']])?>"><?=e($c['nombre'])?></a> <em>curso</em>
					<ul>
						<?php foreach ($this->filterDataItems('curso_programas', 'curso_id', $c['id']) as $cp) {
							$modalidad   = $this->findDataItem('modalidades', 'id', $cp['modalidad_id']);
							$ciudad      = $this->findDataItem('ciudades', 'id', $cp['ciudad_id']);
							$ciudad_sede = $this->findDataItem('ciudad_sedes', 'id', $cp['ciudad_sede_id']);
							$cp_nombre   = $cp['nombre'] ?: "{$ciudad['nombre']} - {$ciudad_sede['nombre']} | {$modalidad['nombre']} | {$cp['fecha_inicio']}";
						?>
						<li>
							<?=e($cp_nombre)?>
						</li>
						<?php } ?>
					</ul>
				</li>
				<?php } ?>
			</ul>
		</li>
		<?php } ?>
	</ul>
</div>
