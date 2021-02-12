<?php

defined('_JEXEC') or die;

use function htmlspecialchars as e;

$evento   = &$this->data['evento'];
$tipo     = &$this->data['tipo'];
$ponentes = &$this->data['ponentes'];

$basepath = JUri::base(true);
?>

<div class="evento">
	<div class="container">
		<div class="row">
			<div class="col-12 top-evento">
				<h1><?=e($evento['nombre'])?></h1>
				<p style="color: #000"><?=e($tipo['nombre'])?></p>
			</div>
			<div class="col-lg-6" id="descripcion-event">
				<h1><?=e($evento['nombre'])?></h1>
				<p style="color: #000" id="seminario"><?=e($tipo['nombre'])?></p>
				<div style="font-size: 16px;"><?=e($evento['descripcion'])?></div>

				<?php if (count($ponentes) > 0) { ?>
				<p id="author-event">Por: <?php foreach ($ponentes as $i => $ponente) { ?><?=$i?',':''?>
					<strong style="text-decoration: underline;"><?="{$ponente['nombres']} {$ponente['apellidos']}"?></strong>
				<?php } ?></p>
				<?php } ?>

			</div>
			<div class="col-lg-6">
				<p id="img-evento"><img src="<?="{$basepath}{$evento['imagen1']}"?>"></p>
			</div>
		</div>
	</div>

	<div class="container">
		<div class="row">
			<div class="col-lg-1"></div>
			<div class="col-lg-10">
				<div class="borde-evento">
					<div class="row">
						<div class="col">
							<p id="location"><?=e($evento['lugar'])?></p>
						</div>
						<div class="col">
							<p id="data"><?=e($evento['fecha_texto'])?></p>
						</div>
						<div class="col">
							<p id="hours"><?=e($evento['hora'])?></p>
						</div>
						<div class="col">
							<p id="costo"><?=e($evento['costo'])?></p>
						</div>
						<div class="col">
							<a class="genit-btn outline" id="boton-even" href="#form-suscripcion">Inscribirme</a>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-8" style="margin-top: 40px;">
							<a href="#form-suscripcion" class="link-next" style="font-size: 14px"><span>Si deseas más información, déjanos tus datos y te llamaremos</span><i class="icon icon-next"></i></a>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-1"></div>
		</div>
	</div>

	<?php if (trim($evento['contenido'] ?: '') !== '') { ?>
	<div class="container" id="tercer-bloque">
		<div class="row">
			<div class="col-lg-6 order">
				<p id="img-evento2"><img src="<?="{$basepath}{$evento['imagen2']}"?>"></p>
			</div>
			<div class="col-lg-6">
				<div id="content-event">
					<div class="intro">
						<h2>Reseña</h2>
					</div>

					<?=$evento['contenido']?>
				</div>
			</div>
		</div>
	</div>
	<?php } ?>

	<?php if (count($ponentes) > 0) { ?>
	<div class="container" id="docente">
		<div class="row">
			<div class="col-lg-12">
				<div class="intro">
					<h2>Expositor</h2>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="borde-docente-eve">
				<div class="col-lg-12" style="padding: 0px;">
					<?php foreach ($ponentes as $i => $ponente) { $foto = $ponente['foto'] ?: '/images/usuarios/placeholder.png'; ?><?=$i?',':''?>
					<div class="img-docent-eve">
						<img src="<?="{$basepath}{$foto}"?>">
					</div>
					<div class="conten-doce">
						<h3><?=e("{$ponente['nombres']} {$ponente['apellidos']}")?></h3>
						<p><?=$ponente['resena']?></p>
					</div>
				<?php } ?>
				</div>
			</div>
		</div>
	</div>
	<?php } ?>

	<div class="container" id="form-suscripcion">
		<div class="evento-suscribe">
			<form class="evento-form" action="#" method="POST">
				<h2 class="genits-title title-mark">Inscríbete</h2>
				<p>El ingreso es libre previa inscripción. Cupos limitados.</p>
				<p>Regístrate en el evento mediante el siguiente formulario:</p>

				<div class="effields">
					<div class="efentry efentry-box">
						<input name="nombres" type="text" placeholder="Nombres y apellidos">
					</div>

					<div class="efentry efentry-box">
						<input name="email" type="text" placeholder="Correo electrónico">
					</div>

					<div class="efentry efentry-box efentry-2-col">
						<select name="documento_tipo">
							<option value="DNI">DNI</option>
							<option value="CE">CE</option>
							<option value="PASAPORTE">PASAPORTE</option>
						</select>
						<input name="documento_numero" type="text" maxlength="12" placeholder="Número de documento">
					</div>

					<div class="efentry efentry-box">
						<input name="celular" type="tel" maxlength="9" placeholder="Número de celular">
					</div>

					<div class="efentry efentry-box">
						<input name="empresa" type="text" placeholder="Empresa">
					</div>

					<div class="efentry efentry-box">
						<input name="cargo" type="text" placeholder="Cargo">
					</div>

					<div class="efentry efentry-checkbox">
						<label>
							<input name="tratamiento_datos" type="checkbox"> Acepto las <em>condiciones de tratamiento para mis datos personales</em>.
						</label>
					</div>
				</div>

				<input type="hidden" name="extra" value="Costo: <?=e($evento['costo'])?> <?="\n"?>Pronto pago: <?=e($evento['pronto_pago_costo'])?> - <?=e($evento['pronto_pago_fecha'])?>">

				<div class="efentry efentry-button">
					<button type="submit" class="genit-btn">Inscribirme</button>
				</div>
			</form>

			<div class="evento-suscribe-msg success" style="display: none;">
				<p>Gracias.</p>
				<?php if ($evento['url_pago']) { ?>
				<p><?=e($evento['texto_pago'] ?: 'Pague en el siguiente enlace:')?></p>
				<p><a href="<?=$evento['url_pago']?>" class="genit-btn">Pagar</a></p>
				<?php } ?>
			</div>
			<div class="evento-suscribe-msg error" style="display: none;">
				<p>Surgió un error.</p>
			</div>

			<div class="evento-contact">
				<?php if (isset($evento['contacto_info']) && $evento['contacto_info'] && trim($evento['contacto_info']) !== '') { ?>
				<div class="evento-contact-inner">
					<div><?=$evento['contacto_info']?></div>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
