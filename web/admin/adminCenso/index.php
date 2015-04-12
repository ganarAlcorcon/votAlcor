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
		<title>Administración | Censo</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link rel="stylesheet" type="text/css" href="../../css/estilo.css">
	</head>
	<body>
<?php
if (!autenticado()) {?>
	<h1 class="error">Debe iniciar sesión para entrar aquí</h1>
	<a href="../index.php">Iniciar sesión</a>
<?php 
} else {
?>
	<h1>Administración del censo</h1>
	<!-- <a href="alta.php">Nuevo simpatizante</a><br>
	<a href="editar.php">Modificar datos de simpatizante</a><br>
	<a href="baja.php">Baja de simpatizante</a><br>
	<a href="consulta.php">Consulta simpatizantes</a><br> -->
	<a href="verificarCenso.php">Verificar censo web</a><br>
	<a href="comprobarCensoMesa.php">Comprobar censo para votación en mesa</a><br>
	<a href="comprobarDocumentosCenso.php">Validación de los documentos del censo</a><br>
	<a href="recuento.php">Recuento del resultado</a>

<?php 
}
?>
	</body>
</html>