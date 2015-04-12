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
} else if (!permitido('RECU')) {?>
	<h1 class="error">No tiene permisos para realizar el recuento</h1>
	<a href="../index.php">Volver a iniciar sesión</a>
<?php 
} else {
?>
	<h1>Verificación de los documentos del censo</h1>
	
	<h2>Documentos no válidos</h2>
	<table class="tabla_votacion">
		<thead style="background-color: #AAA">
			<tr>
				<td>
					Documento
				</td>
				<td>
					Tipo error
				</td>
				<td>
					Mesa
				</td>
				<td>
					Hora
				</td>
			</tr>
		</thead>
		<tbody>
	<?php 
	$resultados=validaDocumentosCenso();
	
	
	foreach ($resultados["ERRONEOS"] as $resultado) {
	?>
			<tr>
				<td>
					<?php echo $resultado["DOCUMENTO"];?>
				</td>
				<td>
					<?php echo $resultado["TIPO_ERROR"];?>
				</td>
				<td>
					<?php echo $resultado["MESA"];?>
				</td>
				<td>
					<?php echo $resultado["HORA"];?>
				</td>
			</tr>
	<?php 
	}
	?>
		</tbody>
	</table>

<?php 
}
?>
	</body>
</html>