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
	

<?php 
}
?>
	</body>
</html>