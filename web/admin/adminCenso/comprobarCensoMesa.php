<?php
if (!(include_once dirname(__FILE__).'/../comunAdmin.php')) {
	die ("Falta el archivo comunAdmin.php");
}
if (!(include_once dirname(__FILE__).'/../../lib/comunVotacion.php')) {
	die ("Falta el archivo comunVotacion.php");
}
?>


<!DOCTYPE html>
<html lang="es">
	<head>
		<title>Administración | Comprobación censo en mesa</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link rel="stylesheet" type="text/css" href="../../css/jQueryUI/jquery-ui.min.css">
		<link rel="stylesheet" type="text/css" href="../../css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="../../css/bootstrap-theme.min.css">
		<link rel="stylesheet" type="text/css" href="../../css/estilo.css">
		
		<script type="text/javascript" src="../../js/ext/jQuery/jquery.min.js" ></script>
		<script type="text/javascript" src="../../js/ext/jQuery/jquery-ui.min.js" ></script>
		<script type="text/javascript" src="../../js/ext/jQuery/datepicker-es.js" ></script>
		<script type="text/javascript" src="../../js/ext/bootstrap.min.js" ></script>
		<script type="text/javascript" src="../../js/comprobarCensoMesa.js" ></script>
	</head>
	<body onload="inicio()">
<?php
if (!autenticado()) {?>
	<h1 class="error">Debe iniciar sesión para entrar aquí</h1>
	<a href="../../index.php">Iniciar sesión</a>
<?php 
} else {
	if (isset($_POST["mesa"])) {
		//Inicializar mesa
		$mesas =recuperaMesas();		

		if ($mesas) {
			$_SESSION["mesa"]= $mesas[$_POST["mesa"]];
		}
	}
?>
	
	<?php if (isset($_SESSION["mesa"])) {
		//Está comprobando votantes
		
		?>
		<h1>Votación en mesa <?php echo $_SESSION["mesa"]["NOMBRE"];?></h1>
		
		<script type="text/javascript">
		<?php 
		if (isset($_POST["comprobar"])) {
			// Leemos la fecha en el formato que se indica
			$fchaNac= leerFecha($_POST["fechaNacimiento"]);
			
			// Algunos navegadores envían la fecha con otro formato (al ser el input de tipo date)
			if (!$fchaNac) {
				$fchaNac= leerFechaBD($_POST["fechaNacimiento"]);
			}
				
			if (!$fchaNac) {
				echo "mensajeError= \"Fecha " . $_POST["fechaNacimiento"] . " incorrecta. El formato es dd/mm/aaaa\";";
			} else {
				$resultado= votaEnMesa($_POST["nombre"],$_POST["apellido1"],$_POST["apellido2"],$_POST["nif"], $fchaNac,$_SESSION["mesa"]["ID"]);

				if ($resultado["puedeVotar"]) {
					echo "mensajeOk= \"Se ha registrado el voto de ". $_POST["nombre"] . "\"";
				} else {
					echo "mensajeError= \"" . $resultado["mensajeError"] . "\";";
				}
			}
		
		}?>

		</script>
		<div id="divError" class="alert alert-danger" style="display:none"></div>
		<div id="divOk" class="alert alert-success" style="display:none"></div>
		
		<h2>Datos del votante</h2>
		<form class="form-horizontal" action="" method="post">
			<div class="container-fluid">
				<div class="row" id="datos">
					<div class="form-group">
						<label for="nombre" class="col-sm-2 control-label">Nombre (*)</label>
						<div class="col-sm-8 col-md-6 col-lg-5">
							<input type="text" name="nombre" class="form-control" id="nombre" placeholder="Nombre" required="required">
						</div>
					</div>
					<div class="form-group">
						<label for="apellido1" class="col-sm-2 control-label">Primer apellido (*)</label>
						<div class="col-sm-8 col-md-6 col-lg-5">
							<input type="text" name="apellido1" class="form-control" id="apellido1" placeholder="Primer apellido" required="required">
						</div>
					</div>
					<div class="form-group">
						<label for="apellido2" class="col-sm-2 control-label">Segundo apellido</label>
						<div class="col-sm-8 col-md-6 col-lg-5">
							<input type="text" name="apellido2" class="form-control" id="apellido2" placeholder="Segundo apellido si tiene">
						</div>
					</div>
					<div class="form-group">
						<label for="nif" class="col-sm-2 control-label">NIF</label>
						<div class="col-sm-4 col-md-3 col-lg-2">
							<input type="text" name="nif" class="form-control" id="nif" placeholder="NIF o NIE si tiene">
						</div>
					</div>
					<div class="form-group">
						<label for="fechaNacimiento" class="col-sm-2 control-label">Fecha de nacimiento (*)</label>
						<div class="col-sm-4 col-md-3 col-lg-2">
							<input type="text" name="fechaNacimiento" class="form-control" id="fechaNacimiento" placeholder="Fecha de nacimiento" required="required">
						</div>
					</div>
				</div>
			
				<div class="row" id="accion">
					<div class="col-sm-offset-2 col-sm-10">
						<input name="comprobar" type="submit" class="btn btn-default" value="Comprobar y votar" />
					</div>
				</div>
			
			</div>
		</form>
	<?php 
	} else {
		//Aún no ha empezado
?>
		<h1>Seleccionar mesa</h1>
		<form action="" method="post">
			<select id="mesa" name="mesa">
				<option value="">Seleccione la mesa</option>
				<?php 
				$mesas= recuperaMesas();
				if ($mesas) {
					foreach ($mesas as $mesa) {?>
					<option value="<?php echo $mesa["ID"];?>"><?php echo $mesa["NOMBRE"];?></option>
					<?php }
				}?>
			</select>
			<button type="submit">Comenzar</button>
		</form>
	<?php 
	}?>

<?php 
}
?>
	</body>
</html>