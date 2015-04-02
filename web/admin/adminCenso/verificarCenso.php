<?php
if (!(include_once dirname(__FILE__).'/../comunAdmin.php')) {
	die ("Falta el archivo comunAdmin.php");
}
if (!(include_once dirname(__FILE__).'/../../lib/comunCenso.php')) {
	die ("Falta el archivo comunCenso.php");
}
?>


<!DOCTYPE html>
<html lang="es">
	<head>
		<title>Administración | Verificar censo web</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link rel="stylesheet" type="text/css" href="../../css/jQueryUI/jquery-ui.min.css">
		<link rel="stylesheet" type="text/css" href="../../css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="../../css/bootstrap-theme.min.css">
		<link rel="stylesheet" type="text/css" href="../../css/estilo.css">
		
		<script type="text/javascript" src="../../js/ext/jQuery/jquery.min.js" ></script>
		<script type="text/javascript" src="../../js/ext/jQuery/jquery-ui.min.js" ></script>
		<script type="text/javascript" src="../../js/ext/jQuery/datepicker-es.js" ></script>
		<script type="text/javascript" src="../../js/ext/bootstrap.min.js" ></script>
	</head>
	<body>
<?php
if (!autenticado()) {?>
	<h1 class="error">Debe iniciar sesión para entrar aquí</h1>
	<a href="../../index.php">Iniciar sesión</a>
<?php 
} else {
	if (isset($_POST["numRegistros"])) {
		//INICIALIZAR
		
		if (!iniciaVerificacion($_POST["numRegistros"])) {?>
			<script>alert("Ocurrió un error");</script>
		<?php } elseif ($_SESSION["numSimpatizantes"] == 0) {
			unset($_SESSION["misSimpatizantes"]);
			unset($_SESSION["numSimpatizantes"]);
			?>
			<script>alert("No hay simpatizantes pendientes de verificar");</script>
		<?php }
	}
?>
	<h1>Administración del censo</h1>
	
	<?php if (isset($_SESSION["misSimpatizantes"])) {
		//Está verificando simpatizantes
		
		?>
		<div class="container-fluid">
			<div class="row" id="datos">
				<div class="col-sm-6 col-md-5 col-lg-4" id="datosTexto">
					<div class="row" id="personales">
						<h3>Datos personales</h3>
					</div>
					<div class="row" id="seguridad">
						<h3>Datos de seguridad</h3>
					</div>
				</div>
				<div class="col-sm-6 col-md-7 col-lg-8" id="documentos">
					<div class="row" id="anverso">
						<h3>Anverso</h3>
						<img alt="" src="">
					</div>
					<div class="row" id="reverso">
						<h3>Reverso</h3>
						<img alt="" src="">
					</div>
				</div>
			</div>
		
			<div class="row" id="accion">
				<div class="col-xs-4 col-sm-offset-1 col-sm-3 col-md-offset-3 col-md-2 col-lg-offset-3 col-lg-2">
					<input type="radio" name="estado" id="estadoRechazar" value="R" /> <label for="estadoRechazar">Rechazar</label>
				</div>
				<div class="col-xs-4 col-sm-3 col-md-2 col-lg-2">
					<input type="radio" name="estado" id="estadoPendiente" value="P" /> <label for="estadoPendiente">Pendiente</label>
				</div>
				<div class="col-xs-4 col-sm-3 col-md-2 col-lg-2">
					<input type="radio" name="estado" id="estadoAprobar" value="A" /> <label for="estadoAprobar">Aprobar</label>
				</div>
			</div>
		
			<div class="row" id="navegacion">
				<div class="col-xs-3 col-md-offset-1 col-md-2">
					<input type="button" name="direccion" id="anterior" value="Anterior"></input>
				</div>
				<div class="col-xs-6 col-md-4 col-md-offset-1">
					1 ... 3 4 <b>5</b> 6 7 ... 10
				</div>
				<div class="col-xs-3 col-md-2 col-md-offset-1">
					<input type="button" name="direccion" id="siguiente" value="Siguiente"></input>
				</div>
			</div>
		</div>
	<?php 
	} else {
		//Aún no ha empezado
?>
		<form action="" method="post">
			<select id="numRegistros" name="numRegistros">
				<option value="5">Verificar 5 simpatizantes</option>
				<option value="10">Verificar 10 simpatizantes</option>
				<option value="20">Verificar 20 simpatizantes</option>
				<option value="-1">Verificar todos los simpatizantes</option>
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