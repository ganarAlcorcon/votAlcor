<?php
if (!(include_once dirname(__FILE__).'/../lib/comun.php')) {
	die ("Falta el archivo comun.php");
}
if (!(include_once dirname(__FILE__).'/../lib/comunCenso.php')) {
	die ("Falta el archivo comunCenso.php");
}

//TODO: Es una prueba
if ($POST["identificacion"]=="padron") {
	guardarImagenSubida("padron","prueba");
}

?>


<!DOCTYPE html>
<html lang="es">
	<head>
		<title>Nuevo simpatizante Ganar Alcorcón</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="../css/estilo.css">
		
		<script type="text/javascript" src="../js/ext/jQuery/jquery.min.js" ></script>
		<script type="text/javascript" src="../js/ext/bootstrap.min.js" ></script>
		<script type="text/javascript" src="../js/censoWeb.js" ></script>
	</head>
	<body class="container">
		<div class="container">
			<form action="" accept-charset="utf-8" method="post" enctype="multipart/form-data" >
				<div class="row">
					<div class="col-md-2">
						<label for="nombre">Nombre: </label>
					</div>
					<div class="col-md-10">
						<input id="nombre" name="nombre" type="text" maxlength="50" autocomplete="off" required="required" />
					</div>
				</div>
				<div class="row">
					<div class="col-md-2">
						<label for="apellido1">Primer apellido: </label>
					</div>
					<div class="col-md-10">
						<input id="apellido1" name="apellido1" type="text" maxlength="50" autocomplete="off" required="required" />
					</div>
				</div>
				<div class="row">
					<div class="col-md-2">
						<label for="apellido2">Segundo apellido: </label>
					</div>
					<div class="col-md-10">
						<input id="apellido2" name="apellido2" type="text" maxlength="50" autocomplete="off" />
					</div>
				</div>
				<div class="row">
					<div class="col-md-2">
						<label for="nif">NIF: </label>
					</div>
					<div class="col-md-4">
						<input id="nif" name="nif" type="text" maxlength="10" autocomplete="off" />
					</div>
					<div class="col-md-2">
						<label for="fechaNacimiento">Fecha de nacimiento: </label>
					</div>
					<div class="col-md-4">
						<input id="fechaNacimiento" name="fechaNacimiento" type="text" maxlength="10" autocomplete="off" required="required" />
					</div>
				</div>
				<div class="row">
					<div class="col-md-2">
						<label for="email">Email: </label>
					</div>
					<div class="col-md-10">
						<input id="email" name="email" type="email" maxlength="100" autocomplete="off" required="required" />
					</div>
				</div>
				<div class="row">
					<div class="col-md-2">
						<label for="telefono">Teléfono: </label>
					</div>
					<div class="col-md-10">
						<input id="telefono" name="telefono" type="text" maxlength="9" autocomplete="off" required="required" />
					</div>
				</div>
				<div class="row">
					<div class="col-md-2">
						<label for="identificacion">Tipo de identifiación</label>
					</div>
					<div class="col-md-10">
						<select id="identificacion" name="identificacion" onchange="cambiaId(this.value)">
							<option value="dni">DNI</option>
							<option value="nie">NIE</option>
							<option value="padron">Padrón</option>
						</select>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<p>Las imágenes deben enviarse en formatos jpg, png o gif. Puede ser un escaneado o una foto. Debe ser a color.</p>
						<p>Los datos deben coincidir exactamente con los indicados en los documentos, en otro caso el registro será rechazado</p>
					</div>
				</div>
				<div class="row">
					<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $CONFIG["MAX_IMG_SIZE"]?>" />
					<div id="id_nif" style="display:none">
						<label for="nif_anv">Anverso: </label> <input id="nif_anv" name="nif_anv" type="file" />
						<label for="nif_rev">Reverso: </label> <input id="nif_rev" name="nif_rev" type="file" />
					</div>
					<div id="id_padron"style="display:none">
						<label for="nif_anv">Padrón: </label> <input id="nif_anv" name="nif_anv" type="file" />
					</div>
				</div>
				
				<input type="submit" value="Enviar" />
			</form>
		</div>
	</body>
</html>