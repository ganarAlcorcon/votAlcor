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

			if ($_SESSION["telefono_v"]) {
				if ($_SESSION["email_v"]) {
					$cabezaLista= $_POST["cabeza_lista"];
					$listaGeneral= $_POST["lista_general"];
					$simpatizante= $_SESSION["simpatizante"];

					if (count($listaGeneral) <= $CONFIG["MAX_VOTOS_GENERAL"]) {
						$votado= votaEnWeb($simpatizante,$cabezaLista,$listaGeneral);
						if ($votado["votoCorrecto"]) {


		?>
		<div id="divError" class="alert alert-danger"></div>
		<div id="divOk" class="alert alert-success"></div>
		<h2>Su voto se ha registrado correctamente. Puede cerrar la ventana.</h2>
		<p>Gracias por participar en las primarias de Ganar Alcorcón</p>
		<?php
							} else {
		?>
			<h1 class="error">Ocurrió un error al votar. <?php echo $votado["mensajeError"];?></h1>
		<?php
							}
						} else {
		?>
			<h1 class="error">El máximo de candidatos a elegir para la lista general es de <?php echo $CONFIG["MAX_VOTOS_GENERAL"];?></h1>
		<?php
							}
						} else {
		?>
			<h1 class="error">El email no está validado</h1>
		<?php 
						}
				} else {
		?>
			<h1 class="error">El teléfono no está validado</h1>
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