<?php
if (!(include_once dirname(__FILE__).'/../lib/comun.php')) {
	die ("Falta el archivo comun.php");
}
if (!(include_once dirname(__FILE__).'/../lib/comunCenso.php')) {
	die ("Falta el archivo comunCenso.php");
}

//Comprobamos si tiene la cookie
if (!isset($_COOKIE["_suef"])) {
	//Recuperamos el dominio
	$domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
	
	$idAleatorio=textoAleatorio($CONFIG["CO_TAMANO"],$CONFIG["CO_GRUPOS"],$CONFIG["CO_SEPARADOR"]);
	
	//Enviamos la cookie
	setcookie('_suef', $idAleatorio, time()+60*60*24*365, '/', $domain, false);
	
	//La guardamos por si fuera necesaria en esta conexión
	$_COOKIE["_suef"] = $idAleatorio;
}

?>


<!DOCTYPE html>
<html lang="es">
	<head>
		<title>Nuevo simpatizante Ganar Alcorcón</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link rel="stylesheet" type="text/css" href="../css/jQueryUI/jquery-ui.min.css">
		<link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="../css/bootstrap-theme.min.css">
		<link rel="stylesheet" type="text/css" href="../css/estilo.css">
		
		<script type="text/javascript" src="../js/ext/jQuery/jquery.min.js" ></script>
		<script type="text/javascript" src="../js/ext/jQuery/jquery-ui.min.js" ></script>
		<script type="text/javascript" src="../js/ext/jQuery/datepicker-es.js" ></script>
		<script type="text/javascript" src="../js/ext/bootstrap.min.js" ></script>
		<script type="text/javascript" src="../js/censoWeb.js" ></script>
		
		<script type="text/javascript">
		<?php 
		if (isset($_POST["identificacion"])) {
			if (!isset($_POST["declaracion"])) {
				echo "mensajeError='Debe aceptar la declaracion de valores';";
			} else {
				if (!isset($_POST["aceptacion"])) {
					echo "mensajeError='Debe aceptar la política de privacidad';";
				} else {
		
					// Leemos la fecha en el formato que se indica
					$fchaNac= leerFecha($_POST["fechaNacimiento"]);
				
					// Algunos navegadores envían la fecha con otro formato (al ser el input de tipo date)
					if (!$fchaNac) {
						$fchaNac= leerFechaBD($_POST["fechaNacimiento"]);
					}
					
					if (!$fchaNac) {
						echo "mensajeError='Fecha " . $_POST["fechaNacimiento"] . " incorrecta. El formato es dd/mm/aaaa';";
					} else {
						//Comprobamos el NIF
						$valCheck= check_nif_cif_nie($_POST["nif"]);
						
						if ($valCheck == 1 || $valCheck == 3) {
							//Damos de alta al simpatizante
							$resultado= altaSimpatizanteWeb ($_POST["nombre"], $_POST["apellido1"], $_POST["apellido2"], $_POST["nif"],
								$fchaNac, $_POST["email"], $_POST["telefono"], $_SERVER["REMOTE_ADDR"], $_SERVER["REMOTE_PORT"],
								$_COOKIE["_suef"]);
							
							if ($resultado["correcto"]) {
								echo "mensajeOk='" . $_POST["nombre"] . " ha sido dado de alta correctamente';";
							} else {
								echo "mensajeError=\"Ocurrió un error al dar de alta al simpatizante. " . str_replace(array("\n","\r")," ",$resultado["mensajeError"]) . " \";";
							}
						} else if ($valCheck == 2){
							echo "mensajeError=\"Ocurrió un error al dar de alta al simpatizante. Los CIF no son válidos\";";
						} else {
							echo "mensajeError=\"Ocurrió un error al dar de alta al simpatizante. El NIF introducido no es correcto\";";
						}					
					}
				}
			}
		}
		?>
		</script>
	</head>
	<body class="container" onload="inicio()">
		<div id="divError" class="alert alert-danger"></div>
		<div id="divOk" class="alert alert-success"></div>
		<form action="" accept-charset="utf-8" method="post" enctype="multipart/form-data" role="form" class="form-horizontal" >
			<div class="form-group">
				<label for="nombre" class="control-label col-sm-2">Nombre: </label>
				<div class="col-sm-10">
					<input id="nombre" name="nombre" type="text" class="form-control" class="form-control" size="50" maxlength="50" autocomplete="off" required="required" />
				</div>
			</div>
			<div class="form-group">
				<label for="apellido1" class="control-label col-sm-2">Primer apellido: </label>
				<div class="col-sm-10">
					<input id="apellido1" name="apellido1" type="text" class="form-control" size="50" maxlength="50" autocomplete="off" required="required" />
				</div>
			</div>
			<div class="form-group">
				<label for="apellido2" class="control-label col-sm-2">Segundo apellido: </label>
				<div class="col-sm-10">
					<input id="apellido2" name="apellido2" type="text" class="form-control" size="50" maxlength="50" autocomplete="off" />
				</div>
			</div>
			<div class="form-group">
				<label for="nif" class="control-label col-sm-2">NIF: </label>
				<div class="col-sm-10">
					<input id="nif" name="nif" type="text" class="form-control" size="10" maxlength="10" autocomplete="off" onchange="validar()" />
				</div>
			</div>
			<div class="form-group">
				<label for="fechaNacimiento" class="control-label col-sm-2">Fecha de nacimiento: </label>
				<div class="col-sm-10">
					<input id="fechaNacimiento" name="fechaNacimiento" type="text" class="form-control" size="50" maxlength="50" autocomplete="off" required="required" placeholder="Introduzca una fecha en formato dd/mm/aaaa" pattern=[0-9]{1,2}/([1-9]|1[0-2]|0[1-9])/[1,2][0-9]{3} />
				</div>
			</div>
			<div class="form-group">
				<label for="email" class="control-label col-sm-2">Email: </label>
				<div class="col-sm-10">
					<input id="email" name="email" type="email" class="form-control" size="100" maxlength="100" autocomplete="off" required="required" />
				</div>
			</div>
			<div class="form-group">
				<label for="telefono" class="control-label col-sm-2">Teléfono móvil: </label>
				<div class="col-sm-10">
					<input id="telefono" name="telefono" type="text" class="form-control" size="50" maxlength="50" autocomplete="off" required="required" placeholder="Teléfono móvil completo (9 cifras) sin espacios" pattern="[0-9]{9}" />
				</div>
			</div>
			<div class="form-group">
				<label for="identificacion" class="control-label col-sm-2">Tipo de identificación</label>
				<div class="col-sm-10">
					<select id="identificacion" name="identificacion" class="form-control" onchange="cambiaId(this.value)" required="required">
						<option value="">Seleccione un tipo</option>
						<option value="dni">DNI</option>
						<option value="nie">NIE</option>
						<option value="padron">Padrón</option>
					</select>
				</div>
			</div>
			<p>Las imágenes deben enviarse en formatos jpg, png o gif. Puede ser un escaneado o una foto siempre que sea <b>legible y a color</b>.<br/>
			Los documentos borrosos no serán admitidos. La manipulación de documentos oficiales será denunciada a las autoridades competentes.<br/>
			Los datos deben coincidir <b>exactamente</b> con los indicados en los documentos, en otro caso el registro será rechazado</p>
			<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $CONFIG["MAX_IMG_SIZE"]?>" />
			<div id="id_nif">
				<div class="form-group">
					<label for="nif_anv" class="control-label col-sm-2">Anverso: </label>
					<div class="col-sm-10">
						<input id="nif_anv" name="nif_anv" type="file" class="" />
					</div>
				</div>
				<div class="form-group">
					<label for="nif_rev" class="control-label col-sm-2">Reverso: </label>
					<div class="col-sm-10">
						<input id="nif_rev" name="nif_rev" type="file" class="" />
					</div>
				</div>
			</div>
			<div id="id_padron">
				<div class="form-group">
					<label for="padron" class="control-label col-sm-2">Padrón: </label>
					<div class="col-sm-10">
						<input id="padron" name="padron" type="file" class="" />
					</div>
				</div>
			</div>
			
			<div class="form-group">
				<div class="col-sm-2">
					<input id="declaracion" name="declaracion" type="checkbox" required="required" class="form-control" style="text-align:right" />
				</div>
				<div class="col-sm-10">
					<label style="margin-top:7px" for="declaracion">Declaro que conozco y comparto los valores de Ganar Alcorcón y de su <a href="http://ganaralcorcon.info/nueva/wp-content/uploads/2015/03/documento-etico.pdf" target="_blank" rel="help">documento ético</a>.</label>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-2">
					<input id="aceptacion" name="aceptacion" type="checkbox" required="required" class="form-control" style="text-align:right"/>
				</div>
				<div class="col-sm-10">
					<label style="margin-top:7px" for="aceptacion">Acepto ser incluido en el registro de SIMPATIZANTES de Ganar Alcorcón, así como la <a id="privacidad" href="privacidad.html" target="privacidad" rel="help">política de privacidad</a>.</label>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<input type="submit" value="Enviar" class="form-control" />
				</div>
			</div>
		</form>
	</body>
</html>