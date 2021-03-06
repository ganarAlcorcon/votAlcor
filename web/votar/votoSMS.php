<?php
if (!(include_once dirname(__FILE__).'/../lib/comun.php')) {
	die ("Falta el archivo comun.php");
}
if (!(include_once dirname(__FILE__).'/../lib/comunVotacion.php')) {
	die ("Falta el archivo comunVotar.php");
}
if (!(include_once dirname(__FILE__).'/../lib/comunCenso.php')) {
	die ("Falta el archivo comunCenso.php");
}


?>


<!DOCTYPE html>
<html lang="es">
	<head>
		<title>Votación primarias | Validar SMS</title>
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
		if (isset($_SESSION["VOTACION"]) && $_SESSION["VOTACION"]) {

			if (isset($_POST["telefono"]) && isset($_POST["cod_email"])) {
				if (verificarEmail($_POST["cod_email"],'V')) {
					$resultado= codigoSMS($_POST["telefono"]);
					
					if ($resultado["puedeVotar"]) {
		?>
		<div id="divError" class="alert alert-danger"></div>
		<div id="divOk" class="alert alert-success"></div>
		<form action="votoCandidatos.php" accept-charset="utf-8" method="post" enctype="multipart/form-data" role="form" class="form-horizontal" >
			<h3>Para continuar, introduzca el código enviado al teléfono</h3>
			<div class="form-group">
				<label for="cod_telefono" class="control-label col-sm-2">Código enviado al teléfono: </label>
				<div class="col-sm-10">
					<input id="cod_telefono" name="cod_telefono" type="text" class="form-control" size="50" maxlength="50" autocomplete="off" required="required" placeholder="Introduzca el código enviado por telefono" />
				</div>
			</div>
			
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<input type="submit" value="Continuar" class="form-control" />
				</div>
			</div>
		</form>
		<?php
						} else {
		?>
			<h1 class="error">Ocurrió un error al validar el teléfono. <?php echo $resultado["mensajeError"];?></h1>
		<?php 
						} 
					} else {
		?>
			<h1 class="error">El código del email no es válido</h1>
		<?php 
					} 
				} else {
		?>
			<h1 class="error">No se ha introducido el teléfono o el código del email</h1>
		<?php 
				} 
			} else {
			?>
			<h1 class="error">Debe comenzar de nuevo</h1>
		<?php 
			}
		?>
		
	</body>
</html>