<?php
if (!(include_once dirname(__FILE__).'/../lib/comun.php')) {
	die ("Falta el archivo comun.php");
}
if (!(include_once dirname(__FILE__).'/../lib/comunCenso.php')) {
	die ("Falta el archivo comunCenso.php");
}

	
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<title>Verificación de email | Ganar Alcorcón</title>
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
	
		if (isset($_GET["verificar"]) && isset($_GET["tipo"])) {
			$clave= $_GET["verificar"];
			$tipo= $_GET["tipo"];
		
			if (verificarEmail($clave,$tipo)) {
				echo "mensajeOk='Email verificado correctamente';";
			} else {
				echo "mensajeError='Los valores no son correctos';";
			}
		} else {
			echo "mensajeError='Falta algo...';";
		}
		?>
		</script>
	</head>
	<body class="container" onload="inicio()">
		<div id="divError" class="alert alert-danger"></div>
		<div id="divOk" class="alert alert-success"></div>
	</body>
</html>