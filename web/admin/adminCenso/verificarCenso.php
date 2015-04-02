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
		Verificando <?php echo $_SESSION["numSimpatizantes"] ?> simpatizantes<br>
		<?php var_dump($_SESSION["misSimpatizantes"])?>
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