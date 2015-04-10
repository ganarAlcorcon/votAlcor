<?php
if (!(include_once 'comunAdmin.php')) {
	die ("Falta el archivo comunAdmin.php");
}

$fallo=false;
if (isset($_POST["email"])){
	if ($DEBUG) {
		echo "autenticando a " . $_POST["email"];
	}
	$fallo=!autenticar($_POST["email"], $_POST["passwd"]);
}
?>

<!DOCTYPE html>
<html lang="es">
	<head>
		<title>Administración | Inicio</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link rel="stylesheet" type="text/css" href="../css/estilo.css">
	</head>
	<body>
		<h1>Ganar Alcorcón</h1>
		
		
		<?php if (!autenticado()) {?>
		<h2>Entrar</h2>
		<form action="" method="post" class="vertical">
			<div>
				<label for="email" >Email</label><input type="email" id="email" name="email" />
			</div>
			<div>
				<label for="passwd">Contraseña</label><input type="password" id="passwd" name="passwd" />
				<?php if ($fallo) {?>
					<span class="error">* El email o contraseña son incorrectos o el usuario no tiene permisos de administrador, vuelva a intentarlo</span>
				<?php }?>
			</div>
			<div>
				<input type="submit" value="Acceder">
			</div>
		</form>
		<?php } else {?>
			Bienvenido <?php echo $_SESSION["nombre"]?> <br />
			<form method="post" action="index.php">
				<input type="hidden" name="salir" value="true" />
				<input type="submit" value="Salir" />
			</form>
		
			<h2>Aplicación de gestión</h2>
			<a href="adminCenso/comprobarCensoMesa.php">Votación en mesa</a><br />
		<?php }?>
	</body>
</html>