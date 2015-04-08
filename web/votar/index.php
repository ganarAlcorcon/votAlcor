<?php
if (!(include_once dirname(__FILE__).'/../lib/comun.php')) {
	die ("Falta el archivo comun.php");
}
if (!(include_once dirname(__FILE__).'/../lib/comunVotacion.php')) {
	die ("Falta el archivo comunVotacion.php");
}

?>


<!DOCTYPE html>
<html lang="es">
	<head>
		<title>Inicio de la votación</title>
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
		
	</head>
	<body class="container" onload="inicio()">
		<?php 
		if (!isset($_SESSION["VOTACION"]) || !$_SESSION["VOTACION"]) {
			$_SESSION["VOTACION"]=recuperaVotacion($CONFIG["ID_PRIMARIAS"]);
		}
		
		if ($_SESSION["VOTACION"]) {
			if (comprobarFechasVotacion($_SESSION["VOTACION"])) {
		?>
		<div id="divError" class="alert alert-danger"></div>
		<div id="divOk" class="alert alert-success"></div>
		<form action="votoEmail.php" accept-charset="utf-8" method="post" enctype="multipart/form-data" role="form" class="form-horizontal" >
			<h2>Importante para la votación</h2>
			<p>Antes de iniciar la votación asegúrese de tener acceso al correo electrónico con el que se registró (mejor si lo tiene abierto en otra ventana/pestaña) y que tiene cerca y encendido el teléfono móvil con el que se registró.</p>
			<p>Durante el proceso de votación se le enviarán distintos códigos, tanto al correo electrónico como por SMS, que deberá introducir para continuar con la votación. Si permanece demasiado tiempo sin hacer nada la sesión podría caducar y tendría que empezar otra vez.<br/>
			Es posible que se produzca un retraso de hasta 1 minuto o más desde que se envía el código hasta que se recibe (aunque normalmente será casi inmediato), por favor, tenga paciencia. Cuando reciba el código, introdúzcalo para continuar.
			</p>
			<p>Su inscripción en el censo debe haber pasado el proceso de validación para poder votar. Errores en los datos o documentos enviados provocarán que su inscripción sea invalidada y no pueda votar</p>
			<p>Si tiene problemas para realizar la votación, acuda a la mesa física que esta abierta de forma permanente en el Ateneo Popular de Alcorcón</p>
			
			<h3>Para comenzar, introduzca el email con el que se registró</h3>
			<div class="form-group">
				<label for="email" class="control-label col-sm-2">Email: </label>
				<div class="col-sm-10">
					<input id="email" name="email" type="email" class="form-control" size="100" maxlength="100" autocomplete="off" required="required" />
				</div>
			</div>
			
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<input type="submit" value="Enviar" class="form-control" />
				</div>
			</div>
		</form>
		<?php
				} else {
		?>
			<h1 class="error">El periodo de votaciones es de <?php echo formatearTiempoFecha(leerFechaTiempoBD($_SESSION["VOTACION"]["INICIO"]));?> a <?php echo formatearTiempoFecha(leerFechaTiempoBD($_SESSION["VOTACION"]["FIN"]));?></h1>
		<?php 
				} 
			} else {
			?>
			<h1 class="error">No existen la votación</h1>
		<?php 
			}
		?>
		
	</body>
</html>