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
		<link rel="stylesheet" type="text/css" href="../css/estilo.css">
		
		<script type="text/javascript" src="../js/ext/jQuery/jquery.min.js" ></script>
		<script type="text/javascript" src="../js/censoWeb.js" ></script>
	</head>
	<body>
		<?php //TODO: Bootstrap?>
		<form action="" accept-charset="utf-8" method="post" enctype="multipart/form-data" >
			<label for="nombre">Nombre: </label> <input id="nombre" name="nombre" type="text" size="50" maxlength="50" autocomplete="off" required="required" />
			<label for="apellido1">Primer apellido: </label> <input id="apellido1" name="apellido1" type="text" size="50" maxlength="50" autocomplete="off" required="required" />
			<label for="apellido2">Segundo apellido: </label> <input id="apellido2" name="apellido2" type="text" size="50" maxlength="50" autocomplete="off" required="required" />
			<label for="nif">NIF: </label> <input id="nif" name="nif" type="text" size="10" maxlength="10" autocomplete="off" required="required" />
			<label for="fechaNacimiento">Fecha de nacimiento: </label> <input id="fechaNacimiento" name="fechaNacimiento" type="text" size="50" maxlength="50" autocomplete="off" required="required" />
			<label for="email">Email: </label> <input id="email" name="email" type="email" size="100" maxlength="100" autocomplete="off" required="required" />
			<label for="telefono">Teléfono: </label> <input id="telefono" name="telefono" type="text" size="50" maxlength="50" autocomplete="off" required="required" />
			
			<select id="identificacion" name="identificacion" onchange="cambiaId(this.value)">
				<option value="dni">DNI</option>
				<option value="nie">NIE</option>
				<option value="padron">Padrón</option>
			</select>
			<p>Las imágenes deben enviarse en formatos jpg, png o gif. Puede ser un escaneado o una foto. Debe ser a color.</p>
			<p>Los datos deben coincidir exactamente con los indicados en los documentos, en otro caso el registro será rechazado</p>
			<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $CONFIG["MAX_IMG_SIZE"]?>" />
			<div id="id_nif" style="display:none">
				<label for="nif_anv">Anverso: </label> <input id="nif_anv" name="nif_anv" type="file" />
				<label for="nif_rev">Reverso: </label> <input id="nif_rev" name="nif_rev" type="file" />
			</div>
			<div id="id_padron"style="display:none">
				<label for="nif_anv">Padrón: </label> <input id="nif_anv" name="nif_anv" type="file" />
			</div>
			
			<input type="submit" value="Enviar" />
		</form>
	</body>
</html>