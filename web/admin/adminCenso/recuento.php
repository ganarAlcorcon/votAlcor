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
	<h1>Recuento de la votación</h1>
	
	<h2>Cabeza de lista</h2>
	<table>
		<thead>
			<tr>
				Nombre
			</tr>
			<tr>
				Votos
			</tr>
		</thead>
		<tbody>
	<?php 
	$resultados=recuperaResultados();
	
	
	foreach ($resultados["CABEZA_LISTA"] as $resultado) {
	?>
			<tr>
				<td>
					<?php echo $resultado["NOMBRE"];?>
				</td>
				<td>
					<?php echo $resultado["VOTOS"];?>
				</td>
			</tr>
	<?php 
	}
	?>
		</tbody>
	</table>
	
	<h2>Lista general</h2>
	<table>
		<thead>
			<tr>
				<td>
					Nombre
				</td>
				<td>
					Votos
				</td>
			</tr>
		</thead>
		<tbody>
	<?php 
	
	foreach ($resultados["LISTA_GENERAL"] as $resultado) {
	?>
			<tr>
				<td>
					<?php echo $resultado["NOMBRE"];?>
				</td>
				<td>
					<?php echo $resultado["VOTOS"];?>
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